@extends('layout.main')
@section('title', 'Add Permission')

@section('content')
<div class="container mt-5">
    
       
            <!-- Card Header with optional actions -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Create Permission</h3>
                    <a class="btn btn-light btn-sm" href="{{ route('permission.list') }}">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <!-- Form Start -->
                <div class="card-body">
                    <form action="{{ route('permission.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="permissionName" class="form-label">Permission Name</label>
                            <input type="text" name="name" class="form-control" id="permissionName" placeholder="Enter the Permission Name" value="{{ old('name') }}" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Add Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
       
  
</div>
@endsection
