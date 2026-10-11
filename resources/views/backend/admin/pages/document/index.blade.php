@extends('layout.main')
@section('title', 'Documents')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Document List</h3>
               @can('create document')
            <a class="btn btn-light" href="{{ route('document.create') }}">
                <i class="fas fa-plus"></i> Upload New Document
            </a>
             @endcan
        </div>

        <div class="col-md-12 mt-2">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('document.list') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="program_id" class="form-control" id="filter_program">
                                    <option value="">All Programs</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="academic_year_id" class="form-control" id="filter_academic_year">
                                    <option value="">All Years</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="semester_id" class="form-control" id="filter_semester">
                                    <option value="">All Semesters</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S.N</th>
                                    <th>Title</th>
                                    <th>Program</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>Subject</th>
                                    <th>File Type</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                <tr>
                                    <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->program->name ?? '-' }}</td>
                                    <td>{{ $document->academicYear->name ?? '-' }}</td>
                                    <td>{{ $document->semester->name ?? '-' }}</td>
                                    <td>{{ $document->subject->name ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ strtoupper($document->file_type) }}</span></td>
                                    <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                    <td>
                                        @if($document->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $document->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('document.view', ['id' => $document->id]) }}" target="_blank">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a class="btn btn-success btn-sm" href="{{ route('document.download', ['id' => $document->id]) }}">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                         @can('edit document')
                                        <a class="btn btn-primary btn-sm" href="{{ route('document.edit', ['id' => $document->id]) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                         @endcan
                                         @can('toggle document')
                                        <a class="btn btn-warning btn-sm" href="{{ route('document.toggle-status', ['id' => $document->id]) }}">
                                            <i class="fas fa-toggle-on"></i>
                                        </a>
                                         @endcan
                                         @can('delete document')
                                        <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="confirmDelete('{{ route('document.destroy', ['id' => $document->id]) }}')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                         @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex">
                        {{ $documents->links('pagination::bootstrap-5') }}
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
