<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganBahanBakuPenunjang;
use App\Models\Shift;
use App\Models\Bahan;
use App\Models\Produk;
use App\Models\Produsen;
use App\Models\Distributor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Monarobase\CountryList\CountryListFacade as Countries; // Menggunakan Facade


class PemeriksaanKedatanganBahanBakuPenunjangController extends Controller
{

    /**
     * Simpan file COA (PDF atau gambar).
     * Jika gambar > 3MB, otomatis dikompres sebelum disimpan.
     */
    private function compressAndStoreCoa($uploadedFile, string $subDir = 'pemeriksaan-bahan-baku-penunjang/coa'): ?string
    {
        if (!$uploadedFile) return null;

        $sizeBytes = $uploadedFile->getSize();
        $mimeType  = $uploadedFile->getMimeType();
        $threshold = 3 * 1024 * 1024; // 3 MB

        $isImage = in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']);

        if ($isImage && $sizeBytes > $threshold) {
            $tmpPath = $uploadedFile->getRealPath();

            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $img = @imagecreatefromjpeg($tmpPath);
                    break;
                case 'image/png':
                    $img = @imagecreatefrompng($tmpPath);
                    break;
                case 'image/webp':
                    $img = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmpPath) : null;
                    break;
                default:
                    $img = null;
            }

