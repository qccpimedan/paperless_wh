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
     * Show the login form — redirect ke PDQC portal login jika SSO dikonfigurasi.
     * Jika EMPLOYEE_PORTAL_URL tidak diset, fallback ke halaman login lokal.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $portalLoginUrl = config('services.employee_api.portal_url');
        if (!empty($portalLoginUrl)) {
            // Redirect ke halaman login portal PDQC
            return redirect($portalLoginUrl . '/login');
        }

        // Fallback: tampilkan form login lokal
        return view('auth.login');
    }

    /**
     * Show local login form — akses tersembunyi untuk Superadmin.
     * Dapat diakses via /local-login jika SSO/central sedang bermasalah.
     */
    public function showLocalLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

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
        $user = $request->user();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            try {
                \Illuminate\Support\Facades\Http::withToken(config('services.employee_api.sso_secret'))
                    ->timeout(5)
                    ->post(config('services.employee_api.url') . '/sso/report-logout', [
                        'user_uuid'    => $user->uuid,
                        'project_uuid' => config('services.employee_api.this_project_uuid'),
                    ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('SSO: Laporan logout ke central gagal', ['error' => $e->getMessage()]);
            }
        }

        $portalUrl = config('services.employee_api.portal_url');
        if (!empty($portalUrl)) {
            return redirect($portalUrl);
        }

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
