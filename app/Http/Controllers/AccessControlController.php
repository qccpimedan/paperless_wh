<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessControlController extends Controller
{
    /**
     * Display the access control dashboard
     */
    public function index()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                abort(401, 'User not authenticated');
            }

            $user = auth()->user();

            // Check if user has superadmin role
            if (!$user->hasRole('superadmin')) {
                abort(403, 'Unauthorized access - Anda harus menjadi Super Admin');
            }

            // Get all roles except superadmin
            $roles = Role::where('role', '!=', 'superadmin')->get();

            // Get all modules
            $modules = [
                'detail_komplain' => 'Detail Komplain',
                'golden_sample_retort' => 'Golden Sample Retort',
                'pemeriksaan_barang_mudah_pecah' => 'Pemeriksaan Barang Mudah Pecah',
                'pemeriksaan_kebersihan_area' => 'Pemeriksaan Kebersihan Area',
                'pemeriksaan_kedatangan_bahan_baku_penunjang' => 'Pemeriksaan Kedatangan Bahan Baku Penunjang',
                'pemeriksaan_kedatangan_chemical' => 'Pemeriksaan Kedatangan Chemical',
                'pemeriksaan_kedatangan_kemasan' => 'Pemeriksaan Kedatangan Kemasan',
                'pemeriksaan_loading_kendaraan' => 'Pemeriksaan Loading Kendaraan',
                'pemeriksaan_loading_produk' => 'Pemeriksaan Loading Produk',
                'pemeriksaan_return_barang_customer' => 'Pemeriksaan Return Barang Customer',
                'pemeriksaan_suhu_ruang' => 'Pemeriksaan Suhu Ruang',
                'pemeriksaan_suhu_ruang_v2' => 'Pemeriksaan Suhu Ruang V2',
                'pemeriksaan_suhu_ruang_v3' => 'Pemeriksaan Suhu Ruang V3',
            ];

            // Get all permissions
            $permissions = Permission::all();

            return view('access-control.index', compact('roles', 'modules', 'permissions'));
            
        } catch (\Exception $e) {
            return redirect('/dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update permissions for a role
     */
    public function update(Request $request, $roleId)
    {
        try {
            // Check authorization
            if (!auth()->user()->hasRole('superadmin')) {
                return redirect()->back()->with('error', 'Unauthorized access');
            }

            // Find role by ID
            $role = Role::findOrFail($roleId);

            // Validate that role is not superadmin
            if ($role->role === 'superadmin') {
                return redirect()->back()->with('error', 'Tidak bisa mengubah permissions Superadmin');
            }

            // Get all permission IDs from request
            $permissionIds = $request->input('permissions', []);

            // Filter out empty values
            $permissionIds = array_filter($permissionIds);

            // Convert to integers
            $permissionIds = array_map('intval', $permissionIds);

            // Sync permissions for the role
            $role->syncPermissions(Permission::whereIn('id', $permissionIds)->get());

            return redirect('access-control')->with('success', "Permissions untuk role '{$role->role}' berhasil diupdate!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get permissions for a specific role (AJAX)
     */
    public function getPermissions($roleId)
    {
        try {
            // Check authorization
            if (!auth()->user()->hasRole('superadmin')) {
                return response()->json(['error' => 'Unauthorized', 'success' => false], 403);
            }

            // Find role by ID
            $role = Role::findOrFail($roleId);
            $rolePermissions = $role->permissions()->pluck('id')->toArray();
            
            return response()->json([
                'permissions' => $rolePermissions,
                'role' => $role,
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}