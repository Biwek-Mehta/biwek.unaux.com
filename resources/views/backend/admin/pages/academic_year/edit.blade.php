@extends('layout.main')
@section('title', 'Edit Academic Year')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Edit Academic Year</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('academic_year.update', ['id' => $academicYear->id]) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Academic Year Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $academicYear->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ $academicYear->status ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$academicYear->status ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('academic_year.list') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
