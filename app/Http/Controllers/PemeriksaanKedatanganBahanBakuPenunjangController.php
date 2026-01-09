<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganBahanBakuPenunjang;
use App\Models\Shift;
use App\Models\Bahan;
use App\Models\Produsen;
use App\Models\Distributor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monarobase\CountryList\CountryListFacade as Countries; // Menggunakan Facade


class PemeriksaanKedatanganBahanBakuPenunjangController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanKedatanganBahanBakuPenunjang::with(['user.role', 'user.plant', 'bahan', 'shift'])
                ->latest()
                ->paginate(10);
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $pemeriksaans = PemeriksaanKedatanganBahanBakuPenunjang::with(['user.role', 'user.plant', 'bahan', 'shift'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
                })
                ->latest()
                ->paginate(10);
        }

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $bahans = Bahan::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::all();
            $distributors = Distributor::all();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            
            $bahans = Bahan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
        }

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.create', compact('shifts', 'bahans', 'countries', 'produsens', 'distributors'));
    }

    private function checkPlantAccess($pemeriksaan)
    {
        $user = Auth::user();
        
        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            if ($pemeriksaan->user->id_plant !== $user->id_plant) { // ✅ GUNAKAN id_plant
                abort(403, 'Unauthorized access to different plant data.');
            }
        }
    }

    public function store(Request $request)
    {
        // Log semua input untuk debugging
        \Log::info('Form Submit Data:', $request->all());
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'id_shift' => 'nullable|exists:shifts,id',
            'status_baris' => 'required|array|min:1',
            'status_baris.*' => 'required|in:Release,Hold',
            // Validasi array fields dari dynamic rows
            'id_bahan' => 'nullable|array',
            'id_bahan.*' => 'nullable|exists:bahans,id',
            'produsen' => 'nullable|array',
            'produsen.*' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|array',
            'negara_produsen.*' => 'nullable|string|max:255',
            'distributor' => 'nullable|array',
            'distributor.*' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|array',
            'kode_produksi.*' => 'nullable|string|max:255',
            'expire_date' => 'nullable|array',
            'expire_date.*' => 'nullable|date',
            'jumlah_datang' => 'nullable|array',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|array',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|array',
            'spesifikasi.*' => 'nullable|string',
            'kondisi_produk' => 'nullable|array',
            'kondisi_produk.*' => 'nullable|string|max:255',
            'suhu_produk' => 'nullable|array',
            'suhu_produk.*' => 'nullable|string|max:255',
            'suhu_produk_type' => 'nullable|array',
            'suhu_produk_type.*' => 'nullable|string|max:255',
            'suhu_mobil' => 'nullable|array',
            'suhu_mobil.*' => 'nullable|string|max:255',
            'suhu_mobil_type' => 'nullable|array',
            'suhu_mobil_type.*' => 'nullable|string|max:255',
            'kondisi_produk_suhu' => 'nullable|array',
            'kondisi_produk_suhu.*' => 'nullable|string|max:255',
            'hasil_uji_ffa' => 'nullable|array',
            'hasil_uji_ffa.*' => 'nullable|string|max:255',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
            'file_coa.*' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Process kondisi mobil dan fisik dengan logic yang benar
        $kondisiMobil = [
            'bersih' => $request->input('kondisi_mobil.bersih') === '1',
            'bebas_hama' => $request->input('kondisi_mobil.bebas_hama') === '1',
            'tidak_kondensasi' => $request->input('kondisi_mobil.tidak_kondensasi') === '1',
            'bebas_produk_halal' => $request->input('kondisi_mobil.bebas_produk_halal') === '1',
            'tidak_berbau' => $request->input('kondisi_mobil.tidak_berbau') === '1',
            'tidak_ada_sampah' => $request->input('kondisi_mobil.tidak_ada_sampah') === '1',
            'tidak_ada_mikroba' => $request->input('kondisi_mobil.tidak_ada_mikroba') === '1',
            'lampu_cover_utuh' => $request->input('kondisi_mobil.lampu_cover_utuh') === '1',
            'pallet_utuh' => $request->input('kondisi_mobil.pallet_utuh') === '1',
            'tertutup_rapat' => $request->input('kondisi_mobil.tertutup_rapat') === '1',
            'bebas_kontaminan' => $request->input('kondisi_mobil.bebas_kontaminan') === '1',
        ];

        $data = $request->all();
        $data['id_user'] = Auth::id();
        $data['segel_gembok'] = $request->input('segel_gembok');
        $data['kondisi_mobil'] = $kondisiMobil;
        // Hapus kondisi_fisik, logo_halal, dokumen_halal, coa dari data karena akan diproses sebagai array
        unset($data['kondisi_fisik']);
        unset($data['logo_halal']);
        unset($data['dokumen_halal']);
        unset($data['coa']);

        // Map array fields dari form (id_bahan[]) ke database columns (id_bahan_array)
        $arrayFieldMapping = [
            'id_bahan' => 'id_bahan_array',
            'produsen' => 'produsen_array',
            'negara_produsen' => 'negara_produsen_array',
            'distributor' => 'distributor_array',
            'kode_produksi' => 'kode_produksi_array',
            'expire_date' => 'expire_date_array',
            'jumlah_datang' => 'jumlah_datang_array',
            'jumlah_sampling' => 'jumlah_sampling_array',
            'spesifikasi' => 'spesifikasi_array',
            'suhu_mobil' => 'suhu_mobil_array',
            'suhu_mobil_type' => 'suhu_mobil_type_array',
            'kondisi_fisik' => 'kondisi_fisik_array',
            'logo_halal' => 'logo_halal_array',
            'hasil_uji_ffa' => 'hasil_uji_ffa_array',
            'dokumen_halal' => 'dokumen_halal_array',
            'coa' => 'coa_array',
            'keterangan_hasil' => 'keterangan_array',
        ];

        // Process kondisi_fisik_array dari radio buttons
        $kondisiFisikArray = [];
        if ($request->has('kondisi_fisik_kemasan')) {
            $kemasanArray = $request->input('kondisi_fisik_kemasan', []);
            $warnaArray = $request->input('kondisi_fisik_warna', []);
            $bendaAsingArray = $request->input('kondisi_fisik_benda_asing', []);
            $aromaArray = $request->input('kondisi_fisik_aroma', []);
            
            $rowCount = max(count($kemasanArray), count($warnaArray), count($bendaAsingArray), count($aromaArray));
            
            for ($i = 0; $i < $rowCount; $i++) {
                $kondisiFisikArray[] = [
                    'kemasan' => (isset($kemasanArray[$i]) && $kemasanArray[$i] === '1') ? true : false,
                    'warna' => (isset($warnaArray[$i]) && $warnaArray[$i] === '1') ? true : false,
                    'benda_asing' => (isset($bendaAsingArray[$i]) && $bendaAsingArray[$i] === '1') ? true : false,
                    'aroma' => (isset($aromaArray[$i]) && $aromaArray[$i] === '1') ? true : false,
                ];
            }
            $data['kondisi_fisik_array'] = json_encode($kondisiFisikArray);
        }
        
        // Process logo_halal_array, dokumen_halal_array, coa_array dari radio buttons
        if ($request->has('logo_halal')) {
            $logoHalalArray = $request->input('logo_halal', []);
            $data['logo_halal_array'] = json_encode(array_map(function($val) {
                return ($val === '1') ? true : false;
            }, $logoHalalArray));
        }
        
        if ($request->has('dokumen_halal')) {
            $dokumenHalalArray = $request->input('dokumen_halal', []);
            $data['dokumen_halal_array'] = json_encode(array_map(function($val) {
                return ($val === '1') ? true : false;
            }, $dokumenHalalArray));
        }
        
        if ($request->has('coa')) {
            $coaArray = $request->input('coa', []);
            $data['coa_array'] = json_encode(array_map(function($val) {
                return ($val === '1') ? true : false;
            }, $coaArray));
        }

        $fileCoaPaths = [];
        $uploadedCoas = (array) $request->file('file_coa', []);
        $rowCountFileCoa = max(count($request->input('coa', [])), count($uploadedCoas));
        for ($i = 0; $i < $rowCountFileCoa; $i++) {
            $uploadedFile = $uploadedCoas[$i] ?? null;
            if ($uploadedFile) {
                $fileCoaPaths[$i] = $uploadedFile->storePublicly('pemeriksaan-bahan-baku-penunjang/coa', 'public');
            } else {
                $fileCoaPaths[$i] = null;
            }
        }
        $data['file_coa_array'] = json_encode(array_values($fileCoaPaths));
        
        // Hapus field-field kondisi_fisik yang dikirim dari form agar tidak conflict
        unset($data['kondisi_fisik_kemasan']);
        unset($data['kondisi_fisik_warna']);
        unset($data['kondisi_fisik_benda_asing']);
        unset($data['kondisi_fisik_aroma']);

        // Process array fields dari form dan simpan ke kolom database yang benar
        foreach ($arrayFieldMapping as $formField => $dbColumn) {
            // Skip fields yang sudah diproses di atas
            if (in_array($formField, ['kondisi_fisik', 'logo_halal', 'dokumen_halal', 'coa'])) {
                continue;
            }
            
            if ($request->has($formField) && is_array($request->input($formField))) {
                $data[$dbColumn] = json_encode($request->input($formField));
                // Hapus field form agar tidak conflict dengan kolom database
                if (isset($data[$formField])) {
                    unset($data[$formField]);
                }
            }
        }
        
        // Set id_bahan ke null atau ambil dari array pertama jika ada
        if (isset($data['id_bahan_array'])) {
            $idBahanArray = json_decode($data['id_bahan_array'], true);
            $data['id_bahan'] = !empty($idBahanArray) && !empty($idBahanArray[0]) ? $idBahanArray[0] : null;
        }
        
        // Set status dari status_baris - jika ada Hold maka Hold, jika semua Release maka Release
        if ($request->has('status_baris') && is_array($request->input('status_baris'))) {
            $statusArray = $request->input('status_baris');
            // Jika ada satu saja yang Hold, maka status keseluruhan adalah Hold
            $data['status'] = in_array('Hold', $statusArray) ? 'Hold' : 'Release';
            // Simpan status_baris ke kolom status_baris_array sebagai JSON
            $data['status_baris_array'] = json_encode($statusArray);
            // Hapus status_baris dari data karena tidak ada kolom di database
            unset($data['status_baris']);
        }

        // Handle kondisi_produk dan suhu_produk yang juga array
        if ($request->has('kondisi_produk') && is_array($request->input('kondisi_produk'))) {
            $data['kondisi_produk'] = json_encode($request->input('kondisi_produk'));
        }
        if ($request->has('suhu_produk') && is_array($request->input('suhu_produk'))) {
            $data['suhu_produk'] = json_encode($request->input('suhu_produk'));
        }
        if ($request->has('suhu_produk_type') && is_array($request->input('suhu_produk_type'))) {
            $data['suhu_produk_type'] = json_encode($request->input('suhu_produk_type'));
        }
        if ($request->has('kondisi_produk_suhu') && is_array($request->input('kondisi_produk_suhu'))) {
            $data['kondisi_produk_suhu'] = json_encode($request->input('kondisi_produk_suhu'));
        }

        // Log data yang akan disimpan untuk debugging
        \Log::info('Data yang akan disimpan ke database:', $data);
        
        try {
            $pemeriksaan = PemeriksaanKedatanganBahanBakuPenunjang::create($data);
            \Log::info('Data berhasil disimpan dengan ID: ' . $pemeriksaan->id);
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan data: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }

        return redirect()->route('pemeriksaan-bahan-baku.index')
            ->with('success', 'Data pemeriksaan kedatangan bahan baku penunjang berhasil ditambahkan!');
    }

    public function show(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $pemeriksaanBahanBaku->load(['user.plant', 'shift', 'bahan']);
        
        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.show', compact('pemeriksaanBahanBaku'));
    }

    public function edit(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $bahans = Bahan::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::all();
            $distributors = Distributor::all();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            
            $bahans = Bahan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
            $countries = Countries::getList('en', 'php');
        }
    
        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.edit', compact('pemeriksaanBahanBaku', 'shifts', 'bahans', 'produsens', 'distributors', 'countries'));
    }

    public function update(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'id_shift' => 'nullable|exists:shifts,id',
            'status_baris' => 'required|array|min:1',
            'status_baris.*' => 'required|in:Release,Hold',
            // Validasi array fields dari dynamic rows
            'id_bahan' => 'nullable|array',
            'id_bahan.*' => 'nullable|exists:bahans,id',
            'produsen' => 'nullable|array',
            'produsen.*' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|array',
            'negara_produsen.*' => 'nullable|string|max:255',
            'distributor' => 'nullable|array',
            'distributor.*' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|array',
            'kode_produksi.*' => 'nullable|string|max:255',
            'expire_date' => 'nullable|array',
            'expire_date.*' => 'nullable|date',
            'jumlah_datang' => 'nullable|array',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|array',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|array',
            'spesifikasi.*' => 'nullable|string',
            'kondisi_produk' => 'nullable|array',
            'kondisi_produk.*' => 'nullable|string|max:255',
            'suhu_produk' => 'nullable|array',
            'suhu_produk.*' => 'nullable|string|max:255',
            'suhu_produk_type' => 'nullable|array',
            'suhu_produk_type.*' => 'nullable|string|max:255',
            'suhu_mobil' => 'nullable|array',
            'suhu_mobil.*' => 'nullable|string|max:255',
            'suhu_mobil_type' => 'nullable|array',
            'suhu_mobil_type.*' => 'nullable|in:Fresh,Frozen,Tidak Ada',
            'kondisi_produk_suhu' => 'nullable|array',
            'kondisi_produk_suhu.*' => 'nullable|string|max:255',
            'hasil_uji_ffa' => 'nullable|array',
            'hasil_uji_ffa.*' => 'nullable|string|max:255',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
            'file_coa.*' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Process kondisi mobil dan fisik dengan logic yang benar
        $kondisiMobil = [
            'bersih' => $request->input('kondisi_mobil.bersih') === '1',
            'bebas_hama' => $request->input('kondisi_mobil.bebas_hama') === '1',
            'tidak_kondensasi' => $request->input('kondisi_mobil.tidak_kondensasi') === '1',
            'bebas_produk_halal' => $request->input('kondisi_mobil.bebas_produk_halal') === '1',
            'tidak_berbau' => $request->input('kondisi_mobil.tidak_berbau') === '1',
            'tidak_ada_sampah' => $request->input('kondisi_mobil.tidak_ada_sampah') === '1',
            'tidak_ada_mikroba' => $request->input('kondisi_mobil.tidak_ada_mikroba') === '1',
            'lampu_cover_utuh' => $request->input('kondisi_mobil.lampu_cover_utuh') === '1',
            'pallet_utuh' => $request->input('kondisi_mobil.pallet_utuh') === '1',
            'tertutup_rapat' => $request->input('kondisi_mobil.tertutup_rapat') === '1',
            'bebas_kontaminan' => $request->input('kondisi_mobil.bebas_kontaminan') === '1',
        ];

        $data = $request->all();
        $data['segel_gembok'] = $request->input('segel_gembok');
        $data['kondisi_mobil'] = $kondisiMobil;
        // Hapus kondisi_fisik, logo_halal, dokumen_halal, coa dari data karena akan diproses sebagai array
        unset($data['kondisi_fisik']);
        unset($data['logo_halal']);
        unset($data['dokumen_halal']);
        unset($data['coa']);

        // Map array fields dari form (id_bahan[]) ke database columns (id_bahan_array)
        $arrayFieldMapping = [
            'id_bahan' => 'id_bahan_array',
            'produsen' => 'produsen_array',
            'negara_produsen' => 'negara_produsen_array',
            'distributor' => 'distributor_array',
            'kode_produksi' => 'kode_produksi_array',
            'expire_date' => 'expire_date_array',
            'jumlah_datang' => 'jumlah_datang_array',
            'jumlah_sampling' => 'jumlah_sampling_array',
            'spesifikasi' => 'spesifikasi_array',
            'suhu_mobil' => 'suhu_mobil_array',
            'suhu_mobil_type' => 'suhu_mobil_type_array',
            'kondisi_fisik' => 'kondisi_fisik_array',
            'logo_halal' => 'logo_halal_array',
            'hasil_uji_ffa' => 'hasil_uji_ffa_array',
            'dokumen_halal' => 'dokumen_halal_array',
            'coa' => 'coa_array',
            'keterangan_hasil' => 'keterangan_array',
        ];

        // Process kondisi_fisik_array dari radio buttons
        $kondisiFisikArray = [];
        if ($request->has('kondisi_fisik_kemasan')) {
            $kemasanArray = $request->input('kondisi_fisik_kemasan', []);
            $warnaArray = $request->input('kondisi_fisik_warna', []);
            $bendaAsingArray = $request->input('kondisi_fisik_benda_asing', []);
            $aromaArray = $request->input('kondisi_fisik_aroma', []);
            
            $rowCount = max(count($kemasanArray), count($warnaArray), count($bendaAsingArray), count($aromaArray));
            
            for ($i = 0; $i < $rowCount; $i++) {
                $kondisiFisikArray[] = [
                    'kemasan' => (isset($kemasanArray[$i]) && $kemasanArray[$i] === '1') ? true : false,
                    'warna' => (isset($warnaArray[$i]) && $warnaArray[$i] === '1') ? true : false,
                    'benda_asing' => (isset($bendaAsingArray[$i]) && $bendaAsingArray[$i] === '1') ? true : false,
                    'aroma' => (isset($aromaArray[$i]) && $aromaArray[$i] === '1') ? true : false,
                ];
            }
            $data['kondisi_fisik_array'] = json_encode($kondisiFisikArray);
        }
        
        // Process logo_halal_array, dokumen_halal_array, coa_array dari radio buttons
        if ($request->has('logo_halal')) {
            $logoHalalArray = $request->input('logo_halal', []);
            $data['logo_halal_array'] = json_encode(array_map(function($val) {
                return ($val === '1') ? true : false;
            }, $logoHalalArray));
        }
        
        if ($request->has('dokumen_halal')) {
            $dokumenHalalArray = $request->input('dokumen_halal', []);
            $data['dokumen_halal_array'] = json_encode(array_map(function($val) {
                return ($val === '1') ? true : false;
            }, $dokumenHalalArray));
        }
        
        if ($request->has('coa')) {
            $coaArray = $request->input('coa', []);
            $data['coa_array'] = json_encode(array_map(function($val) {
                return ($val === '1') ? true : false;
            }, $coaArray));
        }

        $existingFileCoa = json_decode($pemeriksaanBahanBaku->file_coa_array ?? '[]', true);
        if (!is_array($existingFileCoa)) {
            $existingFileCoa = [];
        }

        $newFileCoa = [];
        $uploadedCoas = (array) $request->file('file_coa', []);
        $rowCountFileCoa = max(
            count($request->input('id_bahan', [])),
            count($request->input('produsen', [])),
            count($request->input('distributor', [])),
            count($request->input('coa', []))
        );
        for ($i = 0; $i < $rowCountFileCoa; $i++) {
            $uploadedFile = $uploadedCoas[$i] ?? null;
            if ($uploadedFile) {
                $newFileCoa[$i] = $uploadedFile->storePublicly('pemeriksaan-bahan-baku-penunjang/coa', 'public');
            } else {
                $newFileCoa[$i] = $existingFileCoa[$i] ?? null;
            }
        }
        $data['file_coa_array'] = json_encode(array_values($newFileCoa));
        
        // Hapus field-field kondisi_fisik yang dikirim dari form agar tidak conflict
        unset($data['kondisi_fisik_kemasan']);
        unset($data['kondisi_fisik_warna']);
        unset($data['kondisi_fisik_benda_asing']);
        unset($data['kondisi_fisik_aroma']);

        // Process array fields dari form dan simpan ke kolom database yang benar
        foreach ($arrayFieldMapping as $formField => $dbColumn) {
            // Skip fields yang sudah diproses di atas
            if (in_array($formField, ['kondisi_fisik', 'logo_halal', 'dokumen_halal', 'coa'])) {
                continue;
            }
            
            if ($request->has($formField) && is_array($request->input($formField))) {
                $data[$dbColumn] = json_encode($request->input($formField));
                // Hapus field form agar tidak conflict dengan kolom database
                if (isset($data[$formField])) {
                    unset($data[$formField]);
                }
            }
        }
        
        // Handle kondisi_produk, suhu_produk, dll yang dikirim sebagai array
        if ($request->has('kondisi_produk') && is_array($request->input('kondisi_produk'))) {
            $data['kondisi_produk'] = json_encode($request->input('kondisi_produk'));
        }
        if ($request->has('suhu_produk') && is_array($request->input('suhu_produk'))) {
            $data['suhu_produk'] = json_encode($request->input('suhu_produk'));
        }
        if ($request->has('suhu_produk_type') && is_array($request->input('suhu_produk_type'))) {
            $data['suhu_produk_type'] = json_encode($request->input('suhu_produk_type'));
        }
        if ($request->has('kondisi_produk_suhu') && is_array($request->input('kondisi_produk_suhu'))) {
            $data['kondisi_produk_suhu'] = json_encode($request->input('kondisi_produk_suhu'));
        }
        
        // Set id_bahan ke null atau ambil dari array pertama jika ada
        if (isset($data['id_bahan_array'])) {
            $idBahanArray = json_decode($data['id_bahan_array'], true);
            $data['id_bahan'] = !empty($idBahanArray) && !empty($idBahanArray[0]) ? $idBahanArray[0] : null;
        }
        
        // Set status dari status_baris - jika ada Hold maka Hold, jika semua Release maka Release
        if ($request->has('status_baris') && is_array($request->input('status_baris'))) {
            $statusArray = $request->input('status_baris');
            // Jika ada satu saja yang Hold, maka status keseluruhan adalah Hold
            $data['status'] = in_array('Hold', $statusArray) ? 'Hold' : 'Release';
            // Simpan status_baris sebagai JSON
            $data['status_baris_array'] = json_encode($statusArray);
        }

        $pemeriksaanBahanBaku->update($data);

        return redirect()->route('pemeriksaan-bahan-baku.index')
        ->with('success', 'Data pemeriksaan kedatangan bahan baku penunjang berhasil diupdate!');
    }

    public function createRow(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);

        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $bahans = Bahan::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::all();
            $distributors = Distributor::all();
        } else {
            $bahans = Bahan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->get();
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->get();
        }

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.tambah-baris', compact(
            'pemeriksaanBahanBaku',
            'bahans',
            'countries',
            'produsens',
            'distributors'
        ));
    }

    public function storeRow(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);

        $request->validate([
            'status_baris' => 'required|in:Release,Hold',
            'id_bahan' => 'nullable|exists:bahans,id',
            'produsen' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|string|max:255',
            'distributor' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|string|max:255',
            'expire_date' => 'nullable|date',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'suhu_produk_type' => 'nullable|string|max:255',
            'suhu_produk' => 'nullable|string|max:255',
            'suhu_mobil_type' => 'nullable|string|max:255',
            'suhu_mobil' => 'nullable|string|max:255',
            'kondisi_produk' => 'nullable|string|max:255',
            'kondisi_produk_suhu' => 'nullable|string|max:255',
            'hasil_uji_ffa' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'kondisi_fisik_kemasan' => 'nullable|in:0,1',
            'kondisi_fisik_warna' => 'nullable|in:0,1',
            'kondisi_fisik_benda_asing' => 'nullable|in:0,1',
            'kondisi_fisik_aroma' => 'nullable|in:0,1',
            'logo_halal' => 'nullable|in:0,1',
            'dokumen_halal' => 'nullable|in:0,1',
            'coa' => 'nullable|in:0,1',
            'file_coa' => 'nullable|mimes:pdf|max:5120',
        ]);

        $appendJsonArray = function (?string $raw, $value): array {
            $arr = json_decode($raw ?? '[]', true);
            if (!is_array($arr)) {
                $arr = [];
            }
            $arr[] = $value;
            return $arr;
        };

        $idBahanArr = $appendJsonArray($pemeriksaanBahanBaku->id_bahan_array, $request->input('id_bahan'));
        $produsenArr = $appendJsonArray($pemeriksaanBahanBaku->produsen_array, $request->input('produsen'));
        $negaraProdusenArr = $appendJsonArray($pemeriksaanBahanBaku->negara_produsen_array, $request->input('negara_produsen'));
        $distributorArr = $appendJsonArray($pemeriksaanBahanBaku->distributor_array, $request->input('distributor'));
        $kodeProduksiArr = $appendJsonArray($pemeriksaanBahanBaku->kode_produksi_array, $request->input('kode_produksi'));
        $expireDateArr = $appendJsonArray($pemeriksaanBahanBaku->expire_date_array, $request->input('expire_date'));
        $jumlahDatangArr = $appendJsonArray($pemeriksaanBahanBaku->jumlah_datang_array, $request->input('jumlah_datang'));
        $jumlahSamplingArr = $appendJsonArray($pemeriksaanBahanBaku->jumlah_sampling_array, $request->input('jumlah_sampling'));
        $spesifikasiArr = $appendJsonArray($pemeriksaanBahanBaku->spesifikasi_array, $request->input('spesifikasi'));

        $suhuProdukArr = $appendJsonArray($pemeriksaanBahanBaku->suhu_produk, $request->input('suhu_produk'));
        $suhuProdukTypeArr = $appendJsonArray($pemeriksaanBahanBaku->suhu_produk_type, $request->input('suhu_produk_type'));
        $suhuMobilArr = $appendJsonArray($pemeriksaanBahanBaku->suhu_mobil_array, $request->input('suhu_mobil'));
        $suhuMobilTypeArr = $appendJsonArray($pemeriksaanBahanBaku->suhu_mobil_type_array, $request->input('suhu_mobil_type'));
        $kondisiProdukArr = $appendJsonArray($pemeriksaanBahanBaku->kondisi_produk, $request->input('kondisi_produk'));
        $kondisiProdukSuhuArr = $appendJsonArray($pemeriksaanBahanBaku->kondisi_produk_suhu, $request->input('kondisi_produk_suhu'));
        $hasilUjiFfaArr = $appendJsonArray($pemeriksaanBahanBaku->hasil_uji_ffa_array, $request->input('hasil_uji_ffa'));
        $keteranganArr = $appendJsonArray($pemeriksaanBahanBaku->keterangan_array, $request->input('keterangan'));

        $kondisiFisikArr = json_decode($pemeriksaanBahanBaku->kondisi_fisik_array ?? '[]', true);
        if (!is_array($kondisiFisikArr)) {
            $kondisiFisikArr = [];
        }
        $kondisiFisikArr[] = [
            'kemasan' => $request->input('kondisi_fisik_kemasan') === '1',
            'warna' => $request->input('kondisi_fisik_warna') === '1',
            'benda_asing' => $request->input('kondisi_fisik_benda_asing') === '1',
            'aroma' => $request->input('kondisi_fisik_aroma') === '1',
        ];

        $logoHalalArr = $appendJsonArray($pemeriksaanBahanBaku->logo_halal_array, $request->input('logo_halal') === '1');
        $dokumenHalalArr = $appendJsonArray($pemeriksaanBahanBaku->dokumen_halal_array, $request->input('dokumen_halal') === '1');
        $coaArr = $appendJsonArray($pemeriksaanBahanBaku->coa_array, $request->input('coa') === '1');

        $fileCoaArr = json_decode($pemeriksaanBahanBaku->file_coa_array ?? '[]', true);
        if (!is_array($fileCoaArr)) {
            $fileCoaArr = [];
        }
        $uploadedFileCoa = $request->file('file_coa');
        if ($uploadedFileCoa) {
            $fileCoaArr[] = $uploadedFileCoa->storePublicly('pemeriksaan-bahan-baku-penunjang/coa', 'public');
        } else {
            $fileCoaArr[] = null;
        }

        $statusBarisArr = $appendJsonArray($pemeriksaanBahanBaku->status_baris_array, $request->input('status_baris'));
        $statusOverall = in_array('Hold', $statusBarisArr, true) ? 'Hold' : 'Release';

        $data = [
            'id_bahan_array' => json_encode($idBahanArr),
            'produsen_array' => json_encode($produsenArr),
            'negara_produsen_array' => json_encode($negaraProdusenArr),
            'distributor_array' => json_encode($distributorArr),
            'kode_produksi_array' => json_encode($kodeProduksiArr),
            'expire_date_array' => json_encode($expireDateArr),
            'jumlah_datang_array' => json_encode($jumlahDatangArr),
            'jumlah_sampling_array' => json_encode($jumlahSamplingArr),
            'spesifikasi_array' => json_encode($spesifikasiArr),
            'suhu_produk' => json_encode($suhuProdukArr),
            'suhu_produk_type' => json_encode($suhuProdukTypeArr),
            'suhu_mobil_array' => json_encode($suhuMobilArr),
            'suhu_mobil_type_array' => json_encode($suhuMobilTypeArr),
            'kondisi_produk' => json_encode($kondisiProdukArr),
            'kondisi_produk_suhu' => json_encode($kondisiProdukSuhuArr),
            'hasil_uji_ffa_array' => json_encode($hasilUjiFfaArr),
            'keterangan_array' => json_encode($keteranganArr),
            'kondisi_fisik_array' => json_encode($kondisiFisikArr),
            'logo_halal_array' => json_encode($logoHalalArr),
            'dokumen_halal_array' => json_encode($dokumenHalalArr),
            'coa_array' => json_encode($coaArr),
            'file_coa_array' => json_encode($fileCoaArr),
            'status_baris_array' => json_encode($statusBarisArr),
            'status' => $statusOverall,
        ];

        if (!$pemeriksaanBahanBaku->id_bahan && $request->input('id_bahan')) {
            $data['id_bahan'] = $request->input('id_bahan');
        }

        $pemeriksaanBahanBaku->update($data);

        return redirect()->route('pemeriksaan-bahan-baku.index')
            ->with('success', 'Baris baru berhasil ditambahkan!');
    }

    public function destroy(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $pemeriksaanBahanBaku->delete();

        return redirect()->route('pemeriksaan-bahan-baku.index')
        ->with('success', 'Data pemeriksaan kedatangan bahan baku penunjang berhasil dihapus!');
    }

    public function sendToProduksi(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        if ($pemeriksaanBahanBaku->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        $pemeriksaanBahanBaku->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now()
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        if ($pemeriksaanBahanBaku->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        $pemeriksaanBahanBaku->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        if ($pemeriksaanBahanBaku->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanBahanBaku->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        if ($pemeriksaanBahanBaku->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        $pemeriksaanBahanBaku->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        if ($pemeriksaanBahanBaku->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanBahanBaku->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }

    /**
     * Export data to PDF based on filters
     */
    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $id_shift = $request->input('id_shift');
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        // Build query
        $query = PemeriksaanKedatanganBahanBakuPenunjang::with([
            'user.role', 
            'user.plant', 
            'bahan', 
            'shift'
        ])->with([
            'qcVerifier' => function($q) {
                $q->select('id', 'name');
            },
            'produksiVerifier' => function($q) {
                $q->select('id', 'name');
            },
            'spvVerifier' => function($q) {
                $q->select('id', 'name');
            }
        ]);

        // Filter by plant access
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        // Filter by shift
        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

        // Filter by tanggal berdasarkan shift
        // Shift 1: date range (tanggal_dari - tanggal_sampai)
        // Shift 2 & 3: single date (tanggal)
        if ($tanggal_dari && $tanggal_sampai) {
            // Shift 1: Filter dengan date range
            $query->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai]);
        } elseif ($tanggal) {
            // Shift 2 & 3: Filter dengan single date
            $query->whereDate('tanggal', $tanggal);
        }

        $pemeriksaans = $query->latest()->get();

        // Get shift name for display
        $shift = $id_shift ? Shift::find($id_shift) : null;
        
        // Get signature users from verified_by fields
        $qcUser = null;
        $produksiUser = null;
        $spvQcUser = null;
        
        // Collect all unique verified_by IDs
        $allQcIds = $pemeriksaans->pluck('verified_by_qc')->filter()->unique();
        $allProduksiIds = $pemeriksaans->pluck('verified_by_produksi')->filter()->unique();
        $allSpvIds = $pemeriksaans->pluck('verified_by_spv')->filter()->unique();
        
        // Get QC user with role
        if($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if($qcUserData) {
                $qcUser = $qcUserData->name;
            }
        }
        
        // Get Produksi user with role
        if($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if($produksiUserData) {
                $produksiUser = $produksiUserData->name;
            }
        }
        
        // Get SPV user with role
        if($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if($spvUserData) {
                $spvQcUser = $spvUserData->name;
            }
        }

        // Generate PDF
        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
            'shift' => $shift,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser
        ]);

        // Generate filename berdasarkan filter
        if ($tanggal_dari && $tanggal_sampai) {
            $filename = 'laporan-pemeriksaan-bahan-baku-' . $tanggal_dari . '-to-' . $tanggal_sampai . '.pdf';
        } else {
            $filename = 'laporan-pemeriksaan-bahan-baku-' . ($tanggal ?? date('Y-m-d')) . '.pdf';
        }
        
        return $pdf->download($filename);
    }
}