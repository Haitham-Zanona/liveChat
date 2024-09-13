<?php

namespace App\Livewire\Chat;

use App\Traits\AvatarTrait;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ChatList extends Component
{
    use AvatarTrait;
    public $selectedConversation;

    public function render()
    {
        $user = auth()->user();
        $conversations = Cache::remember('conversations_' . $user->id, 60, function () use ($user) {
            // dd($conversations);
            return $user->conversations()->latest('updated_at')->get();
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
