<?php
use Carbon\Carbon;

function plant_time($datetime)
{
    if (!$datetime) return null;

    $tz = config('app.display_timezone', 'Asia/Jakarta');

    return Carbon::parse($datetime)
        ->timezone($tz);
}