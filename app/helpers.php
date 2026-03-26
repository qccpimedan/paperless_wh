<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function plant_time($datetime)
{
    if (!$datetime) return null;

    $tz = config('app.display_timezone', 'Asia/Jakarta');

    return Carbon::parse($datetime)
        ->timezone($tz);
}

/**
 * Dapatkan plant ID efektif dari user yang sedang login.
 * - Manager dengan active_plant_id → return active_plant_id
 * - User lain → return id_plant
 *
 * @param \App\Models\User|null $user
 * @return int|null
 */
function effective_plant_id($user = null): ?int
{
    $user = $user ?? Auth::user();

    if (!$user) return null;

    if ($user->isManager() && $user->active_plant_id) {
        return $user->active_plant_id;
    }

    return $user->id_plant;
}