<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganChemical;
use App\Models\Shift;
use App\Models\Chemical;
use App\Models\Produsen;
use App\Models\User;
use App\Models\Distributor;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Monarobase\CountryList\CountryListFacade as Countries;

class PemeriksaanKedatanganChemicalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanKedatanganChemical::with(['user.role', 'user.plant', 'shift']);

        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        if ($search !== '') {
            $matchingChemicalIds = Chemical::query()
                ->select('id')
                ->where('nama_chemical', 'like', '%' . $search . '%')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $query->where(function ($q) use ($search, $matchingChemicalIds) {
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
                    ->orWhere('detail_chemicals', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    });

                if (!empty($matchingChemicalIds)) {
                    $q->orWhere(function ($qj) use ($matchingChemicalIds) {
                        foreach ($matchingChemicalIds as $cid) {
                            $qj->orWhereRaw(
                                "JSON_CONTAINS(CAST(detail_chemicals AS JSON), ?, '$')",
                                [json_encode(['id_chemical' => $cid])]
                            );

                            $qj->orWhereRaw(
                                "JSON_CONTAINS(CAST(detail_chemicals AS JSON), ?, '$')",
                                [json_encode(['id_chemical' => (string) $cid])]
                            );

                            $qj->orWhere('detail_chemicals', 'like', '%"id_chemical":' . $cid . '%');
                            $qj->orWhere('detail_chemicals', 'like', '%"id_chemical":"' . $cid . '"%');
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

return view('qc-sistem.pemeriksaan-kedatangan-chemical.index', compact('pemeriksaans', 'produkKategoriOptions', 'produkList', 'produkByKategori'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $chemicals = Chemical::with(['user.plant'])->get();
            $produsens = Produsen::with(['user.plant'])->get();
            $distributors = Distributor::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $chemicals = Chemical::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $countries = Countries::getList('en', 'php');
        }

        $plantId = $user->id_plant;

        $produkKategoriOptions = Produk::query()
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        if ($produkKategoriOptions->isEmpty()) {
            $produkKategoriOptions = collect(['CHEMICAL']);
        }

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
                        'produsen_ids' => $p->produsens->pluck('id')->values()->toArray(),
                        'produsen_names' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor_ids' => $p->distributors->pluck('id')->values()->toArray(),
                        'distributor_names' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        $chemicalByName = $chemicals
            ->mapWithKeys(function ($c) {
                $key = strtolower(trim((string) $c->nama_chemical));
                return [$key => $c->id];
            });

        $normalizeName = function ($value) {
            $v = strtolower(trim((string) $value));
            $v = preg_replace('/[^a-z0-9]+/i', '', $v);
            return $v;
        };

        $chemicalByNorm = $chemicals
            ->mapWithKeys(function ($c) use ($normalizeName) {
                return [$normalizeName($c->nama_chemical) => $c->id];
            });

        $chemicalByProdukId = $produkList
            ->mapWithKeys(function ($p) use ($chemicalByName, $chemicalByNorm, $normalizeName) {
                $rawKey = strtolower(trim((string) $p->nama_produk));
                $normKey = $normalizeName($p->nama_produk);

                $exact = $chemicalByName[$rawKey] ?? null;
                if ($exact) {
                    return [$p->id => $exact];
                }

                $normExact = $chemicalByNorm[$normKey] ?? null;
                if ($normExact) {
                    return [$p->id => $normExact];
                }

                // Fallback partial match on normalized strings
                foreach ($chemicalByNorm as $chemNorm => $chemId) {
                    if ($chemNorm && $normKey && (str_contains($chemNorm, $normKey) || str_contains($normKey, $chemNorm))) {
                        return [$p->id => $chemId];
                    }
                }

                return [$p->id => null];
            });

        return view('qc-sistem.pemeriksaan-kedatangan-chemical.create', compact('shifts', 'chemicals', 'produsens', 'distributors', 'countries', 'produkKategoriOptions', 'produkByKategori', 'produkMeta', 'chemicalByName', 'chemicalByProdukId'));
    }

    private function checkPlantAccess($pemeriksaan)
    {
        $user = Auth::user();
        
        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            if ($pemeriksaan->user->id_plant !== $user->id_plant) {
                abort(403, 'Unauthorized access to different plant data.');
            }
        }
    }

    public function store(Request $request)
    {
        $inputProdukIds = $request->input('id_produk', []);
        $inputChemicalIds = $request->input('id_chemical', []);

        if (is_array($inputProdukIds) && is_array($inputChemicalIds)) {
            $produkRows = Produk::query()
                ->select(['id', 'nama_produk'])
                ->whereIn('id', array_filter($inputProdukIds))
                ->get();

            $produkNameById = $produkRows
                ->mapWithKeys(function ($p) {
                    return [$p->id => strtolower(trim((string) $p->nama_produk))];
                });

            $produkRawNameById = $produkRows
                ->mapWithKeys(function ($p) {
                    return [$p->id => (string) $p->nama_produk];
                });

            $user = Auth::user();

            if ($user->role && strtolower($user->role->role) === 'superadmin') {
                $chemicalRows = Chemical::query()
                    ->select(['id', 'nama_chemical'])
                    ->get();
            } else {
                $chemicalRows = Chemical::query()
                    ->select(['id', 'nama_chemical'])
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('id_plant', $user->id_plant);
                    })
                    ->get();
            }

            $chemicalIdByName = $chemicalRows
                ->mapWithKeys(function ($c) {
                    return [strtolower(trim((string) $c->nama_chemical)) => $c->id];
                });

            $normalizeName = function ($value) {
                $v = strtolower(trim((string) $value));
                $v = preg_replace('/[^a-z0-9]+/i', '', $v);
                return $v;
            };

            $chemicalIdByNorm = $chemicalRows
                ->mapWithKeys(function ($c) use ($normalizeName) {
                    return [$normalizeName($c->nama_chemical) => $c->id];
                });

            $mappedChemicalIds = [];
            $missingMapErrors = [];
            foreach ($inputProdukIds as $idx => $produkId) {
                $produkKey = $produkNameById[(int) $produkId] ?? null;
                $produkRawName = $produkRawNameById[(int) $produkId] ?? null;
                $currentChemicalId = $inputChemicalIds[$idx] ?? null;

                if (!empty($currentChemicalId)) {
                    $mappedChemicalIds[$idx] = $currentChemicalId;
                    continue;
                }

                $mapped = $produkKey ? ($chemicalIdByName[$produkKey] ?? null) : null;
                if (!$mapped && $produkKey) {
                    $normProdukKey = $normalizeName($produkKey);
                    $mapped = $chemicalIdByNorm[$normProdukKey] ?? null;
                }

                if (!$mapped && $produkKey) {
                    $normProdukKey = $normalizeName($produkKey);
                    foreach ($chemicalIdByNorm as $chemNorm => $chemId) {
                        if ($chemNorm && $normProdukKey && (str_contains($chemNorm, $normProdukKey) || str_contains($normProdukKey, $chemNorm))) {
                            $mapped = $chemId;
                            break;
                        }
                    }
                }

                if (!$mapped && $produkRawName) {
                    $createPayload = [
                        'id_user' => $user->id,
                        'nama_chemical' => $produkRawName,
                    ];

                    $incomingProdusen = $request->input('id_produsen.' . $idx);
                    if (!empty($incomingProdusen)) {
                        $createPayload['id_produsen'] = $incomingProdusen;
                    }

                    $incomingDistributor = $request->input('id_distributor.' . $idx);
                    if (!empty($incomingDistributor)) {
                        $createPayload['id_distributor'] = $incomingDistributor;
                    }

                    $createdChemical = Chemical::create($createPayload);
                    $mapped = $createdChemical->id;
                }

                if (!$mapped) {
                    $missingMapErrors['id_chemical.' . $idx] = 'Produk "' . ($produkRawName ?? $produkKey ?? '-') . '" belum terhubung ke master Chemical dan tidak bisa dibuat otomatis. Silakan cek master Chemical.';
                }

                $mappedChemicalIds[$idx] = $mapped;
            }

            $request->merge([
                'id_chemical' => $mappedChemicalIds,
            ]);

            if (!empty($missingMapErrors)) {
                throw ValidationException::withMessages($missingMapErrors);
            }
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'id_shift' => 'nullable|exists:shifts,id',
            // Validasi array untuk dynamic rows
            'id_chemical' => 'required|array',
            'id_chemical.*' => 'required|exists:chemicals,id',
            'kondisi_chemical' => 'nullable|array',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'negara_produsen' => 'nullable|array',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
            'kode_produksi' => 'nullable|array',
            'expire_date' => 'nullable|array',
            'jumlah_datang' => 'nullable|array',
            'jumlah_sampling' => 'nullable|array',
            'kondisi_fisik_kemasan' => 'nullable|array',
            'kondisi_fisik_warna' => 'nullable|array',
            'persyaratan_dokumen_halal' => 'nullable|array',
            'coa' => 'nullable|array',
            'status_baris' => 'required|array',
            'status_baris.*' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|array',
            'image_chemical' => 'nullable|array',
            'image_chemical.*' => 'nullable|image|max:1024',
        ]);

        // Process kondisi mobil (11 items)
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

        // Process detail chemicals dari dynamic rows
        $detailChemicals = [];
        $idChemicals = $request->input('id_chemical', []);
        $uploadedImages = (array) $request->file('image_chemical', []);
        
        foreach ($idChemicals as $index => $idChemical) {
            $uploadedImage = $uploadedImages[$index] ?? null;
            $imagePath = $uploadedImage ? $uploadedImage->storePublicly('pemeriksaan-chemical/images', 'public') : null;
            $detailChemicals[] = [
                'id_chemical' => $idChemical,
                'kondisi_chemical' => $request->input('kondisi_chemical.' . $index),
                'id_produsen' => $request->input('id_produsen.' . $index),
                'negara_produsen' => $request->input('negara_produsen.' . $index),
                'id_distributor' => $request->input('id_distributor.' . $index),
                'kode_produksi' => $request->input('kode_produksi.' . $index),
                'expire_date' => $request->input('expire_date.' . $index),
                'jumlah_datang' => $request->input('jumlah_datang.' . $index),
                'jumlah_sampling' => $request->input('jumlah_sampling.' . $index),
                'image_chemical' => $imagePath,
                'kondisi_fisik' => [
                    'kemasan' => $request->input('kondisi_fisik_kemasan.' . $index) === '1',
                    'warna' => $request->input('kondisi_fisik_warna.' . $index) === '1',
                ],
                'persyaratan_dokumen_halal' => $request->input('persyaratan_dokumen_halal.' . $index) === '1',
                'coa' => $request->input('coa.' . $index) === '1',
                'status' => $request->input('status_baris.' . $index),
                'keterangan' => $request->input('keterangan.' . $index),
            ];
        }

        // Create data
        $data = [
            'id_user' => Auth::id(),
            'id_shift' => $request->input('id_shift'),
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => $kondisiMobil,
            'detail_chemicals' => $detailChemicals,
        ];

        PemeriksaanKedatanganChemical::create($data);

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Data pemeriksaan kedatangan chemical berhasil ditambahkan!');
    }

    public function show(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        // Load hanya relasi yang masih digunakan (tidak ada relasi chemical, produsen, distributor lagi)
        $pemeriksaanChemical->load(['user.plant', 'shift']);

        $user = Auth::user();
        $plantId = $user->id_plant;

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

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
                        'produsen_ids' => $p->produsens->pluck('id')->values()->toArray(),
                        'produsen_names' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor_ids' => $p->distributors->pluck('id')->values()->toArray(),
                        'distributor_names' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        $produkByName = $produkList
            ->mapWithKeys(function ($p) {
                $key = strtolower(trim((string) $p->nama_produk));
                return [$key => ['id' => $p->id, 'kategori_code' => $p->kategori_code]];
            });

        $chemicals = Chemical::query()->select(['id', 'nama_chemical'])->get();
        $produkByChemicalId = $chemicals
            ->mapWithKeys(function ($c) use ($produkByName) {
                $key = strtolower(trim((string) $c->nama_chemical));
                $produk = $produkByName[$key] ?? null;
                return [
                    $c->id => [
                        'id_produk' => $produk['id'] ?? null,
                        'kategori_code' => $produk['kategori_code'] ?? null,
                    ],
                ];
            });
        
        return view('qc-sistem.pemeriksaan-kedatangan-chemical.show', compact('pemeriksaanChemical', 'produkMeta', 'produkByChemicalId'));
    }

    public function edit(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $chemicals = Chemical::with(['user.plant'])->get();
            $produsens = Produsen::with(['user.plant'])->get();
            $distributors = Distributor::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $chemicals = Chemical::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }
        
        $countries = Countries::getList('en', 'php');

        $plantId = $user->id_plant;

        $produkKategoriOptions = Produk::query()
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        if ($produkKategoriOptions->isEmpty()) {
            $produkKategoriOptions = collect(['CHEMICAL']);
        }

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
                        'produsen_ids' => $p->produsens->pluck('id')->values()->toArray(),
                        'produsen_names' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor_ids' => $p->distributors->pluck('id')->values()->toArray(),
                        'distributor_names' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        $chemicalByName = $chemicals
            ->mapWithKeys(function ($c) {
                $key = strtolower(trim((string) $c->nama_chemical));
                return [$key => $c->id];
            });

        $produkByName = $produkList
            ->mapWithKeys(function ($p) {
                $key = strtolower(trim((string) $p->nama_produk));
                return [$key => ['id' => $p->id, 'kategori_code' => $p->kategori_code]];
            });

        $produkByChemicalId = $chemicals
            ->mapWithKeys(function ($c) use ($produkByName) {
                $key = strtolower(trim((string) $c->nama_chemical));
                $produk = $produkByName[$key] ?? null;
                return [
                    $c->id => [
                        'id_produk' => $produk['id'] ?? null,
                        'kategori_code' => $produk['kategori_code'] ?? null,
                    ],
                ];
            });

        $normalizeName = function ($value) {
            $v = strtolower(trim((string) $value));
            $v = preg_replace('/[^a-z0-9]+/i', '', $v);
            return $v;
        };

        $chemicalByNorm = $chemicals
            ->mapWithKeys(function ($c) use ($normalizeName) {
                return [$normalizeName($c->nama_chemical) => $c->id];
            });

        $chemicalByProdukId = $produkList
            ->mapWithKeys(function ($p) use ($chemicalByName, $chemicalByNorm, $normalizeName) {
                $rawKey = strtolower(trim((string) $p->nama_produk));
                $normKey = $normalizeName($p->nama_produk);

                $exact = $chemicalByName[$rawKey] ?? null;
                if ($exact) {
                    return [$p->id => $exact];
                }

                $normExact = $chemicalByNorm[$normKey] ?? null;
                if ($normExact) {
                    return [$p->id => $normExact];
                }

                foreach ($chemicalByNorm as $chemNorm => $chemId) {
                    if ($chemNorm && $normKey && (str_contains($chemNorm, $normKey) || str_contains($normKey, $chemNorm))) {
                        return [$p->id => $chemId];
                    }
                }

                return [$p->id => null];
            });

        return view('qc-sistem.pemeriksaan-kedatangan-chemical.edit', compact('pemeriksaanChemical', 'shifts', 'chemicals', 'produsens', 'distributors', 'countries', 'produkKategoriOptions', 'produkByKategori', 'produkMeta', 'chemicalByName', 'produkByChemicalId', 'chemicalByProdukId'));
    }

    public function createRow(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);

        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $chemicals = Chemical::with(['user.plant'])->get();
            $produsens = Produsen::with(['user.plant'])->get();
            $distributors = Distributor::with(['user.plant'])->get();
        } else {
            $chemicals = Chemical::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        $countries = Countries::getList('en', 'php');

        $plantId = $user->id_plant;

        $produkKategoriOptions = Produk::query()
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        if ($produkKategoriOptions->isEmpty()) {
            $produkKategoriOptions = collect(['CHEMICAL']);
        }

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
                        'produsen_ids' => $p->produsens->pluck('id')->values()->toArray(),
                        'produsen_names' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor_ids' => $p->distributors->pluck('id')->values()->toArray(),
                        'distributor_names' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        $chemicalByName = $chemicals
            ->mapWithKeys(function ($c) {
                $key = strtolower(trim((string) $c->nama_chemical));
                return [$key => $c->id];
            });

        $normalizeName = function ($value) {
            $v = strtolower(trim((string) $value));
            $v = preg_replace('/[^a-z0-9]+/i', '', $v);
            return $v;
        };

        $chemicalByNorm = $chemicals
            ->mapWithKeys(function ($c) use ($normalizeName) {
                return [$normalizeName($c->nama_chemical) => $c->id];
            });

        $chemicalByProdukId = $produkList
            ->mapWithKeys(function ($p) use ($chemicalByName, $chemicalByNorm, $normalizeName) {
                $rawKey = strtolower(trim((string) $p->nama_produk));
                $normKey = $normalizeName($p->nama_produk);

                $exact = $chemicalByName[$rawKey] ?? null;
                if ($exact) {
                    return [$p->id => $exact];
                }

                $normExact = $chemicalByNorm[$normKey] ?? null;
                if ($normExact) {
                    return [$p->id => $normExact];
                }

                foreach ($chemicalByNorm as $chemNorm => $chemId) {
                    if ($chemNorm && $normKey && (str_contains($chemNorm, $normKey) || str_contains($normKey, $chemNorm))) {
                        return [$p->id => $chemId];
                    }
                }

                return [$p->id => null];
            });

        return view('qc-sistem.pemeriksaan-kedatangan-chemical.tambah-baris', compact(
            'pemeriksaanChemical',
            'chemicals',
            'produsens',
            'distributors',
            'countries',
            'produkKategoriOptions',
            'produkByKategori',
            'produkMeta',
            'chemicalByName',
            'chemicalByProdukId'
        ));
    }

    public function storeRow(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);

        $inputProdukId = $request->input('id_produk');
        $inputChemicalId = $request->input('id_chemical');

        if (!empty($inputProdukId)) {
            $produk = Produk::query()->select(['id', 'nama_produk'])->find($inputProdukId);
            $user = Auth::user();

            if ($produk) {
                if ($user->role && strtolower($user->role->role) === 'superadmin') {
                    $chemicalRows = Chemical::query()->select(['id', 'nama_chemical'])->get();
                } else {
                    $chemicalRows = Chemical::query()
                        ->select(['id', 'nama_chemical'])
                        ->whereHas('user', function ($q) use ($user) {
                            $q->where('id_plant', $user->id_plant);
                        })
                        ->get();
                }

                $chemicalIdByName = $chemicalRows
                    ->mapWithKeys(function ($c) {
                        return [strtolower(trim((string) $c->nama_chemical)) => $c->id];
                    });

                $normalizeName = function ($value) {
                    $v = strtolower(trim((string) $value));
                    $v = preg_replace('/[^a-z0-9]+/i', '', $v);
                    return $v;
                };

                $chemicalIdByNorm = $chemicalRows
                    ->mapWithKeys(function ($c) use ($normalizeName) {
                        return [$normalizeName($c->nama_chemical) => $c->id];
                    });

                $produkKey = strtolower(trim((string) $produk->nama_produk));
                $mapped = $chemicalIdByName[$produkKey] ?? null;
                if (!$mapped) {
                    $normKey = $normalizeName($produkKey);
                    $mapped = $chemicalIdByNorm[$normKey] ?? null;
                }
                if (!$mapped) {
                    $normKey = $normalizeName($produkKey);
                    foreach ($chemicalIdByNorm as $chemNorm => $chemId) {
                        if ($chemNorm && $normKey && (str_contains($chemNorm, $normKey) || str_contains($normKey, $chemNorm))) {
                            $mapped = $chemId;
                            break;
                        }
                    }
                }

                if (!$mapped) {
                    $createPayload = [
                        'id_user' => $user->id,
                        'nama_chemical' => (string) $produk->nama_produk,
                    ];

                    $incomingProdusen = $request->input('id_produsen');
                    if (!empty($incomingProdusen)) {
                        $createPayload['id_produsen'] = $incomingProdusen;
                    }

                    $incomingDistributor = $request->input('id_distributor');
                    if (!empty($incomingDistributor)) {
                        $createPayload['id_distributor'] = $incomingDistributor;
                    }

                    $createdChemical = Chemical::create($createPayload);
                    $mapped = $createdChemical->id;
                }

                if (!empty($mapped)) {
                    $request->merge(['id_chemical' => $mapped]);
                } elseif (!empty($inputChemicalId)) {
                    $request->merge(['id_chemical' => $inputChemicalId]);
                }
            }
        }

        $request->validate([
            'status_baris' => 'required|in:Release,Hold',
            'kategori_code' => 'required|string',
            'id_produk' => 'required|exists:produks,id',
            'id_chemical' => 'required|exists:chemicals,id',
            'kondisi_chemical' => 'nullable|string|max:255',
            'id_produsen' => 'nullable|exists:produsens,id',
            'negara_produsen' => 'nullable|string|max:255',
            'id_distributor' => 'nullable|exists:distributors,id',
            'kode_produksi' => 'nullable|string|max:255',
            'expire_date' => 'nullable|date',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'kondisi_fisik_kemasan' => 'nullable|in:0,1',
            'kondisi_fisik_warna' => 'nullable|in:0,1',
            'persyaratan_dokumen_halal' => 'nullable|in:0,1',
            'coa' => 'nullable|in:0,1',
            'keterangan' => 'nullable|string|max:500',
            'image_chemical' => 'nullable|image|max:1024',
        ]);

        $detailChemicals = $pemeriksaanChemical->detail_chemicals ?? [];
        if (!is_array($detailChemicals)) {
            $detailChemicals = [];
        }

        $newRow = [
            'id_chemical' => $request->input('id_chemical'),
            'kondisi_chemical' => $request->input('kondisi_chemical'),
            'id_produsen' => $request->input('id_produsen'),
            'negara_produsen' => $request->input('negara_produsen'),
            'id_distributor' => $request->input('id_distributor'),
            'kode_produksi' => $request->input('kode_produksi'),
            'expire_date' => $request->input('expire_date'),
            'jumlah_datang' => $request->input('jumlah_datang'),
            'jumlah_sampling' => $request->input('jumlah_sampling'),
            'image_chemical' => ($request->file('image_chemical'))
                ? $request->file('image_chemical')->storePublicly('pemeriksaan-chemical/images', 'public')
                : null,
            'kondisi_fisik' => [
                'kemasan' => $request->input('kondisi_fisik_kemasan') === '1',
                'warna' => $request->input('kondisi_fisik_warna') === '1',
            ],
            'persyaratan_dokumen_halal' => $request->input('persyaratan_dokumen_halal') === '1',
            'coa' => $request->input('coa') === '1',
            'status' => $request->input('status_baris'),
            'keterangan' => $request->input('keterangan'),
        ];

        $detailChemicals[] = $newRow;

        $pemeriksaanChemical->update([
            'detail_chemicals' => $detailChemicals,
        ]);

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Baris chemical berhasil ditambahkan!');
    }

    public function update(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);

        $inputProdukIds = $request->input('id_produk', []);
        $inputChemicalIds = $request->input('id_chemical', []);

        if (is_array($inputProdukIds) && is_array($inputChemicalIds)) {
            $produkRows = Produk::query()
                ->select(['id', 'nama_produk'])
                ->whereIn('id', array_filter($inputProdukIds))
                ->get();

            $produkNameById = $produkRows
                ->mapWithKeys(function ($p) {
                    return [$p->id => strtolower(trim((string) $p->nama_produk))];
                });

            $produkRawNameById = $produkRows
                ->mapWithKeys(function ($p) {
                    return [$p->id => (string) $p->nama_produk];
                });

            $user = Auth::user();

            if ($user->role && strtolower($user->role->role) === 'superadmin') {
                $chemicalRows = Chemical::query()
                    ->select(['id', 'nama_chemical'])
                    ->get();
            } else {
                $chemicalRows = Chemical::query()
                    ->select(['id', 'nama_chemical'])
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('id_plant', $user->id_plant);
                    })
                    ->get();
            }

            $chemicalIdByName = $chemicalRows
                ->mapWithKeys(function ($c) {
                    return [strtolower(trim((string) $c->nama_chemical)) => $c->id];
                });

            $normalizeName = function ($value) {
                $v = strtolower(trim((string) $value));
                $v = preg_replace('/[^a-z0-9]+/i', '', $v);
                return $v;
            };

            $chemicalIdByNorm = $chemicalRows
                ->mapWithKeys(function ($c) use ($normalizeName) {
                    return [$normalizeName($c->nama_chemical) => $c->id];
                });

            $mappedChemicalIds = [];
            $missingMapErrors = [];
            foreach ($inputProdukIds as $idx => $produkId) {
                $produkKey = $produkNameById[(int) $produkId] ?? null;
                $produkRawName = $produkRawNameById[(int) $produkId] ?? null;
                $currentChemicalId = $inputChemicalIds[$idx] ?? null;

                // Always prefer mapping based on the selected product.
                $mapped = $produkKey ? ($chemicalIdByName[$produkKey] ?? null) : null;
                if (!$mapped && $produkKey) {
                    $normProdukKey = $normalizeName($produkKey);
                    $mapped = $chemicalIdByNorm[$normProdukKey] ?? null;
                }

                if (!$mapped && $produkKey) {
                    $normProdukKey = $normalizeName($produkKey);
                    foreach ($chemicalIdByNorm as $chemNorm => $chemId) {
                        if ($chemNorm && $normProdukKey && (str_contains($chemNorm, $normProdukKey) || str_contains($normProdukKey, $chemNorm))) {
                            $mapped = $chemId;
                            break;
                        }
                    }
                }

                // If there is still no mapping, auto-create chemical master using product name.
                if (!$mapped && $produkRawName) {
                    $createPayload = [
                        'id_user' => $user->id,
                        'nama_chemical' => $produkRawName,
                    ];

                    $incomingProdusen = $request->input('id_produsen.' . $idx);
                    if (!empty($incomingProdusen)) {
                        $createPayload['id_produsen'] = $incomingProdusen;
                    }

                    $incomingDistributor = $request->input('id_distributor.' . $idx);
                    if (!empty($incomingDistributor)) {
                        $createPayload['id_distributor'] = $incomingDistributor;
                    }

                    $createdChemical = Chemical::create($createPayload);
                    $mapped = $createdChemical->id;
                }

                if (!$mapped && !empty($currentChemicalId)) {
                    // As a last resort, keep the submitted chemical id.
                    $mapped = $currentChemicalId;
                }

                if (!$mapped) {
                    $missingMapErrors['id_chemical.' . $idx] = 'Produk "' . ($produkRawName ?? $produkKey ?? '-') . '" belum terhubung ke master Chemical dan tidak bisa dibuat otomatis. Silakan cek master Chemical.';
                }

                $mappedChemicalIds[$idx] = $mapped;
            }

            $request->merge([
                'id_chemical' => $mappedChemicalIds,
            ]);

            if (!empty($missingMapErrors)) {
                throw ValidationException::withMessages($missingMapErrors);
            }
        }
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_produk' => 'required|array',
            'id_produk.*' => 'required|exists:produks,id',
            // Validasi array untuk dynamic rows
            'id_chemical' => 'required|array',
            'id_chemical.*' => 'required|exists:chemicals,id',
            'kondisi_chemical' => 'nullable|array',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'negara_produsen' => 'nullable|array',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
            'kode_produksi' => 'nullable|array',
            'expire_date' => 'nullable|array',
            'jumlah_datang' => 'nullable|array',
            'jumlah_sampling' => 'nullable|array',
            'kondisi_fisik_kemasan' => 'nullable|array',
            'kondisi_fisik_warna' => 'nullable|array',
            'persyaratan_dokumen_halal' => 'nullable|array',
            'coa' => 'nullable|array',
            'status_baris' => 'required|array',
            'status_baris.*' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|array',
            'image_chemical' => 'nullable|array',
            'image_chemical.*' => 'nullable|image|max:1024',
        ]);

        // Process kondisi mobil (11 items)
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

        // Process detail chemicals dari dynamic rows
        $detailChemicals = [];
        $idChemicals = $request->input('id_chemical', []);
        $uploadedImages = (array) $request->file('image_chemical', []);
        $existingDetails = $pemeriksaanChemical->detail_chemicals;
        if (!is_array($existingDetails)) {
            $existingDetails = [];
        }
        
        foreach ($idChemicals as $index => $idChemical) {
            $uploadedImage = $uploadedImages[$index] ?? null;
            $existingImage = $existingDetails[$index]['image_chemical'] ?? null;
            $imagePath = $uploadedImage
                ? $uploadedImage->storePublicly('pemeriksaan-chemical/images', 'public')
                : $existingImage;
            $detailChemicals[] = [
                'id_chemical' => $idChemical,
                'kondisi_chemical' => $request->input('kondisi_chemical.' . $index),
                'id_produsen' => $request->input('id_produsen.' . $index),
                'negara_produsen' => $request->input('negara_produsen.' . $index),
                'id_distributor' => $request->input('id_distributor.' . $index),
                'kode_produksi' => $request->input('kode_produksi.' . $index),
                'expire_date' => $request->input('expire_date.' . $index),
                'jumlah_datang' => $request->input('jumlah_datang.' . $index),
                'jumlah_sampling' => $request->input('jumlah_sampling.' . $index),
                'image_chemical' => $imagePath,
                'kondisi_fisik' => [
                    'kemasan' => $request->input('kondisi_fisik_kemasan.' . $index) === '1',
                    'warna' => $request->input('kondisi_fisik_warna.' . $index) === '1',
                ],
                'persyaratan_dokumen_halal' => $request->input('persyaratan_dokumen_halal.' . $index) === '1',
                'coa' => $request->input('coa.' . $index) === '1',
                'status' => $request->input('status_baris.' . $index),
                'keterangan' => $request->input('keterangan.' . $index),
            ];
        }

        // Update data
        $data = [
            'id_shift' => $request->input('id_shift'),
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => $kondisiMobil,
            'detail_chemicals' => $detailChemicals,
        ];

        $pemeriksaanChemical->update($data);

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Data pemeriksaan kedatangan chemical berhasil diupdate!');
    }

    public function destroy(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        $pemeriksaanChemical->delete();

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Data pemeriksaan kedatangan chemical berhasil dihapus!');
    }

    public function sendToProduksi(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now()
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
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

        $query = PemeriksaanKedatanganChemical::with([
            'user.role', 
            'user.plant',    
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

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        
        // Filter by produk / kategori
        if ($id_produk) {
            $query->where(function ($q) use ($id_produk) {
                $q->whereRaw("JSON_CONTAINS(CAST(id_bahan_array AS JSON), ?, '$')", [json_encode((int)$id_produk)])
                  ->orWhereRaw("JSON_CONTAINS(CAST(id_bahan_array AS JSON), ?, '$')", [json_encode((string)$id_produk)])
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
                        $q->orWhereRaw("JSON_CONTAINS(CAST(id_bahan_array AS JSON), ?, '$')", [json_encode((int)$pid)])
                          ->orWhereRaw("JSON_CONTAINS(CAST(id_bahan_array AS JSON), ?, '$')", [json_encode((string)$pid)])
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
                // Shift 2/3 dan lainnya: single date
                if ($tanggal) {
                    $query->whereDate('tanggal', $tanggal);
                }
            }
        } else {
            // Jika shift tidak dipilih, fallback single date jika ada
            if ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            }
        }

        $pemeriksaans = $query->latest()->get();

        $shift = $id_shift ? Shift::find($id_shift) : null;
        
        $qcUser = null;
        $produksiUser = null;
        $spvQcUser = null;
        
        $allQcIds = $pemeriksaans->pluck('verified_by_qc')->filter()->unique();
        $allProduksiIds = $pemeriksaans->pluck('verified_by_produksi')->filter()->unique();
        $allSpvIds = $pemeriksaans->pluck('verified_by_spv')->filter()->unique();
        
        if($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if($qcUserData) {
                $qcUser = $qcUserData->name;
            }
        }
        
        if($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if($produksiUserData) {
                $produksiUser = $produksiUserData->name;
            }
        }
        
        if($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if($spvUserData) {
                $spvQcUser = $spvUserData->name;
            }
        }

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-kedatangan-chemical.pdf-report', [
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
        $filename = 'laporan-pemeriksaan-chemical-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }
}