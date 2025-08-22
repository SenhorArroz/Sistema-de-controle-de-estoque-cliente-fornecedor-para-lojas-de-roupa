<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginSistem extends Controller
{
    public function login(Request $request){
        $messages = [
        'email.required' => 'O campo e-mail é obrigatório.',
        'email.email' => 'Digite um e-mail válido.',
        'password.required' => 'O campo senha é obrigatório.',
    ];

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], $messages);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    return back()->withErrors([
        'email' => 'As credenciais fornecidas estão erradas.',
    ])->onlyInput('email');
    }
}
