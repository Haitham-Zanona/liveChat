<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ChatBox extends Component
{

    public $query;
    public $selectedConversation;
    public $body = '';

    public $loadedMessages;

    public function mount()
    {
        $this->query = request()->route('query');

        // Fetch the conversation object using the query parameter
        $this->selectedConversation = Conversation::find($this->query);

        // Check if the conversation was found
        if (!$this->selectedConversation) {
            // Handle the case where the conversation is not found
            abort(404, 'Conversation not found');
        }
        $this->loadMessages();
    }

    public function loadMessages()
    {
        // dd($this->selectedConversation->id);
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)->where(function ($query) {
            $query->where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id());
        })->get();
    }

    public function sendMessage()
    {

        // dd($this->body);

        // $this->validate(['body' => 'required|string']);

// dd($this->body);
        $validator = Validator::make(['body' => $this->body], [
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        // Output the current state of the $body property before validation
        // dd('Before validation:', $this->body);

        // $validatedData = $this->validate([
        //     'body' => 'string',
        // ]);

        // // Output the current state of the $body property after validation
        // dd('After validation:', $validatedData);

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body,
        ]);
        // scroll to bottom
        $this->dispatch('scroll-bottom');

        // push the message
        $this->loadedMessages->push($createdMessage);

        // update conversation model
        $this->selectedConversation->updated_at = now();

        $this->selectedConversation->save();
        // $conversations = Conversation::orderBy('updated_at', 'desc')->get();

        // refresh chatlist
        // $this->emitTo('chat.chat-list','refresh');

    }

    public function resetInput()
    {
        $this->body = ''; // Reset the value
    }

    public function render()
    {

        return view('livewire.chat.chat-box', [
            'selectedConversation' => $this->selectedConversation,
        ]);
    }
}
