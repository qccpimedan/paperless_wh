<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AccessControlController extends Controller
{
    private function isSuperAdmin($user): bool
    {
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('superadmin')) {
            return true;
        }

        return strtolower(optional($user->role)->role ?? '') === 'superadmin';
    }

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
            if (!$this->isSuperAdmin($user)) {
                abort(403, 'Unauthorized access - Anda harus menjadi Super Admin');
            }

            // Get all roles (superadmin included for display; editing superadmin remains blocked)
            $roles = Role::all();

            // Get all modules
            $modules = [
                'detail_komplain' => 'Detail Komplain',
                'golden_sample_retort' => 'Golden Sample Retort',
                'plants' => 'Data Plant',
                'produks' => 'Input Produk',
                'users' => 'Data User',
                'pemeriksaan_barang_mudah_pecah' => 'Pemeriksaan Barang Mudah Pecah',
                'pemeriksaan_kebersihan_area' => 'Pemeriksaan Kebersihan Area',
                'pemeriksaan_kedatangan_bahan_baku_penunjang' => 'Pemeriksaan Kedatangan Bahan Baku Penunjang',
                'pemeriksaan_kedatangan_chemical' => 'Pemeriksaan Kedatangan Chemical',
                'pemeriksaan_kedatangan_kemasan' => 'Pemeriksaan Kedatangan Kemasan',
                'pemeriksaan_produk_finish_good' => 'Pemeriksaan Produk Finish Good',
                'pemeriksaan_loading_kendaraan' => 'Pemeriksaan Loading Kendaraan',
                'pemeriksaan_loading_produk' => 'Pemeriksaan Loading Produk',
                'pemeriksaan_return_barang_customer' => 'Pemeriksaan Return Barang Customer',
                'pemeriksaan_suhu_ruang' => 'Pemeriksaan Suhu Ruang',
                'pemeriksaan_suhu_ruang_v2' => 'Pemeriksaan Suhu Ruang V2',
                'pemeriksaan_suhu_ruang_v3' => 'Pemeriksaan Suhu Ruang V3',
            ];

            foreach ($modules as $moduleKey => $moduleName) {
                Permission::findOrCreate('view_' . $moduleKey);
                Permission::findOrCreate('create_' . $moduleKey);
                Permission::findOrCreate('edit_' . $moduleKey);
                Permission::findOrCreate('delete_' . $moduleKey);
            }

            app(PermissionRegistrar::class)->forgetCachedPermissions();

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
            if (!$this->isSuperAdmin(auth()->user())) {
                return redirect()->back()->with('error', 'Unauthorized access');
            }

            // Find role by ID
            $role = Role::findOrFail($roleId);

            // Get all permission IDs from request (only checked permissions)
            $newPermissionIds = $request->input('permissions', []);

            // Filter out empty values
            $newPermissionIds = array_filter($newPermissionIds);

            // Convert to integers
            $newPermissionIds = array_map('intval', $newPermissionIds);

            // Get existing permissions for this role
            $existingPermissionIds = $role->permissions()->pluck('id')->toArray();

            // Get all permissions from the request (to identify which module is being updated)
            // We need to identify which module permissions are in the request
            $requestPermissions = Permission::whereIn('id', $newPermissionIds)->get();
            
            // Extract module names from requested permissions
            $requestedModules = [];
            foreach ($requestPermissions as $perm) {
                // Permission name format: action_module (e.g., view_detail_komplain)
                $parts = explode('_', $perm->name);
                if (count($parts) >= 2) {
                    // Remove the action part to get module
                    array_shift($parts);
                    $module = implode('_', $parts);
                    $requestedModules[$module] = true;
                }
            }

            // Get all existing permissions and remove those from the modules being updated
            $permissionsToKeep = [];
            foreach ($role->permissions as $perm) {
                $parts = explode('_', $perm->name);
                if (count($parts) >= 2) {
                    array_shift($parts);
                    $module = implode('_', $parts);
                    // Keep permission only if its module is NOT being updated
                    if (!isset($requestedModules[$module])) {
                        $permissionsToKeep[] = $perm->id;
                    }
                }
            }

            // Merge: keep permissions from other modules + add new permissions from current module
            $finalPermissionIds = array_unique(array_merge($permissionsToKeep, $newPermissionIds));

            // Get all permissions to sync
            $allPermissionsToSync = Permission::whereIn('id', $finalPermissionIds)->get();

            // Sync permissions for the role
            $role->syncPermissions($allPermissionsToSync);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

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
            if (!$this->isSuperAdmin(auth()->user())) {
                return response()->json(['error' => 'Unauthorized', 'success' => false], 403);
            }

            // Find role by ID
            $role = Role::findOrFail($roleId);
            
            // Get all permissions for this role
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