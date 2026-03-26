<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganKemasan;
use App\Models\BahanKemasan;
use App\Models\Produk;
use App\Models\Shift;
use App\Models\Produsen;
use App\Models\User;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PemeriksaanKedatanganKemasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $effectivePlantId = $this->getActivePlantId($user);

        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanKedatanganKemasan::with(['user.role', 'user.plant', 'bahan', 'shift']);

        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            $query->whereHas('user', function ($q) use ($effectivePlantId) {
                $q->where('id_plant', $effectivePlantId);
            });
        }


        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                // Coba parse tanggal dari format d/m/Y atau Y-m-d
                $tanggalSearch = null;
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search)) {
                    // Format d/m/Y → Y-m-d
                    $parts = explode('/', $search);
                    $tanggalSearch = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) {
                    $tanggalSearch = $search;
                }

                if ($tanggalSearch) {
                    $q->whereDate('tanggal', $tanggalSearch);
                }

                $q->orWhere('no_po', 'like', '%' . $search . '%')
                    ->orWhere('kode_produksi_array', 'like', '%' . $search . '%')
                    ->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhere('verification_notes', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('bahan', function ($qb) use ($search) {
                        $qb->where('nama_bahan', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->whereHas('plant', function ($qp) use ($search) {
                            $qp->where('plant', 'like', '%' . $search . '%');
                        });
                    });

                // Cari nama produk di id_bahan_array via subquery ke tabel produks
                $matchingProdukIds = \App\Models\Produk::where('nama_produk', 'like', '%' . $search . '%')
                    ->pluck('id')
                    ->toArray();
                if (!empty($matchingProdukIds)) {
                    foreach ($matchingProdukIds as $pid) {
                        $q->orWhere('id_bahan_array', 'like', '%"' . $pid . '"%')
                          ->orWhere('id_bahan_array', 'like', '%,' . $pid . ',%')
                          ->orWhere('id_bahan_array', 'like', '[' . $pid . ',%')
                          ->orWhere('id_bahan_array', 'like', '%,' . $pid . ']');
                    }
                }
            });
        }

        $pemeriksaans = $query->latest()->paginate(25);

        $produkIds = $pemeriksaans
            ->flatMap(function ($pemeriksaan) {
                $ids = json_decode($pemeriksaan->id_bahan_array ?? '[]', true);
                return is_array($ids) ? $ids : [];
            })
            ->filter(fn ($id) => !empty($id))
            ->unique()
            ->values();

        $produkNamaById = $produkIds->isNotEmpty()
            ? Produk::whereIn('id', $produkIds)->pluck('nama_produk', 'id')
            : collect();
        
        
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

