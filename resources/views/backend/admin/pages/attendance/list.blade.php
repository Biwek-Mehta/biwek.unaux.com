@extends('layout.main')
@section('title', 'View Attendance')
@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Card Header with optional actions -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Attendance List</h3>
            <a class="btn btn-light" href="{{ route('attendance.create') }}">
                <i class="fas fa-plus"></i> Add New Attendance
            </a>
        </div>

        <!-- Search Form -->
        <div class="col-md-4 mt-2 ms-auto">
            <form action="{{ route('attendance.list') }}" method="get">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name Date or Status" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>  
                    <a class="btn btn-secondary ml-2" href="{{route('attendance.list')}}">Reset</a>
                </div>
            </form>
        </div>

        <!-- Role Management Table -->
        <div class="col-md-12 mt-2">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Add table-responsive wrapper -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S.N</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>status</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $data)
                                    <tr>
                                        <td scope="row">{{ $loop->iteration + ($datas->currentPage() - 1) * $datas->perPage() }}</td>
                                        <td class="align-middle">{{ $data->student_name }}</td>
                                        <td class="align-middle">  {{ \Carbon\Carbon::parse($data->date)->isToday() ? 'Today' : $data->date }}
                                            
                                        </td>
                                        <td class="align-middle">{{ $data->status }}</td>
                                        <td class="align-middle">
                                            @can('edit attendance')
                                            <a class="btn btn-primary btn-sm" href="{{ route('attendance.edit', ['id' => $data->id]) }}">
                                                <i class="fas fa-edit"></i> 
                                            </a>
                                            @endcan
                                            @can('delete attendance')
                                            <a class="btn btn-danger btn-sm" href="javascript:void(0);" 
                                               onclick="confirmDelete('{{ route('attendance.destroy', ['id' => $data->id]) }}')"
                                               data-bs-toggle="tooltip" title="Delete Attendance">
                                                <i class="fas fa-trash-alt"></i> 
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No attendance records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                        </table>
                    </div> <!-- End of table-responsive -->
                    
                    <!-- Pagination Links -->
                    <div class="d-flex">
                        {{ $datas->links('pagination::bootstrap-5') }}
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
