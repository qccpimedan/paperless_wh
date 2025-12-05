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
                    <h3>🔐 Access Control Management</h3>
                    <p class="text-subtitle text-muted">Kelola permissions untuk setiap role</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Access Control</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Error!</h4>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pilih Role untuk Dikonfigurasi</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        @foreach ($roles as $role)
                            <button type="button" class="btn btn-outline-primary role-btn" data-role-id="{{ $role->id }}" data-role-name="{{ $role->role }}">
                                {{ ucfirst(str_replace('_', ' ', $role->role)) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="permissions-container" style="display: none;">
        <section class="section">
            <form id="permissions-form" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0" id="role-title">Permissions untuk Role</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="modules-list">
                            <!-- Modules will be loaded here -->
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modules = @json($modules);
    const allPermissions = @json($permissions);
    const permissionMap = {};
    
    // Create permission ID mapping from all permissions
    @php
        $allPerms = \Spatie\Permission\Models\Permission::all();
    @endphp
    @foreach($allPerms as $permission)
        permissionMap['{{ $permission->name }}'] = {{ $permission->id }};
    @endforeach

    // Handle role button click
    document.querySelectorAll('.role-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const roleId = this.dataset.roleId;
            const roleName = this.dataset.roleName;
            
            // Update active button
            document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Load permissions for this role
            loadPermissionsForRole(roleId, roleName);
        });
    });

    function loadPermissionsForRole(roleId, roleName) {
        // Show permissions container
        document.getElementById('permissions-container').style.display = 'block';
        document.getElementById('role-title').textContent = `Permissions untuk Role: ${roleName.toUpperCase()}`;
        
        // Update form action
        document.getElementById('permissions-form').action = `/access-control/${roleId}`;
        
        // Build modules HTML
        let modulesHtml = '';
        
        for (const [moduleKey, moduleName] of Object.entries(modules)) {
            modulesHtml += `
                <div class="col-md-6 mb-4">
                    <div class="card border-light">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">${moduleName}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" 
                                    id="view_${moduleKey}" name="permissions[]" value="${getPermissionId('view_' + moduleKey)}">
                                <label class="form-check-label" for="view_${moduleKey}">
                                    👁️ View (Lihat Data)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" 
                                    id="create_${moduleKey}" name="permissions[]" value="${getPermissionId('create_' + moduleKey)}">
                                <label class="form-check-label" for="create_${moduleKey}">
                                    ➕ Create (Buat Data Baru)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" 
                                    id="edit_${moduleKey}" name="permissions[]" value="${getPermissionId('edit_' + moduleKey)}">
                                <label class="form-check-label" for="edit_${moduleKey}">
                                    ✏️ Edit (Ubah Data)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" 
                                    id="delete_${moduleKey}" name="permissions[]" value="${getPermissionId('delete_' + moduleKey)}">
                                <label class="form-check-label" for="delete_${moduleKey}">
                                    🗑️ Delete (Hapus Data)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        document.getElementById('modules-list').innerHTML = modulesHtml;
        
        // Fetch current permissions for this role
        fetch(`/access-control/${roleId}/permissions`)
            .then(response => response.json())
            .then(data => {
                // Check the checkboxes for current permissions
                data.permissions.forEach(permId => {
                    const checkbox = document.querySelector(`input[value="${permId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            });
    }

    function getPermissionId(permissionName) {
        // Get permission ID from mapping
        return permissionMap[permissionName] || '';
    }
});

function resetForm() {
    document.getElementById('permissions-form').reset();
}
</script>

<style>
.role-btn {
    margin: 5px;
}

.role-btn.active {
    background-color: #0d6efd;
    color: white;
}

.permission-checkbox {
    margin-right: 10px;
}

.form-check {
    margin-bottom: 12px;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.form-check:hover {
    background-color: #f8f9fa;
}
</style>
@endsection