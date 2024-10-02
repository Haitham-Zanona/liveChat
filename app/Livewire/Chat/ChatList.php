<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Traits\AvatarTrait;
use App\Models\Conversation;
use Illuminate\Support\Facades\Cache;

class ChatList extends Component
{
    use AvatarTrait;
    public $selectedConversation;
    public $query;

    public function mount($query = null) {
        if ($query) {
            $this->selectedConversation = $query;
        } else {
            // Get the default conversation from the database
            $defaultConversation = auth()->user()->conversations()->latest()->first();

            if ($defaultConversation) {
                $this->selectedConversation = $defaultConversation->id;
            }
                // else {
                // // Redirect the user to a page where they can select a conversation
                // return redirect()->route('conversations.index');
                // }
        }

        // $this->loadMessages();
    }

    public function render()
    {
        // dd($this->selectedConversation);
        // dump($this->selectedConversation);
        // dump(request()->all());
        $user = auth()->user();

        $conversations = Cache::remember('conversations_' . $user->id, 60, function () use ($user) {
            // dd($conversations);
            return $user->conversations()->latest('updated_at')->get();
            // return Conversation::orderBy('updated_at', 'desc')->get();

        });

        return view('livewire.chat.chat-list', [
            'conversations' => $conversations,
            'user' => $user,

        ]);
    }
    // public function render()
    // {
    //     $user = auth()->user();
    //     $conversations = Cache::store('redis')->remember('conversations_' . $user->id, 60, function () use ($user) {
    //         return $user->conversations()->latest('updated_at')->get();
    //     });
    //     return view('livewire.chat.chat-list', [
    //         'conversations' => $conversations,
    //         // 'conversations' => $user->conversations()->latest('updated_at')->get(),
    //         /* 'conversations' => Conversation::cacheFor(60) // cache for 1 minute
    //         ->where('user_id', auth()->id())
    //         ->get(), */
    //         'user' => $user,
    //     ]);
    // }
}
