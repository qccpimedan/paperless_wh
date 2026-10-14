<?php

/**
 * Konfigurasi Master Standar Audit QC System
 *
 * Mendaftarkan modul QC yang mendukung audit, field yang dapat diaudit,
 * dan tipe parameternya.
 *
 * Kunci opsional per modul:
 *  - 'dynamic' => true  : sebagian data tersimpan di kolom JSON dan harus
 *                         ditangani kode khusus (bukan dari daftar 'fields').
 *
 * Kunci opsional per field:
 *  - 'is_array' => true : kolom berisi array JSON; setiap elemen dicek sendiri.
 */

return [

    /**
     * Daftar tipe parameter (satu sumber untuk dropdown, label tabel, dan validasi).
     * Urutan dan key harus sama dengan kolom `parameter_type` di database.
     */
    'parameter_types' => [
        'suhu'             => 'Suhu Produk',
        'suhu_ruang'       => 'Suhu Ruangan',
        'suhu_kondisi'     => 'Suhu Kondisi Khusus',
        'suhu_pusat'       => 'Suhu Pusat Produk',
        'suhu_pemasakan'   => 'Suhu Pemasakan',
        'status_checklist' => 'Status Checklist',
    ],

    'modules' => [

        /**
         * PRIORITY HIGH: Pemeriksaan Suhu Ruang
         */
        'pemeriksaan_suhu_ruang' => [
            'label'      => 'Food Processing',
            'main_table' => 'pemeriksaan_suhu_ruangs',
            'fields'     => [
                // Suhu Produk
                'suhu_produk' => ['label' => 'Suhu Produk (°C)', 'type' => 'suhu'],
                
                // Cold Storage 1-4 (Setting, Display, Actual)
                'cold_storage_1_setting'   => ['label' => 'CS 1 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_1_display'   => ['label' => 'CS 1 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_1_actual'    => ['label' => 'CS 1 Actual (°C)',    'type' => 'suhu_ruang'],
                'cold_storage_2_setting'   => ['label' => 'CS 2 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_2_display'   => ['label' => 'CS 2 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_2_actual'    => ['label' => 'CS 2 Actual (°C)',    'type' => 'suhu_ruang'],
                'cold_storage_3_setting'   => ['label' => 'CS 3 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_3_display'   => ['label' => 'CS 3 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_3_actual'    => ['label' => 'CS 3 Actual (°C)',    'type' => 'suhu_ruang'],
                'cold_storage_4_setting'   => ['label' => 'CS 4 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_4_display'   => ['label' => 'CS 4 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_4_actual'    => ['label' => 'CS 4 Actual (°C)',    'type' => 'suhu_ruang'],
                
                // Anteroom Loading 1-4 (Setting, Display, Actual)
                'anteroom_loading_1_setting' => ['label' => 'Anteroom Loading 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_1_display' => ['label' => 'Anteroom Loading 1 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_1_actual'  => ['label' => 'Anteroom Loading 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'anteroom_loading_2_setting' => ['label' => 'Anteroom Loading 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_2_display' => ['label' => 'Anteroom Loading 2 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_2_actual'  => ['label' => 'Anteroom Loading 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'anteroom_loading_3_setting' => ['label' => 'Anteroom Loading 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_3_display' => ['label' => 'Anteroom Loading 3 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_3_actual'  => ['label' => 'Anteroom Loading 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'anteroom_loading_4_setting' => ['label' => 'Anteroom Loading 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_4_display' => ['label' => 'Anteroom Loading 4 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_4_actual'  => ['label' => 'Anteroom Loading 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Pre Loading (Opsional)
                'pre_loading_setting' => ['label' => 'Pre Loading Setting (°C)', 'type' => 'suhu_ruang'],
                'pre_loading_display' => ['label' => 'Pre Loading Display (°C)', 'type' => 'suhu_ruang'],
                'pre_loading_actual'  => ['label' => 'Pre Loading Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Prestaging (Opsional)
                'prestaging_setting' => ['label' => 'Prestaging Setting (°C)', 'type' => 'suhu_ruang'],
                'prestaging_display' => ['label' => 'Prestaging Display (°C)', 'type' => 'suhu_ruang'],
                'prestaging_actual'  => ['label' => 'Prestaging Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Anteroom Ekspansi Further (Opsional)
                'anteroom_ekspansi_further_setting' => ['label' => 'Anteroom Ekspansi Further Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_ekspansi_further_display' => ['label' => 'Anteroom Ekspansi Further Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_ekspansi_further_actual'  => ['label' => 'Anteroom Ekspansi Further Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Anteroom Ekspansi Sausage (Opsional)
                'anteroom_ekspansi_sausage_setting' => ['label' => 'Anteroom Ekspansi Sausage Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_ekspansi_sausage_display' => ['label' => 'Anteroom Ekspansi Sausage Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_ekspansi_sausage_actual'  => ['label' => 'Anteroom Ekspansi Sausage Actual (°C)',  'type' => 'suhu_ruang'],
            ],
        ],

        'pemeriksaan_suhu_ruang_v2' => [
            'label'      => 'CS Meat',
            'main_table' => 'pemeriksaan_suhu_ruang_v2s',
            'fields'     => [
                // Suhu Produk
                'suhu_produk' => ['label' => 'Suhu Produk (°C)', 'type' => 'suhu'],
                
                // Cold Storage 1-4 (Setting, Display, Actual)
                'cold_storage_1_setting'   => ['label' => 'CS 1 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_1_display'   => ['label' => 'CS 1 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_1_actual'    => ['label' => 'CS 1 Actual (°C)',    'type' => 'suhu_ruang'],
                'cold_storage_2_setting'   => ['label' => 'CS 2 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_2_display'   => ['label' => 'CS 2 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_2_actual'    => ['label' => 'CS 2 Actual (°C)',    'type' => 'suhu_ruang'],
                'cold_storage_3_setting'   => ['label' => 'CS 3 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_3_display'   => ['label' => 'CS 3 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_3_actual'    => ['label' => 'CS 3 Actual (°C)',    'type' => 'suhu_ruang'],
                'cold_storage_4_setting'   => ['label' => 'CS 4 Setting (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_4_display'   => ['label' => 'CS 4 Display (°C)',   'type' => 'suhu_ruang'],
                'cold_storage_4_actual'    => ['label' => 'CS 4 Actual (°C)',    'type' => 'suhu_ruang'],
                
                // Anteroom Loading 1-4 (Setting, Display, Actual)
                'anteroom_loading_1_setting' => ['label' => 'Anteroom Loading 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_1_display' => ['label' => 'Anteroom Loading 1 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_1_actual'  => ['label' => 'Anteroom Loading 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'anteroom_loading_2_setting' => ['label' => 'Anteroom Loading 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_2_display' => ['label' => 'Anteroom Loading 2 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_2_actual'  => ['label' => 'Anteroom Loading 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'anteroom_loading_3_setting' => ['label' => 'Anteroom Loading 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_3_display' => ['label' => 'Anteroom Loading 3 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_3_actual'  => ['label' => 'Anteroom Loading 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'anteroom_loading_4_setting' => ['label' => 'Anteroom Loading 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_4_display' => ['label' => 'Anteroom Loading 4 Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_loading_4_actual'  => ['label' => 'Anteroom Loading 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Pre Loading (Opsional)
                'pre_loading_setting' => ['label' => 'Pre Loading Setting (°C)', 'type' => 'suhu_ruang'],
                'pre_loading_display' => ['label' => 'Pre Loading Display (°C)', 'type' => 'suhu_ruang'],
                'pre_loading_actual'  => ['label' => 'Pre Loading Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Prestaging (Opsional)
                'prestaging_setting' => ['label' => 'Prestaging Setting (°C)', 'type' => 'suhu_ruang'],
                'prestaging_display' => ['label' => 'Prestaging Display (°C)', 'type' => 'suhu_ruang'],
                'prestaging_actual'  => ['label' => 'Prestaging Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Anteroom Ekspansi ABF (Opsional) - Ini yang beda dari V1
                'anteroom_ekspansi_abf_setting' => ['label' => 'Anteroom Ekspansi ABF Setting (°C)', 'type' => 'suhu_ruang'],
                'anteroom_ekspansi_abf_display' => ['label' => 'Anteroom Ekspansi ABF Display (°C)', 'type' => 'suhu_ruang'],
                'anteroom_ekspansi_abf_actual'  => ['label' => 'Anteroom Ekspansi ABF Actual (°C)',  'type' => 'suhu_ruang'],
            ],
        ],

        'pemeriksaan_suhu_ruang_v3' => [
            'label'      => 'Gudang Dry',
            'main_table' => 'pemeriksaan_suhu_ruang_v3s',
            'fields'     => [
                // Suhu Premix (1-4) - Setting, Display, Actual
                'suhu_premix_1_setting'   => ['label' => 'Suhu Premix 1 Setting (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_1_display'   => ['label' => 'Suhu Premix 1 Display (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_1_actual'    => ['label' => 'Suhu Premix 1 Actual (°C)',    'type' => 'suhu_ruang'],
                'suhu_premix_2_setting'   => ['label' => 'Suhu Premix 2 Setting (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_2_display'   => ['label' => 'Suhu Premix 2 Display (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_2_actual'    => ['label' => 'Suhu Premix 2 Actual (°C)',    'type' => 'suhu_ruang'],
                'suhu_premix_3_setting'   => ['label' => 'Suhu Premix 3 Setting (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_3_display'   => ['label' => 'Suhu Premix 3 Display (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_3_actual'    => ['label' => 'Suhu Premix 3 Actual (°C)',    'type' => 'suhu_ruang'],
                'suhu_premix_4_setting'   => ['label' => 'Suhu Premix 4 Setting (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_4_display'   => ['label' => 'Suhu Premix 4 Display (°C)',   'type' => 'suhu_ruang'],
                'suhu_premix_4_actual'    => ['label' => 'Suhu Premix 4 Actual (°C)',    'type' => 'suhu_ruang'],
                
                // Suhu Seasoning (1-4) - Setting, Display, Actual
                'suhu_seasoning_1_setting' => ['label' => 'Suhu Seasoning 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_1_display' => ['label' => 'Suhu Seasoning 1 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_1_actual'  => ['label' => 'Suhu Seasoning 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_seasoning_2_setting' => ['label' => 'Suhu Seasoning 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_2_display' => ['label' => 'Suhu Seasoning 2 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_2_actual'  => ['label' => 'Suhu Seasoning 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_seasoning_3_setting' => ['label' => 'Suhu Seasoning 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_3_display' => ['label' => 'Suhu Seasoning 3 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_3_actual'  => ['label' => 'Suhu Seasoning 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_seasoning_4_setting' => ['label' => 'Suhu Seasoning 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_4_display' => ['label' => 'Suhu Seasoning 4 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_seasoning_4_actual'  => ['label' => 'Suhu Seasoning 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Suhu Dry (1-4) - Setting, Display, Actual
                'suhu_dry_1_setting' => ['label' => 'Suhu Dry 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_1_display' => ['label' => 'Suhu Dry 1 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_1_actual'  => ['label' => 'Suhu Dry 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_dry_2_setting' => ['label' => 'Suhu Dry 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_2_display' => ['label' => 'Suhu Dry 2 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_2_actual'  => ['label' => 'Suhu Dry 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_dry_3_setting' => ['label' => 'Suhu Dry 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_3_display' => ['label' => 'Suhu Dry 3 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_3_actual'  => ['label' => 'Suhu Dry 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_dry_4_setting' => ['label' => 'Suhu Dry 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_4_display' => ['label' => 'Suhu Dry 4 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_dry_4_actual'  => ['label' => 'Suhu Dry 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Suhu Cassing (1-4) - Setting, Display, Actual
                'suhu_cassing_1_setting' => ['label' => 'Suhu Cassing 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_1_display' => ['label' => 'Suhu Cassing 1 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_1_actual'  => ['label' => 'Suhu Cassing 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_cassing_2_setting' => ['label' => 'Suhu Cassing 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_2_display' => ['label' => 'Suhu Cassing 2 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_2_actual'  => ['label' => 'Suhu Cassing 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_cassing_3_setting' => ['label' => 'Suhu Cassing 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_3_display' => ['label' => 'Suhu Cassing 3 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_3_actual'  => ['label' => 'Suhu Cassing 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_cassing_4_setting' => ['label' => 'Suhu Cassing 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_4_display' => ['label' => 'Suhu Cassing 4 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_cassing_4_actual'  => ['label' => 'Suhu Cassing 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Suhu Beef (1-4) - Setting, Display, Actual
                'suhu_beef_1_setting' => ['label' => 'Suhu Beef 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_1_display' => ['label' => 'Suhu Beef 1 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_1_actual'  => ['label' => 'Suhu Beef 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_beef_2_setting' => ['label' => 'Suhu Beef 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_2_display' => ['label' => 'Suhu Beef 2 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_2_actual'  => ['label' => 'Suhu Beef 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_beef_3_setting' => ['label' => 'Suhu Beef 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_3_display' => ['label' => 'Suhu Beef 3 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_3_actual'  => ['label' => 'Suhu Beef 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_beef_4_setting' => ['label' => 'Suhu Beef 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_4_display' => ['label' => 'Suhu Beef 4 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_beef_4_actual'  => ['label' => 'Suhu Beef 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Suhu Packaging (1-4) - Setting, Display, Actual
                'suhu_packaging_1_setting' => ['label' => 'Suhu Packaging 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_1_display' => ['label' => 'Suhu Packaging 1 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_1_actual'  => ['label' => 'Suhu Packaging 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_packaging_2_setting' => ['label' => 'Suhu Packaging 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_2_display' => ['label' => 'Suhu Packaging 2 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_2_actual'  => ['label' => 'Suhu Packaging 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_packaging_3_setting' => ['label' => 'Suhu Packaging 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_3_display' => ['label' => 'Suhu Packaging 3 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_3_actual'  => ['label' => 'Suhu Packaging 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_packaging_4_setting' => ['label' => 'Suhu Packaging 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_4_display' => ['label' => 'Suhu Packaging 4 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_packaging_4_actual'  => ['label' => 'Suhu Packaging 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Suhu Ruang Chemical (1-4) - Setting, Display, Actual
                'suhu_ruang_chemical_1_setting' => ['label' => 'Suhu Ruang Chemical 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_1_display' => ['label' => 'Suhu Ruang Chemical 1 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_1_actual'  => ['label' => 'Suhu Ruang Chemical 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_2_setting' => ['label' => 'Suhu Ruang Chemical 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_2_display' => ['label' => 'Suhu Ruang Chemical 2 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_2_actual'  => ['label' => 'Suhu Ruang Chemical 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_3_setting' => ['label' => 'Suhu Ruang Chemical 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_3_display' => ['label' => 'Suhu Ruang Chemical 3 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_3_actual'  => ['label' => 'Suhu Ruang Chemical 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_4_setting' => ['label' => 'Suhu Ruang Chemical 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_4_display' => ['label' => 'Suhu Ruang Chemical 4 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_chemical_4_actual'  => ['label' => 'Suhu Ruang Chemical 4 Actual (°C)',  'type' => 'suhu_ruang'],
                
                // Suhu Ruang Seasoning (1-4) - Setting, Display, Actual
                'suhu_ruang_seasoning_1_setting' => ['label' => 'Suhu Ruang Seasoning 1 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_1_display' => ['label' => 'Suhu Ruang Seasoning 1 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_1_actual'  => ['label' => 'Suhu Ruang Seasoning 1 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_2_setting' => ['label' => 'Suhu Ruang Seasoning 2 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_2_display' => ['label' => 'Suhu Ruang Seasoning 2 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_2_actual'  => ['label' => 'Suhu Ruang Seasoning 2 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_3_setting' => ['label' => 'Suhu Ruang Seasoning 3 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_3_display' => ['label' => 'Suhu Ruang Seasoning 3 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_3_actual'  => ['label' => 'Suhu Ruang Seasoning 3 Actual (°C)',  'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_4_setting' => ['label' => 'Suhu Ruang Seasoning 4 Setting (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_4_display' => ['label' => 'Suhu Ruang Seasoning 4 Display (°C)', 'type' => 'suhu_ruang'],
                'suhu_ruang_seasoning_4_actual'  => ['label' => 'Suhu Ruang Seasoning 4 Actual (°C)',  'type' => 'suhu_ruang'],
            ],
        ],

        /**
         * PRIORITY MEDIUM: Pemeriksaan Kebersihan & Loading
         */
        'pemeriksaan_kebersihan_areas' => [
            'label'      => 'Pemeriksaan Kebersihan Area',
            'main_table' => 'pemeriksaan_kebersihan_areas',
            'fields'     => [
                'field_status_sebelum' => [
                    'label'    => 'Status Kebersihan Sebelum Proses',
                    'type'     => 'status_checklist',
                    'is_array' => true,
                    // NOTE: Field ini ada di area_data[x]['fields'][y]['field_status_sebelum']
                    // Nested array: per area → per field dinamis dari master form
                ],
                'field_status_saat' => [
                    'label'    => 'Status Kebersihan Saat Proses',
                    'type'     => 'status_checklist',
                    'is_array' => true,
                    // NOTE: Field ini ada di area_data[x]['fields'][y]['field_status_saat']
                    // Nested array: per area → per field dinamis dari master form
                ],
                'field_verifikasi' => [
                    'label'    => 'Status Verifikasi',
                    'type'     => 'status_checklist',
                    'is_array' => true,
                    // NOTE: Field ini ada di area_data[x]['fields'][y]['field_verifikasi']
                ],
            ],
        ],

        'pemeriksaan_loading_produk' => [
            'label'      => 'Pemeriksaan Loading Produk',
            'main_table' => 'pemeriksaan_loading_produks',
            'dynamic'    => true, // temperature_produk adalah array JSON
            'fields'     => [
                'temperature_mobil' => ['label' => 'Temperature Mobil (°C)', 'type' => 'suhu'],
                'temperature_produk' => ['label' => 'Temperature Produk (Multiple) (°C)', 'type' => 'suhu', 'is_array' => true],
                // NOTE: temperature_produk adalah JSON array yang perlu custom handling:
                //  1. Form audit: loop array, render input per index
                //  2. generateAutoCorrection(): cek setiap nilai dalam array
                //  3. applyAutoCorrection(): update array berdasarkan index yang dikoreksi
                //  4. Flag 'is_array' => true menandakan ini field array JSON
            ],
        ],


        /**
         * PRIORITY LOW: Pemeriksaan Lainnya
         */
        'pemeriksaan_loading_kendaraan' => [
            'label'      => 'Pemeriksaan Loading Kendaraan',
            'main_table' => 'pemeriksaan_loading_kendaraans',
            'fields'     => [
                'suhu_precooling' => ['label' => 'Suhu Precooling (°C)', 'type' => 'suhu'],
                // kondisi_kebersihan_mobil & kondisi_mobil adalah JSON checklist, bukan field suhu
            ],
        ],

        'pemeriksaan_return_barang_customer' => [
            'label'      => 'Pemeriksaan Return Barang Customer',
            'main_table' => 'pemeriksaan_return_barang_customers',
            'fields'     => [
                // Field suhu
                'suhu_mobil' => ['label' => 'Suhu Mobil (°C)', 'type' => 'suhu'],
                
                // Field suhu produk (dalam produk_data[] array)
                'suhu_produk' => [
                    'label'    => 'Suhu Produk (°C)', 
                    'type'     => 'suhu_produk',
                    'is_array' => true,  // Flag: field ini ada di dalam produk_data[] JSON array
                    // NOTE: Perlu custom handling untuk audit array field ini
                    // Path: produk_data[x]['suhu_produk'] untuk setiap item produk
                ],
                
                // Field checklist
                'kondisi_fisik'   => ['label' => 'Kondisi Fisik',   'type' => 'status_checklist'],
                'kondisi_kemasan' => ['label' => 'Kondisi Kemasan', 'type' => 'status_checklist'],
            ],
        ],

    ],

    /**
     * Nilai fallback auto-correction jika tidak ada aturan di Master Standar Audit.
     * Semua tipe memakai struktur yang sama (min, max, target) agar akses
     * $defaults[$type]['min'] tidak memicu "Undefined array key".
     */
    'defaults' => [
        'suhu'             => ['min' => -20, 'max' => -15, 'target' => -18],
        'suhu_ruang'       => ['min' => -20, 'max' => -15, 'target' => -18],
        'suhu_kondisi'     => ['min' => 0,   'max' => 5,   'target' => 2],
        'suhu_pusat'       => ['min' => -18, 'max' => -12, 'target' => -15],
        'suhu_pemasakan'   => ['min' => 70,  'max' => 85,  'target' => 75],
        'status_checklist' => ['min' => null, 'max' => null, 'target' => 'OK'],
    ],
];