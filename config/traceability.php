<?php

return [
    'modules' => [
        // 1. Pemeriksaan Kedatangan Bahan Baku Penunjang (VARCHAR + array) - Search by kode OR nama bahan
        'kedatangan_bahan_baku' => [
            'model' => \App\Models\PemeriksaanKedatanganBahanBakuPenunjang::class,
            'column' => 'kode_produksi', // fallback
            'search_columns' => [
                ['name' => 'kode_produksi', 'is_json' => false],
                ['name' => 'kode_produksi_array', 'is_json' => true, 'json_is_array' => true],
            ],
            'route_key' => 'uuid',
            'date_column' => 'tanggal',
            'pdf_route' => 'pemeriksaan-bahan-baku.show',
            'label' => 'Kedatangan Bahan Baku Penunjang',
            'with' => ['user', 'shift', 'bahan'],
            'has_plant_filter' => true,
            'enable_nama_search' => true, // Enable search by nama bahan
            'nama_search_config' => [
                'relation' => 'bahan',
                'name_field' => 'nama_bahan',
                'id_field_single' => 'id_bahan',
                'id_field_array' => 'id_bahan_array',
            ],
            'display_fields' => function ($record, $batchCode) {
                // Check if has array data
                $kodeProduksiArray = $record->kode_produksi_array ?? null;
                
                if ($kodeProduksiArray && is_array($kodeProduksiArray) && count($kodeProduksiArray) > 0) {
                    // Multiple rows mode
                    $idBahanArray = json_decode($record->id_bahan_array, true) ?? [];
                    $produsenArray = $record->produsen_array ?? [];
                    $negaraArray = $record->negara_produsen_array ?? [];
                    $distributorArray = $record->distributor_array ?? [];
                    $jumlahDatangArray = $record->jumlah_datang_array ?? [];
                    $unitDatangArray = $record->unit_datang_array ?? [];
                    $statusArray = $record->status_array ?? [];
                    
                    // Load all bahans at once for efficiency
                    $bahanIds = array_filter($idBahanArray);
                    $bahans = !empty($bahanIds) ? \App\Models\Bahan::whereIn('id', $bahanIds)->pluck('nama_bahan', 'id')->toArray() : [];
                    
                    $fields = [];
                    foreach ($kodeProduksiArray as $idx => $kode) {
                        $bahanId = $idBahanArray[$idx] ?? null;
                        $namaBahan = $bahanId && isset($bahans[$bahanId]) ? $bahans[$bahanId] : '-';
                        
                        // Match by kode OR nama bahan
                        if (str_contains(strtolower($kode ?? ''), strtolower($batchCode)) ||
                            str_contains(strtolower($namaBahan), strtolower($batchCode))) {
                            $fields['Bahan ' . ($idx + 1)] = $namaBahan;
                            $fields['Kode Produksi ' . ($idx + 1)] = $kode;
                            $fields['Produsen ' . ($idx + 1)] = $produsenArray[$idx] ?? '-';
                            $fields['Negara ' . ($idx + 1)] = is_array($negaraArray[$idx] ?? null) ? implode(', ', $negaraArray[$idx]) : ($negaraArray[$idx] ?? '-');
                            $fields['Distributor ' . ($idx + 1)] = $distributorArray[$idx] ?? '-';
                            $fields['Jumlah ' . ($idx + 1)] = ($jumlahDatangArray[$idx] ?? '-') . ' ' . ($unitDatangArray[$idx] ?? '-');
                            $fields['Status ' . ($idx + 1)] = $statusArray[$idx] ?? '-';
                        }
                    }
                    return $fields;
                } else {
                    // Single row mode
                    $namaBahan = $record->bahan ? $record->bahan->nama_bahan : '-';
                    return [
                        'Bahan' => $namaBahan,
                        'Kode Produksi' => $record->kode_produksi ?? '-',
                        'Produsen' => $record->produsen ?? '-',
                        'Negara' => $record->negara_produsen ?? '-',
                        'Distributor' => $record->distributor ?? '-',
                        'Kondisi' => $record->kondisi_produk ?? '-',
                        'Jumlah Datang' => $record->jumlah_datang ?? '-',
                        'Status' => $record->status ?? '-',
                    ];
                }
            },
        ],

        // 2. Pemeriksaan Kedatangan Kemasan (VARCHAR + array) - Search by kode OR nama kemasan
        'kedatangan_kemasan' => [
            'model' => \App\Models\PemeriksaanKedatanganKemasan::class,
            'column' => 'kode_produksi', // fallback
            'search_columns' => [
                ['name' => 'kode_produksi', 'is_json' => false],
                ['name' => 'kode_produksi_array', 'is_json' => true, 'json_is_array' => true],
            ],
            'route_key' => 'uuid',
            'date_column' => 'tanggal',
            'pdf_route' => 'pemeriksaan-kedatangan-kemasan.show',
            'label' => 'Kedatangan Kemasan',
            'with' => ['user', 'shift', 'bahan'],
            'has_plant_filter' => true,
            'enable_nama_search' => true, // Enable search by nama bahan kemasan
            'nama_search_config' => [
                'relation' => 'bahan',
                'name_field' => 'nama_bahan',
                'id_field_single' => 'id_bahan',
                'id_field_array' => 'id_bahan_array',
            ],
            'display_fields' => function ($record, $batchCode) {
                // Check if has array data
                $kodeProduksiArray = $record->kode_produksi_array ?? null;
                
                if ($kodeProduksiArray && is_array($kodeProduksiArray) && count($kodeProduksiArray) > 0) {
                    // Multiple rows mode
                    $idBahanArray = json_decode($record->id_bahan_array, true) ?? [];
                    $produsenArray = $record->produsen_array ?? [];
                    $distributorArray = $record->distributor_array ?? [];
                    $jumlahDatangArray = $record->jumlah_datang_array ?? [];
                    $unitDatangArray = $record->unit_datang_array ?? [];
                    $statusArray = $record->status_array ?? [];
                    
                    // Load all bahans at once for efficiency
                    $bahanIds = array_filter($idBahanArray);
                    $bahans = !empty($bahanIds) ? \App\Models\Bahan::whereIn('id', $bahanIds)->pluck('nama_bahan', 'id')->toArray() : [];
                    
                    $fields = [];
                    foreach ($kodeProduksiArray as $idx => $kode) {
                        $bahanId = $idBahanArray[$idx] ?? null;
                        $namaKemasan = $bahanId && isset($bahans[$bahanId]) ? $bahans[$bahanId] : '-';
                        
                        // Match by kode OR nama kemasan
                        if (str_contains(strtolower($kode ?? ''), strtolower($batchCode)) ||
                            str_contains(strtolower($namaKemasan), strtolower($batchCode))) {
                            $fields['Kemasan ' . ($idx + 1)] = $namaKemasan;
                            $fields['Kode Produksi ' . ($idx + 1)] = $kode;
                            $fields['Produsen ' . ($idx + 1)] = $produsenArray[$idx] ?? '-';
                            $fields['Distributor ' . ($idx + 1)] = $distributorArray[$idx] ?? '-';
                            $fields['Jumlah ' . ($idx + 1)] = ($jumlahDatangArray[$idx] ?? '-') . ' ' . ($unitDatangArray[$idx] ?? '-');
                            $fields['Status ' . ($idx + 1)] = $statusArray[$idx] ?? '-';
                        }
                    }
                    return $fields;
                } else {
                    // Single row mode
                    $namaKemasan = $record->bahan ? $record->bahan->nama_bahan : '-';
                    return [
                        'Kemasan' => $namaKemasan,
                        'Kode Produksi' => $record->kode_produksi ?? '-',
                        'Produsen' => $record->produsen ?? '-',
                        'Distributor' => $record->distributor ?? '-',
                        'Jumlah Datang' => $record->jumlah_datang ?? '-',
                        'Status' => $record->status ?? '-',
                    ];
                }
            },
        ],

        // 3. Pemeriksaan Kedatangan Chemical (JSON detail_chemicals) - Search by kode OR nama chemical
        'kedatangan_chemical' => [
            'model' => \App\Models\PemeriksaanKedatanganChemical::class,
            'column' => 'detail_chemicals',
            'is_json' => true,
            'json_is_array' => false,
            'json_path' => 'kode_produksi',
            'route_key' => 'uuid',
            'date_column' => 'tanggal',
            'pdf_route' => 'pemeriksaan-chemical.show',
            'label' => 'Kedatangan Chemical',
            'with' => ['user', 'shift'],
            'has_plant_filter' => true,
            'enable_nama_search' => true, // Enable search by nama chemical
            'nama_search_config' => [
                'relation' => null, // Will search manually in display_fields via detail_chemicals JSON
                'name_field' => 'nama_chemical',
                'json_field' => 'detail_chemicals',
                'json_id_key' => 'id_chemical',
            ],
            'display_fields' => function ($record, $batchCode) {
                $detailChemicals = $record->detail_chemicals ?? [];
                
                // Get all chemical IDs from detail_chemicals
                $chemicalIds = collect($detailChemicals)->pluck('id_chemical')->filter()->unique()->toArray();
                $chemicals = !empty($chemicalIds) ? \App\Models\Chemical::whereIn('id', $chemicalIds)->pluck('nama_chemical', 'id')->toArray() : [];
                
                $matchingChemicals = collect($detailChemicals)->filter(function ($item) use ($batchCode, $chemicals) {
                    $kodeProduksi = $item['kode_produksi'] ?? '';
                    $chemicalId = $item['id_chemical'] ?? null;
                    $namaChemical = $chemicalId && isset($chemicals[$chemicalId]) ? $chemicals[$chemicalId] : '';
                    
                    return str_contains(strtolower($kodeProduksi), strtolower($batchCode)) ||
                           str_contains(strtolower($namaChemical), strtolower($batchCode));
                });

                $fields = [];

                foreach ($matchingChemicals as $idx => $chemical) {
                    // Get chemical name
                    $chemicalId = $chemical['id_chemical'] ?? null;
                    $chemicalNama = $chemicalId && isset($chemicals[$chemicalId]) ? $chemicals[$chemicalId] : '-';

                    // Get produsen name
                    $produsenId = $chemical['id_produsen'] ?? null;
                    $produsenNama = '-';
                    if ($produsenId) {
                        $prod = \App\Models\Produsen::find($produsenId);
                        $produsenNama = $prod ? $prod->produsen : '-';
                    }

                    $fields['Chemical ' . ($idx + 1)] = $chemicalNama;
                    $fields['Kode Produksi ' . ($idx + 1)] = $chemical['kode_produksi'] ?? '-';
                    $fields['Produsen ' . ($idx + 1)] = $produsenNama;
                    $fields['Kondisi ' . ($idx + 1)] = $chemical['kondisi_chemical'] ?? '-';
                    $fields['Jumlah ' . ($idx + 1)] = $chemical['jumlah_datang'] ?? '-';
                    $fields['Status ' . ($idx + 1)] = $chemical['status'] ?? '-';
                }

                return $fields;
            },
        ],

        // 4. Pemeriksaan Loading Produk (JSON produk_data) - Search by kode OR nama produk
        'loading_produk' => [
            'model' => \App\Models\PemeriksaanLoadingProduk::class,
            'column' => 'produk_data', // fallback
            'search_columns' => [
                ['name' => 'produk_data', 'is_json' => true, 'json_is_array' => false, 'json_path' => 'kode_produksi'],
                ['name' => 'produk_data', 'is_json' => true, 'json_is_array' => false, 'json_path' => 'nama_produk'],
            ],
            'route_key' => 'uuid',
            'date_column' => 'tanggal',
            'pdf_route' => 'pemeriksaan-loading-produk.show',
            'label' => 'Loading Produk',
            'with' => ['user', 'shift', 'tujuanPengiriman', 'kendaraan'],
            'has_plant_filter' => true,
            'enable_nama_search' => true, // Enable search by nama produk via id_produk in JSON
            'nama_search_config' => [
                'relation' => null,
                'name_field' => 'nama_produk',
                'json_field' => 'produk_data',
                'json_id_key' => 'id_produk',
            ],
            'display_fields' => function ($record, $batchCode) {
                $produkData = $record->produk_data ?? [];
                
                // Get all produk IDs from produk_data for efficient loading
                $produkIds = collect($produkData)->pluck('id_produk')->filter()->unique()->toArray();
                $produks = !empty($produkIds) ? \App\Models\Produk::whereIn('id', $produkIds)->pluck('nama_produk', 'id')->toArray() : [];
                
                $matchingProducts = collect($produkData)->filter(function ($item) use ($batchCode, $produks) {
                    $kodeProduksi = $item['kode_produksi'] ?? '';
                    $namaProduk = $item['nama_produk'] ?? '';
                    $produkId = $item['id_produk'] ?? null;
                    $namaProdukFromDb = $produkId && isset($produks[$produkId]) ? $produks[$produkId] : '';
                    
                    return str_contains(strtolower($kodeProduksi), strtolower($batchCode)) ||
                           str_contains(strtolower($namaProduk), strtolower($batchCode)) ||
                           str_contains(strtolower($namaProdukFromDb), strtolower($batchCode));
                });

                $fields = [
                    'Tujuan' => $record->tujuanPengiriman ? $record->tujuanPengiriman->tujuan_pengiriman : '-',
                    'Kendaraan' => $record->kendaraan ? $record->kendaraan->jenis_kendaraan : '-',
                    'Kondisi' => $record->kondisi_produk ?? '-',
                ];

                foreach ($matchingProducts as $idx => $produk) {
                    $produkId = $produk['id_produk'] ?? null;
                    $namaProduk = $produk['nama_produk'] ?? ($produkId && isset($produks[$produkId]) ? $produks[$produkId] : '-');
                    
                    $fields['Produk ' . ($idx + 1)] = $namaProduk;
                    $fields['Kode Produksi ' . ($idx + 1)] = $produk['kode_produksi'] ?? '-';
                    $fields['Best Before ' . ($idx + 1)] = $produk['best_before'] ?? '-';
                    $fields['Jumlah ' . ($idx + 1)] = ($produk['jumlah_kemasan'] ?? '-') . ' ' . ($produk['unit'] ?? '');
                }

                return $fields;
            },
        ],

        // 5. Pemeriksaan Produk Finish Good (JSON array) - Search by kode OR nama produk
        'produk_finish_good' => [
            'model' => \App\Models\PemeriksaanProdukFinishGood::class,
            'column' => 'kode_produksi_array',
            'is_json' => true,
            'json_is_array' => true,
            'route_key' => 'uuid',
            'date_column' => 'tanggal',
            'pdf_route' => 'pemeriksaan-produk-finish-good.show',
            'label' => 'Produk Finish Good',
            'with' => ['user', 'shift'],
            'has_plant_filter' => true,
            'enable_nama_search' => true, // Enable search by nama produk
            'nama_search_config' => [
                'relation' => null, // Will search manually via id_produk_array
                'name_field' => 'nama_produk',
                'id_field_array' => 'id_produk_array',
            ],
            'display_fields' => function ($record, $batchCode) {
                $kodeProduksiArray = $record->kode_produksi_array ?? [];
                $idProdukArray = $record->id_produk_array ?? [];
                $produsenArray = $record->produsen_array ?? [];
                $distributorArray = $record->distributor_array ?? [];
                $jumlahDatangArray = $record->jumlah_datang_array ?? [];
                $unitDatangArray = $record->unit_datang_array ?? [];
                $statusArray = $record->status_array ?? [];

                // Load all produks at once for efficiency
                $produkIds = array_filter($idProdukArray);
                $produks = !empty($produkIds) ? \App\Models\Produk::whereIn('id', $produkIds)->pluck('nama_produk', 'id')->toArray() : [];

                $fields = [
                    'Kondisi Produk' => $record->kondisi_produk ?? '-',
                    'Suhu Mobil' => $record->suhu_mobil ?? '-',
                ];

                foreach ($kodeProduksiArray as $idx => $kode) {
                    $produkId = $idProdukArray[$idx] ?? null;
                    $produkNama = $produkId && isset($produks[$produkId]) ? $produks[$produkId] : '-';
                    
                    // Match by kode OR nama produk
                    if (str_contains(strtolower($kode ?? ''), strtolower($batchCode)) ||
                        str_contains(strtolower($produkNama), strtolower($batchCode))) {
                        $fields['Produk ' . ($idx + 1)] = $produkNama;
                        $fields['Kode Produksi ' . ($idx + 1)] = $kode;
                        $fields['Produsen ' . ($idx + 1)] = $produsenArray[$idx] ?? '-';
                        $fields['Distributor ' . ($idx + 1)] = $distributorArray[$idx] ?? '-';
                        $fields['Jumlah ' . ($idx + 1)] = ($jumlahDatangArray[$idx] ?? '-') . ' ' . ($unitDatangArray[$idx] ?? '');
                        $fields['Status ' . ($idx + 1)] = $statusArray[$idx] ?? '-';
                    }
                }

                return $fields;
            },
        ],

        // 6. Pemeriksaan Return Barang Customer (JSON produk_data) - Search by kode OR nama produk
        'return_barang_customer' => [
            'model' => \App\Models\PemeriksaanReturnBarangCustomer::class,
            'column' => 'produk_data', // fallback
            'search_columns' => [
                ['name' => 'produk_data', 'is_json' => true, 'json_is_array' => false, 'json_path' => 'kode_produksi'],
                ['name' => 'produk_data', 'is_json' => true, 'json_is_array' => false, 'json_path' => 'nama_produk'],
            ],
            'route_key' => 'uuid',
            'date_column' => 'tanggal',
            'pdf_route' => 'return-barang.show',
            'label' => 'Return Barang Customer',
            'with' => ['user', 'shift', 'customer', 'ekspedisi'],
            'has_plant_filter' => true,
            'enable_nama_search' => true, // Enable search by nama produk via id_produk in JSON
            'nama_search_config' => [
                'relation' => null,
                'name_field' => 'nama_produk',
                'json_field' => 'produk_data',
                'json_id_key' => 'id_produk',
            ],
            'display_fields' => function ($record, $batchCode) {
                $produkData = $record->produk_data ?? [];
                
                // Get all produk IDs from produk_data for efficient loading
                $produkIds = collect($produkData)->pluck('id_produk')->filter()->unique()->toArray();
                $produks = !empty($produkIds) ? \App\Models\Produk::whereIn('id', $produkIds)->pluck('nama_produk', 'id')->toArray() : [];
                
                $matchingProducts = collect($produkData)->filter(function ($item) use ($batchCode, $produks) {
                    $kodeProduksi = $item['kode_produksi'] ?? '';
                    $namaProduk = $item['nama_produk'] ?? '';
                    $produkId = $item['id_produk'] ?? null;
                    $namaProdukFromDb = $produkId && isset($produks[$produkId]) ? $produks[$produkId] : '';
                    
                    return str_contains(strtolower($kodeProduksi), strtolower($batchCode)) ||
                           str_contains(strtolower($namaProduk), strtolower($batchCode)) ||
                           str_contains(strtolower($namaProdukFromDb), strtolower($batchCode));
                });

                $fields = [
                    'Customer' => $record->customer ? $record->customer->customer : '-',
                    'Alasan Return' => $record->alasan_return ?? '-',
                    'Suhu Mobil' => $record->suhu_mobil ?? '-',
                ];

                foreach ($matchingProducts as $idx => $produk) {
                    $produkId = $produk['id_produk'] ?? null;
                    $namaProduk = $produk['nama_produk'] ?? ($produkId && isset($produks[$produkId]) ? $produks[$produkId] : '-');
                    
                    $fields['Produk ' . ($idx + 1)] = $namaProduk;
                    $fields['Kode Produksi ' . ($idx + 1)] = $produk['kode_produksi'] ?? '-';
                    $fields['Best Before ' . ($idx + 1)] = $produk['best_before'] ?? '-';
                    $fields['Jumlah ' . ($idx + 1)] = ($produk['jumlah'] ?? '-') . ' ' . ($produk['satuan'] ?? '');
                    $fields['Kondisi ' . ($idx + 1)] = $produk['kondisi_fisik'] ?? '-';
                }

                return $fields;
            },
        ],

        // 7. Golden Sample Reports (JSON samples)
        'golden_sample' => [
            'model' => \App\Models\GoldenSampleReport::class,
            'column' => 'samples',
            'is_json' => true,
            'json_is_array' => false,
            'json_path' => 'kode_produksi',
            'route_key' => 'uuid',
            'date_column' => 'tanggal',
            'pdf_route' => 'golden-sample-reports.show',
            'label' => 'Golden Sample Report',
            'with' => ['user', 'shift', 'plant'],
            'has_plant_filter' => true,
            'display_fields' => function ($record, $batchCode) {
                $samples = $record->samples ?? [];
                $matchingSamples = collect($samples)->filter(function ($item) use ($batchCode) {
                    $kodeProduksi = $item['kode_produksi'] ?? '';
                    return str_contains(strtolower($kodeProduksi), strtolower($batchCode));
                });

                $fields = [
                    'Plant' => $record->plant ? $record->plant->plant : ($record->plant_manual ?? '-'),
                    'Sample Type' => $record->sample_type ?? '-',
                    'Masa Penyimpanan' => $record->masa_penyimpanan ?? '-',
                ];

                foreach ($matchingSamples as $idx => $sample) {
                    $fields['Sample ' . ($idx + 1)] = 'ID: ' . ($sample['id_supplier'] ?? '-');
                    $fields['Kode Produksi ' . ($idx + 1)] = $sample['kode_produksi'] ?? '-';
                    $fields['Best Before ' . ($idx + 1)] = $sample['best_before'] ?? '-';
                    $fields['Qty ' . ($idx + 1)] = $sample['qty'] ?? '-';
                }

                return $fields;
            },
        ],

        // 8. Detail Komplain (VARCHAR + array) - Search by kode OR nama produk
        'detail_komplain' => [
            'model' => \App\Models\DetailKomplain::class,
            'column' => 'kode_produksi', // fallback
            'search_columns' => [
                ['name' => 'kode_produksi', 'is_json' => false],
                ['name' => 'kode_produksi_array', 'is_json' => true, 'json_is_array' => true],
                ['name' => 'nama_produk', 'is_json' => false],
                ['name' => 'nama_produk_array', 'is_json' => true, 'json_is_array' => true],
            ],
            'route_key' => 'uuid',
            'date_column' => 'tanggal_kedatangan',
            'pdf_route' => 'detail-komplain.show',
            'label' => 'Detail Komplain',
            'with' => ['user', 'shift'],
            'has_plant_filter' => true,
            'display_fields' => function ($record, $batchCode) {
                // Check if using array format
                $kodeProduksiArray = $record->kode_produksi_array ?? null;
                
                if ($kodeProduksiArray && is_array($kodeProduksiArray) && count($kodeProduksiArray) > 0) {
                    $namaProdukArray = $record->nama_produk_array ?? [];
                    $jumlahDatangArray = $record->jumlah_datang_array ?? [];
                    $jumlahDitolakArray = $record->jumlah_di_tolak_array ?? [];
                    $expiredDateArray = $record->expired_date_array ?? [];
                    
                    $fields = [
                        'Supplier' => $record->nama_supplier ?? '-',
                        'No PO' => $record->no_po ?? '-',
                    ];

                    foreach ($kodeProduksiArray as $idx => $kode) {
                        $namaProduk = $namaProdukArray[$idx] ?? '';
                        // Match by kode OR nama produk
                        if (str_contains(strtolower($kode ?? ''), strtolower($batchCode)) ||
                            str_contains(strtolower($namaProduk), strtolower($batchCode))) {
                            $fields['Produk ' . ($idx + 1)] = $namaProduk ?: '-';
                            $fields['Kode Produksi ' . ($idx + 1)] = $kode;
                            $fields['Expired ' . ($idx + 1)] = $expiredDateArray[$idx] ?? '-';
                            $fields['Jumlah Datang ' . ($idx + 1)] = $jumlahDatangArray[$idx] ?? '-';
                            $fields['Jumlah Ditolak ' . ($idx + 1)] = $jumlahDitolakArray[$idx] ?? '-';
                        }
                    }
                } else {
                    // Single record format
                    $fields = [
                        'Supplier' => $record->nama_supplier ?? '-',
                        'No PO' => $record->no_po ?? '-',
                        'Produk' => $record->nama_produk ?? '-',
                        'Kode Produksi' => $record->kode_produksi ?? '-',
                        'Expired' => $record->expired_date ? $record->expired_date->format('d/m/Y') : '-',
                        'Jumlah Datang' => $record->jumlah_datang ?? '-',
                        'Jumlah Ditolak' => $record->jumlah_di_tolak ?? '-',
                    ];
                }

                return $fields;
            },
        ],
    ],
];
