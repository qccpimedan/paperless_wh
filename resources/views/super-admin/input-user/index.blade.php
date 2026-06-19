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
                    <h3>Data User</h3>
                    <p class="text-subtitle text-muted">Kelola data user sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data User</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar User</h5>
                    @can('create_users')
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah User
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari Nama, Username, atau Email..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-center" style="white-space:nowrap;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Plant</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + ($index + 1) }}</td>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $user->username }}</span>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role)
                                                @php $roleSlug = strtolower($user->role->role); @endphp
                                                @if($roleSlug === 'manager')
                                                    <span class="badge" style="background: linear-gradient(135deg, #6f42c1, #5a289e); color:#fff;">
                                                        <i class="bi bi-building me-1"></i>{{ $user->role->role }}
                                                    </span>
                                                @elseif($roleSlug === 'superadmin')
                                                    <span class="badge bg-danger">{{ $user->role->role }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ $user->role->role }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">No Role</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->plant)
                                                <span class="badge bg-primary">{{ $user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-vertical">
                                                @can('edit_users')
                                                    <a href="{{ route('users.edit', $user->uuid) }}" 
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit User">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan

                                                {{-- Tombol Assign Plant: hanya untuk role Manager --}}
                                                @if($user->role && strtolower($user->role->role) === 'manager')
                                                @can('edit_users')
                                                    <a href="{{ route('users.assign-plants', $user->uuid) }}"
                                                       class="btn btn-sm"
                                                       title="Assign Plant Akses"
                                                       style="background: linear-gradient(135deg, #6f42c1, #5a289e); color:#fff; border:none;">
                                                        <i class="bi bi-building"></i>
                                                    </a>
                                                @endcan
                                                @endif

                                                @can('delete_users')
                                                    <form action="{{ route('users.destroy', $user->uuid) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus User">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2">Belum ada data user</p>
                                                @can('create_users')
                                                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                                                        <i class="bi bi-plus-circle"></i> Tambah User Pertama
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection