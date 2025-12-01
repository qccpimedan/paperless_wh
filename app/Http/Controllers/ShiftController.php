<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.role', 'user.plant'])
                ->latest()
                ->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $shifts = Shift::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-shift.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-shift.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shift' => 'required|string|max:255',
        ]);

        Shift::create([
            'shift' => $request->shift,
            'id_user' => Auth::id(),
        ]);

        return redirect()->route('shifts.index')
            ->with('success', 'Data shift berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        // Check access based on plant
        $this->checkPlantAccess($shift);
        
        $shift->load(['user.role', 'user.plant']);
        return view('super-admin.input-shift.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        // Check access based on plant
        $this->checkPlantAccess($shift);
        
        return view('super-admin.input-shift.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift)
    {
        // Check access based on plant
        $this->checkPlantAccess($shift);
        
        $request->validate([
            'shift' => 'required|string|max:255',
        ]);

        $shift->update([
            'shift' => $request->shift,
        ]);

        return redirect()->route('shifts.index')
            ->with('success', 'Data shift berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        // Check access based on plant
        $this->checkPlantAccess($shift);
        
        $shift->delete();
        return redirect()->route('shifts.index')
            ->with('success', 'Data shift berhasil dihapus!');
    }

    /**
     * Check if user has access to shift based on plant
     */
    private function checkPlantAccess(Shift $shift)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($shift->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
