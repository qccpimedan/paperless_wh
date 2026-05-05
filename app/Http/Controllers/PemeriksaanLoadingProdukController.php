<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanLoadingProduk;
use App\Models\Shift;
use App\Models\TujuanPengiriman;
use App\Models\JenisKendaraan;
use App\Models\Supir;
use App\Models\Customer;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PemeriksaanLoadingProdukController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanLoadingProduk::with([
            'user.role',
            'user.plant',
            'shift',
            'tujuanPengiriman',
            'kendaraan',
            'supir',
            'produk'
        ]);

        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if ($search !== '') {
            $matchingProductIds = Produk::query()
                ->select('id')
                ->where('nama_produk', 'like', '%' . $search . '%')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $query->where(function ($q) use ($search, $matchingProductIds) {
                // Konversi format d/m/Y ke Y-m-d
                $tanggalSearch = null;
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search)) {
                    $parts = explode('/', $search);
                    $tanggalSearch = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) {
                    $tanggalSearch = $search;
                }

                if ($tanggalSearch) {
                    $q->whereDate('tanggal', $tanggalSearch);
                }

                $q->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhere('verification_notes', 'like', '%' . $search . '%')
                    ->orWhere('produk_data', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kendaraan', function ($qk) use ($search) {
                        $qk->where('jenis_kendaraan', 'like', '%' . $search . '%')
                            ->orWhere('no_kendaraan', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('supir', function ($qsu) use ($search) {
                        $qsu->where('nama_supir', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('tujuanPengiriman', function ($qt) use ($search) {
                        $qt->where('nama_tujuan', 'like', '%' . $search . '%')
                            ->orWhereHas('customer', function ($qc) use ($search) {
                                $qc->where('nama_cust', 'like', '%' . $search . '%');
                            });
                    });

                if (!empty($matchingProductIds)) {
                    $q->orWhere(function ($qj) use ($matchingProductIds) {
                        foreach ($matchingProductIds as $pid) {
                            $qj->orWhereRaw(
                                "JSON_CONTAINS(produk_data, ?, '$')",
                                [json_encode(['id_produk' => $pid])]
                            );
                            $qj->orWhereRaw(
                                "JSON_CONTAINS(produk_data, ?, '$')",
                                [json_encode(['id_produk' => (string) $pid])]
                            );
                            $qj->orWhere('produk_data', 'like', '%"id_produk":' . $pid . '%');
                            $qj->orWhere('produk_data', 'like', '%"id_produk":"' . $pid . '"%');
                        }
                    });
                }
            });
        }


        $pemeriksaans = $query->latest()->paginate(25);

        
        $produkKategoriOptions = \App\Models\Produk::query()
            ->whereNotNull('kategori_code')
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        $produkList = \App\Models\Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

                $produkByKategori = $produkList
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return ['id' => $p->id, 'nama' => $p->nama_produk];
                })->values();
            });

