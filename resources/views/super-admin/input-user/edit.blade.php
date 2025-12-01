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
                    <h3>Edit User</h3>
                    <p class="text-subtitle text-muted">Edit user yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
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
                            <h4 class="card-title">Form Edit User</h4>
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

                                <form class="form form-horizontal" action="{{ route('users.update', $user->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                                        name="name" placeholder="Nama Lengkap" value="{{ old('name', $user->name) }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="username">Username <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" id="username" class="form-control @error('username') is-invalid @enderror"
                                                        name="username" placeholder="Username" value="{{ old('username', $user->username) }}" required>
                                                    @error('username')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                                        name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="password">Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                                                <div class="form-group">
                                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                                        name="password" placeholder="Password Baru">
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="password_confirmation">Konfirmasi Password</label>
                                                <div class="form-group">
                                                    <input type="password" id="password_confirmation" class="form-control"
                                                        name="password_confirmation" placeholder="Konfirmasi Password Baru">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="id_role">Role <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select id="id_role" class="form-control @error('id_role') is-invalid @enderror" name="id_role" required>
                                                        <option value="">Pilih Role</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}" 
                                                                {{ (old('id_role', $user->id_role) == $role->id) ? 'selected' : '' }}>
                                                                {{ $role->role }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_role')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="id_plant">Plant <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select id="id_plant" class="form-control @error('id_plant') is-invalid @enderror" name="id_plant" required>
                                                        <option value="">Pilih Plant</option>
                                                        @foreach($plants as $plant)
                                                            <option value="{{ $plant->id }}" 
                                                                {{ (old('id_plant', $user->id_plant) == $plant->id) ? 'selected' : '' }}>
                                                                {{ $plant->plant }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_plant')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <a href="{{ route('users.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                                <button type="button" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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