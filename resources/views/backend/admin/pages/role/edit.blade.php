@extends('layout.main')
@section('title', 'Edit Role')
@section('content')
<div class="container mt-5">
    <!-- Card with shadow for better visual appeal -->
    <div class="card shadow">
        <!-- Card Header with optional actions -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Update Role</h3>
            <a class="btn btn-light btn-sm" href="{{ route('role.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Form Start -->
        <div class="card-body">
            <form action="{{ route('role.update', $datas->id) }}" method="POST">
                @csrf

                <!-- Role Name Field -->
                <div class="mb-3">
                    <label for="roleName" class="form-label">Role Name</label>
                    <input type="text" name="name" class="form-control" id="roleName" 
                           placeholder="Enter the Role Name"
                           value="{{ old('name', $datas->name) }}" required>
                </div>

                <!-- Permissions List -->
                <div class="mb-3 col-lg-12">
                    <label for="permission" class="form-label">Assign Permissions</label>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="permission[]" 
                                        value="{{ $permission->name }}" 
                                        id="permission_{{ $permission->id }}"
                                        {{ $datas->permissions->contains($permission->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
