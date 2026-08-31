<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SsoLoginController extends Controller
{
    /**
     * Menerima tiket SSO dari Portal, memverifikasi ke Employee API,
     * lalu login user secara lokal jika valid.
     */
    public function login(Request $request)
    {
        // 1. Validasi format tiket (harus UUID)
        $request->validate([
            'ticket' => 'required|uuid',
        ]);

        Log::info('SSO: Ticket received', ['ticket' => $request->ticket]);

        // 2. Verifikasi tiket ke Employee API (komunikasi server-ke-server)
        try {
            $response = Http::withToken(config('services.employee_api.sso_secret'))
                ->timeout(10)
                ->post(config('services.employee_api.url') . '/sso/verify', [
                    'ticket'       => $request->ticket,
                    'project_uuid' => config('services.employee_api.this_project_uuid'),
                ]);
        } catch (\Exception $e) {
            Log::error('SSO: HTTP request to Employee API failed', ['error' => $e->getMessage()]);
            return redirect('/login')->withErrors([
                'sso' => 'Tidak dapat terhubung ke server autentikasi. Silakan login manual.',
            ]);
        }

        Log::info('SSO: Employee API response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        // 3. Kasus: user tidak punya akses ke project ini
        if ($response->status() === 403) {
            return redirect('/login')->withErrors([
                'sso' => 'Anda tidak memiliki akses ke sistem ini.',
            ]);
        }

        // 4. Kasus umum: tiket tidak valid / sudah kedaluwarsa / sudah dipakai
        if ($response->failed()) {
            return redirect('/login')->withErrors([
                'sso' => 'Sesi login otomatis tidak valid atau sudah kedaluwarsa. Silakan login manual.',
            ]);
        }

        $remoteUser = $response->json('user');

        if (empty($remoteUser['uuid'])) {
            Log::warning('SSO: Response valid but user UUID is empty', ['body' => $response->body()]);
            return redirect('/login')->withErrors([
                'sso' => 'Data pengguna tidak valid dari server autentikasi.',
            ]);
        }

        Log::info('SSO: Remote user received', ['uuid' => $remoteUser['uuid']]);

        // 5. Cocokkan user lokal berdasarkan uuid (database bersama)
        $user = User::where('uuid', $remoteUser['uuid'])->first();

        if (! $user) {
            Log::warning('SSO: User not found locally', ['uuid' => $remoteUser['uuid']]);
            return redirect('/login')->withErrors([
                'sso' => 'Akun tidak ditemukan di sistem ini.',
            ]);
        }

        // 6. Login user & regenerate session (mencegah session fixation attack)
        Auth::login($user);
        $request->session()->regenerate();

        Log::info('SSO: Login successful', ['user_id' => $user->id, 'uuid' => $user->uuid]);

        return redirect()->intended('/dashboard');
    }

    /**
     * Menerima webhook dari Employee Central ketika user logout dari mana pun.
     * Central mengirim POST ke /api/sso/logout dengan Bearer token = sso_secret.
     */
    public function ssoLogout(Request $request)
    {
        $token    = $request->bearerToken();
        $expected = config('services.employee_api.sso_secret');

        if (! $token || ! $expected || ! hash_equals($expected, $token)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate(['user_uuid' => 'required|uuid']);

        User::where('uuid', $request->user_uuid)
            ->update(['force_logout_after' => now()]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Logout lokal: hapus sesi, laporkan ke Employee Central,
     * lalu redirect ke portal PDQC (sumber SSO).
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            try {
                Http::withToken(config('services.employee_api.sso_secret'))
                    ->timeout(5)
                    ->post(config('services.employee_api.url') . '/sso/report-logout', [
                        'user_uuid'    => $user->uuid,
                        'project_uuid' => config('services.employee_api.this_project_uuid'),
                    ]);
            } catch (\Throwable $e) {
                Log::warning('SSO: Laporan logout ke central gagal', ['error' => $e->getMessage()]);
                // tetap lanjutkan logout lokal walau central tidak terjangkau
            }
        }

        return redirect(config('services.employee_api.portal_url'));
    }
}
