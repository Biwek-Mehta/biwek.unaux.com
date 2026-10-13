@extends('layout.main')
@section('title', 'Personal Data')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Personal Data Management</h3>
                    <a href="{{ route('personal-data.create') }}" class="btn btn-light btn-sm">Add New</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>File Name</th>
                                    <th>File Type</th>
                                    <th>File Size</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($personalData as $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $data->file_name }}</td>
                                    <td>{{ strtoupper($data->file_type) }}</td>
                                    <td>{{ number_format($data->file_size / 1024, 2) }} KB</td>
                                    <td>
                                        @if($data->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('personal-data.view', $data->id) }}" target="_blank" class="btn btn-sm btn-primary">View</a>
                                        <a href="{{ route('personal-data.download', $data->id) }}" class="btn btn-sm btn-success">Download</a>
                                        <a href="{{ route('personal-data.edit', $data->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        <a href="{{ route('personal-data.toggle-status', $data->id) }}" class="btn btn-sm btn-warning">Toggle</a>
                                        <form action="{{ route('personal-data.destroy', $data->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No personal data found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
