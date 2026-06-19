<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::with(['role', 'plant', 'allowedPlants'])
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.input-user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $plants = Plant::orderBy('plant')->get();
        return view('super-admin.input-user.create', compact('roles', 'plants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'id_role'  => 'required|exists:roles,id',
            'id_plant' => 'required|exists:plants,id',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'id_role'  => $request->id_role,
            'id_plant' => $request->id_plant,
        ]);

        // Assign Spatie role
        $role = Role::findOrFail($request->id_role);
        $user->assignRole($role->role);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['role', 'plant', 'allowedPlants']);
        return view('super-admin.input-user.show', compact('user'));
    }

    /**
     * Show the form for editing the existing resource.
     */
    public function edit(User $user)
    {
        $roles  = Role::all();
        $plants = Plant::orderBy('plant')->get();
        $user->load(['role', 'plant']);
        return view('super-admin.input-user.edit', compact('user', 'roles', 'plants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'id_role'  => 'required|exists:roles,id',
            'id_plant' => 'required|exists:plants,id',
        ]);

        $updateData = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'id_role'  => $request->id_role,
            'id_plant' => $request->id_plant,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Sync Spatie role
        $role = Role::findOrFail($request->id_role);
        $user->syncRoles($role->role);

        // Jika role berubah dari Manager, hapus allowed plants & reset active plant
        if (strtolower($role->role) !== 'manager') {
            $user->allowedPlants()->detach();
            $user->update(['active_plant_id' => null]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    // ===================================================================
    // MANAGER: Assign Plants — Halaman Terpisah
    // ===================================================================

    /**
     * Tampilkan halaman assign plant untuk user Manager.
     */
    public function assignPlants(User $user)
    {
        // Hanya bisa diakses untuk user dengan role Manager
        if (!$user->isManager()) {
            return redirect()->route('users.index')
                ->with('error', 'Fitur assign plant hanya tersedia untuk role Manager.');
        }

        $user->load(['role', 'plant', 'allowedPlants']);
        $plants = Plant::orderBy('plant')->get();
        $selectedPlantIds = $user->allowedPlants->pluck('id')->toArray();

        return view('super-admin.input-user.assign-plants', compact('user', 'plants', 'selectedPlantIds'));
    }

    /**
     * Simpan perubahan assign plant untuk user Manager.
     */
    public function saveAssignPlants(Request $request, User $user)
    {
        if (!$user->isManager()) {
            return redirect()->route('users.index')
                ->with('error', 'Fitur assign plant hanya tersedia untuk role Manager.');
        }

        $request->validate([
            'allowed_plants'   => 'nullable|array',
            'allowed_plants.*' => 'exists:plants,id',
        ]);

        $allowedPlants = $request->input('allowed_plants', []);

        // Sync allowed plants
        $user->allowedPlants()->sync($allowedPlants);

        // Jika active_plant_id tidak lagi dalam daftar, reset
        if ($user->active_plant_id && !in_array($user->active_plant_id, $allowedPlants)) {
            $user->update(['active_plant_id' => null]);
        }

        return redirect()->route('users.index')
            ->with('success', "Akses plant untuk Manager \"{$user->name}\" berhasil diperbarui!");
    }
}
