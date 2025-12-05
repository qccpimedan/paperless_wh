<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define modules
        $modules = [
            'detail_komplain',
            'golden_sample_retort',
            'pemeriksaan_barang_mudah_pecah',
            'pemeriksaan_kebersihan_area',
            'pemeriksaan_kedatangan_bahan_baku_penunjang',
            'pemeriksaan_kedatangan_chemical',
            'pemeriksaan_kedatangan_kemasan',
            'pemeriksaan_loading_kendaraan',
            'pemeriksaan_loading_produk',
            'pemeriksaan_return_barang_customer',
            'pemeriksaan_suhu_ruang',
            'pemeriksaan_suhu_ruang_v2',
            'pemeriksaan_suhu_ruang_v3',
        ];

        // Create permissions for each module
        foreach ($modules as $module) {
            Permission::firstOrCreate(['name' => "view_{$module}", 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => "create_{$module}", 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => "edit_{$module}", 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => "delete_{$module}", 'guard_name' => 'web']);
        }

        // Get or create roles (using 'role' column from existing table)
        $superAdminRole = Role::firstOrCreate(
            ['role' => 'super_admin'],
            ['uuid' => \Illuminate\Support\Str::uuid(), 'name' => 'super_admin', 'guard_name' => 'web']
        );
        $adminRole = Role::firstOrCreate(
            ['role' => 'admin'],
            ['uuid' => \Illuminate\Support\Str::uuid(), 'name' => 'admin', 'guard_name' => 'web']
        );
        $spvQcRole = Role::firstOrCreate(
            ['role' => 'spv_qc'],
            ['uuid' => \Illuminate\Support\Str::uuid(), 'name' => 'spv_qc', 'guard_name' => 'web']
        );
        $qcRole = Role::firstOrCreate(
            ['role' => 'qc'],
            ['uuid' => \Illuminate\Support\Str::uuid(), 'name' => 'qc', 'guard_name' => 'web']
        );
        $produksiRole = Role::firstOrCreate(
            ['role' => 'produksi'],
            ['uuid' => \Illuminate\Support\Str::uuid(), 'name' => 'produksi', 'guard_name' => 'web']
        );

        // Assign all permissions to SuperAdmin
        $superAdminRole->syncPermissions(Permission::all());

        // Assign permissions to Admin (view, create, edit - no delete)
        $adminPermissions = [];
        foreach ($modules as $module) {
            $adminPermissions[] = "view_{$module}";
            $adminPermissions[] = "create_{$module}";
            $adminPermissions[] = "edit_{$module}";
        }
        $adminRole->syncPermissions($adminPermissions);

        // Assign permissions to SPV QC (view, create, edit - no delete)
        $spvQcRole->syncPermissions($adminPermissions);

        // Assign permissions to QC (view only)
        $qcPermissions = [];
        foreach ($modules as $module) {
            $qcPermissions[] = "view_{$module}";
        }
        $qcRole->syncPermissions($qcPermissions);

        // Assign permissions to Produksi (view only)
        $produksiPermissions = [];
        foreach ($modules as $module) {
            $produksiPermissions[] = "view_{$module}";
        }
        $produksiRole->syncPermissions($produksiPermissions);
    }
}