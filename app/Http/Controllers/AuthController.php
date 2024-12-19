<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginRegisterForm()
    {
        return view('auth.login-register');
    }

    public function loginRegister(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'login') {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                if (Auth::user()->role === 'HEAD_STAFF') {
                    return redirect()->intended('/headstaff/data');
                } elseif (Auth::user()->role === 'STAFF') {
                    return redirect()->intended('/responses/responses');
                } else {
                    return redirect()->intended('/reports/article');
                }
            }

            return back()->withErrors(['email_password' => 'Email atau password salah.'])->withInput();
        } elseif ($action === 'register') {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'GUEST',
            ]);

            Auth::login($user);
            return redirect()->intended('/reports/article');
        }

        return back()->withErrors(['action' => 'Aksi tidak valid.']);
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/auth');
    }
}
