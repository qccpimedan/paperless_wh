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
                            <button type="button" class="btn btn-outline-primary role-btn"
                                data-role-id="{{ $role->id }}"
                                data-role-name="{{ $role->role }}">
                                {{ ucfirst(str_replace('_', ' ', $role->role)) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Module Selection Container --}}
    <div id="module-selection-container" style="display: none;">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pilih Module untuk Dikonfigurasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pilih Module:</label>
                            <select id="module-select" class="form-select form-select-lg">
                                <option value="">-- Pilih Module --</option>
                                <option value="all">📋 Lihat Semua Module</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Permissions Form Container --}}
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
                        <div id="module-permissions">
                            <!-- Module permissions will be loaded here -->
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

    let currentRoleId = null;
    let currentRoleName = null;

    // Handle role button click
    document.querySelectorAll('.role-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentRoleId = this.dataset.roleId;
            currentRoleName = this.dataset.roleName;
            
            // Update active button
            document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show module selection
            showModuleSelection(currentRoleId, currentRoleName);
        });
    });

    function showModuleSelection(roleId, roleName) {
        // Show module selection container
        document.getElementById('module-selection-container').style.display = 'block';
        document.getElementById('permissions-container').style.display = 'none';
        
        // Populate module dropdown
        let moduleOptions = '<option value="">-- Pilih Module --</option>';
        moduleOptions += '<option value="all">📋 Lihat Semua Module</option>';
        for (const [moduleKey, moduleName] of Object.entries(modules)) {
            moduleOptions += `<option value="${moduleKey}">${moduleName}</option>`;
        }
        document.getElementById('module-select').innerHTML = moduleOptions;
    }

    // Handle module selection
    document.getElementById('module-select').addEventListener('change', function() {
        const moduleKey = this.value;
        if (moduleKey === 'all') {
            loadAllModulePermissions(currentRoleId, currentRoleName);
        } else if (moduleKey) {
            loadModulePermissions(currentRoleId, currentRoleName, moduleKey);
        } else {
            document.getElementById('permissions-container').style.display = 'none';
        }
    });

    function loadAllModulePermissions(roleId, roleName) {
        // Show permissions container
        document.getElementById('permissions-container').style.display = 'block';
        document.getElementById('role-title').textContent = `Permissions untuk Role: ${roleName.toUpperCase()} - Semua Module`;
        
        // Update form action
        document.getElementById('permissions-form').action = `{{ url('/access-control') }}/${roleId}`;
        
        // Fetch current permissions for this role
        fetch(`{{ url('/access-control') }}/${roleId}/permissions`)
            .then(response => response.json())
            .then(data => {
                // Build HTML for all modules
                let allModulesHtml = '';
                
                for (const [moduleKey, moduleName] of Object.entries(modules)) {
                    allModulesHtml += `
                        <div class="col-md-6 mb-3">
                            <div class="card border-light">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">${moduleName}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                            id="view_${moduleKey}" name="permissions[]" value="${getPermissionId('view_' + moduleKey)}" data-module="${moduleKey}">
                                        <label class="form-check-label" for="view_${moduleKey}">
                                            👁️ View
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                            id="create_${moduleKey}" name="permissions[]" value="${getPermissionId('create_' + moduleKey)}" data-module="${moduleKey}">
                                        <label class="form-check-label" for="create_${moduleKey}">
                                            ➕ Create
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                            id="edit_${moduleKey}" name="permissions[]" value="${getPermissionId('edit_' + moduleKey)}" data-module="${moduleKey}">
                                        <label class="form-check-label" for="edit_${moduleKey}">
                                            ✏️ Edit
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                            id="delete_${moduleKey}" name="permissions[]" value="${getPermissionId('delete_' + moduleKey)}" data-module="${moduleKey}">
                                        <label class="form-check-label" for="delete_${moduleKey}">
                                            🗑️ Delete
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                // Wrap in row
                let modulePermissionsHtml = `<div class="row">${allModulesHtml}</div>`;
                document.getElementById('module-permissions').innerHTML = modulePermissionsHtml;
                
                // Check the checkboxes for current permissions
                data.permissions.forEach(permId => {
                    const checkbox = document.querySelector(`input[value="${permId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            });
    }

    function loadModulePermissions(roleId, roleName, moduleKey) {
        const moduleName = modules[moduleKey];
        
        // Show permissions container
        document.getElementById('permissions-container').style.display = 'block';
        document.getElementById('role-title').textContent = `Permissions untuk Role: ${roleName.toUpperCase()} - Module: ${moduleName}`;
        
        // Update form action
        document.getElementById('permissions-form').action = `{{ url('/access-control') }}/${roleId}`;
        
        // Fetch current permissions for this role
        fetch(`{{ url('/access-control') }}/${roleId}/permissions`)
            .then(response => response.json())
            .then(data => {
                // Build single module HTML with all permissions
                let moduleHtml = `
                    <div class="col-md-8">
                        <div class="card border-light">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">${moduleName}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                        id="view_${moduleKey}" name="permissions[]" value="${getPermissionId('view_' + moduleKey)}" data-module="${moduleKey}">
                                    <label class="form-check-label" for="view_${moduleKey}">
                                        👁️ View (Lihat Data)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                        id="create_${moduleKey}" name="permissions[]" value="${getPermissionId('create_' + moduleKey)}" data-module="${moduleKey}">
                                    <label class="form-check-label" for="create_${moduleKey}">
                                        ➕ Create (Buat Data Baru)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                        id="edit_${moduleKey}" name="permissions[]" value="${getPermissionId('edit_' + moduleKey)}" data-module="${moduleKey}">
                                    <label class="form-check-label" for="edit_${moduleKey}">
                                        ✏️ Edit (Ubah Data)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox module-permission" type="checkbox" 
                                        id="delete_${moduleKey}" name="permissions[]" value="${getPermissionId('delete_' + moduleKey)}" data-module="${moduleKey}">
                                    <label class="form-check-label" for="delete_${moduleKey}">
                                        🗑️ Delete (Hapus Data)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('module-permissions').innerHTML = moduleHtml;
                
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

// Handle form submission to ensure all permissions are sent
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('permissions-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Collect all checked permissions
            const checkedPermissions = [];
            document.querySelectorAll('input[name="permissions[]"]:checked').forEach(checkbox => {
                checkedPermissions.push(checkbox.value);
            });
            
            // If no permissions are checked, show warning
            if (checkedPermissions.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu permission untuk module ini');
                return false;
            }
            
            // Form will submit with all checked permissions
            return true;
        });
    }
});
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

.form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

#module-selection-container {
    animation: slideDown 0.3s ease-in-out;
}

#permissions-container {
    animation: slideDown 0.3s ease-in-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.module-permission {
    cursor: pointer;
}

.module-permission:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}
</style>
@endsection