<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username
        $user = User::where('username', $request->username)->first();
        
        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan.',
            ])->onlyInput('username');
        }

        // Check password manually
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Password salah.',
            ])->onlyInput('username');
        }

        // Login user manually
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();
        
        return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }

    /**
     * Show dashboard (protected route)
     */
    public function dashboard()
    {
        return view('dashboard');
    }
}
