<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
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
         if (is_null($user->email_verified_at)) {
            return back()->withErrors([
                'username' => 'Akun belum diaktivasi. Silakan hubungi admin kepegawaian untuk mengaktivasi akun Anda.',
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
    public function check(): JsonResponse
    {
        try {
            DB::connection()->getPdo();


            return response()->json([
                'status' => 'ok',
                'message' => 'MySQL connection is alive',
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'MySQL connection failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

}
