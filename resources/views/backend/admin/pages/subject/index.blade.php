@extends('layout.main')
@section('title', 'Subjects')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Subject List</h3>
             @can('create subject')
            <a class="btn btn-light" href="{{ route('subject.create') }}">
                <i class="fas fa-plus"></i> Add New Subject
            </a>
             @endcan
        </div>

        <div class="col-md-12 mt-2">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('subject.list') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="program_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Programs</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="academic_year_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Years</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="semester_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Semesters</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('subject.list') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S.N</th>
                                    <th>Program</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>Subject Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $subject)
                                <tr>
                                    <td>{{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}</td>
                                    <td>{{ $subject->program->name ?? '-' }}</td>
                                    <td>{{ $subject->academicYear->name ?? '-' }}</td>
                                    <td>{{ $subject->semester->name ?? '-' }}</td>
                                    <td>{{ $subject->name }}</td>
                                    <td>
                                        @if($subject->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                           @can('edit subject')
                                        <a class="btn btn-primary btn-sm" href="{{ route('subject.edit', ['id' => $subject->id]) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                         @endcan
                                             @can('toggle subject')
                                        <a class="btn btn-info btn-sm" href="{{ route('subject.toggle-status', ['id' => $subject->id]) }}">
                                            <i class="fas fa-toggle-on"></i> Toggle
                                        </a>
                                         @endcan
                                           @can('delete subject')
                                        <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="confirmDelete('{{ route('subject.destroy', ['id' => $subject->id]) }}')">
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
                        {{ $subjects->links('pagination::bootstrap-5') }}
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
