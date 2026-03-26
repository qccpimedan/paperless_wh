<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    /**
     * Switch ke plant lain (khusus role Manager).
     * Plant yang dipilih HARUS termasuk dalam daftar allowedPlants yang di-assign Superadmin.
     */
    public function switchPlant(Request $request)
    {
        $user = Auth::user();

        // Pastikan hanya Manager yang bisa switch plant
        if (!$user->isManager()) {
            abort(403, 'Hanya Manager yang dapat melakukan switch plant.');
        }

        $request->validate([
            'plant_id' => 'required|exists:plants,id',
        ]);

        $plantId = (int) $request->plant_id;

        // ✅ Validasi: Manager hanya boleh switch ke plant yang diizinkan oleh Superadmin
        if (!$user->canAccessPlant($plantId)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke plant tersebut.');
        }

        $plant = Plant::findOrFail($plantId);

        // Update active_plant_id user
        $user->update(['active_plant_id' => $plant->id]);
        $user->refresh();

        return redirect()->back()->with('success', "Berhasil berpindah ke plant: {$plant->plant}");
    }

    /**
     * Reset ke plant asal (plant yang di-assign saat create user).
     */
    public function resetPlant(Request $request)
    {
        $user = Auth::user();

        if (!$user->isManager()) {
            abort(403, 'Hanya Manager yang dapat melakukan reset plant.');
        }

        $user->update(['active_plant_id' => null]);

        $originalPlant = $user->plant?->plant ?? 'Plant Asal';

        return redirect()->back()->with('success', "Berhasil kembali ke {$originalPlant}.");
    }
}
