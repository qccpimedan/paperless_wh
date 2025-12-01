<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->get();
        return view('super-admin.input-role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string|max:255|unique:roles,role'
        ]);

        Role::create([
            'role' => $request->role
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('super-admin.input-role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('super-admin.input-role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role' => 'required|string|max:255|unique:roles,role,' . $role->id
        ]);

        $role->update([
            'role' => $request->role
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }
}
