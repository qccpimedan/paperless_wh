@extends('layouts.app')
@section('container')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Input Pemeriksaan Loading Kendaraan</h3>
                    <p class="text-subtitle text-muted">Tambah pemeriksaan loading kendaraan baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-kendaraan.index') }}">Pemeriksaan Loading Kendaraan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Pemeriksaan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Input Pemeriksaan Loading Kendaraan</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-loading-kendaraan.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                                                                        <!-- Kondisi Kebersihan Mobil -->
                                            <div class="col-md-6">
                                                <label><strong>Kondisi Kebersihan Mobil <span class="text-danger">*</span></strong></label>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>1. Berdebu, Kondensasi</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[berdebu]" id="berdebu_ya" value="1" {{ old('kondisi_kebersihan_mobil.berdebu') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="berdebu_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[berdebu]" id="berdebu_tidak" value="0" {{ old('kondisi_kebersihan_mobil.berdebu') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="berdebu_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>2. Noda (Karat, cat, tinta, oli, Asap Kendaraan), Sampah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[noda]" id="noda_ya" value="1" {{ old('kondisi_kebersihan_mobil.noda') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="noda_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[noda]" id="noda_tidak" value="0" {{ old('kondisi_kebersihan_mobil.noda') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="noda_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>3. Terdapat Pertumbuhan Mikroorganisme (Jamur, Bau Busuk, Bau Menyimpang)</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[mikroorganisme]" id="mikroorganisme_ya" value="1" {{ old('kondisi_kebersihan_mobil.mikroorganisme') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="mikroorganisme_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[mikroorganisme]" id="mikroorganisme_tidak" value="0" {{ old('kondisi_kebersihan_mobil.mikroorganisme') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="mikroorganisme_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Pallet, Pintu, Langit-langit, Dinding Kotor</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[pallet_kotor]" id="pallet_ya" value="1" {{ old('kondisi_kebersihan_mobil.pallet_kotor') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[pallet_kotor]" id="pallet_tidak" value="0" {{ old('kondisi_kebersihan_mobil.pallet_kotor') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>5. Terdapat Aktivitas Binatang (Tikus, Kecoa, Lalat, Belatung, Hama)</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[aktivitas_binatang]" id="binatang_ya" value="1" {{ old('kondisi_kebersihan_mobil.aktivitas_binatang') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="binatang_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[aktivitas_binatang]" id="binatang_tidak" value="0" {{ old('kondisi_kebersihan_mobil.aktivitas_binatang') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="binatang_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kondisi Mobil -->
                                            <div class="col-md-6">
                                                <label><strong>Kondisi Mobil <span class="text-danger">*</span></strong></label>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>1. Kaca Mobil Pecah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[kaca_pecah]" id="kaca_ya" value="1" {{ old('kondisi_mobil.kaca_pecah') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kaca_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[kaca_pecah]" id="kaca_tidak" value="0" {{ old('kondisi_mobil.kaca_pecah') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kaca_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>2. Dinding Mobil Rusak (Pecah)/Langit-langit Rusak/Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[dinding_rusak]" id="dinding_ya" value="1" {{ old('kondisi_mobil.dinding_rusak') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dinding_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[dinding_rusak]" id="dinding_tidak" value="0" {{ old('kondisi_mobil.dinding_rusak') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dinding_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>3. Lampu Dalam Box Pecah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_pecah]" id="lampu_ya" value="1" {{ old('kondisi_mobil.lampu_pecah') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_pecah]" id="lampu_tidak" value="0" {{ old('kondisi_mobil.lampu_pecah') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Karet Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[karet_pintu_rusak]" id="karet_ya" value="1" {{ old('kondisi_mobil.karet_pintu_rusak') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="karet_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[karet_pintu_rusak]" id="karet_tidak" value="0" {{ old('kondisi_mobil.karet_pintu_rusak') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="karet_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>5. Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pintu_rusak]" id="pintu_rusak_ya" value="1" {{ old('kondisi_mobil.pintu_rusak') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pintu_rusak_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pintu_rusak]" id="pintu_rusak_tidak" value="0" {{ old('kondisi_mobil.pintu_rusak') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pintu_rusak_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>6. Seal Tidak Utuh</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[seal_tidak_utuh]" id="seal_ya" value="1" {{ old('kondisi_mobil.seal_tidak_utuh') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="seal_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[seal_tidak_utuh]" id="seal_tidak" value="0" {{ old('kondisi_mobil.seal_tidak_utuh') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="seal_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>7. Terdapat Celah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[terdapat_celah]" id="celah_ya" value="1" {{ old('kondisi_mobil.terdapat_celah') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="celah_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[terdapat_celah]" id="celah_tidak" value="0" {{ old('kondisi_mobil.terdapat_celah') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="celah_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tanggal -->
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Ekspedisi -->
                                            <div class="col-md-6">
                                                <label for="id_ekspedisi">Ekspedisi <span class="text-danger">*</span></label>
                                                <select id="id_ekspedisi" class="choices form-select @error('id_ekspedisi') is-invalid @enderror"
                                                    name="id_ekspedisi" required>
                                                    <option value="">-- Pilih Ekspedisi --</option>
                                                    @foreach($ekspedisis as $ekspedisi)
                                                        <option value="{{ $ekspedisi->id }}" {{ old('id_ekspedisi') == $ekspedisi->id ? 'selected' : '' }}>
                                                            {{ $ekspedisi->nama_ekspedisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_ekspedisi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Kendaraan -->
                                            <div class="col-md-6">
                                                <label for="id_kendaraan">Jenis & No Kendaraan</label>
                                                    <select id="id_kendaraan" class="choices form-select @error('id_kendaraan') is-invalid @enderror" name="id_kendaraan">
                                                        <option value="">-- Pilih Kendaraan --</option>
                                                        @foreach($kendaraans as $kendaraan)
                                                            <option value="{{ $kendaraan->id }}" {{ old('id_kendaraan') == $kendaraan->id ? 'selected' : '' }}>
                                                                {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}
                                                            </option>
                                                        @endforeach
                                                        <option value="other" {{ old('id_kendaraan') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                    </select>
                                                    @error('id_kendaraan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <!-- Tujuan Pengiriman -->
                                            <div class="col-md-6">
                                                <label for="id_tujuan_pengiriman">Tujuan Pengiriman <span class="text-danger">*</span></label>
                                                <select id="id_tujuan_pengiriman" class="choices form-select @error('id_tujuan_pengiriman') is-invalid @enderror"
                                                    name="id_tujuan_pengiriman" required>
                                                    <option value="">-- Pilih Tujuan --</option>
                                                    @foreach($tujuanPengirimens as $tujuan)
                                                        <option value="{{ $tujuan->id }}" {{ old('id_tujuan_pengiriman') == $tujuan->id ? 'selected' : '' }}>
                                                            {{ $tujuan->nama_tujuan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_tujuan_pengiriman')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <!-- Shift -->
                                            <div class="col-md-6">
                                                <label for="id_shift">Shift</label>
                                                <select id="id_shift" class="form-select @error('id_shift') is-invalid @enderror"
                                                    name="id_shift">
                                                    <option value="">-- Pilih Shift --</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>
                                                            {{ $shift->shift }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_shift')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <!-- Std Precooling -->
                                            <div class="col-md-6">
                                                <label for="id_std_precooling">Std Precooling <span class="text-danger">*</span></label>
                                                <select id="id_std_precooling" class="choices form-select @error('id_std_precooling') is-invalid @enderror"
                                                    name="id_std_precooling" required>
                                                    <option value="">-- Pilih Std Precooling --</option>
                                                    @foreach($stdPrecoolings as $std)
                                                        <option value="{{ $std->id }}" {{ old('id_std_precooling') == $std->id ? 'selected' : '' }}>
                                                            {{ $std->nama_std_precooling }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_std_precooling')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Jam Mulai -->
                                            <div class="col-md-6">
                                                <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                                <input type="time" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                                                    name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                                                @error('jam_mulai')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Jam Selesai -->
                                            <div class="col-md-6">
                                                <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                                                <input type="time" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror"
                                                    name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                                                @error('jam_selesai')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Suhu Precooling -->
                                            <div class="col-md-6">
                                                <label for="suhu_precooling">Suhu Precooling (°C) <span class="text-danger">*</span></label>
                                                <input type="text" id="suhu_precooling" class="form-control @error('suhu_precooling') is-invalid @enderror"
                                                    name="suhu_precooling" placeholder="Contoh: -18°C" value="{{ old('suhu_precooling') }}" required>
                                                @error('suhu_precooling')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <br>
                                            
                                            <!-- Keterangan -->
                                            <div class="col-md-12">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                name="keterangan" rows="3" placeholder="Masukkan keterangan tambahan">{{ old('keterangan') }}</textarea>
                                                @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Buttons -->
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <a href="{{ route('pemeriksaan-loading-kendaraan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection