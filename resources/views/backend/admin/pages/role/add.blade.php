@extends('layout.main')
@section('title', 'Add Role')
@section('content')
<div class="container mt-5">
    <!-- Card for Role Creation -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Create Role</h3>
            <a class="btn btn-light btn-sm" href="{{ route('role.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Form Start -->
        <div class="card-body">
            <form action="{{ route('role.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Role Name Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="roleName" class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" id="roleName" placeholder="Enter the Role Name" value="{{ old('name') }}" required>
                    </div>

                    <!-- Permissions List -->
                    <div class="mb-3 col-lg-12">
                        <label for="permission" class="form-label">Assign Permissions</label>
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}">
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> Add Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
