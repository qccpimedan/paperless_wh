<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Regenerate CSRF token setiap 30 menit untuk request GET
        if ($request->isMethod('GET') && !$request->ajax()) {
            $lastRegeneration = session('csrf_last_regeneration', 0);
            $now = time();
            
            // Regenerate token setiap 30 menit (1800 detik)
            if (($now - $lastRegeneration) > 1800) {
                $request->session()->regenerateToken();
                session(['csrf_last_regeneration' => $now]);
            }
        }

        return $response;
    }
}
