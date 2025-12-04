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
use App\Http\Controllers\PemeriksaanSuhuRuangV3Controller;
use App\Http\Controllers\PemeriksaanSuhuRuangV2Controller;
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
        
        // Routes untuk verifikasi pemeriksaan barang mudah pecah
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/send-to-produksi', [PemeriksaanBarangMudahPecahController::class, 'sendToProduksi'])->name('pemeriksaan-barang-mudah-pecah.send-to-produksi');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/approve-produksi', [PemeriksaanBarangMudahPecahController::class, 'approveProduksi'])->name('pemeriksaan-barang-mudah-pecah.approve-produksi');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/reject-produksi', [PemeriksaanBarangMudahPecahController::class, 'rejectProduksi'])->name('pemeriksaan-barang-mudah-pecah.reject-produksi');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/approve-spv', [PemeriksaanBarangMudahPecahController::class, 'approveSPV'])->name('pemeriksaan-barang-mudah-pecah.approve-spv');
        Route::post('pemeriksaan-barang-mudah-pecah/{pemeriksaanBarangMudahPecah:uuid}/reject-spv', [PemeriksaanBarangMudahPecahController::class, 'rejectSPV'])->name('pemeriksaan-barang-mudah-pecah.reject-spv');
        
        // Routes untuk verifikasi detail komplain
        Route::post('detail-komplain/{detailKomplain:uuid}/send-to-qc', [DetailKomplainController::class, 'sendToQC'])->name('detail-komplain.send-to-qc');
        Route::post('detail-komplain/{detailKomplain:uuid}/approve-qc', [DetailKomplainController::class, 'approveQC'])->name('detail-komplain.approve-qc');
        Route::post('detail-komplain/{detailKomplain:uuid}/reject-qc', [DetailKomplainController::class, 'rejectQC'])->name('detail-komplain.reject-qc');
        Route::post('detail-komplain/{detailKomplain:uuid}/approve-spv', [DetailKomplainController::class, 'approveSPV'])->name('detail-komplain.approve-spv');
        Route::post('detail-komplain/{detailKomplain:uuid}/reject-spv', [DetailKomplainController::class, 'rejectSPV'])->name('detail-komplain.reject-spv');
        
        // Routes untuk verifikasi golden sample report
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/send-to-produksi', [GoldenSampleReportController::class, 'sendToProduksi'])->name('golden-sample-reports.send-to-produksi');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/approve-produksi', [GoldenSampleReportController::class, 'approveProduksi'])->name('golden-sample-reports.approve-produksi');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/reject-produksi', [GoldenSampleReportController::class, 'rejectProduksi'])->name('golden-sample-reports.reject-produksi');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/approve-spv', [GoldenSampleReportController::class, 'approveSPV'])->name('golden-sample-reports.approve-spv');
        Route::post('golden-sample-reports/{goldenSampleReport:uuid}/reject-spv', [GoldenSampleReportController::class, 'rejectSPV'])->name('golden-sample-reports.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kebersihan area
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/send-to-produksi', [PemeriksaanKebersihanAreaController::class, 'sendToProduksi'])->name('pemeriksaan-kebersihan-area.send-to-produksi');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/approve-produksi', [PemeriksaanKebersihanAreaController::class, 'approveProduksi'])->name('pemeriksaan-kebersihan-area.approve-produksi');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/reject-produksi', [PemeriksaanKebersihanAreaController::class, 'rejectProduksi'])->name('pemeriksaan-kebersihan-area.reject-produksi');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/approve-spv', [PemeriksaanKebersihanAreaController::class, 'approveSPV'])->name('pemeriksaan-kebersihan-area.approve-spv');
        Route::post('pemeriksaan-kebersihan-area/{pemeriksaanKebersihanArea:uuid}/reject-spv', [PemeriksaanKebersihanAreaController::class, 'rejectSPV'])->name('pemeriksaan-kebersihan-area.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang (food prosesing)
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/send-to-produksi', [PemeriksaanSuhuRuangController::class, 'sendToProduksi'])->name('pemeriksaan-suhu-ruang.send-to-produksi');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/approve-produksi', [PemeriksaanSuhuRuangController::class, 'approveProduksi'])->name('pemeriksaan-suhu-ruang.approve-produksi');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/reject-produksi', [PemeriksaanSuhuRuangController::class, 'rejectProduksi'])->name('pemeriksaan-suhu-ruang.reject-produksi');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/approve-spv', [PemeriksaanSuhuRuangController::class, 'approveSPV'])->name('pemeriksaan-suhu-ruang.approve-spv');
        Route::post('pemeriksaan-suhu-ruang/{pemeriksaanSuhuRuang:uuid}/reject-spv', [PemeriksaanSuhuRuangController::class, 'rejectSPV'])->name('pemeriksaan-suhu-ruang.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang v2 (cs meat)
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/send-to-produksi', [PemeriksaanSuhuRuangV2Controller::class, 'sendToProduksi'])->name('pemeriksaan-suhu-ruang-v2.send-to-produksi');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/approve-produksi', [PemeriksaanSuhuRuangV2Controller::class, 'approveProduksi'])->name('pemeriksaan-suhu-ruang-v2.approve-produksi');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/reject-produksi', [PemeriksaanSuhuRuangV2Controller::class, 'rejectProduksi'])->name('pemeriksaan-suhu-ruang-v2.reject-produksi');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/approve-spv', [PemeriksaanSuhuRuangV2Controller::class, 'approveSPV'])->name('pemeriksaan-suhu-ruang-v2.approve-spv');
        Route::post('pemeriksaan-suhu-ruang-v2/{pemeriksaanSuhuRuangV2:uuid}/reject-spv', [PemeriksaanSuhuRuangV2Controller::class, 'rejectSPV'])->name('pemeriksaan-suhu-ruang-v2.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan suhu ruang v3 (gudang dry)
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/send-to-produksi', [PemeriksaanSuhuRuangV3Controller::class, 'sendToProduksi'])->name('pemeriksaan-suhu-ruang-v3.send-to-produksi');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/approve-produksi', [PemeriksaanSuhuRuangV3Controller::class, 'approveProduksi'])->name('pemeriksaan-suhu-ruang-v3.approve-produksi');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/reject-produksi', [PemeriksaanSuhuRuangV3Controller::class, 'rejectProduksi'])->name('pemeriksaan-suhu-ruang-v3.reject-produksi');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/approve-spv', [PemeriksaanSuhuRuangV3Controller::class, 'approveSPV'])->name('pemeriksaan-suhu-ruang-v3.approve-spv');
        Route::post('pemeriksaan-suhu-ruang-v3/{pemeriksaanSuhuRuangV3:uuid}/reject-spv', [PemeriksaanSuhuRuangV3Controller::class, 'rejectSPV'])->name('pemeriksaan-suhu-ruang-v3.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan return barang customer
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/send-to-produksi', [PemeriksaanReturnBarangCustomerController::class, 'sendToProduksi'])->name('return-barang.send-to-produksi');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/approve-produksi', [PemeriksaanReturnBarangCustomerController::class, 'approveProduksi'])->name('return-barang.approve-produksi');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/reject-produksi', [PemeriksaanReturnBarangCustomerController::class, 'rejectProduksi'])->name('return-barang.reject-produksi');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/approve-spv', [PemeriksaanReturnBarangCustomerController::class, 'approveSPV'])->name('return-barang.approve-spv');
        Route::post('return-barang/{pemeriksaanReturnBarangCustomer:uuid}/reject-spv', [PemeriksaanReturnBarangCustomerController::class, 'rejectSPV'])->name('return-barang.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan loading produk
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/send-to-produksi', [PemeriksaanLoadingProdukController::class, 'sendToProduksi'])->name('pemeriksaan-loading-produk.send-to-produksi');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/approve-produksi', [PemeriksaanLoadingProdukController::class, 'approveProduksi'])->name('pemeriksaan-loading-produk.approve-produksi');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/reject-produksi', [PemeriksaanLoadingProdukController::class, 'rejectProduksi'])->name('pemeriksaan-loading-produk.reject-produksi');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/approve-spv', [PemeriksaanLoadingProdukController::class, 'approveSPV'])->name('pemeriksaan-loading-produk.approve-spv');
        Route::post('pemeriksaan-loading-produk/{pemeriksaanLoadingProduk:uuid}/reject-spv', [PemeriksaanLoadingProdukController::class, 'rejectSPV'])->name('pemeriksaan-loading-produk.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan loading kendaraan
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/send-to-produksi', [PemeriksaanLoadingKendaraanController::class, 'sendToProduksi'])->name('pemeriksaan-loading-kendaraan.send-to-produksi');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/approve-produksi', [PemeriksaanLoadingKendaraanController::class, 'approveProduksi'])->name('pemeriksaan-loading-kendaraan.approve-produksi');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/reject-produksi', [PemeriksaanLoadingKendaraanController::class, 'rejectProduksi'])->name('pemeriksaan-loading-kendaraan.reject-produksi');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/approve-spv', [PemeriksaanLoadingKendaraanController::class, 'approveSPV'])->name('pemeriksaan-loading-kendaraan.approve-spv');
        Route::post('pemeriksaan-loading-kendaraan/{pemeriksaanLoadingKendaraan:uuid}/reject-spv', [PemeriksaanLoadingKendaraanController::class, 'rejectSPV'])->name('pemeriksaan-loading-kendaraan.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kedatangan kemasan
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/send-to-produksi', [PemeriksaanKedatanganKemasanController::class, 'sendToProduksi'])->name('pemeriksaan-kedatangan-kemasan.send-to-produksi');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/approve-produksi', [PemeriksaanKedatanganKemasanController::class, 'approveProduksi'])->name('pemeriksaan-kedatangan-kemasan.approve-produksi');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/reject-produksi', [PemeriksaanKedatanganKemasanController::class, 'rejectProduksi'])->name('pemeriksaan-kedatangan-kemasan.reject-produksi');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/approve-spv', [PemeriksaanKedatanganKemasanController::class, 'approveSPV'])->name('pemeriksaan-kedatangan-kemasan.approve-spv');
        Route::post('pemeriksaan-kedatangan-kemasan/{pemeriksaanKedatanganKemasan:uuid}/reject-spv', [PemeriksaanKedatanganKemasanController::class, 'rejectSPV'])->name('pemeriksaan-kedatangan-kemasan.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kedatangan bahan baku penunjang
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/send-to-produksi', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'sendToProduksi'])->name('pemeriksaan-bahan-baku.send-to-produksi');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/approve-produksi', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'approveProduksi'])->name('pemeriksaan-bahan-baku.approve-produksi');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/reject-produksi', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'rejectProduksi'])->name('pemeriksaan-bahan-baku.reject-produksi');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/approve-spv', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'approveSPV'])->name('pemeriksaan-bahan-baku.approve-spv');
        Route::post('pemeriksaan-bahan-baku/{pemeriksaanBahanBaku:uuid}/reject-spv', [PemeriksaanKedatanganBahanBakuPenunjangController::class, 'rejectSPV'])->name('pemeriksaan-bahan-baku.reject-spv');
        
        // Routes untuk verifikasi pemeriksaan kedatangan chemical
        Route::post('pemeriksaan-chemical/{pemeriksaanChemical:uuid}/send-to-produksi', [PemeriksaanKedatanganChemicalController::class, 'sendToProduksi'])->name('pemeriksaan-chemical.send-to-produksi');
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
        
        // Upload File
        Route::post('detail-komplain/{detailKomplain:uuid}/upload-suplier', [DetailKomplainController::class, 'uploadSuplier'])->name('detail-komplain.upload-suplier');
        
        // Export PDF
        Route::get('detail-komplain/{detailKomplain:uuid}/export-pdf', [DetailKomplainController::class, 'exportPdf'])->name('detail-komplain.export-pdf');
        
        // AJAX routes untuk dependent dropdown
        Route::get('api/area-locations/{idArea}', [PemeriksaanBarangMudahPecahController::class, 'getAreaLocations'])->name('api.area-locations');
        Route::get('api/barang-details/{idBarang}', [PemeriksaanBarangMudahPecahController::class, 'getBarangDetails'])->name('api.barang-details');
    });
});