return view('qc-sistem.pemeriksaan-kedatangan-kemasan.index', compact('pemeriksaans', 'produkNamaById', 'produkKategoriOptions', 'produkList', 'produkByKategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Debug: Log user info
        \Log::info('User creating pemeriksaan:', [
            'user_id' => $user->id,
            'id_plant' => $user->id_plant,
            'role' => $user->role ? $user->role->role : 'no role'
        ]);
        
        // Get bahan kemasans, shifts, produsens, and distributors based on plant access
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $bahanKemasans = BahanKemasan::with(['user.plant', 'distributor', 'produsen'])->get();
            $shifts = Shift::with('user.plant')->get();
            $produsens = Produsen::with('user.plant')->get();
            $distributors = Distributor::with('user.plant')->get();
        } else {
            if ($user->id_plant) {
                // Filter berdasarkan plant
                $bahanKemasans = BahanKemasan::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with(['user.plant', 'distributor', 'produsen'])->get();
                
                $shifts = Shift::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
                
                $produsens = Produsen::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
                
                $distributors = Distributor::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
            } else {
                // Fallback: User has no plant, get all
                $bahanKemasans = BahanKemasan::with(['distributor', 'produsen'])->get();
                $shifts = Shift::all();
                $produsens = Produsen::all();
                $distributors = Distributor::all();
            }
        }
        
        // Fallback if no data found
        if ($bahanKemasans->isEmpty()) {
            $bahanKemasans = BahanKemasan::with(['distributor', 'produsen'])->get();
        }
        if ($shifts->isEmpty()) {
            $shifts = Shift::all();
        }
        if ($produsens->isEmpty()) {
            $produsens = Produsen::all();
        }
        if ($distributors->isEmpty()) {
            $distributors = Distributor::all();
        }

        $referencedProdusenIds = $bahanKemasans->pluck('id_produsen')->filter()->unique()->values();
        if ($referencedProdusenIds->isNotEmpty()) {
            $referencedProdusens = Produsen::with('user.plant')->whereIn('id', $referencedProdusenIds)->get();
            $produsens = $produsens->concat($referencedProdusens)->unique('id')->values();
        }

        $referencedDistributorIds = $bahanKemasans->pluck('id_distributor')->filter()->unique()->values();
        if ($referencedDistributorIds->isNotEmpty()) {
            $referencedDistributors = Distributor::with('user.plant')->whereIn('id', $referencedDistributorIds)->get();
            $distributors = $distributors->concat($referencedDistributors)->unique('id')->values();
        }
        
        \Log::info('Data for create view:', [
            'bahanKemasans_count' => $bahanKemasans->count(),
            'shifts_count' => $shifts->count(),
            'produsens_count' => $produsens->count(),
            'distributors_count' => $distributors->count()
        ]);

        $bahanKemasanMeta = $bahanKemasans
            ->mapWithKeys(function ($bk) {
                return [
                    $bk->id => [
                        'produsen' => $bk->produsen ? $bk->produsen->nama_produsen : '',
                        'distributor' => $bk->distributor ? $bk->distributor->nama_distributor : '',
                    ],
                ];
            });

        $plantId = $user->id_plant;

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

        $produkMeta = Produk::with([
                'produsens' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
                'distributors' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
            ])
            ->get()
            ->mapWithKeys(function ($p) {
                return [
                    $p->id => [
                        'produsen' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        return view('qc-sistem.pemeriksaan-kedatangan-kemasan.create', compact('bahanKemasans', 'shifts', 'produsens', 'distributors', 'bahanKemasanMeta', 'produkKategoriOptions', 'produkByKategori', 'produkMeta'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'kategori_code.*' => 'nullable|string|in:WHSE,WHD2,WHDS,RT01,CR01,CR02,SHTS,SHCS,OTRM,SHCS & OTRM',
            'id_produk.*' => 'nullable|exists:produks,id',
            'produsen' => 'array',
            'produsen.*' => 'array',
            'produsen.*.*' => 'nullable|string|max:255',
            'distributor' => 'array',
            'distributor.*' => 'array',
            'distributor.*.*' => 'nullable|string|max:255',
            'kode_produksi.*' => 'nullable|string|max:255',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'spesifikasi.*' => 'nullable|string',
            'penampakan.*' => 'nullable|in:0,1',
            'sealing.*' => 'nullable|in:0,1',
            'cetakan.*' => 'nullable|in:0,1',
            'ketebalan_micron.*' => 'nullable|numeric',
            'dimensi.*' => 'nullable|string|max:255',
            'status.*' => 'nullable|in:Release,Hold',
            'logo_halal.*' => 'nullable|in:0,1',
            'dokumen_halal.*' => 'nullable|in:0,1',
            'coa.*' => 'nullable|in:0,1',
            'keterangan.*' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
            'image_kemasan.*' => 'nullable|image|max:1024',
        ]);
    
        // Process kondisi mobil dengan logic yang benar
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
    
        // Get array data from dynamic form
        $id_produks = $request->input('id_produk', []);
        $produsenInput = $request->input('produsen', []);
        $distributorInput = $request->input('distributor', []);
        $kode_produksis = $request->input('kode_produksi', []);
        $jumlah_datangs = $request->input('jumlah_datang', []);
        $jumlah_samplings = $request->input('jumlah_sampling', []);
        $spesifikasis = $request->input('spesifikasi', []);
        $penampakans = array_values((array) $request->input('penampakan', []));
        $sealings = array_values((array) $request->input('sealing', []));
        $cetakans = array_values((array) $request->input('cetakan', []));
        $ketebalan_microns = $request->input('ketebalan_micron', []);
        $dimensis = $request->input('dimensi', []);
        $statuses = $request->input('status', []);
        $logo_halals = array_values((array) $request->input('logo_halal', []));
        $dokumen_halals = array_values((array) $request->input('dokumen_halal', []));
        $coas = array_values((array) $request->input('coa', []));
        $keterangans = $request->input('keterangan', []);

        $imageKemasanPaths = [];
        if ($request->hasFile('image_kemasan')) {
            foreach ((array) $request->file('image_kemasan') as $uploadedFile) {
                if ($uploadedFile) {
                    $imageKemasanPaths[] = $uploadedFile->storePublicly('pemeriksaan-kedatangan-kemasan', 'public');
                } else {
                    $imageKemasanPaths[] = null;
                }
            }
        }
    
        $normalizeMultiSelectRows = function ($input, int $rowCount) {
            $out = [];
            for ($i = 0; $i < $rowCount; $i++) {
                $vals = $input[$i] ?? [];
                if (is_string($vals)) {
                    $vals = [$vals];
                }
                if (!is_array($vals)) {
                    $vals = [];
                }
                $vals = array_values(array_filter($vals, fn ($v) => $v !== null && $v !== ''));
                $out[$i] = implode(', ', $vals);
            }
            return $out;
        };

        $rowCountForSupplier = max(count($id_produks), count($kode_produksis), count($jumlah_datangs), count($jumlah_samplings), count((array) $produsenInput), count((array) $distributorInput));
        $produsens = $normalizeMultiSelectRows((array) $produsenInput, $rowCountForSupplier);
        $distributors = $normalizeMultiSelectRows((array) $distributorInput, $rowCountForSupplier);

        // Ensure all arrays are properly formatted as JSON
        $data = [
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'jenis_pemeriksaan' => $request->input('jenis_pemeriksaan'),
            'no_po' => $request->input('no_po'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => $kondisiMobil,
            'id_user' => Auth::id(),
            'id_shift' => $request->input('id_shift'),
            'id_bahan_array' => json_encode(is_array($id_produks) ? $id_produks : []),
            'produsen_array' => json_encode(is_array($produsens) ? $produsens : []),
            'distributor_array' => json_encode(is_array($distributors) ? $distributors : []),
            'kode_produksi_array' => json_encode(is_array($kode_produksis) ? $kode_produksis : []),
            'jumlah_datang_array' => json_encode(is_array($jumlah_datangs) ? $jumlah_datangs : []),
            'jumlah_sampling_array' => json_encode(is_array($jumlah_samplings) ? $jumlah_samplings : []),
            'spesifikasi_array' => json_encode(is_array($spesifikasis) ? $spesifikasis : []),
            'penampakan_array' => json_encode(is_array($penampakans) ? $penampakans : []),
            'sealing_array' => json_encode(is_array($sealings) ? $sealings : []),
            'cetakan_array' => json_encode(is_array($cetakans) ? $cetakans : []),
            'ketebalan_micron_array' => json_encode(is_array($ketebalan_microns) ? $ketebalan_microns : []),
            'dimensi_array' => json_encode(is_array($dimensis) ? $dimensis : []),
            'status_array' => json_encode(is_array($statuses) ? $statuses : []),
            'logo_halal_array' => json_encode(is_array($logo_halals) ? $logo_halals : []),
            'dokumen_halal_array' => json_encode(is_array($dokumen_halals) ? $dokumen_halals : []),
            'coa_array' => json_encode(is_array($coas) ? $coas : []),
            'keterangan_array' => json_encode(is_array($keterangans) ? $keterangans : []),
            'image_kemasan_array' => json_encode(is_array($imageKemasanPaths) ? $imageKemasanPaths : []),
        ];
    
        PemeriksaanKedatanganKemasan::create($data);
    
        return redirect()->route('pemeriksaan-kedatangan-kemasan.index')
            ->with('success', 'Data pemeriksaan kedatangan kemasan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $pemeriksaanKedatanganKemasan->load(['user', 'bahan', 'shift']);

        $produkIds = collect(json_decode($pemeriksaanKedatanganKemasan->id_bahan_array ?? '[]', true))
            ->filter(fn ($id) => !empty($id))
            ->unique()
            ->values();

        $produkNamaById = $produkIds->isNotEmpty()
            ? Produk::whereIn('id', $produkIds)->pluck('nama_produk', 'id')->toArray()
            : [];

        return view('qc-sistem.pemeriksaan-kedatangan-kemasan.show', compact('pemeriksaanKedatanganKemasan', 'produkNamaById'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $user = Auth::user();
        
        // Get bahan kemasans, shifts, produsens, and distributors based on plant access (SAMA SEPERTI CREATE)
    if ($user->role && strtolower($user->role->role) === 'superadmin') {
        $bahanKemasans = BahanKemasan::with(['user.plant', 'distributor', 'produsen'])->get();
        $shifts = Shift::with('user.plant')->get();
        $produsens = Produsen::with('user.plant')->get();
        $distributors = Distributor::with('user.plant')->get();
    } else {
        if ($user->id_plant) {
            // Filter berdasarkan plant
            $bahanKemasans = BahanKemasan::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant', 'distributor', 'produsen'])->get();
            
            $shifts = Shift::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with('user.plant')->get();
            
            $produsens = Produsen::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with('user.plant')->get();
            
            $distributors = Distributor::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with('user.plant')->get();
        } else {
            // Fallback: User has no plant, get all
            $bahanKemasans = BahanKemasan::with(['distributor', 'produsen'])->get();
            $shifts = Shift::all();
            $produsens = Produsen::all();
            $distributors = Distributor::all();
        }
    }

    // Fallback if no data found
    if ($bahanKemasans->isEmpty()) {
        $bahanKemasans = BahanKemasan::with(['distributor', 'produsen'])->get();
    }
    if ($shifts->isEmpty()) {
        $shifts = Shift::all();
    }
    if ($produsens->isEmpty()) {
        $produsens = Produsen::all();
    }
    if ($distributors->isEmpty()) {
        $distributors = Distributor::all();
    }

    $referencedProdusenIds = $bahanKemasans->pluck('id_produsen')->filter()->unique()->values();
    if ($referencedProdusenIds->isNotEmpty()) {
        $referencedProdusens = Produsen::with('user.plant')->whereIn('id', $referencedProdusenIds)->get();
        $produsens = $produsens->concat($referencedProdusens)->unique('id')->values();
    }

    $referencedDistributorIds = $bahanKemasans->pluck('id_distributor')->filter()->unique()->values();
    if ($referencedDistributorIds->isNotEmpty()) {
        $referencedDistributors = Distributor::with('user.plant')->whereIn('id', $referencedDistributorIds)->get();
        $distributors = $distributors->concat($referencedDistributors)->unique('id')->values();
    }

    $plantId = $user->id_plant;

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

    $produkMeta = Produk::with([
            'produsens' => function ($q) use ($plantId) {
                if ($plantId) {
                    $q->wherePivot('id_plant', $plantId);
                }
            },
            'distributors' => function ($q) use ($plantId) {
                if ($plantId) {
                    $q->wherePivot('id_plant', $plantId);
                }
            },
        ])
        ->get()
        ->mapWithKeys(function ($p) {
            return [
                $p->id => [
                    'produsen' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                    'distributor' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                ],
            ];
        });

    $existingProdukIds = json_decode($pemeriksaanKedatanganKemasan->id_bahan_array ?? '[]', true);
    $existingProdukIds = is_array($existingProdukIds) ? array_values(array_filter($existingProdukIds, fn ($v) => $v !== null && $v !== '')) : [];

    $existingKategoriByProdukId = !empty($existingProdukIds)
        ? Produk::whereIn('id', $existingProdukIds)->pluck('kategori_code', 'id')->toArray()
        : [];

    return view('qc-sistem.pemeriksaan-kedatangan-kemasan.edit', 
    compact('pemeriksaanKedatanganKemasan', 'bahanKemasans', 'shifts', 'produsens', 'distributors', 'produkKategoriOptions', 'produkByKategori', 'produkMeta', 'existingKategoriByProdukId'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'kategori_code.*' => 'nullable|string|in:WHSE,WHD2,WHDS,RT01,CR01,CR02,SHTS,SHCS,OTRM,SHCS & OTRM',
            'id_produk.*' => 'nullable|exists:produks,id',
            'produsen' => 'array',
            'produsen.*' => 'array',
            'produsen.*.*' => 'nullable|string|max:255',
            'distributor' => 'array',
            'distributor.*' => 'array',
            'distributor.*.*' => 'nullable|string|max:255',
            'kode_produksi.*' => 'nullable|string|max:255',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'spesifikasi.*' => 'nullable|string',
            'penampakan.*' => 'nullable|in:0,1',
            'sealing.*' => 'nullable|in:0,1',
            'cetakan.*' => 'nullable|in:0,1',
            'ketebalan_micron.*' => 'nullable|numeric',
            'dimensi.*' => 'nullable|string|max:255',
            'status.*' => 'nullable|in:Release,Hold',
            'logo_halal.*' => 'nullable|in:0,1',
            'dokumen_halal.*' => 'nullable|in:0,1',
            'coa.*' => 'nullable|in:0,1',
            'keterangan.*' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
            'image_kemasan.*' => 'nullable|image|max:1024',
        ]);
    
        // Process kondisi mobil dengan logic yang benar
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
    
        // Get array data from dynamic form
        $id_produks = $request->input('id_produk', []);
        $produsenInput = $request->input('produsen', []);
        $distributorInput = $request->input('distributor', []);
        $kode_produksis = $request->input('kode_produksi', []);
        $jumlah_datangs = $request->input('jumlah_datang', []);
        $jumlah_samplings = $request->input('jumlah_sampling', []);
        $spesifikasis = $request->input('spesifikasi', []);
        $penampakans = array_values((array) $request->input('penampakan', []));
        $sealings = array_values((array) $request->input('sealing', []));
        $cetakans = array_values((array) $request->input('cetakan', []));
        $ketebalan_microns = $request->input('ketebalan_micron', []);
        $dimensis = $request->input('dimensi', []);
        $statuses = $request->input('status', []);
        $logo_halals = array_values((array) $request->input('logo_halal', []));
        $dokumen_halals = array_values((array) $request->input('dokumen_halal', []));
        $coas = array_values((array) $request->input('coa', []));
        $keterangans = $request->input('keterangan', []);

        $existingImageKemasan = json_decode($pemeriksaanKedatanganKemasan->image_kemasan_array ?? '[]', true);
        if (!is_array($existingImageKemasan)) {
            $existingImageKemasan = [];
        }

        $newImageKemasan = [];
        $uploadedImages = (array) $request->file('image_kemasan', []);

        $rowCount = max(count($id_produks), count($kode_produksis), count($uploadedImages), count($existingImageKemasan));
        for ($i = 0; $i < $rowCount; $i++) {
            $uploadedFile = $uploadedImages[$i] ?? null;
            if ($uploadedFile) {
                $newImageKemasan[$i] = $uploadedFile->storePublicly('pemeriksaan-kedatangan-kemasan', 'public');
            } else {
                $newImageKemasan[$i] = $existingImageKemasan[$i] ?? null;
            }
        }

        $normalizeMultiSelectRows = function ($input, int $rowCount) {
            $out = [];
            for ($i = 0; $i < $rowCount; $i++) {
                $vals = $input[$i] ?? [];
                if (is_string($vals)) {
                    $vals = [$vals];
                }
                if (!is_array($vals)) {
                    $vals = [];
                }
                $vals = array_values(array_filter($vals, fn ($v) => $v !== null && $v !== ''));
                $out[$i] = implode(', ', $vals);
            }
            return $out;
        };

        $rowCountForSupplier = max(count($id_produks), count($kode_produksis), count($jumlah_datangs), count($jumlah_samplings), count((array) $produsenInput), count((array) $distributorInput));
        $produsens = $normalizeMultiSelectRows((array) $produsenInput, $rowCountForSupplier);
        $distributors = $normalizeMultiSelectRows((array) $distributorInput, $rowCountForSupplier);
    
        // Ensure all arrays are properly formatted as JSON
        $data = [
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'jenis_pemeriksaan' => $request->input('jenis_pemeriksaan'),
            'no_po' => $request->input('no_po'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => $kondisiMobil,
            'id_shift' => $request->input('id_shift'),
            'id_bahan_array' => json_encode(is_array($id_produks) ? $id_produks : []),
            'produsen_array' => json_encode(is_array($produsens) ? $produsens : []),
            'distributor_array' => json_encode(is_array($distributors) ? $distributors : []),
            'kode_produksi_array' => json_encode(is_array($kode_produksis) ? $kode_produksis : []),
            'jumlah_datang_array' => json_encode(is_array($jumlah_datangs) ? $jumlah_datangs : []),
            'jumlah_sampling_array' => json_encode(is_array($jumlah_samplings) ? $jumlah_samplings : []),
            'spesifikasi_array' => json_encode(is_array($spesifikasis) ? $spesifikasis : []),
            'penampakan_array' => json_encode(is_array($penampakans) ? $penampakans : []),
            'sealing_array' => json_encode(is_array($sealings) ? $sealings : []),
            'cetakan_array' => json_encode(is_array($cetakans) ? $cetakans : []),
            'ketebalan_micron_array' => json_encode(is_array($ketebalan_microns) ? $ketebalan_microns : []),
            'dimensi_array' => json_encode(is_array($dimensis) ? $dimensis : []),
            'status_array' => json_encode(is_array($statuses) ? $statuses : []),
            'logo_halal_array' => json_encode(is_array($logo_halals) ? $logo_halals : []),
            'dokumen_halal_array' => json_encode(is_array($dokumen_halals) ? $dokumen_halals : []),
            'coa_array' => json_encode(is_array($coas) ? $coas : []),
            'keterangan_array' => json_encode(is_array($keterangans) ? $keterangans : []),
            'image_kemasan_array' => json_encode(array_values($newImageKemasan)),
        ];
    
        $pemeriksaanKedatanganKemasan->update($data);
    
        return redirect()->route('pemeriksaan-kedatangan-kemasan.index')
            ->with('success', 'Data pemeriksaan kedatangan kemasan berhasil diupdate!');
    }

    public function createRow(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);

        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $bahanKemasans = BahanKemasan::with(['user.plant', 'distributor', 'produsen'])->get();
            $shifts = Shift::with('user.plant')->get();
            $produsens = Produsen::with('user.plant')->get();
            $distributors = Distributor::with('user.plant')->get();
        } else {
            if ($user->id_plant) {
                $bahanKemasans = BahanKemasan::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with(['user.plant', 'distributor', 'produsen'])->get();

                $shifts = Shift::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();

                $produsens = Produsen::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();

                $distributors = Distributor::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
            } else {
                $bahanKemasans = BahanKemasan::with(['distributor', 'produsen'])->get();
                $shifts = Shift::all();
                $produsens = Produsen::all();
                $distributors = Distributor::all();
            }
        }

        if ($bahanKemasans->isEmpty()) {
            $bahanKemasans = BahanKemasan::with(['distributor', 'produsen'])->get();
        }
        if ($shifts->isEmpty()) {
            $shifts = Shift::all();
        }
        if ($produsens->isEmpty()) {
            $produsens = Produsen::all();
        }
        if ($distributors->isEmpty()) {
            $distributors = Distributor::all();
        }

        $plantId = $user->id_plant;

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

        $produkMeta = Produk::with([
                'produsens' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
                'distributors' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
            ])
            ->get()
            ->mapWithKeys(function ($p) {
                return [
                    $p->id => [
                        'produsen' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        return view('qc-sistem.pemeriksaan-kedatangan-kemasan.tambah-baris', compact(
            'pemeriksaanKedatanganKemasan',
            'bahanKemasans',
            'shifts',
            'produsens',
            'distributors',
            'produkKategoriOptions',
            'produkByKategori',
            'produkMeta'
        ));
    }

    public function storeRow(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);

        $request->validate([
            'kategori_code.*' => 'nullable|string|in:WHSE,WHD2,WHDS,RT01,CR01,CR02,SHTS,SHCS,OTRM,SHCS & OTRM',
            'id_produk.*' => 'nullable|exists:produks,id',
            'produsen' => 'array',
            'produsen.*' => 'array',
            'produsen.*.*' => 'nullable|string|max:255',
            'distributor' => 'array',
            'distributor.*' => 'array',
            'distributor.*.*' => 'nullable|string|max:255',
            'kode_produksi.*' => 'nullable|string|max:255',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'spesifikasi.*' => 'nullable|string',
            'penampakan.*' => 'nullable|in:0,1',
            'sealing.*' => 'nullable|in:0,1',
            'cetakan.*' => 'nullable|in:0,1',
            'ketebalan_micron.*' => 'nullable|numeric',
            'dimensi.*' => 'nullable|string|max:255',
            'status.*' => 'nullable|in:Release,Hold',
            'logo_halal.*' => 'nullable|in:0,1',
            'dokumen_halal.*' => 'nullable|in:0,1',
            'coa.*' => 'nullable|in:0,1',
            'keterangan.*' => 'nullable|string',
            'image_kemasan.*' => 'nullable|image|max:1024',
        ]);

        $existingIdBahans = json_decode($pemeriksaanKedatanganKemasan->id_bahan_array ?? '[]', true) ?? [];
        $existingProdusens = json_decode($pemeriksaanKedatanganKemasan->produsen_array ?? '[]', true) ?? [];
        $existingDistributors = json_decode($pemeriksaanKedatanganKemasan->distributor_array ?? '[]', true) ?? [];
        $existingKodeProduksis = json_decode($pemeriksaanKedatanganKemasan->kode_produksi_array ?? '[]', true) ?? [];
        $existingJumlahDatangs = json_decode($pemeriksaanKedatanganKemasan->jumlah_datang_array ?? '[]', true) ?? [];
        $existingJumlahSamplings = json_decode($pemeriksaanKedatanganKemasan->jumlah_sampling_array ?? '[]', true) ?? [];
        $existingSpesifikasis = json_decode($pemeriksaanKedatanganKemasan->spesifikasi_array ?? '[]', true) ?? [];
        $existingPenampakans = json_decode($pemeriksaanKedatanganKemasan->penampakan_array ?? '[]', true) ?? [];
        $existingSealings = json_decode($pemeriksaanKedatanganKemasan->sealing_array ?? '[]', true) ?? [];
        $existingCetakans = json_decode($pemeriksaanKedatanganKemasan->cetakan_array ?? '[]', true) ?? [];
        $existingKetebalanMicrons = json_decode($pemeriksaanKedatanganKemasan->ketebalan_micron_array ?? '[]', true) ?? [];
        $existingDimensis = json_decode($pemeriksaanKedatanganKemasan->dimensi_array ?? '[]', true) ?? [];
        $existingStatuses = json_decode($pemeriksaanKedatanganKemasan->status_array ?? '[]', true) ?? [];
        $existingLogoHalals = json_decode($pemeriksaanKedatanganKemasan->logo_halal_array ?? '[]', true) ?? [];
        $existingDokumenHalals = json_decode($pemeriksaanKedatanganKemasan->dokumen_halal_array ?? '[]', true) ?? [];
        $existingCoas = json_decode($pemeriksaanKedatanganKemasan->coa_array ?? '[]', true) ?? [];
        $existingKeterangans = json_decode($pemeriksaanKedatanganKemasan->keterangan_array ?? '[]', true) ?? [];
        $existingImageKemasans = json_decode($pemeriksaanKedatanganKemasan->image_kemasan_array ?? '[]', true) ?? [];

        $normalizeMultiSelectRow = function ($vals) {
            if (is_string($vals)) {
                $vals = [$vals];
            }
            if (!is_array($vals)) {
                $vals = [];
            }
            $vals = array_values(array_filter($vals, fn ($v) => $v !== null && $v !== ''));
            return implode(', ', $vals);
        };

        $existingIdBahans[] = $request->input('id_produk.0');
        $existingProdusens[] = $normalizeMultiSelectRow($request->input('produsen.0'));
        $existingDistributors[] = $normalizeMultiSelectRow($request->input('distributor.0'));
        $existingKodeProduksis[] = $request->input('kode_produksi.0');
        $existingJumlahDatangs[] = $request->input('jumlah_datang.0');
        $existingJumlahSamplings[] = $request->input('jumlah_sampling.0');
        $existingSpesifikasis[] = $request->input('spesifikasi.0');
        $existingPenampakans[] = $request->input('penampakan.0');
        $existingSealings[] = $request->input('sealing.0');
        $existingCetakans[] = $request->input('cetakan.0');
        $existingKetebalanMicrons[] = $request->input('ketebalan_micron.0');
        $existingDimensis[] = $request->input('dimensi.0');
        $existingStatuses[] = $request->input('status.0');
        $existingLogoHalals[] = $request->input('logo_halal.0');
        $existingDokumenHalals[] = $request->input('dokumen_halal.0');
        $existingCoas[] = $request->input('coa.0');
        $existingKeterangans[] = $request->input('keterangan.0');

        $uploadedImage = $request->file('image_kemasan.0');
        if ($uploadedImage) {
            $existingImageKemasans[] = $uploadedImage->storePublicly('pemeriksaan-kedatangan-kemasan', 'public');
        } else {
            $existingImageKemasans[] = null;
        }

        $pemeriksaanKedatanganKemasan->update([
            'id_bahan_array' => json_encode($existingIdBahans),
            'produsen_array' => json_encode($existingProdusens),
            'distributor_array' => json_encode($existingDistributors),
            'kode_produksi_array' => json_encode($existingKodeProduksis),
            'jumlah_datang_array' => json_encode($existingJumlahDatangs),
            'jumlah_sampling_array' => json_encode($existingJumlahSamplings),
            'spesifikasi_array' => json_encode($existingSpesifikasis),
            'penampakan_array' => json_encode($existingPenampakans),
            'sealing_array' => json_encode($existingSealings),
            'cetakan_array' => json_encode($existingCetakans),
            'ketebalan_micron_array' => json_encode($existingKetebalanMicrons),
            'dimensi_array' => json_encode($existingDimensis),
            'status_array' => json_encode($existingStatuses),
            'logo_halal_array' => json_encode($existingLogoHalals),
            'dokumen_halal_array' => json_encode($existingDokumenHalals),
            'coa_array' => json_encode($existingCoas),
            'keterangan_array' => json_encode($existingKeterangans),
            'image_kemasan_array' => json_encode($existingImageKemasans),
        ]);

        return redirect()->route('pemeriksaan-kedatangan-kemasan.index')
            ->with('success', 'Baris baru berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $pemeriksaanKedatanganKemasan->delete();
        return redirect()->route('pemeriksaan-kedatangan-kemasan.index')
            ->with('success', 'Data pemeriksaan kedatangan kemasan berhasil dihapus!');
    }

    /**
     * Check if user has access to pemeriksaan based on plant
     */
    private function checkPlantAccess(PemeriksaanKedatanganKemasan $pemeriksaan)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($pemeriksaan->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    public function sendToProduksi(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        $pemeriksaanKedatanganKemasan->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now()
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        $pemeriksaanKedatanganKemasan->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanKedatanganKemasan->update(['status_verifikasi' => 'rejected_produksi', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        $pemeriksaanKedatanganKemasan->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanKedatanganKemasan->update(['status_verifikasi' => 'rejected_spv', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
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

        // Build query
        $query = PemeriksaanKedatanganKemasan::with([
            'user.role', 
            'user.plant', 
            'bahan', 
            'shift', 
            'verifiedBy.role'
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
        
        // Filter by produk / kategori
        if ($id_produk) {
            $query->where(function ($q) use ($id_produk) {
                $q->whereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((int)$id_produk)])
                  ->orWhereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((string)$id_produk)])
                  ->orWhere('id_bahan_array', 'like', '%"' . $id_produk . '"%')
                  ->orWhere('id_bahan_array', 'like', '%,' . $id_produk . ',%')
                  ->orWhere('id_bahan_array', 'like', '[' . $id_produk . ',%')
                  ->orWhere('id_bahan_array', 'like', '%,' . $id_produk . ']');
            });
        } elseif ($kategori_code) {
            // Because JSON array only stores ID, we must find matching produk IDs first to filter by category
            $matchedIds = \App\Models\Produk::where('kategori_code', $kategori_code)->pluck('id')->toArray();
            if (!empty($matchedIds)) {
                $query->where(function ($q) use ($matchedIds) {
                    foreach ($matchedIds as $pid) {
                        $q->orWhereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((int)$pid)])
                          ->orWhereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((string)$pid)])
                          ->orWhere('id_bahan_array', 'like', '%"' . $pid . '"%')
                          ->orWhere('id_bahan_array', 'like', '%,' . $pid . ',%')
                          ->orWhere('id_bahan_array', 'like', '[' . $pid . ',%')
                          ->orWhere('id_bahan_array', 'like', '%,' . $pid . ']');
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

        // Filter tanggal berdasarkan shift
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
        
        \Log::info('Verified IDs:', [
            'qc_ids' => $allQcIds->toArray(),
            'produksi_ids' => $allProduksiIds->toArray(),
            'spv_ids' => $allSpvIds->toArray()
        ]);
        
        // Get QC user with role
        if($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if($qcUserData) {
                $qcUser = $qcUserData->name;
                \Log::info('Found QC user: ' . $qcUser . ' (ID: ' . $qcUserData->id . ')');
            }
        }
        
        // Get Produksi user with role
        if($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if($produksiUserData) {
                $produksiUser = $produksiUserData->name;
                \Log::info('Found Produksi user: ' . $produksiUser . ' (ID: ' . $produksiUserData->id . ')');
            }
        }
        
        // Get SPV user with role
        if($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if($spvUserData) {
                $spvQcUser = $spvUserData->name;
                \Log::info('Found SPV user: ' . $spvQcUser . ' (ID: ' . $spvUserData->id . ')');
            }
        }

        // Generate PDF
        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-kedatangan-kemasan.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-kemasan-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }
}
