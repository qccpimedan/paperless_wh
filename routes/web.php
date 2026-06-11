<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccessControlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ProdusenController;
use App\Http\Controllers\ChemicalController;
use App\Http\Controllers\JenisKendaraanController;
use App\Http\Controllers\TujuanPengirimanController;
use App\Http\Controllers\SupirController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\EkspedisiController;
use App\Http\Controllers\StdPrecoolingController;
use App\Http\Controllers\InputAreaController;
use App\Http\Controllers\InputMasterFormController;
use App\Http\Controllers\InputDeskripsiController;
use App\Http\Controllers\PemeriksaanKebersihanAreaController;
use App\Http\Controllers\PemeriksaanKedatanganChemicalController;
use App\Http\Controllers\PemeriksaanKedatanganKemasanController;
use App\Http\Controllers\PemeriksaanKedatanganBahanBakuPenunjangController;
use App\Http\Controllers\PemeriksaanProdukFinishGoodController;
use App\Http\Controllers\PemeriksaanLoadingProdukController;
use App\Http\Controllers\PemeriksaanLoadingKendaraanController;
use App\Http\Controllers\PemeriksaanReturnBarangCustomerController;
use App\Http\Controllers\PemeriksaanSuhuRuangController;
use App\Http\Controllers\PemeriksaanSuhuRuangV3Controller;
use App\Http\Controllers\PemeriksaanSuhuRuangV2Controller;
use App\Http\Controllers\DetailKomplainController;
use App\Http\Controllers\GoldenSampleReportController;
use App\Http\Controllers\PemeriksaanBarangMudahPecahController;
use App\Http\Controllers\BahanKemasanController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Auth;

// Redirect root to login
Route::match(['get', 'post'], '/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});
// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', function () {
    return redirect('/login');
});
// Dashboard
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

