@extends('layouts.auth')
@section('container')
<style>
    /* Tambahkan CSS ini ke dalam file CSS Anda atau di dalam tag <style> */

#auth-right {
    position: relative;
    
    /* background-image: url('{{ asset('dist/images/logo/logo.jpg') }}'); */
    /* background-size: cover;
    background-position: center;
    background-repeat: no-repeat; */
    overflow: hidden;
}

/* Background Image Warehouse dengan Opacity */
#auth-right::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: inherit;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.25;
    z-index: 1;
}

/* Shadow Gradient Overlay - Efek seperti pada gambar referensi */
#auth-right::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        135deg,
        rgba(45, 79, 235, 0.88) 0%,
        rgba(143, 123, 163, 0.82) 50%,
        rgba(143, 118, 172, 0.88) 100%
    );
    box-shadow: inset -50px 0 100px rgba(0, 0, 0, 0.35);
    z-index: 2;
}

/* Optional: Tambahkan icon dekoratif warehouse (opsional) */
.warehouse-decorative-icons {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 3;
}

.warehouse-icon {
    position: absolute;
    color: rgba(255, 255, 255, 0.08);
    font-size: 120px;
}

.warehouse-icon:nth-child(1) {
    top: 10%;
    right: 15%;
    animation: float 6s ease-in-out infinite;
}

.warehouse-icon:nth-child(2) {
    bottom: 15%;
    right: 25%;
    animation: float 8s ease-in-out infinite 1s;
}

.warehouse-icon:nth-child(3) {
    top: 45%;
    right: 10%;
    animation: float 7s ease-in-out infinite 2s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.08;
    }
    50% {
        transform: translateY(-25px) rotate(3deg);
        opacity: 0.12;
    }
}
</style>
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <!-- <div class="auth-logo">
                <a href="{{ route('login') }}"><img src="{{ asset('dist/images/logo/logo.png') }}" alt="Logo"></a>
            </div> -->
            <h6 class="auth-title">Log in.</h6>

            <p class="auth-subtitle">Log in with your data that you entered during registration.</p>
            <!-- Button to trigger modal - Prominent position -->
            <button type="button" class="btn btn-info btn-sm mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#loginGuideModal">
                <i class="bi bi-info-circle me-2"></i>Baca Petunjuk Sebelum Login
            </button>


            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl @error('username') is-invalid @enderror" 
                           placeholder="Username" name="username" value="{{ old('username') }}" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" 
                           placeholder="Password" name="password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-gray-600" for="remember">
                        Keep me logged in
                    </label>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
            </form>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right" style="background-image: url('{{ asset('dist/images/logo/logo1.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loginGuideModal" tabindex="-1" aria-labelledby="loginGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginGuideModalLabel">
                    <i class="bi bi-info-circle me-2"></i>Petunjuk Login
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">Sebelum melakukan login, perhatikan hal-hal berikut:</h6>
                
                <!-- Petunjuk Umum Login -->
                <div class="mb-4">
                    <h6 class="text-primary mb-2">
                        <i class="bi bi-shield-lock me-2"></i>Petunjuk Umum Login
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Pastikan Anda telah memiliki akun yang terdaftar dalam sistem
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Masukkan username dan password yang benar
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Perhatikan penggunaan huruf besar/kecil pada password
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Jika lupa password, hubungi administrator sistem
                        </li>
                    </ul>
                </div>

                <!-- Petunjuk Role dan Peran -->
                <div class="mb-4">
                    <h6 class="text-primary mb-2">
                        <i class="bi bi-people me-2"></i>Role dan Peran Sistem
                    </h6>
                    <div class="alert alert-light border-start border-primary border-4">
                        <div class="mb-3">
                            <strong class="text-info">
                                <i class="bi bi-person-badge me-2"></i>QC
                            </strong>
                            <p class="mb-0 ms-4">Hanya bertugas untuk mengisi form yang telah disediakan</p>
                        </div>
                        <div class="mb-3">
                            <strong class="text-warning">
                                <i class="bi bi-person-badge me-2"></i>Produksi
                            </strong>
                            <p class="mb-0 ms-4">Hanya bertugas untuk verifikasi awal dari form yang telah diisi QC</p>
                        </div>
                        <div class="mb-0">
                            <strong class="text-success">
                                <i class="bi bi-person-badge me-2"></i>SPV QC
                            </strong>
                            <p class="mb-0 ms-4">Bertugas untuk melakukan verifikasi lanjutan dari form yang telah diisi oleh QC dan disetujui oleh produksi</p>
                        </div>
                    </div>
                </div>

                <!-- Keamanan -->
                <div class="mb-3">
                    <h6 class="text-danger mb-2">
                        <i class="bi bi-shield-exclamation me-2"></i>Keamanan
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-shield-check text-warning me-2"></i>
                            Jangan memberikan informasi login Anda kepada orang lain
                        </li>
                    </ul>
                </div>

               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection