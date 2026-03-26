<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SetPlantTimezone
{
    public function handle(Request $request, Closure $next)
    {
        $tz = 'Asia/Jakarta';

        if (Auth::check()) {
            $plant = Auth::user()->getEffectivePlant();
            if ($plant) {
                $tz = $plant->timezone ?? 'Asia/Jakarta';
            }
        }

        // timezone hanya untuk DISPLAY
        config(['app.display_timezone' => $tz]);

        // optional (format bahasa carbon)
        Carbon::setLocale(config('app.locale'));

        return $next($request);
    }
}