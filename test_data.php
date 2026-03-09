<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$produkList = \App\Models\Produk::query()->select(['id', 'nama_produk', 'kategori_code'])->orderBy('nama_produk')->get();
$produkByKategori = $produkList->groupBy('kategori_code')->map(function ($items) {
    return $items->map(function ($p) {
        return ['id' => $p->id, 'nama' => $p->nama_produk];
    })->values();
});

echo json_encode(array_keys($produkByKategori->toArray()));
