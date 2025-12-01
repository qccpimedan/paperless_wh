<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\PemeriksaanLoadingProdukController;
use App\Http\Controllers\PemeriksaanLoadingKendaraanController;
use App\Http\Controllers\PemeriksaanReturnBarangCustomerController;
use App\Http\Controllers\PemeriksaanSuhuRuangController;
use App\Http\Controllers\PemeriksaanSuhuRuangV2Controller;
use App\Http\Controllers\PemeriksaanSuhuRuangV3Controller;
use App\Http\Controllers\DetailKomplainController;
use App\Http\Controllers\GoldenSampleReportController;
use App\Http\Controllers\PemeriksaanBarangMudahPecahController;


// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Dashboard
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');


// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Data Master Routes (Super Admin)
    Route::prefix('super-admin')->group(function () {
        // Role Management Routes
        Route::resource('roles', RoleController::class);
        // Plant Management Routes
        Route::resource('plants', PlantController::class);
        // User Management Routes
        Route::resource('users', UserController::class);
        // Barang Management Routes
        Route::resource('barangs', BarangController::class);
        // Bahan Management Routes
        Route::resource('bahans', BahanController::class);
        // Customer Management Routes
        Route::resource('customers', CustomerController::class);
        // Shift Management Routes
        Route::resource('shifts', ShiftController::class);
        // Distributor Management Routes
        Route::resource('distributors', DistributorController::class);
        // Produsen Management Routes
        Route::resource('produsens', ProdusenController::class);
        // Chemical Management Routes
        Route::resource('chemicals', ChemicalController::class);
        // Jenis Kendaraan Management Routes
        Route::resource('jenis-kendaraans', JenisKendaraanController::class);
        // Tujuan Pengiriman Management Routes
        Route::resource('tujuan-pengirimans', TujuanPengirimanController::class);
        // Supir Management Routes
        Route::resource('supirs', SupirController::class);
        // Produk Management Routes
        Route::resource('produks', ProdukController::class);
        // Ekspedisi Management Routes
        Route::resource('ekspedisis', EkspedisiController::class);
        // Input Area Management Routes
        Route::resource('input-areas', InputAreaController::class);
        // Input Master Form Management Routes
        Route::resource('input-master-forms', InputMasterFormController::class);
        // Std Precooling Management Routes
        Route::resource('std-precoolings', StdPrecoolingController::class);
        // Input Deskripsi Management Routes
        Route::resource('input-deskripsis', InputDeskripsiController::class);
    });
    
    // QC System Routes
    Route::prefix('qc-sistem')->group(function () {
        // Routes Menu
        Route::resource('pemeriksaan-kedatangan-kemasan', PemeriksaanKedatanganKemasanController::class);
        Route::resource('pemeriksaan-bahan-baku', PemeriksaanKedatanganBahanBakuPenunjangController::class);
        Route::resource('pemeriksaan-chemical', PemeriksaanKedatanganChemicalController::class);
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

        // route history per 2 jam
        Route::get('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang}/history', [PemeriksaanSuhuRuangController::class, 'history'])->name('pemeriksaan-suhu-ruang.history');
        Route::get('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2}/history', [PemeriksaanSuhuRuangV2Controller::class, 'history'])->name('pemeriksaan-suhu-ruang-v2.history');
        Route::get('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3}/history', [PemeriksaanSuhuRuangV3Controller::class, 'history'])->name('pemeriksaan-suhu-ruang-v3.history');
        
        // API routes untuk check editable records
        Route::get('api/check-editable-records', [PemeriksaanSuhuRuangController::class, 'checkEditableRecords'])->name('api.check-editable-records');
        Route::get('api/check-editable-records-v2', [PemeriksaanSuhuRuangV2Controller::class, 'checkEditableRecords'])->name('api.check-editable-records-v2');
        Route::get('api/editable-records', [PemeriksaanSuhuRuangController::class, 'getEditableRecordsApi'])->name('api.editable-records');
        Route::get('api/editable-records-v2', [PemeriksaanSuhuRuangV2Controller::class, 'getEditableRecordsApi'])->name('api.editable-records-v2');
        
        // Upload File
        Route::post('detail-komplain/{detailKomplain:uuid}/upload-suplier', [DetailKomplainController::class, 'uploadSuplier'])->name('detail-komplain.upload-suplier');
        
        // Export PDF
        Route::get('detail-komplain/{detailKomplain:uuid}/export-pdf', [DetailKomplainController::class, 'exportPdf'])->name('detail-komplain.export-pdf');
        
        // AJAX routes untuk dependent dropdown
        Route::get('api/area-locations/{idArea}', [PemeriksaanBarangMudahPecahController::class, 'getAreaLocations'])->name('api.area-locations');
        Route::get('api/barang-details/{idBarang}', [PemeriksaanBarangMudahPecahController::class, 'getBarangDetails'])->name('api.barang-details');
    });
});