// Protected Routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/debug/check-roles', function () {
    try {
        // 1. Cek table roles
        $allRoles = \Spatie\Permission\Models\Role::all();
        
        $rolesData = $allRoles->map(function($role) {
            return [
                'id' => $role->id,
                'role' => $role->role,
                'guard_name' => $role->guard_name,
                'permissions_count' => $role->permissions()->count(),
            ];
        });
        
        // 2. Cek roles yang bukan superadmin
        $nonSuperAdminRoles = \Spatie\Permission\Models\Role::where('role', '!=', 'superadmin')->get();
        
        // 3. Cek apakah table roles kosong
        $rolesCount = \Spatie\Permission\Models\Role::count();
        
        // 4. Cek semua permissions
        $permissions = \Spatie\Permission\Models\Permission::all();
        
        $info = [
            'Total Roles' => $rolesCount,
            'All Roles' => $rolesData,
            'Non SuperAdmin Roles Count' => $nonSuperAdminRoles->count(),
            'Non SuperAdmin Roles' => $nonSuperAdminRoles->pluck('role')->toArray(),
            'Total Permissions' => $permissions->count(),
            'Sample Permissions' => $permissions->take(5)->pluck('name')->toArray(),
        ];
        
        return '<pre style="background: #f4f4f4; padding: 20px; font-family: monospace; white-space: pre-wrap;">' . 
               json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . 
               '</pre>';
    }   catch (\Exception $e) {
        return '<pre style="color: red; padding: 20px;">' . 
               'Error: ' . $e->getMessage() . 
               '\nFile: ' . $e->getFile() . 
               '\nLine: ' . $e->getLine() . 
               '</pre>';
    }
})->middleware('auth');
    // Access Control Routes (Only SuperAdmin)
    Route::get('access-control', [AccessControlController::class, 'index'])->name('access-control.index');
    Route::put('access-control/{roleId}', [AccessControlController::class, 'update'])->name('access-control.update');
    Route::get('access-control/{roleId}/permissions', [AccessControlController::class, 'getPermissions'])->name('access-control.permissions');

    // Manager - Switch Plant Routes
    Route::post('manager/switch-plant', [ManagerController::class, 'switchPlant'])->name('manager.switch-plant');
    Route::post('manager/reset-plant', [ManagerController::class, 'resetPlant'])->name('manager.reset-plant');
    
    // Data Master Routes (Super Admin)
    Route::prefix('super-admin')->group(function () {
        // Download template excel
        Route::get('bahans/template', [BahanController::class, 'template'])->name('bahans.template');
        Route::get('bahan-kemasans/template', [BahanKemasanController::class, 'template'])->name('bahan-kemasans.template');
        Route::get('produks/template', [ProdukController::class, 'template'])->name('produks.template');
        Route::get('customers/template', [CustomerController::class, 'template'])->name('customers.template');
        Route::get('barangs/template', [BarangController::class, 'template'])->name('barangs.template');
        Route::get('ekspedisis/template', [EkspedisiController::class, 'template'])->name('ekspedisis.template');

        // Role Management Routes
        Route::resource('roles', RoleController::class);
        // Plant Management Routes
        Route::resource('plants', PlantController::class);
        // User Management Routes
        Route::resource('users', UserController::class);
        // Manager: Assign Plant Access (halaman terpisah)
        Route::get('users/{user}/assign-plants', [UserController::class, 'assignPlants'])->name('users.assign-plants');
        Route::post('users/{user}/assign-plants', [UserController::class, 'saveAssignPlants'])->name('users.save-assign-plants');

        // Barang Management Routes
        Route::resource('barangs', BarangController::class);
        Route::post('barangs/import', [BarangController::class, 'import'])->name('barangs.import');
        // Bahan Management Routes
        Route::resource('bahans', BahanController::class);
        Route::post('bahans/import', [BahanController::class, 'import'])->name('bahans.import');
        // Customer Management Routes
        Route::resource('customers', CustomerController::class);
        Route::post('customers/import', [CustomerController::class, 'import'])->name('customers.import');
        // Shift Management Routes
        Route::resource('shifts', ShiftController::class);
        // Distributor Management Routes
        Route::get('distributors/template', [DistributorController::class, 'template'])->name('distributors.template');
        Route::post('distributors/import', [DistributorController::class, 'import'])->name('distributors.import');
        Route::resource('distributors', DistributorController::class);
        // Produsen Management Routes
        Route::get('produsens/template', [ProdusenController::class, 'template'])->name('produsens.template');
        Route::post('produsens/import', [ProdusenController::class, 'import'])->name('produsens.import');
        Route::resource('produsens', ProdusenController::class);
        // Chemical Management Routes
        Route::resource('chemicals', ChemicalController::class);
        // Bahan Kemasan Management Routes
        Route::resource('bahan-kemasans', BahanKemasanController::class);
        Route::post('bahan-kemasans/import', [BahanKemasanController::class, 'import'])->name('bahan-kemasans.import');
        // Jenis Kendaraan Management Routes
        Route::resource('jenis-kendaraans', JenisKendaraanController::class);
        // Tujuan Pengiriman Management Routes
        Route::resource('tujuan-pengirimans', TujuanPengirimanController::class);
        // Supir Management Routes
        Route::resource('supirs', SupirController::class);
        // Produk Management Routes
        Route::get('produks/export-update', [ProdukController::class, 'exportUpdate'])->name('produks.export-update');
        Route::resource('produks', ProdukController::class);
        Route::post('produks/import', [ProdukController::class, 'import'])->name('produks.import');
        Route::post('produks/import-update', [ProdukController::class, 'importUpdate'])->name('produks.import-update');
        // Ekspedisi Management Routes
        Route::resource('ekspedisis', EkspedisiController::class);
        Route::post('ekspedisis/import', [EkspedisiController::class, 'import'])->name('ekspedisis.import');
        // Input Area Management Routes
        Route::resource('input-areas', InputAreaController::class);
        // Input Master Form Management Routes
        Route::resource('input-master-forms', InputMasterFormController::class);
        // Std Precooling Management Routes
        Route::get('std-precoolings/template', [StdPrecoolingController::class, 'template'])->name('std-precoolings.template');
        Route::post('std-precoolings/import', [StdPrecoolingController::class, 'import'])->name('std-precoolings.import');
        Route::resource('std-precoolings', StdPrecoolingController::class);
        // Input Deskripsi Management Routes
        Route::get('input-deskripsis/template', [InputDeskripsiController::class, 'template'])->name('input-deskripsis.template');
        Route::post('input-deskripsis/import', [InputDeskripsiController::class, 'import'])->name('input-deskripsis.import');
        Route::resource('input-deskripsis', InputDeskripsiController::class);
    });
    
    // QC System Routes
    Route::prefix('qc-sistem')->group(function () {
        // Export PDF Routes (harus sebelum resource routes)
        Route::get('pemeriksaan-kedatangan-kemasan/export-pdf', [PemeriksaanKedatanganKemasanController::class, 'exportPDF'])->name('pemeriksaan-kedatangan-kemasan.export-pdf');
        Route::get('pemeriksaan-bahan-baku/export-pdf', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'exportPDF'])->name('pemeriksaan-bahan-baku.export-pdf');
        Route::get('pemeriksaan-chemical/export-pdf', [PemeriksaanKedatanganChemicalController::class, 'exportPDF'])->name('pemeriksaan-chemical.export-pdf');
        Route::get('pemeriksaan-produk-finish-good/export-pdf', [PemeriksaanProdukFinishGoodController::class, 'exportPDF'])->name('pemeriksaan-produk-finish-good.export-pdf');
        Route::get('pemeriksaan-loading-produk/export-pdf', [PemeriksaanLoadingProdukController::class, 'exportPDF'])->name('pemeriksaan-loading-produk.export-pdf');
        Route::get('pemeriksaan-loading-kendaraan/export-pdf', [PemeriksaanLoadingKendaraanController::class, 'exportPDF'])->name('pemeriksaan-loading-kendaraan.export-pdf');
        Route::get('return-barang/export-pdf', [PemeriksaanReturnBarangCustomerController::class, 'exportPDF'])->name('return-barang.export-pdf');
        Route::get('pemeriksaan-kebersihan-area/export-pdf/{uuid?}', [PemeriksaanKebersihanAreaController::class, 'exportPDF'])->name('pemeriksaan-kebersihan-area.export-pdf');
        Route::get('golden-sample-reports/export-pdf', [GoldenSampleReportController::class, 'exportPDF'])->name('golden-sample-reports.export-pdf');
        Route::get('pemeriksaan-barang-mudah-pecah/export-pdf', [PemeriksaanBarangMudahPecahController::class, 'exportPDF'])->name('pemeriksaan-barang-mudah-pecah.export-pdf');
        Route::get('pemeriksaan-suhu-ruang/export-pdf', [PemeriksaanSuhuRuangController::class, 'exportPDF'])->name('pemeriksaan-suhu-ruang.export-pdf');
        Route::get('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/print-pdf', [PemeriksaanSuhuRuangController::class, 'printPDF'])->name('pemeriksaan-suhu-ruang.print-pdf');
        Route::get('pemeriksaan-suhu-ruang-v2/export-pdf', [PemeriksaanSuhuRuangV2Controller::class, 'exportPDF'])->name('pemeriksaan-suhu-ruang-v2.export-pdf');
        Route::get('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/print-pdf', [PemeriksaanSuhuRuangV2Controller::class, 'printPDF'])->name('pemeriksaan-suhu-ruang-v2.print-pdf');
        Route::get('pemeriksaan-suhu-ruang-v3/export-pdf', [PemeriksaanSuhuRuangV3Controller::class, 'exportPDF'])->name('pemeriksaan-suhu-ruang-v3.export-pdf');
        Route::get('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/print-pdf', [PemeriksaanSuhuRuangV3Controller::class, 'printPDF'])->name('pemeriksaan-suhu-ruang-v3.print-pdf');
        Route::get('detail-komplain/export-pdf/{uuid?}', [DetailKomplainController::class, 'exportPdf'])->name('detail-komplain.export-pdf');
        
        // Routes Tambah data per uuid
        Route::get('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/tambah-baris', [PemeriksaanKedatanganKemasanController::class, 'createRow'])->name('pemeriksaan-kedatangan-kemasan.tambah-baris');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/tambah-baris', [PemeriksaanKedatanganKemasanController::class, 'storeRow'])->name('pemeriksaan-kedatangan-kemasan.tambah-baris.store');
        Route::get('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/tambah-baris', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'createRow'])->name('pemeriksaan-bahan-baku.tambah-baris');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/tambah-baris', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'storeRow'])->name('pemeriksaan-bahan-baku.store-baris');
        Route::get('pemeriksaan-chemical/{pemeriksaanChemical:uuid}/tambah-baris', [PemeriksaanKedatanganChemicalController::class, 'createRow'])->name('pemeriksaan-chemical.tambah-baris');
        Route::post('pemeriksaan-chemical/{pemeriksaanChemical:uuid}/tambah-baris', [PemeriksaanKedatanganChemicalController::class, 'storeRow'])->name('pemeriksaan-chemical.store-baris');
        
        // Upload File Detail Komplain
        Route::post('detail-komplain/{detail_komplain:uuid}/upload-supplier', [DetailKomplainController::class, 'uploadSupplier'])->name('detail-komplain.upload-supplier');
        
        // Routes Menu
        Route::resource('pemeriksaan-kedatangan-kemasan', PemeriksaanKedatanganKemasanController::class);
        Route::resource('pemeriksaan-bahan-baku', PemeriksaanKedatanganBahanBakuPenunjangController::class);
        Route::resource('pemeriksaan-chemical', PemeriksaanKedatanganChemicalController::class);
        Route::resource('pemeriksaan-produk-finish-good', PemeriksaanProdukFinishGoodController::class);
        Route::get('pemeriksaan-loading-produk/download-template', [PemeriksaanLoadingProdukController::class, 'downloadTemplate'])->name('pemeriksaan-loading-produk.download-template');
        Route::get('pemeriksaan-loading-produk/download-template-universal', [PemeriksaanLoadingProdukController::class, 'downloadTemplateUniversal'])->name('pemeriksaan-loading-produk.download-template-universal');
        Route::post('pemeriksaan-loading-produk/import-universal', [PemeriksaanLoadingProdukController::class, 'importUniversal'])->name('pemeriksaan-loading-produk.import-universal');
        Route::resource('pemeriksaan-loading-produk', PemeriksaanLoadingProdukController::class);
        Route::resource('pemeriksaan-loading-kendaraan', PemeriksaanLoadingKendaraanController::class);
        Route::resource('return-barang', PemeriksaanReturnBarangCustomerController::class)->parameters(['return-barang' => 'pemeriksaanReturnBarangCustomer:uuid']);
        Route::resource('pemeriksaan-kebersihan-area', PemeriksaanKebersihanAreaController::class);
        Route::resource('pemeriksaan-suhu-ruang', PemeriksaanSuhuRuangController::class);
        Route::resource('pemeriksaan-suhu-ruang-v2', PemeriksaanSuhuRuangV2Controller::class);
        Route::resource('pemeriksaan-suhu-ruang-v3', PemeriksaanSuhuRuangV3Controller::class);
        Route::resource('detail-komplain', DetailKomplainController::class)->parameters(['detail-komplain' => 'detailKomplain:uuid']);
        Route::resource('golden-sample-reports', GoldenSampleReportController::class);
        Route::resource('pemeriksaan-barang-mudah-pecah', PemeriksaanBarangMudahPecahController::class);
        
        
        // Routes untuk verifikasi pemeriksaan barang mudah pecah
        Route::post('barang-mudah-pecah/batch-verify', [PemeriksaanBarangMudahPecahController::class, 'batchVerify'])->name('pemeriksaan-barang-mudah-pecah.batch-verify');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/send-to-produksi', [PemeriksaanBarangMudahPecahController::class, 'sendToProduksi'])->name('pemeriksaan-barang-mudah-pecah.send-to-produksi');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/approve-produksi', [PemeriksaanBarangMudahPecahController::class, 'approveProduksi'])->name('pemeriksaan-barang-mudah-pecah.approve-produksi');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/reject-produksi', [PemeriksaanBarangMudahPecahController::class, 'rejectProduksi'])->name('pemeriksaan-barang-mudah-pecah.reject-produksi');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/approve-spv', [PemeriksaanBarangMudahPecahController::class, 'approveSPV'])->name('pemeriksaan-barang-mudah-pecah.approve-spv');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/reject-spv', [PemeriksaanBarangMudahPecahController::class, 'rejectSPV'])->name('pemeriksaan-barang-mudah-pecah.reject-spv');
        
        // Routes untuk verifikasi detail komplain
        Route::post('detail-komplain/batch-verify', [DetailKomplainController::class, 'batchVerify'])->name('detail-komplain.batch-verify');
        Route::post('/detail-komplain/batch-verify', [DetailKomplainController::class, 'batchVerify'])->name('detail-komplain.batch-verify');
        Route::post('/detail-komplain/{detail_komplain:uuid}/send-to-produksi', [DetailKomplainController::class, 'sendToProduksi'])->name('detail-komplain.send-to-produksi');
        Route::post('/detail-komplain/{detail_komplain:uuid}/approve-qc', [DetailKomplainController::class, 'approveQC'])->name('detail-komplain.approve-qc');
        Route::post('/detail-komplain/{detail_komplain:uuid}/reject-qc', [DetailKomplainController::class, 'rejectQC'])->name('detail-komplain.reject-qc');
        Route::post('/detail-komplain/{detail_komplain:uuid}/approve-spv', [DetailKomplainController::class, 'approveSPV'])->name('detail-komplain.approve-spv');
        Route::post('/detail-komplain/{detail_komplain:uuid}/reject-spv', [DetailKomplainController::class, 'rejectSPV'])->name('detail-komplain.reject-spv');
        
        // Routes untuk verifikasi golden sample report
        Route::post('golden-sample-reports/batch-verify', [GoldenSampleReportController::class, 'batchVerify'])->name('golden-sample-reports.batch-verify');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/send-to-produksi', [GoldenSampleReportController::class, 'sendToProduksi'])->name('golden-sample-reports.send-to-produksi');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/approve-produksi', [GoldenSampleReportController::class, 'approveProduksi'])->name('golden-sample-reports.approve-produksi');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/reject-produksi', [GoldenSampleReportController::class, 'rejectProduksi'])->name('golden-sample-reports.reject-produksi');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/approve-spv', [GoldenSampleReportController::class, 'approveSPV'])->name('golden-sample-reports.approve-spv');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/reject-spv', [GoldenSampleReportController::class, 'rejectSPV'])->name('golden-sample-reports.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kebersihan area
        Route::post('kebersihan-area/batch-verify', [PemeriksaanKebersihanAreaController::class, 'batchVerify'])->name('kebersihan-area.batch-verify');
        Route::post('kebersihan-area/{pemeriksaanKebersihanArea:uuid}/send-to-produksi', [PemeriksaanKebersihanAreaController::class, 'sendToProduksi'])->name('kebersihan-area.send-to-produksi');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/approve-produksi', [PemeriksaanKebersihanAreaController::class, 'approveProduksi'])->name('pemeriksaan-kebersihan-area.approve-produksi');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/reject-produksi', [PemeriksaanKebersihanAreaController::class, 'rejectProduksi'])->name('pemeriksaan-kebersihan-area.reject-produksi');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/approve-spv', [PemeriksaanKebersihanAreaController::class, 'approveSPV'])->name('pemeriksaan-kebersihan-area.approve-spv');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/reject-spv', [PemeriksaanKebersihanAreaController::class, 'rejectSPV'])->name('pemeriksaan-kebersihan-area.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang (food prosesing)
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/send-to-produksi', [PemeriksaanSuhuRuangController::class, 'sendToProduksi'])->name('pemeriksaan-suhu-ruang.send-to-produksi');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/approve-produksi', [PemeriksaanSuhuRuangController::class, 'approveProduksi'])->name('pemeriksaan-suhu-ruang.approve-produksi');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/reject-produksi', [PemeriksaanSuhuRuangController::class, 'rejectProduksi'])->name('pemeriksaan-suhu-ruang.reject-produksi');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang v1
        Route::post('pemeriksaan-suhu-ruang/batch-verify', [PemeriksaanSuhuRuangController::class, 'batchVerify'])->name('pemeriksaan-suhu-ruang.batch-verify');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/approve-spv', [PemeriksaanSuhuRuangController::class, 'approveSPV'])->name('pemeriksaan-suhu-ruang.approve-spv');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/reject-spv', [PemeriksaanSuhuRuangController::class, 'rejectSPV'])->name('pemeriksaan-suhu-ruang.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang v2
        Route::post('pemeriksaan-suhu-ruang-v2/batch-verify', [PemeriksaanSuhuRuangV2Controller::class, 'batchVerify'])->name('pemeriksaan-suhu-ruang-v2.batch-verify');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/approve-spv', [PemeriksaanSuhuRuangV2Controller::class, 'approveSPV'])->name('pemeriksaan-suhu-ruang-v2.approve-spv');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/reject-spv', [PemeriksaanSuhuRuangV2Controller::class, 'rejectSPV'])->name('pemeriksaan-suhu-ruang-v2.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang v3
        Route::post('pemeriksaan-suhu-ruang-v3/batch-verify', [PemeriksaanSuhuRuangV3Controller::class, 'batchVerify'])->name('pemeriksaan-suhu-ruang-v3.batch-verify');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/approve-spv', [PemeriksaanSuhuRuangV3Controller::class, 'approveSPV'])->name('pemeriksaan-suhu-ruang-v3.approve-spv');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/reject-spv', [PemeriksaanSuhuRuangV3Controller::class, 'rejectSPV'])->name('pemeriksaan-suhu-ruang-v3.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang v2 (cs meat)
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/send-to-produksi', [PemeriksaanSuhuRuangV2Controller::class, 'sendToProduksi'])->name('pemeriksaan-suhu-ruang-v2.send-to-produksi');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/approve-produksi', [PemeriksaanSuhuRuangV2Controller::class, 'approveProduksi'])->name('pemeriksaan-suhu-ruang-v2.approve-produksi');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/reject-produksi', [PemeriksaanSuhuRuangV2Controller::class, 'rejectProduksi'])->name('pemeriksaan-suhu-ruang-v2.reject-produksi');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang v3 (gudang dry)
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/send-to-produksi', [PemeriksaanSuhuRuangV3Controller::class, 'sendToProduksi'])->name('pemeriksaan-suhu-ruang-v3.send-to-produksi');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/approve-produksi', [PemeriksaanSuhuRuangV3Controller::class, 'approveProduksi'])->name('pemeriksaan-suhu-ruang-v3.approve-produksi');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/reject-produksi', [PemeriksaanSuhuRuangV3Controller::class, 'rejectProduksi'])->name('pemeriksaan-suhu-ruang-v3.reject-produksi');
        
        // Routes untuk verifikasi pemeriksaan return barang customer
        Route::post('return-barang/batch-verify', [PemeriksaanReturnBarangCustomerController::class, 'batchVerify'])->name('return-barang.batch-verify');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/send-to-produksi', [PemeriksaanReturnBarangCustomerController::class, 'sendToProduksi'])->name('return-barang.send-to-produksi');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/approve-produksi', [PemeriksaanReturnBarangCustomerController::class, 'approveProduksi'])->name('return-barang.approve-produksi');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/reject-produksi', [PemeriksaanReturnBarangCustomerController::class, 'rejectProduksi'])->name('return-barang.reject-produksi');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/approve-spv', [PemeriksaanReturnBarangCustomerController::class, 'approveSPV'])->name('return-barang.approve-spv');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/reject-spv', [PemeriksaanReturnBarangCustomerController::class, 'rejectSPV'])->name('return-barang.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan loading produk
        Route::post('pemeriksaan-loading-produk/batch-verify', [PemeriksaanLoadingProdukController::class, 'batchVerify'])->name('pemeriksaan-loading-produk.batch-verify');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/send-to-produksi', [PemeriksaanLoadingProdukController::class, 'sendToProduksi'])->name('pemeriksaan-loading-produk.send-to-produksi');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/approve-produksi', [PemeriksaanLoadingProdukController::class, 'approveProduksi'])->name('pemeriksaan-loading-produk.approve-produksi');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/reject-produksi', [PemeriksaanLoadingProdukController::class, 'rejectProduksi'])->name('pemeriksaan-loading-produk.reject-produksi');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/approve-spv', [PemeriksaanLoadingProdukController::class, 'approveSPV'])->name('pemeriksaan-loading-produk.approve-spv');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/reject-spv', [PemeriksaanLoadingProdukController::class, 'rejectSPV'])->name('pemeriksaan-loading-produk.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan loading kendaraan
        Route::post('pemeriksaan-loading-kendaraan/batch-verify', [PemeriksaanLoadingKendaraanController::class, 'batchVerify'])->name('pemeriksaan-loading-kendaraan.batch-verify');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/send-to-produksi', [PemeriksaanLoadingKendaraanController::class, 'sendToProduksi'])->name('pemeriksaan-loading-kendaraan.send-to-produksi');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/approve-produksi', [PemeriksaanLoadingKendaraanController::class, 'approveProduksi'])->name('pemeriksaan-loading-kendaraan.approve-produksi');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/reject-produksi', [PemeriksaanLoadingKendaraanController::class, 'rejectProduksi'])->name('pemeriksaan-loading-kendaraan.reject-produksi');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/approve-spv', [PemeriksaanLoadingKendaraanController::class, 'approveSPV'])->name('pemeriksaan-loading-kendaraan.approve-spv');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/reject-spv', [PemeriksaanLoadingKendaraanController::class, 'rejectSPV'])->name('pemeriksaan-loading-kendaraan.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kedatangan kemasan
        Route::post('pemeriksaan-kedatangan-kemasan/batch-verify', [PemeriksaanKedatanganKemasanController::class, 'batchVerify'])->name('pemeriksaan-kedatangan-kemasan.batch-verify');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/send-to-produksi', [PemeriksaanKedatanganKemasanController::class, 'sendToProduksi'])->name('pemeriksaan-kedatangan-kemasan.send-to-produksi');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/approve-produksi', [PemeriksaanKedatanganKemasanController::class, 'approveProduksi'])->name('pemeriksaan-kedatangan-kemasan.approve-produksi');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/reject-produksi', [PemeriksaanKedatanganKemasanController::class, 'rejectProduksi'])->name('pemeriksaan-kedatangan-kemasan.reject-produksi');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/approve-spv', [PemeriksaanKedatanganKemasanController::class, 'approveSPV'])->name('pemeriksaan-kedatangan-kemasan.approve-spv');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/reject-spv', [PemeriksaanKedatanganKemasanController::class, 'rejectSPV'])->name('pemeriksaan-kedatangan-kemasan.reject-spv');

        // Routes untuk verifikasi pemeriksaan produk finish good
        Route::post('pemeriksaan-produk-finish-good/batch-verify', [PemeriksaanProdukFinishGoodController::class, 'batchVerify'])->name('pemeriksaan-produk-finish-good.batch-verify');
        Route::post('pemeriksaan-produk-finish-good/{pemeriksaanProdukFinishGood:uuid}/send-to-produksi', [PemeriksaanProdukFinishGoodController::class, 'sendToProduksi'])->name('pemeriksaan-produk-finish-good.send-to-produksi');
        Route::post('pemeriksaan-produk-finish-good/{pemeriksaanProdukFinishGood:uuid}/approve-produksi', [PemeriksaanProdukFinishGoodController::class, 'approveProduksi'])->name('pemeriksaan-produk-finish-good.approve-produksi');
        Route::post('pemeriksaan-produk-finish-good/{pemeriksaanProdukFinishGood:uuid}/reject-produksi', [PemeriksaanProdukFinishGoodController::class, 'rejectProduksi'])->name('pemeriksaan-produk-finish-good.reject-produksi');
        Route::post('pemeriksaan-produk-finish-good/{pemeriksaanProdukFinishGood:uuid}/approve-spv', [PemeriksaanProdukFinishGoodController::class, 'approveSPV'])->name('pemeriksaan-produk-finish-good.approve-spv');
        Route::post('pemeriksaan-produk-finish-good/{pemeriksaanProdukFinishGood:uuid}/reject-spv', [PemeriksaanProdukFinishGoodController::class, 'rejectSPV'])->name('pemeriksaan-produk-finish-good.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kedatangan bahan baku penunjang
        Route::post('pemeriksaan-bahan-baku/batch-verify', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'batchVerify'])->name('pemeriksaan-bahan-baku.batch-verify');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/send-to-produksi', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'sendToProduksi'])->name('pemeriksaan-bahan-baku.send-to-produksi');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/approve-produksi', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'approveProduksi'])->name('pemeriksaan-bahan-baku.approve-produksi');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/reject-produksi', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'rejectProduksi'])->name('pemeriksaan-bahan-baku.reject-produksi');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/approve-spv', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'approveSPV'])->name('pemeriksaan-bahan-baku.approve-spv');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/reject-spv', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'rejectSPV'])->name('pemeriksaan-bahan-baku.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kedatangan chemical
        Route::post('pemeriksaan-chemical/batch-verify', [PemeriksaanKedatanganChemicalController::class, 'batchVerify'])->name('pemeriksaan-chemical.batch-verify');
        Route::post('pemeriksaan-chemical/{pemeriksaanKedatanganChemical:uuid}/send-to-produksi', [PemeriksaanKedatanganChemicalController::class, 'sendToProduksi'])->name('pemeriksaan-chemical.send-to-produksi');
        Route::post('pemeriksaan-chemical/{pemeriksaanChemical:uuid}/approve-produksi', [PemeriksaanKedatanganChemicalController::class, 'approveProduksi'])->name('pemeriksaan-chemical.approve-produksi');
        Route::post('pemeriksaan-chemical/{pemeriksaanChemical:uuid}/reject-produksi', [PemeriksaanKedatanganChemicalController::class, 'rejectProduksi'])->name('pemeriksaan-chemical.reject-produksi');
        Route::post('pemeriksaan-chemical/{pemeriksaanChemical:uuid}/approve-spv', [PemeriksaanKedatanganChemicalController::class, 'approveSPV'])->name('pemeriksaan-chemical.approve-spv');
        Route::post('pemeriksaan-chemical/{pemeriksaanChemical:uuid}/reject-spv', [PemeriksaanKedatanganChemicalController::class, 'rejectSPV'])->name('pemeriksaan-chemical.reject-spv');
        
        // route history per 2 jam
        Route::get('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang}/history', [PemeriksaanSuhuRuangController::class, 'history'])->name('pemeriksaan-suhu-ruang.history');
        Route::get('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2}/history', [PemeriksaanSuhuRuangV2Controller::class, 'history'])->name('pemeriksaan-suhu-ruang-v2.history');
        Route::get('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3}/history', [PemeriksaanSuhuRuangV3Controller::class, 'history'])->name('pemeriksaan-suhu-ruang-v3.history');
        
        // API routes untuk check editable records
        Route::get('api/check-editable-records', [PemeriksaanSuhuRuangController::class, 'checkEditableRecords'])->name('api.check-editable-records');
        Route::get('api/check-editable-records-v2', [PemeriksaanSuhuRuangV2Controller::class, 'checkEditableRecords'])->name('api.check-editable-records-v2');
        Route::get('api/editable-records', [PemeriksaanSuhuRuangController::class, 'getEditableRecordsApi'])->name('api.editable-records');
        Route::get('api/editable-records-v2', [PemeriksaanSuhuRuangV2Controller::class, 'getEditableRecordsApi'])->name('api.editable-records-v2');
        
        // AJAX routes untuk dependent dropdown
        Route::get('api/area-locations/{idArea}', [PemeriksaanBarangMudahPecahController::class, 'getAreaLocations'])->name('api.area-locations');
        Route::get('api/barang-details/{idBarang}', [PemeriksaanBarangMudahPecahController::class, 'getBarangDetails'])->name('api.barang-details');
        
        
    });
});
