<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait EditablePer2JamTrait
{
    /**
     * Dapatkan semua record yang bisa diedit dalam 10 menit ke depan
     * Logic: updated_at harus >= 2 jam lalu (bisa diedit) AND <= sekarang (belum di-edit lagi)
     */
    public function getEditableRecordsWithin10Min($modelClass)
    {
        $user = Auth::user();
        $now = now();
        $twoHoursAgo = now()->subHours(2);
        
        $query = $modelClass::where('updated_at', '<=', $twoHoursAgo);
        
        // Filter berdasarkan plant jika bukan superadmin
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        
        return $query->get();
    }

    /**
     * Dapatkan count record yang bisa diedit
     * Logic: updated_at harus >= 2 jam lalu (bisa diedit) AND <= sekarang (belum di-edit lagi)
     */
    public function countEditableRecordsWithin10Min($modelClass)
    {
        $user = Auth::user();
        $twoHoursAgo = now()->subHours(2);
        
        $query = $modelClass::where('updated_at', '<=', $twoHoursAgo);
        
        // Filter berdasarkan plant jika bukan superadmin
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        
        return $query->count();
    }

    /**
     * Check apakah record bisa diedit (dalam 2 jam)
     */
    public function canEditPerJam($record)
    {
        $lastUpdate = $record->updated_at;
        $now = now();
        $twoHoursAgo = $now->copy()->subHours(2);
        
        return $lastUpdate <= $twoHoursAgo;
    }

    /**
     * Dapatkan waktu edit berikutnya
     */
    public function getNextEditTime($record)
    {
        $lastUpdate = $record->updated_at;
        return $lastUpdate->copy()->addHours(2);
    }

    /**
     * Dapatkan sisa waktu dalam menit sebelum bisa edit
     */
    public function getMinutesUntilNextEdit($record)
    {
        $nextEditTime = $this->getNextEditTime($record);
        $now = now();
        
        if ($now >= $nextEditTime) {
            return 0;
        }
        
        return $nextEditTime->diffInMinutes($now);
    }

    /**
     * Dapatkan sisa waktu dalam format readable
     */
    public function getTimeUntilNextEditFormatted($record)
    {
        $minutes = $this->getMinutesUntilNextEdit($record);
        
        if ($minutes <= 0) {
            return 'Sekarang';
        }
        
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }
        
        return "{$mins}m";
    }

    /**
     * Dapatkan list editable records dengan detail info untuk API
     */
    public function getEditableRecordsForApi($modelClass)
    {
        $records = $this->getEditableRecordsWithin10Min($modelClass);
        
        return $records->map(function($record) {
            return [
                'uuid' => $record->uuid,
                'tanggal' => $record->tanggal->format('Y-m-d'),
                'area' => $record->area->nama_area ?? 'N/A',
                'shift' => $record->shift->shift ?? 'N/A',
                'updated_at' => $record->updated_at->format('Y-m-d H:i'),
                'next_edit_time' => $this->getNextEditTime($record)->format('Y-m-d H:i'),
                'minutes_until_edit' => $this->getMinutesUntilNextEdit($record),
                'time_formatted' => $this->getTimeUntilNextEditFormatted($record),
                'edit_url' => route($this->getEditRouteName(), [$record->uuid]) . '?edit_per_2jam=1',
            ];
        });
    }

    /**
     * Override method ini di controller untuk return route name yang sesuai
     * V1: 'pemeriksaan-suhu-ruang.edit'
     * V2: 'pemeriksaan-suhu-ruang-v2.edit'
     */
    protected function getEditRouteName()
    {
        return 'pemeriksaan-suhu-ruang-v2.edit';
    }
}
