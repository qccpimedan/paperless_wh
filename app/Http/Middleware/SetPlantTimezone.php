<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetPlantTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $tz = 'Asia/Jakarta';
        if ($user && $user->plant) {
            $tz = (string) ($user->plant->timezone ?? 'Asia/Jakarta');
        }

        config(['app.display_timezone' => $tz]);

        // Keep application storage timezone consistent (UTC). Use app.display_timezone for display.
        date_default_timezone_set((string) config('app.timezone', 'UTC'));
        Carbon::setLocale(config('app.locale'));

        return $next($request);
    }
}