return view('qc-sistem.pemeriksaan-loading-produk.index', compact('pemeriksaans', 'produkKategoriOptions', 'produkList', 'produkByKategori'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $tujuanPengirimans = TujuanPengiriman::with(['user.plant', 'customer'])->get();
            $kendaraans = JenisKendaraan::with(['user.plant'])->get();
            $supirs = Supir::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
            
            $tujuanPengirimans = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant', 'customer'])->get();
            
            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
            
            $supirs = Supir::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
            
            $produks = Produk::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
        }

        if ($produks->isEmpty()) {
            $produks = Produk::with(['user.plant'])->get();
        }

        $produkKategoriOptions = Produk::query()
            ->whereNotNull('kategori_code')
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values()
            ->all();

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkByKategori = $produkList
            ->whereNotNull('kategori_code')
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            })
            ->all();

        $produkKategoriById = $produkList
            ->pluck('kategori_code', 'id')
            ->all();

        return view('qc-sistem.pemeriksaan-loading-produk.create', compact(
            'shifts', 
            'tujuanPengirimans', 
            'kendaraans', 
            'supirs', 
            'produks',
            'produkKategoriOptions',
            'produkByKategori',
            'produkKategoriById'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_tujuan_pengiriman' => [
                'nullable',
                Rule::when(
                    $request->input('id_tujuan_pengiriman') !== 'other',
                    Rule::exists('tujuan_pengirimen', 'id')
                ),
            ],
            'nama_customer_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'nama_tujuan_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'id_kendaraan' => 'nullable',
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_supir' => [
                'nullable',
                Rule::when(
                    $request->input('id_supir') !== 'other',
                    Rule::exists('supirs', 'id')
                ),
            ],
            'nama_supir_manual' => 'nullable|required_if:id_supir,other|string|max:255',
            'star_loading' => 'nullable',
            'selesai_loading' => 'nullable',
            'temperature_mobil' => 'nullable|string|max:255',
            'temperature_produk' => 'nullable|array',
            'temperature_produk.*' => 'nullable|string|max:255',
            'kondisi_produk' => 'nullable|in:Frozen,Fresh,Dry',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
            'id_produk' => 'required|exists:produks,id',
            'produk_detail' => 'nullable|array|min:1',
            'produk_detail.*.kode_produksi' => 'nullable|string|max:255',
            'produk_detail.*.best_before' => 'nullable|date',
            'produk_detail.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_detail.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_detail.*.berat_perkarung' => 'nullable|string|max:255',
            'produk_detail.*.kondisi_kemasan' => 'nullable|boolean',
            'produk_detail.*.keterangan' => 'nullable|string',
            'produk_data' => 'nullable|array|min:1',
            'produk_data.*.id_produk' => 'nullable|exists:produks,id',
            'produk_data.*.kode_produksi' => 'nullable|string|max:255',
            'produk_data.*.best_before' => 'nullable|date',
            'produk_data.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_data.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_data.*.berat_perkarung' => 'nullable|string|max:255',
            'produk_data.*.kondisi_kemasan' => 'nullable|boolean',
            'produk_data.*.keterangan' => 'nullable|string',
        ]);

        // Cek apakah kendaraan diinput manual
        if ($request->id_kendaraan === 'other') {
            // Jika input manual diisi
            if ($request->jenis_kendaraan_manual && $request->no_kendaraan_manual) {
                // Buat record baru di tabel master
                $kendaraan = JenisKendaraan::create([
                    'jenis_kendaraan' => $request->jenis_kendaraan_manual,
                    'no_kendaraan' => $request->no_kendaraan_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_kendaraan'] = $kendaraan->id;
            } else {
                // Jika input manual tidak diisi, set id_kendaraan ke null
                $validated['id_kendaraan'] = null;
            }
        }

        if ($request->id_tujuan_pengiriman === 'other') {
            if ($request->nama_customer_manual && $request->nama_tujuan_manual) {
                $customer = Customer::create([
                    'nama_cust' => $request->nama_customer_manual,
                    'id_user' => Auth::id(),
                ]);

                $tujuan = TujuanPengiriman::create([
                    'id_user' => Auth::id(),
                    'id_customer' => $customer->id,
                    'nama_tujuan' => $request->nama_tujuan_manual,
                ]);

                $validated['id_tujuan_pengiriman'] = $tujuan->id;
            } else {
                $validated['id_tujuan_pengiriman'] = null;
            }
        }

        if ($request->id_supir === 'other') {
            if ($request->nama_supir_manual) {
                $supir = Supir::create([
                    'nama_supir' => $request->nama_supir_manual,
                    'id_user' => Auth::id(),
                ]);

                $validated['id_supir'] = $supir->id;
            } else {
                $validated['id_supir'] = null;
            }
        }

        // Process temperature_produk array
        $temperatureProduk = [];
        if ($request->has('temperature_produk')) {
            foreach ($request->temperature_produk as $temp) {
                if (!empty($temp)) {
                    $temperatureProduk[] = $temp;
                }
            }
        }

        $validated['id_user'] = Auth::id();
        $validated['segel_gembok'] = $request->input('segel_gembok') === 'segel';
        $validated['temperature_produk'] = !empty($temperatureProduk) ? $temperatureProduk : null;
        
        // Process product data
        // Priority: opsi 2 (single id_produk select + produk_data[*] rows with hidden id_produk)
        $produkData = [];
        if ($request->has('produk_data') && is_array($request->produk_data)) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'id_produk' => $produk['id_produk'],
                        'kode_produksi' => $produk['kode_produksi'] ?? null,
                        'best_before' => $produk['best_before'] ?? null,
                        'jumlah_kemasan' => $produk['jumlah_kemasan'] ?? null,
                        'jumlah_sampling' => $produk['jumlah_sampling'] ?? null,
                        'berat_perkarung' => $produk['berat_perkarung'] ?? null,
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : true,
                        'keterangan' => $produk['keterangan'] ?? null,
                    ];
                }
            }
        } elseif ($request->filled('id_produk') && $request->has('produk_detail') && is_array($request->produk_detail)) {
            // Backward compatibility: opsi 1 (id_produk + produk_detail[])
            foreach ($request->produk_detail as $detail) {
                $produkData[] = [
                    'id_produk' => $request->id_produk,
                    'kode_produksi' => $detail['kode_produksi'] ?? null,
                    'best_before' => $detail['best_before'] ?? null,
                    'jumlah_kemasan' => $detail['jumlah_kemasan'] ?? null,
                    'jumlah_sampling' => $detail['jumlah_sampling'] ?? null,
                    'berat_perkarung' => $detail['berat_perkarung'] ?? null,
                    'kondisi_kemasan' => isset($detail['kondisi_kemasan']) ? (bool)$detail['kondisi_kemasan'] : true,
                    'keterangan' => $detail['keterangan'] ?? null,
                ];
            }
        }

        if (empty($produkData)) {
            return back()
                ->withErrors(['id_produk' => 'Data produk wajib diisi (minimal 1 detail).'])
                ->withInput();
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;

        PemeriksaanLoadingProduk::create($validated);

        return redirect()->route('pemeriksaan-loading-produk.index')
            ->with('success', 'Data pemeriksaan loading produk berhasil ditambahkan.');
    }

    public function show(PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $pemeriksaan_loading_produk->load([
            'user.plant', 
            'shift', 
            'tujuanPengiriman.customer', 
            'kendaraan', 
            'supir', 
            'produk'
        ]);
        
        return view('qc-sistem.pemeriksaan-loading-produk.show', [
            'pemeriksaanLoading' => $pemeriksaan_loading_produk
        ]);
    }

    public function edit(PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $tujuanPengirimans = TujuanPengiriman::with(['user.plant', 'customer'])->get();
            $kendaraans = JenisKendaraan::with(['user.plant'])->get();
            $supirs = Supir::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
            
            $tujuanPengirimans = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant', 'customer'])->get();
            
            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
            
            $supirs = Supir::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
            
            $produks = Produk::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
        }

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkKategoriOptions = $produkList
            ->whereNotNull('kategori_code')
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkByKategori = $produkList
            ->whereNotNull('kategori_code')
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            })
            ->all();

        // Ambil dari SEMUA produk (tanpa filter plant) agar id_produk
        // yang sudah tersimpan di produk_data selalu bisa ditemukan kategorinya
        $produkKategoriById = $produkList
            ->whereNotNull('kategori_code')
            ->pluck('kategori_code', 'id')
            ->all();

        return view('qc-sistem.pemeriksaan-loading-produk.edit', [
            'pemeriksaanLoading' => $pemeriksaan_loading_produk,
            'shifts' => $shifts,
            'tujuanPengirimans' => $tujuanPengirimans,
            'kendaraans' => $kendaraans,
            'supirs' => $supirs,
            'produks' => $produks,
            'produkKategoriOptions' => $produkKategoriOptions,
            'produkByKategori' => $produkByKategori,
            'produkKategoriById' => $produkKategoriById,
        ]);
    }
    public function update(Request $request, PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_tujuan_pengiriman' => [
                'nullable',
                Rule::when(
                    $request->input('id_tujuan_pengiriman') !== 'other',
                    Rule::exists('tujuan_pengirimen', 'id')
                ),
            ],
            'nama_customer_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'nama_tujuan_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'id_kendaraan' => 'nullable',
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_supir' => [
                'nullable',
                Rule::when(
                    $request->input('id_supir') !== 'other',
                    Rule::exists('supirs', 'id')
                ),
            ],
            'nama_supir_manual' => 'nullable|required_if:id_supir,other|string|max:255',
            'star_loading' => 'nullable',
            'selesai_loading' => 'nullable',
            'temperature_mobil' => 'nullable|string|max:255',
            'temperature_produk' => 'nullable|array',
            'temperature_produk.*' => 'nullable|string|max:255',
            'kondisi_produk' => 'nullable|in:Frozen,Fresh,Dry',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
            'produk_data' => 'nullable|array|min:1',
            'produk_data.*.id_produk' => 'nullable|exists:produks,id',
            'produk_data.*.kode_produksi' => 'nullable|string|max:255',
            'produk_data.*.best_before' => 'nullable|date',
            'produk_data.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_data.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_data.*.berat_perkarung' => 'nullable|string|max:255',
            'produk_data.*.kondisi_kemasan' => 'nullable|boolean',
            'produk_data.*.keterangan' => 'nullable|string|max:500',
        ]);

        // Cek apakah kendaraan diinput manual
        if ($request->id_kendaraan === 'other') {
            // Jika input manual diisi
            if ($request->jenis_kendaraan_manual && $request->no_kendaraan_manual) {
                // Buat record baru di tabel master
                $kendaraan = JenisKendaraan::create([
                    'jenis_kendaraan' => $request->jenis_kendaraan_manual,
                    'no_kendaraan' => $request->no_kendaraan_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_kendaraan'] = $kendaraan->id;
            } else {
                // Jika input manual tidak diisi, set id_kendaraan ke null
                $validated['id_kendaraan'] = null;
            }
        }

        if ($request->id_tujuan_pengiriman === 'other') {
            if ($request->nama_customer_manual && $request->nama_tujuan_manual) {
                $customer = Customer::create([
                    'nama_cust' => $request->nama_customer_manual,
                    'id_user' => Auth::id(),
                ]);

                $tujuan = TujuanPengiriman::create([
                    'id_user' => Auth::id(),
                    'id_customer' => $customer->id,
                    'nama_tujuan' => $request->nama_tujuan_manual,
                ]);

                $validated['id_tujuan_pengiriman'] = $tujuan->id;
            } else {
                $validated['id_tujuan_pengiriman'] = null;
            }
        }

        if ($request->id_supir === 'other') {
            if ($request->nama_supir_manual) {
                $supir = Supir::create([
                    'nama_supir' => $request->nama_supir_manual,
                    'id_user' => Auth::id(),
                ]);

                $validated['id_supir'] = $supir->id;
            } else {
                $validated['id_supir'] = null;
            }
        }

        // Process temperature_produk array
        $temperatureProduk = [];
        if ($request->has('temperature_produk')) {
            foreach ($request->temperature_produk as $temp) {
                if (!empty($temp)) {
                    $temperatureProduk[] = $temp;
                }
            }
        }

        $validated['temperature_produk'] = !empty($temperatureProduk) ? $temperatureProduk : null;
        $validated['segel_gembok'] = $request->input('segel_gembok') === 'segel';
        
        // Process produk_data array untuk multiple produk
        $produkData = [];
        if ($request->has('produk_data')) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'id_produk' => $produk['id_produk'],
                        'kode_produksi' => $produk['kode_produksi'] ?? null,
                        'best_before' => $produk['best_before'] ?? null,
                        'jumlah_kemasan' => $produk['jumlah_kemasan'] ?? null,
                        'jumlah_sampling' => $produk['jumlah_sampling'] ?? null,
                        'berat_perkarung' => $produk['berat_perkarung'] ?? null,
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : true,
                        'keterangan' => $produk['keterangan'] ?? null,
                    ];
                }
            }
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;
        
        // Hapus keterangan dari validated karena sudah di produk_data
        unset($validated['keterangan']);

        $pemeriksaan_loading_produk->update($validated);

        return redirect()->route('pemeriksaan-loading-produk.index')
            ->with('success', 'Data pemeriksaan loading produk berhasil diupdate.');
    }
    public function destroy(PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $pemeriksaan_loading_produk->delete();

        return redirect()->route('pemeriksaan-loading-produk.index')
            ->with('success', 'Data pemeriksaan loading produk berhasil dihapus.');
    }

    private function checkPlantAccess(PemeriksaanLoadingProduk $pemeriksaan)
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }

        $pemeriksaan->loadMissing('user');
        if ($pemeriksaan->user && $pemeriksaan->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    public function sendToProduksi(PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingProduk);
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingProduk);
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingProduk);
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingProduk);
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingProduk);
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }

    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');
        $id_produk = $request->input('id_produk');
        $kategori_code = $request->input('kategori_code');

        $query = PemeriksaanLoadingProduk::with([
            'user.role',
            'user.plant',
            'shift',
            'tujuanPengiriman.customer',
            'kendaraan',
            'supir',
            'qcVerifier' => function ($q) {
                $q->select('id', 'name');
            },
            'produksiVerifier' => function ($q) {
                $q->select('id', 'name');
            },
            'spvVerifier' => function ($q) {
                $q->select('id', 'name');
            },
        ]);

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        
        // Filter by produk / kategori
        if ($id_produk) {
            $query->where(function ($q) use ($id_produk) {
                $q->whereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['id_produk' => (int)$id_produk])])
                  ->orWhereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['id_produk' => (string)$id_produk])])
                  ->orWhere('produk_data', 'like', '%"id_produk":' . $id_produk . '%')
                  ->orWhere('produk_data', 'like', '%"id_produk":"' . $id_produk . '"%');
            });
        } elseif ($kategori_code) {
            $matchedIds = \App\Models\Produk::where('kategori_code', $kategori_code)->pluck('id')->toArray();
            if (!empty($matchedIds)) {
                $query->where(function ($q) use ($matchedIds) {
                    foreach ($matchedIds as $pid) {
                        $q->orWhereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['id_produk' => (int)$pid])])
                          ->orWhereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['id_produk' => (string)$pid])])
                          ->orWhere('produk_data', 'like', '%"id_produk":' . $pid . '%')
                          ->orWhere('produk_data', 'like', '%"id_produk":"' . $pid . '"%');
                    }
                });
            } else {
                // If category has no products, return no results
                $query->whereRaw('1 = 0');
            }
        }

        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

        if ($id_shift) {
            $shift = Shift::find($id_shift);
            $shiftName = $shift ? trim(strtolower((string) $shift->shift)) : null;

            $isShift1 = $shift && $shift->is_date_range;

            if ($isShift1) {
                if ($tanggalDari && $tanggalSampai) {
                    $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
                } elseif ($tanggalDari) {
                    $query->whereDate('tanggal', '>=', $tanggalDari);
                } elseif ($tanggalSampai) {
                    $query->whereDate('tanggal', '<=', $tanggalSampai);
                }
            } else {
                if ($tanggal) {
                    $query->whereDate('tanggal', $tanggal);
                }
            }
        } else {
            if ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            }
        }

        $pemeriksaans = $query->latest()->get();

        if ($pemeriksaans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang sesuai dengan filter yang dipilih.');
        }

        $shift = $id_shift ? Shift::find($id_shift) : null;

        $qcUser = null;
        $produksiUser = null;
        $spvQcUser = null;

        $allQcIds = $pemeriksaans->pluck('verified_by_qc')->filter()->unique();
        $allProduksiIds = $pemeriksaans->pluck('verified_by_produksi')->filter()->unique();
        $allSpvIds = $pemeriksaans->pluck('verified_by_spv')->filter()->unique();

        if ($allQcIds->count() > 0) {
            $qcUserData = User::whereIn('id', $allQcIds->toArray())->first();
            if ($qcUserData) $qcUser = $qcUserData->name;
        }
        if ($allProduksiIds->count() > 0) {
            $produksiUserData = User::whereIn('id', $allProduksiIds->toArray())->first();
            if ($produksiUserData) $produksiUser = $produksiUserData->name;
        }
        if ($allSpvIds->count() > 0) {
            $spvUserData = User::whereIn('id', $allSpvIds->toArray())->first();
            if ($spvUserData) $spvQcUser = $spvUserData->name;
        }

        $produkIds = $pemeriksaans
            ->flatMap(function ($p) {
                $rows = is_array($p->produk_data) ? $p->produk_data : [];
                return collect($rows)->pluck('id_produk');
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkMap = Produk::query()
            ->whereIn('id', $produkIds)
            ->pluck('nama_produk', 'id')
            ->all();

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-loading-produk.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser,
            'produkMap' => $produkMap,
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-loading-produk-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }
}