            if ($img) {
                $width  = imagesx($img);
                $height = imagesy($img);

                // Scale down jika dimensi terlalu besar (max 1920px)
                $maxDim = 1920;
                if ($width > $maxDim || $height > $maxDim) {
                    $ratio = min($maxDim / $width, $maxDim / $height);
                    $newW  = (int) round($width  * $ratio);
                    $newH  = (int) round($height * $ratio);
                    $resized = imagecreatetruecolor($newW, $newH);
                    if ($mimeType === 'image/png') {
                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);
                    }
                    imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $width, $height);
                    imagedestroy($img);
                    $img = $resized;
                }

                // Compress ke JPEG quality 60
                $tmpCompressed = tempnam(sys_get_temp_dir(), 'coa_');
                imagejpeg($img, $tmpCompressed, 60);
                imagedestroy($img);

                $compressedSize = filesize($tmpCompressed);
                if ($compressedSize < $sizeBytes) {
                    $newFilename = pathinfo($uploadedFile->hashName(), PATHINFO_FILENAME) . '.jpg';
                    $path = $subDir . '/' . $newFilename;
                    Storage::disk('public')->put($path, file_get_contents($tmpCompressed));
                    @unlink($tmpCompressed);
                    return $path;
                }
                @unlink($tmpCompressed);
            }
        }

        return $uploadedFile->storePublicly($subDir, 'public');
    }

    private function mapProdukToBahanIds(Request $request, $produkIds, $currentBahanIds = [])
    {
        if (!is_array($produkIds)) {
            return [$currentBahanIds, []];
        }

        $produkRows = Produk::query()
            ->select(['id', 'nama_produk'])
            ->whereIn('id', array_filter($produkIds))
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
        $plantId = $user->getEffectivePlantId();

        $bahanRows = Bahan::query()
            ->select(['id', 'nama_bahan'])
            ->get();

        $bahanIdByName = $bahanRows
            ->mapWithKeys(function ($b) {
                return [strtolower(trim((string) $b->nama_bahan)) => $b->id];
            });

        $normalizeName = function ($value) {
            $v = strtolower(trim((string) $value));
            $v = preg_replace('/[^a-z0-9]+/i', '', $v);
            return $v;
        };

        $bahanIdByNorm = $bahanRows
            ->mapWithKeys(function ($b) use ($normalizeName) {
                return [$normalizeName($b->nama_bahan) => $b->id];
            });

        $mappedBahanIds = [];
        $missingMapErrors = [];

        foreach ($produkIds as $idx => $produkId) {
            $produkId = (string) $produkId;
            if ($produkId === '' || $produkId === '0') {
                $mappedBahanIds[$idx] = $currentBahanIds[$idx] ?? null;
                continue;
            }

            $produkKey = $produkNameById[(int) $produkId] ?? null;
            $produkRawName = $produkRawNameById[(int) $produkId] ?? null;
            $currentBahanId = $currentBahanIds[$idx] ?? null;

            // Prefer mapping by selected product name
            $mapped = $produkKey ? ($bahanIdByName[$produkKey] ?? null) : null;
            if (!$mapped && $produkKey) {
                $normKey = $normalizeName($produkKey);
                $mapped = $bahanIdByNorm[$normKey] ?? null;
            }
            if (!$mapped && $produkKey) {
                $normKey = $normalizeName($produkKey);
                foreach ($bahanIdByNorm as $bNorm => $bId) {
                    if ($bNorm && $normKey) {
                        $shorter = min(strlen($bNorm), strlen($normKey));
                        $longer  = max(strlen($bNorm), strlen($normKey));
                        // Hanya fuzzy-match jika string pendek minimal 4 karakter
                        // DAN panjang string pendek >= 50% dari string panjang (mencegah "gul" cocok dengan "regular")
                        if ($shorter >= 4 && $longer > 0 && ($shorter / $longer) >= 0.5
                            && (str_contains($bNorm, $normKey) || str_contains($normKey, $bNorm))
                        ) {
                            $mapped = $bId;
                            break;
                        }
                    }
                }
            }

            // Auto-create bahan if missing
            if (!$mapped && $produkRawName) {
                $createPayload = [
                    'id_user' => $user->id,
                    'nama_bahan' => $produkRawName,
                ];

                $kategoriCode = $request->input('kategori_code.' . $idx);
                if (!empty($kategoriCode)) {
                    $createPayload['kategori_code'] = $kategoriCode;
                }

                $createdBahan = Bahan::create($createPayload);
                $mapped = $createdBahan->id;

                $produsenNames = $request->input('produsen.' . $idx);
                if (is_array($produsenNames) && $plantId) {
                    $produsenIds = Produsen::query()
                        ->whereIn('nama_produsen', array_values(array_filter($produsenNames, fn ($v) => (string) $v !== '')))
                        ->pluck('id')
                        ->values();

                    foreach ($produsenIds as $pid) {
                        try {
                            $createdBahan->produsens()->syncWithoutDetaching([$pid => ['id_plant' => $plantId]]);
                        } catch (\Throwable $e) {
                        }
                    }
                }

                $distributorNames = $request->input('distributor.' . $idx);
                if (is_array($distributorNames) && $plantId) {
                    $distributorIds = Distributor::query()
                        ->whereIn('nama_distributor', array_values(array_filter($distributorNames, fn ($v) => (string) $v !== '')))
                        ->pluck('id')
                        ->values();

                    foreach ($distributorIds as $did) {
                        try {
                            $createdBahan->distributors()->syncWithoutDetaching([$did => ['id_plant' => $plantId]]);
                        } catch (\Throwable $e) {
                        }
                    }
                }
            }

            if (!$mapped && !empty($currentBahanId)) {
                $mapped = $currentBahanId;
            }

            if (!$mapped) {
                $missingMapErrors['id_bahan.' . $idx] = 'Produk "' . ($produkRawName ?? $produkKey ?? '-') . '" belum terhubung ke master Bahan dan tidak bisa dibuat otomatis. Silakan cek master Bahan.';
            }

            $mappedBahanIds[$idx] = $mapped;
        }

        return [$mappedBahanIds, $missingMapErrors];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanKedatanganBahanBakuPenunjang::with(['user.role', 'user.plant', 'bahan', 'shift']);
        
        // SuperAdmin dapat melihat semua data, tapi jika ingin filter sesuai plant aktif (jika ada):
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            // Jika superadmin ingin melihat semua, biarkan. 
            // Tapi biasanya superadmin juga terikat ke id_plant default jika tidak ada active_plant_id.
        } else {
            // Manager dan role lain hanya melihat data sesuai plant efektif mereka (hasil switch)
            $effectivePlantId = $user->getEffectivePlantId();
            $query->whereHas('user', function ($q) use ($effectivePlantId) {
                $q->where('id_plant', $effectivePlantId);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                // Konversi format d/m/Y ke Y-m-d untuk pencarian tanggal
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

                $q->orWhere('no_po', 'like', '%' . $search . '%')
                    ->orWhere('kode_produksi_array', 'like', '%' . $search . '%')
                    ->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhere('verification_notes', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('bahan', function ($qb) use ($search) {
                        $qb->where('nama_bahan', 'like', '%' . $search . '%');
                    });

                // Cari nama produk yang cocok → cari di id_bahan_array
                $matchingProdukIds = Produk::where('nama_produk', 'like', '%' . $search . '%')
                    ->pluck('id')->toArray();
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

        // Ambil juga nama bahan dari tabel bahans untuk lookup id_bahan_array
        $bahanNamaById = \App\Models\Bahan::select(['id', 'nama_bahan'])->get()->pluck('nama_bahan', 'id')->all();

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.index', compact('pemeriksaans', 'produkKategoriOptions', 'produkList', 'produkByKategori', 'produkNamaById', 'bahanNamaById'));
    }

    public function create()
    {
        $user = Auth::user();
        $plantId = $user->getEffectivePlantId();
        $produkKategoriOptions = collect();
        $produkByKategori = collect();
        $produkMeta = collect();
        $produkKategoriById = collect();
        
        // Filter data berdasarkan plant aktif (hasil switch)
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($plantId) {
                $query->where('id_plant', $plantId);
            })->with(['user.plant'])->get();
            
            $countries = Countries::getList('en', 'php');
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

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.create', compact(
            'shifts',
            'countries',
            'produkKategoriOptions',
            'produkByKategori',
            'produkMeta',
            'produkKategoriById'
        ));
    }

    private function checkPlantAccess($pemeriksaan)
    {
        $user = Auth::user();
        
        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            $effectivePlantId = $user->getEffectivePlantId();
            if ($pemeriksaan->user->id_plant !== $effectivePlantId) {
                abort(403, 'Unauthorized access to different plant data.');
            }
        }
    }

    public function store(Request $request)
    {
        // Log semua input untuk debugging
        \Log::info('Form Submit Data:', $request->all());

        $inputProdukIds = $request->input('id_produk', $request->input('id_bahan', []));
        $inputBahanIds = $request->input('id_bahan', []);
        [$mappedBahanIds, $missingMapErrors] = $this->mapProdukToBahanIds($request, $inputProdukIds, $inputBahanIds);
        if (!empty($missingMapErrors)) {
            throw \Illuminate\Validation\ValidationException::withMessages($missingMapErrors);
        }
        if (is_array($mappedBahanIds) && !empty($mappedBahanIds)) {
            $request->merge([
                'id_bahan' => $mappedBahanIds,
            ]);
        }
        
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
            'id_produk' => 'nullable|array',
            'id_produk.*' => 'nullable|exists:produks,id',
            'id_bahan' => 'nullable|array',
            'id_bahan.*' => 'nullable|exists:bahans,id',
            'produsen' => 'nullable|array',
            'produsen.*' => 'nullable|array',
            'produsen.*.*' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|array',
            'negara_produsen.*' => 'nullable|string|max:255',
            'distributor' => 'nullable|array',
            'distributor.*' => 'nullable|array',
            'distributor.*.*' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|array',
            'kode_produksi.*' => 'nullable|string|max:255',
            'expire_date' => 'nullable|array',
            'expire_date.*' => 'nullable|date',
            'jumlah_datang' => 'nullable|array',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'unit_datang' => 'nullable|array',
            'unit_datang.*' => 'nullable|string|in:kg,gram,pcs,roll,karung,box,lembar',
            'jumlah_sampling' => 'nullable|array',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'unit_sampling' => 'nullable|array',
            'unit_sampling.*' => 'nullable|string|in:kg,gram,pcs,roll,karung,box,lembar',
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
            'file_coa.*' => 'nullable|mimes:pdf,jpg,jpeg,png,webp,gif|max:5120',
            'file_coa_img.*' => 'nullable|image|max:5120',
            'image_bahan_baku' => 'nullable|array',
            'image_bahan_baku.*' => 'nullable|image|max:5120',
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

        // Normalize nested produsen/distributor arrays (produsen[row][]) into string per row for storage
        $normalizeNestedMulti = function ($rows) {
            if (!is_array($rows)) return [];
            $out = [];
            foreach ($rows as $row) {
                if (is_array($row)) {
                    $vals = array_values(array_filter(array_map('strval', $row), fn ($v) => $v !== ''));
                    $out[] = implode(', ', $vals);
                } else {
                    $out[] = (string) $row;
                }
            }
            return $out;
        };

        if ($request->has('produsen')) {
            $data['produsen'] = $normalizeNestedMulti($request->input('produsen', []));
        }
        if ($request->has('distributor')) {
            $data['distributor'] = $normalizeNestedMulti($request->input('distributor', []));
        }
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
            'unit_datang' => 'unit_datang_array',
            'jumlah_sampling' => 'jumlah_sampling_array',
            'unit_sampling' => 'unit_sampling_array',
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
        $uploadedCoas    = (array) $request->file('file_coa', []);
        $uploadedCoaImgs = (array) $request->file('file_coa_img', []);
        $rowCountFileCoa = max(count($request->input('coa', [])), count($uploadedCoas), count($uploadedCoaImgs));
        for ($i = 0; $i < $rowCountFileCoa; $i++) {
            $uploadedFile = $uploadedCoaImgs[$i] ?? $uploadedCoas[$i] ?? null;
            $fileCoaPaths[$i] = $this->compressAndStoreCoa($uploadedFile);
        }
        $data['file_coa_array'] = json_encode(array_values($fileCoaPaths));

        $imagePaths = [];
        $uploadedImages = (array) $request->file('image_bahan_baku', []);
        $rowCountImages = max(count($request->input('id_bahan', [])), count($uploadedImages));
        for ($i = 0; $i < $rowCountImages; $i++) {
            $uploadedFile = $uploadedImages[$i] ?? null;
            if ($uploadedFile) {
                $imagePaths[$i] = $uploadedFile->storePublicly('pemeriksaan-bahan-baku-penunjang/images', 'public');
            } else {
                $imagePaths[$i] = null;
            }
        }
        $data['image_bahan_baku_array'] = json_encode(array_values($imagePaths));
        
        // Hapus field-field kondisi_fisik yang dikirim dari form agar tidak conflict
        unset($data['kondisi_fisik_kemasan']);
        unset($data['kondisi_fisik_warna']);
        unset($data['kondisi_fisik_benda_asing']);
        unset($data['kondisi_fisik_aroma']);
        unset($data['image_bahan_baku']);
        unset($data['file_coa']);
        unset($data['file_coa_img']);

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
        $plantId = $user->getEffectivePlantId();
        $produkKategoriOptions = collect();
        $produkByKategori = collect();
        $produkMeta = collect();
        $produkKategoriById = collect();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($plantId) {
                $query->where('id_plant', $plantId);
            })->with(['user.plant'])->get();
            
            $countries = Countries::getList('en', 'php');
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

        $normalizeName = function ($value) {
            $v = strtolower(trim((string) $value));
            $v = preg_replace('/[^a-z0-9]+/i', '', $v);
            return $v;
        };

        $produkByNormName = $produkList
            ->mapWithKeys(function ($p) use ($normalizeName) {
                return [$normalizeName($p->nama_produk) => $p->id];
            });

        $produkByBahanId = [];
        $bahanIds = json_decode($pemeriksaanBahanBaku->id_bahan_array ?? '[]', true);
        $bahanIds = is_array($bahanIds) ? array_values(array_filter($bahanIds)) : [];
        if (!empty($bahanIds)) {
            $bahans = Bahan::query()->select(['id', 'nama_bahan'])->whereIn('id', $bahanIds)->get();
            foreach ($bahans as $b) {
                $norm = $normalizeName($b->nama_bahan);
                $produkByBahanId[$b->id] = $produkByNormName[$norm] ?? null;
            }
        }
    
        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.edit', compact(
            'pemeriksaanBahanBaku',
            'shifts',
            'countries',
            'produkKategoriOptions',
            'produkByKategori',
            'produkMeta',
            'produkKategoriById',
            'produkByBahanId'
        ));
    }

    public function update(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);

        $inputProdukIds = $request->input('id_produk', $request->input('id_bahan', []));
        $inputBahanIds = $request->input('id_bahan', []);
        [$mappedBahanIds, $missingMapErrors] = $this->mapProdukToBahanIds($request, $inputProdukIds, $inputBahanIds);
        if (!empty($missingMapErrors)) {
            throw \Illuminate\Validation\ValidationException::withMessages($missingMapErrors);
        }
        if (is_array($mappedBahanIds) && !empty($mappedBahanIds)) {
            $request->merge([
                'id_bahan' => $mappedBahanIds,
            ]);
        }
        
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
            'id_produk' => 'nullable|array',
            'id_produk.*' => 'nullable|exists:produks,id',
            'id_bahan' => 'nullable|array',
            'id_bahan.*' => 'nullable|exists:bahans,id',
            'produsen' => 'nullable|array',
            'produsen.*' => 'nullable|array',
            'produsen.*.*' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|array',
            'negara_produsen.*' => 'nullable|string|max:255',
            'distributor' => 'nullable|array',
            'distributor.*' => 'nullable|array',
            'distributor.*.*' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|array',
            'kode_produksi.*' => 'nullable|string|max:255',
            'expire_date' => 'nullable|array',
            'expire_date.*' => 'nullable|date',
            'jumlah_datang' => 'nullable|array',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'unit_datang' => 'nullable|array',
            'unit_datang.*' => 'nullable|string|in:kg,gram,pcs,roll,karung,box,lembar',
            'jumlah_sampling' => 'nullable|array',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'unit_sampling' => 'nullable|array',
            'unit_sampling.*' => 'nullable|string|in:kg,gram,pcs,roll,karung,box,lembar',
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
            'file_coa.*' => 'nullable|mimes:pdf,jpg,jpeg,png,webp,gif|max:5120',
            'file_coa_img.*' => 'nullable|image|max:5120',
            'image_bahan_baku' => 'nullable|array',
            'image_bahan_baku.*' => 'nullable|image|max:5120',
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

        // Normalize nested produsen/distributor arrays (produsen[row][]) into string per row for storage
        $normalizeNestedMulti = function ($rows) {
            if (!is_array($rows)) return [];
            $out = [];
            foreach ($rows as $row) {
                if (is_array($row)) {
                    $vals = array_values(array_filter(array_map('strval', $row), fn ($v) => $v !== ''));
                    $out[] = implode(', ', $vals);
                } else {
                    $out[] = (string) $row;
                }
            }
            return $out;
        };

        if ($request->has('produsen')) {
            $data['produsen'] = $normalizeNestedMulti($request->input('produsen', []));
        }
        if ($request->has('distributor')) {
            $data['distributor'] = $normalizeNestedMulti($request->input('distributor', []));
        }

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
            'unit_datang' => 'unit_datang_array',
            'jumlah_sampling' => 'jumlah_sampling_array',
            'unit_sampling' => 'unit_sampling_array',
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
        $uploadedCoas    = (array) $request->file('file_coa', []);
        $uploadedCoaImgs = (array) $request->file('file_coa_img', []);
        $rowCountFileCoa = max(
            count($request->input('id_bahan', [])),
            count($request->input('produsen', [])),
            count($request->input('distributor', [])),
            count($request->input('coa', [])),
            count($uploadedCoas),
            count($uploadedCoaImgs)
        );
        for ($i = 0; $i < $rowCountFileCoa; $i++) {
            $uploadedFile = $uploadedCoaImgs[$i] ?? $uploadedCoas[$i] ?? null;
            if ($uploadedFile) {
                $newFileCoa[$i] = $this->compressAndStoreCoa($uploadedFile);
            } else {
                $newFileCoa[$i] = $existingFileCoa[$i] ?? null;
            }
        }
        $data['file_coa_array'] = json_encode(array_values($newFileCoa));

        $existingImages = json_decode($pemeriksaanBahanBaku->image_bahan_baku_array ?? '[]', true);
        if (!is_array($existingImages)) {
            $existingImages = [];
        }

        $newImages = [];
        $uploadedImages = (array) $request->file('image_bahan_baku', []);
        $rowCountImages = max(
            count($request->input('id_bahan', [])),
            count($uploadedImages)
        );
        for ($i = 0; $i < $rowCountImages; $i++) {
            $uploadedFile = $uploadedImages[$i] ?? null;
            if ($uploadedFile) {
                $newImages[$i] = $uploadedFile->storePublicly('pemeriksaan-bahan-baku-penunjang/images', 'public');
            } else {
                $newImages[$i] = $existingImages[$i] ?? null;
            }
        }
        $data['image_bahan_baku_array'] = json_encode(array_values($newImages));
        
        // Hapus field-field kondisi_fisik yang dikirim dari form agar tidak conflict
        unset($data['kondisi_fisik_kemasan']);
        unset($data['kondisi_fisik_warna']);
        unset($data['kondisi_fisik_benda_asing']);
        unset($data['kondisi_fisik_aroma']);
        unset($data['image_bahan_baku']);
        unset($data['file_coa']);
        unset($data['file_coa_img']);

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
        $plantId = $user->getEffectivePlantId();
        $produkKategoriOptions = collect();
        $produkByKategori = collect();
        $produkMeta = collect();
        $produkKategoriById = collect();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $countries = Countries::getList('en', 'php');
        } else {
            $countries = Countries::getList('en', 'php');
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

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.tambah-baris', compact(
            'pemeriksaanBahanBaku',
            'countries',
            'produkKategoriOptions',
            'produkByKategori',
            'produkMeta',
            'produkKategoriById'
        ));
    }

    public function storeRow(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);

        $inputProdukId = $request->input('id_produk');
        if (empty($inputProdukId)) {
            $inputProdukId = $request->input('id_bahan');
        }

        if (!empty($inputProdukId)) {
            $tmp = new Request($request->all());
            $tmp->merge([
                'kategori_code' => [$request->input('kategori_code')],
                'produsen' => [$request->input('produsen')],
                'distributor' => [$request->input('distributor')],
            ]);
            [$mappedBahanIds, $missingMapErrors] = $this->mapProdukToBahanIds($tmp, [$inputProdukId], [$request->input('id_bahan')]);
            if (!empty($missingMapErrors)) {
                throw \Illuminate\Validation\ValidationException::withMessages($missingMapErrors);
            }
            $request->merge([
                'id_bahan' => $mappedBahanIds[0] ?? null,
            ]);
        }

        $request->validate([
            'status_baris' => 'required|in:Release,Hold',
            'kategori_code' => 'nullable|string',
            'id_produk' => 'nullable|exists:produks,id',
            'id_bahan' => 'nullable|exists:bahans,id',
            'produsen' => 'nullable|array',
            'produsen.*' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|string|max:255',
            'distributor' => 'nullable|array',
            'distributor.*' => 'nullable|string|max:255',
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
            'file_coa' => 'nullable|mimes:pdf,jpg,jpeg,png,webp,gif|max:5120',
            'file_coa_img' => 'nullable|image|max:5120',
            'image_bahan_baku' => 'nullable|image|max:5120',
        ]);

        $appendJsonArray = function (?string $raw, $value): array {
            $arr = json_decode($raw ?? '[]', true);
            if (!is_array($arr)) {
                $arr = [];
            }
            $arr[] = $value;
            return $arr;
        };

        $normalizeSingleMulti = function ($vals): ?string {
            if (!is_array($vals)) return $vals !== null ? (string) $vals : null;
            $out = array_values(array_filter(array_map('strval', $vals), fn ($v) => $v !== ''));
            return count($out) ? implode(', ', $out) : null;
        };

        $idBahanArr = $appendJsonArray($pemeriksaanBahanBaku->id_bahan_array, $request->input('id_bahan'));
        $produsenArr = $appendJsonArray($pemeriksaanBahanBaku->produsen_array, $normalizeSingleMulti($request->input('produsen')));
        $negaraProdusenArr = $appendJsonArray($pemeriksaanBahanBaku->negara_produsen_array, $request->input('negara_produsen'));
        $distributorArr = $appendJsonArray($pemeriksaanBahanBaku->distributor_array, $normalizeSingleMulti($request->input('distributor')));
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
        $uploadedFileCoa = $request->file('file_coa_img') ?? $request->file('file_coa');
        if ($uploadedFileCoa) {
            $fileCoaArr[] = $this->compressAndStoreCoa($uploadedFileCoa);
        } else {
            $fileCoaArr[] = null;
        }

        $imageArr = json_decode($pemeriksaanBahanBaku->image_bahan_baku_array ?? '[]', true);
        if (!is_array($imageArr)) {
            $imageArr = [];
        }
        $uploadedImage = $request->file('image_bahan_baku');
        if ($uploadedImage) {
            $imageArr[] = $uploadedImage->storePublicly('pemeriksaan-bahan-baku-penunjang/images', 'public');
        } else {
            $imageArr[] = null;
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
            'image_bahan_baku_array' => json_encode($imageArr),
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
        $id_produk = $request->input('id_produk');
        $kategori_code = $request->input('kategori_code');

        // === MODE: ALL SHIFT ===
        if ($id_shift === 'all') {
            return $this->exportPDFAllShift($request, $user, $tanggal_dari, $tanggal_sampai, $id_produk, $kategori_code);
        }

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
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        // Prepare valid bahan IDs based on filter
        $matchedBahanIds = [];
        $safeFuzzyMatch = function ($normName, $bNorm) {
            if (!$normName || !$bNorm) return false;
            $shorter = min(strlen($bNorm), strlen($normName));
            $longer  = max(strlen($bNorm), strlen($normName));
            return $shorter >= 4 && $longer > 0 && ($shorter / $longer) >= 0.5
                && (str_contains($bNorm, $normName) || str_contains($normName, $bNorm));
        };
        if ($id_produk) {
            $produk = \App\Models\Produk::find($id_produk);
            if ($produk) {
                $normName = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $produk->nama_produk)));
                $bahans = \App\Models\Bahan::select('id', 'nama_bahan')->get();
                foreach($bahans as $b) {
                    $bNorm = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $b->nama_bahan)));
                    if ($safeFuzzyMatch($normName, $bNorm)) {
                        $matchedBahanIds[] = $b->id;
                    }
                }
            }
        } elseif ($kategori_code) {
            $produks = \App\Models\Produk::where('kategori_code', $kategori_code)->get();
            $bahans = \App\Models\Bahan::select('id', 'nama_bahan')->get();
            foreach ($produks as $produk) {
                $normName = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $produk->nama_produk)));
                foreach($bahans as $b) {
                    $bNorm = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $b->nama_bahan)));
                    if ($safeFuzzyMatch($normName, $bNorm)) {
                        $matchedBahanIds[] = $b->id;
                    }
                }
            }
            $matchedBahanIds = array_unique($matchedBahanIds);
        }

        // Filter by produk / kategori based on matched Bahan IDs
        if ($id_produk || $kategori_code) {
            if (!empty($matchedBahanIds)) {
                $query->where(function ($q) use ($matchedBahanIds) {
                    foreach ($matchedBahanIds as $bid) {
                        $q->orWhereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((int)$bid)])
                          ->orWhereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((string)$bid)])
                          ->orWhere('id_bahan_array', 'like', '%"' . $bid . '"%')
                          ->orWhere('id_bahan_array', 'like', '%,' . $bid . ',%')
                          ->orWhere('id_bahan_array', 'like', '[' . $bid . ',%')
                          ->orWhere('id_bahan_array', 'like', '%,' . $bid . ']');
                    }
                });
            } else {
                // If category has no products or no match, return no results
                $query->whereRaw('1 = 0');
            }
        }

        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

        // Filter by tanggal berdasarkan shift
        // Shift 1: date range (tanggal_dari - tanggal_sampai)
        // Shift 2 & 3: single date (tanggal)
        if ($id_shift) {
            $shift = Shift::find($id_shift);
            $shiftName = $shift ? trim(strtolower((string) $shift->shift)) : null;

            $isShift1 = $shift && $shift->is_date_range;

            if ($isShift1) {
                if ($tanggal_dari && $tanggal_sampai) {
                    $query->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai]);
                } elseif ($tanggal_dari) {
                    $query->whereDate('tanggal', '>=', $tanggal_dari);
                } elseif ($tanggal_sampai) {
                    $query->whereDate('tanggal', '<=', $tanggal_sampai);
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
            'spvQcUser' => $spvQcUser,
            'filterBahanIds' => ($id_produk || $kategori_code) ? $matchedBahanIds : null,
            'isAllShift' => false,
            'dataPerShift' => [],
        ]);

        // Generate filename berdasarkan filter
        if ($tanggal_dari && $tanggal_sampai) {
            $filename = 'laporan-pemeriksaan-bahan-baku-' . $tanggal_dari . '-to-' . $tanggal_sampai . '.pdf';
        } else {
            $filename = 'laporan-pemeriksaan-bahan-baku-' . ($tanggal ?? date('Y-m-d')) . '.pdf';
        }
        
        return $pdf->download($filename);
    }

    /**
     * Export PDF untuk semua shift, dikelompokkan per shift
     */
    private function exportPDFAllShift($request, $user, $tanggal_dari, $tanggal_sampai, $id_produk, $kategori_code)
    {
        // Ambil semua shift yang accessible
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $allShifts = Shift::all();
        } else {
            $allShifts = Shift::query()
                ->when($user->id_plant, function ($q) use ($user) {
                    $q->whereHas('user', function ($qu) use ($user) {
                        $qu->where('id_plant', $user->id_plant);
                    });
                })
                ->get();
        }

        // Prepare bahan filter (sama seperti exportPDF)
        $matchedBahanIds = [];
        $safeFuzzyMatch = function ($normName, $bNorm) {
            if (!$normName || !$bNorm) return false;
            $shorter = min(strlen($bNorm), strlen($normName));
            $longer  = max(strlen($bNorm), strlen($normName));
            return $shorter >= 4 && $longer > 0 && ($shorter / $longer) >= 0.5
                && (str_contains($bNorm, $normName) || str_contains($normName, $bNorm));
        };

        if ($id_produk) {
            $produk = \App\Models\Produk::find($id_produk);
            if ($produk) {
                $normName = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $produk->nama_produk)));
                $bahans = \App\Models\Bahan::select('id', 'nama_bahan')->get();
                foreach ($bahans as $b) {
                    $bNorm = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $b->nama_bahan)));
                    if ($safeFuzzyMatch($normName, $bNorm)) $matchedBahanIds[] = $b->id;
                }
            }
        } elseif ($kategori_code) {
            $produks = \App\Models\Produk::where('kategori_code', $kategori_code)->get();
            $bahans = \App\Models\Bahan::select('id', 'nama_bahan')->get();
            foreach ($produks as $produk) {
                $normName = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $produk->nama_produk)));
                foreach ($bahans as $b) {
                    $bNorm = preg_replace('/[^a-z0-9]+/i', '', strtolower(trim((string) $b->nama_bahan)));
                    if ($safeFuzzyMatch($normName, $bNorm)) $matchedBahanIds[] = $b->id;
                }
            }
            $matchedBahanIds = array_unique($matchedBahanIds);
        }

        // Kumpulkan data per shift
        $dataPerShift = [];

        foreach ($allShifts as $shift) {
            $query = PemeriksaanKedatanganBahanBakuPenunjang::with([
                'user.role', 'user.plant', 'bahan', 'shift',
                'qcVerifier'    => fn($q) => $q->select('id', 'name'),
                'produksiVerifier' => fn($q) => $q->select('id', 'name'),
                'spvVerifier'   => fn($q) => $q->select('id', 'name'),
            ]);

            // Plant filter
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('id_plant', $user->getEffectivePlantId());
                });
            }

            // Filter shift
            $query->where('id_shift', $shift->id);

            // Filter tanggal
            if ($tanggal_dari && $tanggal_sampai) {
                $query->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai]);
            } elseif ($tanggal_dari) {
                $query->whereDate('tanggal', '>=', $tanggal_dari);
            } elseif ($tanggal_sampai) {
                $query->whereDate('tanggal', '<=', $tanggal_sampai);
            }

            // Filter bahan/kategori
            if ($id_produk || $kategori_code) {
                if (!empty($matchedBahanIds)) {
                    $query->where(function ($q) use ($matchedBahanIds) {
                        foreach ($matchedBahanIds as $bid) {
                            $q->orWhereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((int)$bid)])
                              ->orWhereRaw("JSON_CONTAINS(id_bahan_array, ?, '$')", [json_encode((string)$bid)])
                              ->orWhere('id_bahan_array', 'like', '%"' . $bid . '"%');
                        }
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            }

            $records = $query->latest()->get();

            // Hanya masukkan jika ada data
            if ($records->isNotEmpty()) {
                // Kumpulkan verifier names
                $qcUser = null; $produksiUser = null; $spvQcUser = null;
                $qcId = $records->pluck('verified_by_qc')->filter()->unique()->first();
                $prodId = $records->pluck('verified_by_produksi')->filter()->unique()->first();
                $spvId = $records->pluck('verified_by_spv')->filter()->unique()->first();
                if ($qcId) $qcUser = optional(User::find($qcId))->name;
                if ($prodId) $produksiUser = optional(User::find($prodId))->name;
                if ($spvId) $spvQcUser = optional(User::find($spvId))->name;

                $dataPerShift[] = [
                    'shift'         => $shift,
                    'pemeriksaans'  => $records,
                    'qcUser'        => $qcUser,
                    'produksiUser'  => $produksiUser,
                    'spvQcUser'     => $spvQcUser,
                    'filterBahanIds' => ($id_produk || $kategori_code) ? $matchedBahanIds : null,
                ];
            }
        }

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.pdf-report', [
            'dataPerShift'   => $dataPerShift,
            'tanggal_dari'   => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
            'allShifts'      => $allShifts,
            // variabel dummy agar tidak error di view yang expect variabel ini
            'pemeriksaans'   => collect(),
            'tanggal'        => null,
            'shift'          => null,
            'qcUser'         => null,
            'produksiUser'   => null,
            'spvQcUser'      => null,
            'filterBahanIds' => ($id_produk || $kategori_code) ? $matchedBahanIds : null,
            'isAllShift'     => true,
        ]);

        $filename = 'laporan-semua-shift-bahan-baku-'
            . ($tanggal_dari ?? date('Y-m-d'))
            . ($tanggal_sampai ? '-to-' . $tanggal_sampai : '')
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

        $query = PemeriksaanKedatanganBahanBakuPenunjang::query();

        // Filter by plant
        if ($userRole !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        // Logic Prioritas: Jika ada pilihan checkbox, gunakan itu. Jika tidak, gunakan filter tanggal.
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
        } elseif ($userRole === 'produksi' || $userRole === 'warehouse') {
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