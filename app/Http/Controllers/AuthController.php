<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required' => 'Numele este obligatoriu.',
            'name.min' => 'Numele trebuie să aibă minim 2 caractere.',
            'name.max' => 'Numele nu poate depăși 255 de caractere.',
            
            'email.required' => 'Email-ul este obligatoriu.',
            'email.email' => 'Introduceți o adresă de email validă (ex: nume@domeniu.com).',
            'email.unique' => 'Acest email este deja înregistrat.',
            
            'password.required' => 'Parola este obligatorie.',
            'password.min' => 'Parola trebuie să aibă minim 8 caractere.',
            
            'password_confirmation.required' => 'Confirmarea parolei este obligatorie.',
            'password_confirmation.same' => 'Parolele nu se potrivesc.',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        Auth::login($user);
        
        return redirect('/home')->with('success', 'Cont creat cu succes!');
    }
    
    public function showLogin()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email-ul este obligatoriu.',
            'email.email' => 'Introduceți o adresă de email validă.',
            'password.required' => 'Parola este obligatorie.',
        ]);
        
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/home')->with('success', 'Bun venit înapoi!');
        }
        
        return back()->withErrors([
            'email' => 'Datele de autentificare sunt incorecte.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'V-ați delogat cu succes!');
    }
}