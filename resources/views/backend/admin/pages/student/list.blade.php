@extends('layout.main')
@section('title', 'View student')
@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Card Header with optional actions -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">student List</h3>
            <a class="btn btn-light" href="{{ route('student.create') }}">
                <i class="fas fa-plus"></i> Add New student
            </a>
        </div>

        <div class="col-md-4 mt-2 ms-auto">
            <form action="{{ route('student.list') }}" method="get">
                @csrf
                <div class="input-group mb-3">
                    <select name="user_id" class="form-control"  >
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" >
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                   
                    <button class="btn btn-primary" type="submit">Search</button>  
                </div>
               
            </form>
        </div>
        <!-- Search Form -->
        <div class="col-md-4 mt-2 ms-auto">
            <form action="{{ route('student.list') }}" method="get">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search " value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>  
                    <a class="btn btn-secondary ml-2" href="{{route('student.list')}}">Reset</a>
                </div>
            </form>
        </div>
      
       

        <!-- Role Management Table -->
        <div class="col-md-12 mt-2">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Make table responsive -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S.N</th>
                                    <th>Image</th>
                                    <th>student Name</th>
                                    <th>Roll Number</th>
                                    <th>address</th>
                                   
                                  
                                    <th>Created_at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                          <tbody>
                                @foreach ($datas as $data)
                                <tr>
                                    <td scope="row">{{ $loop->iteration + ($datas->currentPage() - 1) * $datas->perPage() }}</td>
                                    <td class="align-middle">
                                        <img src="{{ asset('laravel/public/studentImage/' . $data->studentImage) }}" 
                                             style="width: 200px; height: 200px; object-fit: cover; border-radius: 5px;">
                                    </td>
                                    
                                    <td class="align-middle">{{ $data->student_name }}</td>
                                    <td class="align-middle">{{ $data->role_number }}</td>
                                    <td class="align-middle">{{ $data->address }}</td>
                                   
                                    <td class="align-middle">{{ $data->created_at->diffForHumans() }}</td>
                                    <td class="align-middle">
                                        @can('edit student')
                                            
                                        <a class="btn btn-primary" href="{{ route('student.edit', ['id' => $data->id]) }}">
                                            edit
                                        </a>
                                        @endcan
                                        @can('delete student')
                                            
                                        <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete('{{ route('student.destroy', ['id' => $data->id]) }}')" data-bs-toggle="tooltip" title="Delete Permission">
                                            delete
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <div class="d-flex ">
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
