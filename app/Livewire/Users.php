<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Users extends Component
{
    public function message($userId) {
        $authenticatedUserId= auth()->id();
        #check conversation exists
    }
    public function render()
    {
        return view('livewire.users', ['users'=>User::all()]);
    }
}
