@extends('layout.main')
@section('title', 'Academic Years')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Academic Year List</h3>
             @can('create academic-year')
            <a class="btn btn-light" href="{{ route('academic_year.create') }}">
                <i class="fas fa-plus"></i> Add New Academic Year
            </a>
              @endcan
        </div>

        <div class="col-md-12 mt-2">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S.N</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($academicYears as $academicYear)
                                <tr>
                                    <td>{{ $loop->iteration + ($academicYears->currentPage() - 1) * $academicYears->perPage() }}</td>
                                    <td>{{ $academicYear->name }}</td>
                                    <td>
                                        @if($academicYear->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $academicYear->created_at->format('Y-m-d') }}</td>
                                    <td>
                                         @can('edit academic-year')
                                        <a class="btn btn-primary btn-sm" href="{{ route('academic_year.edit', ['id' => $academicYear->id]) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                         @endcan
                                          @can('toggle academic-year')
                                        <a class="btn btn-info btn-sm" href="{{ route('academic_year.toggle-status', ['id' => $academicYear->id]) }}">
                                            <i class="fas fa-toggle-on"></i> Toggle
                                        </a>
                                          @endcan
                                          @can('delete academic-year')
                                        <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="confirmDelete('{{ route('academic_year.destroy', ['id' => $academicYear->id]) }}')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex">
                        {{ $academicYears->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
  function confirmDelete(url) {
      Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = url;
          }
      });
  }
</script>
@endpush

@endsection
