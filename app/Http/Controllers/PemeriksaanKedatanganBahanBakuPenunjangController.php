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
            'suhu_mobil' => 'nullable|string|max:255',
            'suhu_mobil_type' => 'nullable|in:Fresh,Frozen',
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
            'kondisi_produk_suhu' => 'nullable|array',
            'kondisi_produk_suhu.*' => 'nullable|string|max:255',
            'hasil_uji_ffa' => 'nullable|array',
            'hasil_uji_ffa.*' => 'nullable|string|max:255',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
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
            'suhu_mobil' => 'nullable|string|max:255',
            'suhu_mobil_type' => 'nullable|in:Fresh,Frozen',
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
            'kondisi_produk_suhu' => 'nullable|array',
            'kondisi_produk_suhu.*' => 'nullable|string|max:255',
            'hasil_uji_ffa' => 'nullable|array',
            'hasil_uji_ffa.*' => 'nullable|string|max:255',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
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
        $tanggal = $request->input('tanggal');
        $id_shift = $request->input('id_shift');
        $jam_awal = $request->input('jam_awal');
        $jam_akhir = $request->input('jam_akhir');

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

        // Filter by tanggal
        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        // Filter by shift
        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

        // Filter by jam (created_at time range)
        if ($jam_awal && $jam_akhir) {
            $query->whereBetween('created_at', [
                $tanggal . ' ' . $jam_awal . ':00',
                $tanggal . ' ' . $jam_akhir . ':59'
            ]);
        } elseif ($jam_awal) {
            $query->whereTime('created_at', '>=', $jam_awal);
        } elseif ($jam_akhir) {
            $query->whereTime('created_at', '<=', $jam_akhir);
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
            'shift' => $shift,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser
        ]);

        $filename = 'laporan-pemeriksaan-bahan-baku-' . ($tanggal ?? date('Y-m-d')) . '.pdf';
        return $pdf->download($filename);
    }
}