<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasPlantTimezoneTimestamps
{
    public function getCreatedAtAttribute($value)
    {
        if (!$value) {
            return null;
        }

        $tz = (string) config('app.display_timezone', 'Asia/Jakarta');

        if ($value instanceof Carbon) {
            return $value->copy()->timezone($tz);
        }

        return Carbon::parse($value)->timezone($tz);
    }

    public function getUpdatedAtAttribute($value)
    {
        if (!$value) {
            return null;
        }

        $tz = (string) config('app.display_timezone', 'Asia/Jakarta');

        if ($value instanceof Carbon) {
            return $value->copy()->timezone($tz);
        }

        return Carbon::parse($value)->timezone($tz);
    }
}
