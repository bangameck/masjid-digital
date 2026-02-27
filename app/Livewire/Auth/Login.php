<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\AppSetting; // Pastikan model Setting sudah ada
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Login Admin')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return $this->redirectRoute('dashboard', navigate: true);
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        // Ambil data setting pertama (umumnya cuma ada 1 row)
        $setting = AppSetting::getSettings(); // Menggunakan method getSettings() yang sudah di-cache

        return view('livewire.auth.login', [
            'setting' => $setting
        ]);
    }
}
