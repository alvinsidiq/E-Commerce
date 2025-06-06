<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

class RegisterPage extends Component
{
    #[Title('register-page')]

    public $name;
    public $email;
    public $password;

    public function save()
    {
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255', 
            'password' => 'required|min:6|max:10',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user); 
        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
