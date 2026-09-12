<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasSubAreaSession
{
    /**
     * Boot the HasSubAreaSession trait for Eloquent models.
     */
    protected static function bootHasSubAreaSession()
    {
        static::creating(function ($model) {
            if (empty($model->sub_area) && session()->has('active_sub_area')) {
                $model->sub_area = session('active_sub_area');
            }
        });
    }

    /**
     * Scope query to filter records by sub_area.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $subAreaInput 'all', 'Medan KIM 1', 'Medan KIM 2', or null
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterSubArea($query, $subAreaInput = null)
    {
        $user = Auth::user();
        if (!$user) {
            return $query;
        }

        $effectivePlant = $user->getEffectivePlant();
        $isMedanPlant = $effectivePlant && str_contains(strtolower($effectivePlant->plant), 'medan');

        // Jika filter dari dropdown dipilih secara eksplisit
        if (!empty($subAreaInput)) {
            if ($subAreaInput !== 'all') {
                return $query->where('sub_area', $subAreaInput);
            }
            return $query;
        }

        // Jika user berada di Plant Medan dan memiliki session active_sub_area
        if ($isMedanPlant && session()->has('active_sub_area')) {
            $activeArea = session('active_sub_area');
            return $query->where(function ($q) use ($activeArea) {
                $q->where('sub_area', $activeArea)
                  ->orWhereNull('sub_area'); // Sertakan data lama (NULL) agar data historis tetap tampil
            });
        }

        return $query;
    }
}
