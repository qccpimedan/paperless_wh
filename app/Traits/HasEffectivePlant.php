<?php

namespace App\Traits;

use App\Models\Plant;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasEffectivePlant
 *
 * Digunakan di controller agar Manager dapat menggunakan plant yang sedang aktif (switched plant)
 * sementara user lain menggunakan plant yang di-assign (id_plant).
 *
 * Cara penggunaan di controller:
 *   use App\Traits\HasEffectivePlant;
 *   class MyController extends Controller {
 *       use HasEffectivePlant;
 *
 *       public function index() {
 *           $user = Auth::user();
 *           $plantId = $this->getActivePlantId($user);
 *           // gunakan $plantId sebagai pengganti $user->id_plant
 *       }
 *   }
 */
trait HasEffectivePlant
{
    /**
     * Dapatkan plant ID yang efektif:
     * - Jika Manager dan punya active_plant_id → return active_plant_id
     * - Selain itu → return id_plant
     */
    protected function getActivePlantId($user = null): ?int
    {
        $user = $user ?? Auth::user();

        if (!$user) return null;

        if ($user->isManager() && $user->active_plant_id) {
            return $user->active_plant_id;
        }

        return $user->id_plant;
    }

    /**
     * Dapatkan objek Plant yang efektif
     */
    protected function getActivePlant($user = null): ?Plant
    {
        $user = $user ?? Auth::user();

        if (!$user) return null;

        return $user->getEffectivePlant();
    }

    /**
     * Cek apakah user ini seorang Manager
     */
    protected function isManagerUser($user = null): bool
    {
        $user = $user ?? Auth::user();

        return $user ? $user->isManager() : false;
    }
}
