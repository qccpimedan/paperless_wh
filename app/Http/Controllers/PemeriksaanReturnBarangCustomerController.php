<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanReturnBarangCustomer;
use App\Models\Shift;
use App\Models\Ekspedisi;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PemeriksaanReturnBarangCustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanReturnBarangCustomer::with([
            'user.role',
            'user.plant',
            'shift',
            'ekspedisi',
            'customer'
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

            // Cari id_customer yang nama_custnya cocok untuk lookup ke produk_data JSON
            $matchingCustomerIds = Customer::query()
                ->select('id')
                ->where('nama_cust', 'like', '%' . $search . '%')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $query->where(function ($q) use ($search, $matchingProductIds, $matchingCustomerIds) {
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
                    ->orWhereHas('ekspedisi', function ($qe) use ($search) {
                        $qe->where('nama_ekspedisi', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('customer', function ($qc) use ($search) {
                        $qc->where('nama_cust', 'like', '%' . $search . '%');
                    });

                // Cari produk berdasarkan nama di produk_data JSON
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

                // Cari customer berdasarkan nama di produk_data JSON (karena tiap baris punya id_customer)
                if (!empty($matchingCustomerIds)) {
                    $q->orWhere(function ($qj) use ($matchingCustomerIds) {
                        foreach ($matchingCustomerIds as $cid) {
                            $qj->orWhere('produk_data', 'like', '%"id_customer":' . $cid . '%');
                            $qj->orWhere('produk_data', 'like', '%"id_customer":"' . $cid . '"%');
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

        $produkNamaById = $produkList->pluck('nama_produk', 'id')->all();

        return view('qc-sistem.pemeriksaan-return-barang-customer.index', compact('pemeriksaans', 'produkKategoriOptions', 'produkList', 'produkByKategori', 'produkNamaById'));
    }

    public function create()
    {
        $user = Auth::user();

        $isSuperAdmin = $user->role && strtolower($user->role->role) === 'superadmin';

        // SuperAdmin dapat melihat semua data
        if ($isSuperAdmin) {
            $shifts = Shift::with(['user.plant'])->get();
            $ekspedisis = Ekspedisi::with(['user.plant'])->get();
            $customers = Customer::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $ekspedisis = Ekspedisi::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $customers = Customer::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $produks = Produk::with(['user.plant'])->get();
        }

        $produkKategoriOptions = Produk::query()
            ->whereNotNull('kategori_code')
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkByKategori = $produkList
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            });

        $produkKategoriById = $produkList->pluck('kategori_code', 'id');

        return view('qc-sistem.pemeriksaan-return-barang-customer.create', compact(
            'shifts',
            'ekspedisis',
            'customers',
            'produks',
            'produkKategoriOptions',
            'produkByKategori',
            'produkKategoriById'
        ));
    }

    public function store(Request $request)
    {
        // Debug: Uncomment untuk melihat data yang dikirim
        // dd($request->all());
        
        // Validasi dinamis berdasarkan pilihan ekspedisi
        $rules = [
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'no_polisi' => 'required|string|max:20',
            'nama_supir' => 'required|string|max:100',
            'waktu_kedatangan' => 'nullable|date_format:H:i', // Diperbaiki: format yang lebih fleksibel
            'suhu_mobil' => 'required|string|max:50',
            'produk_data' => 'required|array|min:1',
            'produk_data.*.id_customer' => 'required|exists:customers,id',
            'produk_data.*.alasan_return' => 'required|string|max:255',
            'produk_data.*.kondisi_produk' => 'required|in:Frozen,Fresh,Dry',
            'produk_data.*.kategori_code' => 'required|string',
            'produk_data.*.id_produk' => 'required|exists:produks,id',
            'produk_data.*.suhu_produk' => 'nullable|string|max:50',
            'produk_data.*.kode_produksi' => 'required|string|max:100',
            'produk_data.*.expired_date' => 'required|date',
            'produk_data.*.jumlah_barang' => 'required|string|max:100',
            'produk_data.*.kondisi_kemasan' => 'required|in:0,1', // Diperbaiki: validasi sebagai string
            'produk_data.*.kondisi_produk_check' => 'required|in:0,1', // Diperbaiki: validasi sebagai string
            'produk_data.*.rekomendasi' => 'required|string|max:255',
            'produk_data.*.keterangan' => 'nullable|string|max:500',
        ];

        // Jika pilih input manual ekspedisi, validasi nama_ekspedisi_manual
        if ($request->id_ekspedisi === 'other') {
            $rules['nama_ekspedisi_manual'] = 'required|string|max:100';
            $rules['id_ekspedisi'] = 'nullable'; // Ubah menjadi nullable
        } else {
            $rules['id_ekspedisi'] = 'nullable|exists:ekspedisis,id';
        }

        $validated = $request->validate($rules);

        // Cek apakah ekspedisi diinput manual
        if ($request->id_ekspedisi === 'other') {
            // Jika input manual diisi
            if ($request->nama_ekspedisi_manual) {
                // Buat record baru di tabel ekspedisis
                $ekspedisi = Ekspedisi::create([
                    'nama_ekspedisi' => $request->nama_ekspedisi_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_ekspedisi'] = $ekspedisi->id;
            } else {
                // Jika input manual tidak diisi, set id_ekspedisi ke null
                $validated['id_ekspedisi'] = null;
            }
            
            // PENTING: Hapus field manual dari validated data
            unset($validated['nama_ekspedisi_manual']);
        }

        $validated['id_user'] = Auth::id();
        
        // Process produk_data array
        $produkData = [];
        if ($request->has('produk_data')) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'id_customer' => $produk['id_customer'] ?? null,
                        'alasan_return' => $produk['alasan_return'] ?? null,
                        'kondisi_produk' => $produk['kondisi_produk'],
                        'id_produk' => $produk['id_produk'],
                        'suhu_produk' => $produk['suhu_produk'] ?? null,
                        'kode_produksi' => $produk['kode_produksi'],
                        'expired_date' => $produk['expired_date'],
                        'jumlah_barang' => $produk['jumlah_barang'],
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (int)$produk['kondisi_kemasan'] : 0, // Cast ke integer
                        'kondisi_produk_check' => isset($produk['kondisi_produk_check']) ? (int)$produk['kondisi_produk_check'] : 0, // Cast ke integer
                        'rekomendasi' => $produk['rekomendasi'],
                        'keterangan' => $produk['keterangan'] ?? null,
                        'kategori_code' => $produk['kategori_code'],
                    ];
                }
            }
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;

        $firstProduk = !empty($produkData) ? $produkData[0] : null;
        $validated['id_customer'] = $firstProduk['id_customer'] ?? null;
        $validated['alasan_return'] = $firstProduk['alasan_return'] ?? null;

        // Debug sebelum create (uncomment jika perlu)
        // dd($validated);
        
        try {
            PemeriksaanReturnBarangCustomer::create($validated);
            return redirect()->route('return-barang.index')->with('success', 'Pemeriksaan return barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error saving return barang: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        $pemeriksaanReturnBarangCustomer->load('user', 'shift', 'ekspedisi', 'customer', 'produk');
        return view('qc-sistem.pemeriksaan-return-barang-customer.show', compact('pemeriksaanReturnBarangCustomer'));
    }

    public function edit(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        $user = Auth::user();

        $isSuperAdmin = $user->role && strtolower($user->role->role) === 'superadmin';

        // SuperAdmin dapat melihat semua data
        if ($isSuperAdmin) {
            $shifts = Shift::with(['user.plant'])->get();
            $ekspedisis = Ekspedisi::with(['user.plant'])->get();
            $customers = Customer::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $ekspedisis = Ekspedisi::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $customers = Customer::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $produks = Produk::with(['user.plant'])->get();
        }
        $produkKategoriOptions = Produk::query()
            ->whereNotNull('kategori_code')
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkByKategori = $produkList
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            });

        $produkKategoriById = $produkList->pluck('kategori_code', 'id');
        return view('qc-sistem.pemeriksaan-return-barang-customer.edit', compact(
            'pemeriksaanReturnBarangCustomer',
            'shifts',
            'ekspedisis',
            'customers',
            'produks',
            'produkKategoriOptions',
            'produkByKategori',
            'produkKategoriById'
        ));
    }

    public function update(Request $request, PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);

        // Validasi dinamis berdasarkan pilihan ekspedisi
        $rules = [
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'no_polisi' => 'required|string|max:20',
            'nama_supir' => 'required|string|max:100',
            'waktu_kedatangan' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'suhu_mobil' => 'required|string|max:50',
            'produk_data' => 'required|array|min:1',
            'produk_data.*.id_customer' => 'required|exists:customers,id',
            'produk_data.*.alasan_return' => 'required|string|max:255',
            'produk_data.*.kondisi_produk' => 'required|in:Frozen,Fresh,Dry',
            'produk_data.*.kategori_code' => 'required|string',
            'produk_data.*.id_produk' => 'required|exists:produks,id',
            'produk_data.*.suhu_produk' => 'nullable|string|max:50',
            'produk_data.*.kode_produksi' => 'required|string|max:100',
            'produk_data.*.expired_date' => 'required|date',
            'produk_data.*.jumlah_barang' => 'required|string|max:100',
            'produk_data.*.kondisi_kemasan' => 'required|boolean',
            'produk_data.*.kondisi_produk_check' => 'required|boolean',
            'produk_data.*.rekomendasi' => 'required|string|max:255',
            'produk_data.*.keterangan' => 'nullable|string|max:500',
        ];

        // Jika pilih input manual ekspedisi, validasi nama_ekspedisi_manual
        if ($request->id_ekspedisi === 'other') {
            $rules['nama_ekspedisi_manual'] = 'required|string|max:100';
        } else {
            $rules['id_ekspedisi'] = 'nullable|exists:ekspedisis,id';
        }

        $validated = $request->validate($rules);

        // Cek apakah ekspedisi diinput manual
        if ($request->id_ekspedisi === 'other') {
            // Jika input manual diisi
            if ($request->nama_ekspedisi_manual) {
                // Buat record baru di tabel ekspedisis
                $ekspedisi = Ekspedisi::create([
                    'nama_ekspedisi' => $request->nama_ekspedisi_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_ekspedisi'] = $ekspedisi->id;
            } else {
                // Jika input manual tidak diisi, set id_ekspedisi ke null
                $validated['id_ekspedisi'] = null;
            }
        }

        // Process produk_data array
        $produkData = [];
        if ($request->has('produk_data')) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'id_customer' => $produk['id_customer'] ?? null,
                        'alasan_return' => $produk['alasan_return'] ?? null,
                        'kondisi_produk' => $produk['kondisi_produk'],
                        'id_produk' => $produk['id_produk'],
                        'suhu_produk' => $produk['suhu_produk'] ?? null,
                        'kode_produksi' => $produk['kode_produksi'],
                        'expired_date' => $produk['expired_date'],
                        'jumlah_barang' => $produk['jumlah_barang'],
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : false,
                        'kondisi_produk_check' => isset($produk['kondisi_produk_check']) ? (bool)$produk['kondisi_produk_check'] : false,
                        'rekomendasi' => $produk['rekomendasi'],
                        'keterangan' => $produk['keterangan'] ?? null,
                        'kategori_code' => $produk['kategori_code'],
                    ];
                }
            }
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;

        $firstProduk = !empty($produkData) ? $produkData[0] : null;
        $validated['id_customer'] = $firstProduk['id_customer'] ?? null;
        $validated['alasan_return'] = $firstProduk['alasan_return'] ?? null;

        // Remove old fields if they exist
        unset($validated['kondisi_produk'], $validated['id_produk'], $validated['suhu_produk'], 
              $validated['kode_produksi'], $validated['expired_date'], $validated['jumlah_barang'],
              $validated['kondisi_kemasan'], $validated['kondisi_produk_check'], $validated['rekomendasi'], $validated['keterangan']);

        $pemeriksaanReturnBarangCustomer->update($validated);

        return redirect()->route('return-barang.index')->with('success', 'Pemeriksaan return barang berhasil diperbarui!');
    }

    public function destroy(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        $pemeriksaanReturnBarangCustomer->delete();
        return redirect()->route('return-barang.index')->with('success', 'Pemeriksaan return barang berhasil dihapus!');
    }

    protected function checkPlantAccess(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $user = Auth::user();

        // SuperAdmin dapat akses semua
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }

        // Cek apakah data milik plant user
        if ($pemeriksaanReturnBarangCustomer->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Unauthorized access');
        }
    }

    public function sendToProduksi(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        
        if ($pemeriksaanReturnBarangCustomer->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanReturnBarangCustomer->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        
        if ($pemeriksaanReturnBarangCustomer->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanReturnBarangCustomer->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        
        if ($pemeriksaanReturnBarangCustomer->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanReturnBarangCustomer->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        
        if ($pemeriksaanReturnBarangCustomer->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanReturnBarangCustomer->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        
        if ($pemeriksaanReturnBarangCustomer->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanReturnBarangCustomer->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
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
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');
        $id_produk = $request->input('id_produk');
        $kategori_code = $request->input('kategori_code');

        // === MODE: ALL SHIFT ===
        if ($id_shift === 'all') {
            return $this->exportPDFAllShift($request, $user, $tanggalDari, $tanggalSampai, $id_produk, $kategori_code);
        }

        $query = PemeriksaanReturnBarangCustomer::with([
            'user.role',
            'user.plant',
            'shift',
            'ekspedisi',
            'customer',
            'verifiedBy.role'
        ])->with([
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

        
        if ($id_produk) {
            $query->where(function ($q) use ($id_produk) {
                $q->whereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['id_produk' => (int)$id_produk])])
                  ->orWhereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['id_produk' => (string)$id_produk])])
                  ->orWhere('produk_data', 'like', '%"id_produk":' . $id_produk . '%')
                  ->orWhere('produk_data', 'like', '%"id_produk":"' . $id_produk . '"%');
            });
        } elseif ($kategori_code) {
             $query->where(function ($q) use ($kategori_code) {
                $q->whereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['kategori_code' => $kategori_code])])
                  ->orWhere('produk_data', 'like', '%"kategori_code":"' . $kategori_code . '"%');
            });
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

        $produkIds = $pemeriksaans
            ->flatMap(function ($p) {
                $rows = is_array($p->produk_data) ? $p->produk_data : [];
                return collect($rows)
                    ->pluck('id_produk')
                    ->filter(fn ($id) => !empty($id));
            })
            ->unique()
            ->values();

        $produkNamaById = $produkIds->isNotEmpty()
            ? Produk::whereIn('id', $produkIds)->pluck('nama_produk', 'id')->toArray()
            : [];

        $qcUser = null;
        $produksiUser = null;
        $spvQcUser = null;

        $allQcIds = $pemeriksaans->pluck('verified_by_qc')->filter()->unique();
        $allProduksiIds = $pemeriksaans->pluck('verified_by_produksi')->filter()->unique();
        $allSpvIds = $pemeriksaans->pluck('verified_by_spv')->filter()->unique();

        if ($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if ($qcUserData) {
                $qcUser = $qcUserData->name;
            }
        }

        if ($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if ($produksiUserData) {
                $produksiUser = $produksiUserData->name;
            }
        }

        if ($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if ($spvUserData) {
                $spvQcUser = $spvUserData->name;
            }
        }

        $shift = $id_shift ? Shift::find($id_shift) : null;

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-return-barang-customer.pdf-report', [
            'pemeriksaans'   => $pemeriksaans,
            'produkNamaById' => $produkNamaById,
            'tanggal'        => $tanggal,
            'tanggal_dari'   => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift'          => $shift,
            'qcUser'         => $qcUser,
            'produksiUser'   => $produksiUser,
            'spvQcUser'      => $spvQcUser,
            'isAllShift'     => false,
            'dataPerShift'   => [],
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-return-barang-' . $filenameDate . '.pdf';
        $filename = 'laporan-return-barang-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export PDF semua shift dikelompokkan per shift
     */
    private function exportPDFAllShift($request, $user, $tanggalDari, $tanggalSampai, $id_produk, $kategori_code)
    {
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $allShifts = Shift::all();
        } else {
            $allShifts = Shift::query()
                ->when($user->id_plant, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('id_plant', $user->id_plant)))
                ->get();
        }

        $dataPerShift = [];

        foreach ($allShifts as $shift) {
            $query = PemeriksaanReturnBarangCustomer::with([
                'user.role', 'user.plant', 'shift', 'ekspedisi', 'customer',
                'qcVerifier'       => fn($q) => $q->select('id', 'name'),
                'produksiVerifier' => fn($q) => $q->select('id', 'name'),
                'spvVerifier'      => fn($q) => $q->select('id', 'name'),
            ]);

            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->whereHas('user', fn($q) => $q->where('id_plant', $user->getEffectivePlantId()));
            }

            $query->where('id_shift', $shift->id);

            if ($tanggalDari && $tanggalSampai) {
                $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
            } elseif ($tanggalDari) {
                $query->whereDate('tanggal', '>=', $tanggalDari);
            } elseif ($tanggalSampai) {
                $query->whereDate('tanggal', '<=', $tanggalSampai);
            }

            if ($id_produk) {
                $query->where(function ($q) use ($id_produk) {
                    $q->whereRaw("JSON_CONTAINS(produk_data, ?, '$')", [json_encode(['id_produk' => (int)$id_produk])])
                      ->orWhere('produk_data', 'like', '%"id_produk":' . $id_produk . '%');
                });
            } elseif ($kategori_code) {
                $query->where('produk_data', 'like', '%"kategori_code":"' . $kategori_code . '"%');
            }

            $records = $query->latest()->get();
            if ($records->isEmpty()) continue;

            $produkIds = $records->flatMap(function ($p) {
                return collect(is_array($p->produk_data) ? $p->produk_data : [])->pluck('id_produk')->filter();
            })->unique()->values();

            $produkNamaById = $produkIds->isNotEmpty()
                ? Produk::whereIn('id', $produkIds)->pluck('nama_produk', 'id')->toArray()
                : [];

            $dataPerShift[] = [
                'shift'          => $shift,
                'pemeriksaans'   => $records,
                'produkNamaById' => $produkNamaById,
            ];
        }

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-return-barang-customer.pdf-report', [
            'pemeriksaans'   => collect(),
            'produkNamaById' => [],
            'tanggal'        => null,
            'tanggal_dari'   => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift'          => null,
            'qcUser'         => null,
            'produksiUser'   => null,
            'spvQcUser'      => null,
            'isAllShift'     => true,
            'dataPerShift'   => $dataPerShift,
        ]);

        $filename = 'laporan-semua-shift-return-barang-'
            . ($tanggalDari ?? date('Y-m-d'))
            . ($tanggalSampai ? '-to-' . $tanggalSampai : '')
            . '.pdf';

        return $pdf->download($filename);
    }

    public function batchVerify(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role ? strtolower($user->role->role) : null;
        $id_shift = $request->input('id_shift');
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');
        $selected_uuids = $request->input('selected_uuids');

        $query = PemeriksaanReturnBarangCustomer::query();

        if ($userRole !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if (!empty($selected_uuids)) {
            $query->whereIn('uuid', $selected_uuids);
        } else {
            // JIKA MENGGUNAKAN KONTROL RANGE TANGGAL (FALLBACK)
            $tanggal_dari = $request->input('tanggal_dari');
            $tanggal_sampai = $request->input('tanggal_sampai');

            if (!$tanggal_dari || !$tanggal_sampai) {
                return back()->with('error', 'Silakan tentukan rentang tanggal atau gunakan checkbox untuk memilih data.');
            }

            $query->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai]);
        }

        $fromStatus = null;
        $updateData = [];

        if ($userRole === 'qc inspector') {
            $fromStatus = ['pending', null];
            $updateData = [
                'status_verifikasi' => 'sent_to_produksi',
                'verified_by' => $user->id,
                'verified_by_qc' => $user->id,
                'verified_at' => now()
            ];
        } elseif ($userRole === 'produksi' || $userRole === 'warehouse' || $userRole === 'produksi/warehouse') {
            $fromStatus = ['sent_to_produksi'];
            $updateData = [
                'status_verifikasi' => 'approved_produksi',
                'verified_by' => $user->id,
                'verified_by_produksi' => $user->id,
                'verified_at' => now()
            ];
        } elseif ($userRole === 'spv qc' || $userRole === 'superadmin') {
            $fromStatus = ['approved_produksi'];
            $updateData = [
                'status_verifikasi' => 'approved_spv',
                'verified_by' => $user->id,
                'verified_by_spv' => $user->id,
                'verified_at' => now()
            ];
        }

        if (!$fromStatus) {
            return back()->with('error', 'Role Anda tidak diizinkan melakukan verifikasi.');
        }

        $query->whereIn('status_verifikasi', (array) $fromStatus);
        $count = $query->count();

        if ($count > 0) {
            $query->update($updateData);
            return back()->with('success', "$count data pemeriksaan berhasil diverifikasi secara massal.");
        }

        return back()->with('info', 'Tidak ada data yang memenuhi syarat untuk diverifikasi pada filter tersebut.');
    }
}