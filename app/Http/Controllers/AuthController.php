<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        // Hapus 'email' format validator karena kita pakai username/NIP
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role_id == 1) {
                return redirect()->route('tu.dashboard');
            } elseif ($user->role_id == 2) {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Username / NIP atau password salah.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
