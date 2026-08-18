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
}
