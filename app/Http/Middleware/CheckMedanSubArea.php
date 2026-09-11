<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMedanSubArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $effectivePlant = $user->getEffectivePlant();
            
            if ($effectivePlant && str_contains(strtolower($effectivePlant->plant), 'medan')) {
                // Set default session active_sub_area jika belum ada
                if (!session()->has('active_sub_area')) {
                    session(['active_sub_area' => 'Medan KIM 1']);
                    session(['show_sub_area_modal' => true]);
                }
            } else {
                // Hapus session jika switch ke plant lain yang bukan Medan
                session()->forget('active_sub_area');
                session()->forget('show_sub_area_modal');
            }
        }

        return $next($request);
    }
}
