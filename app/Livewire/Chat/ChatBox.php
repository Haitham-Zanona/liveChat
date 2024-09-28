<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;

    public $loadedMessages;

    public function loadMessages()
    {

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)->get();
    }

    public function mount($query = null)
    {
        // $this->selectedConversation = $query;
        if ($query) {
            $this->selectedConversation = $query;
        } else {
            // Get the default conversation from the database
            $defaultConversation = auth()->user()->conversations()->latest()->first();

            if ($defaultConversation) {
                $this->selectedConversation = $defaultConversation;
            } else {
                // Redirect the user to a page where they can select a conversation
                return redirect()->route('conversations.index');
            }
        }

        $this->loadMessages();
    }

    public function sendMessage()
    {

        // dd($this->body);

        $this->validate(['body' => 'required|string']);

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

        // $this->reset('body');
        $this->body = '';

        // dd($this->body);

            // push the message
            $this->loadedMessages->push($createdMessage);

    }
    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
