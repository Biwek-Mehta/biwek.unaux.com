@extends('layout.main')
@section('title', 'Edit student')
@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Edit student</h3>
            <a class="btn btn-light btn-sm" href="{{ route('student.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('student.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
               
                
                <div class="row">
                    <!--  Name Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="student_name" class="form-label">student Name</label>
                        <input type="text" name="student_name" class="form-control" id="student_name" placeholder="Enter the student Name" value="{{ old('student_name',$data->student_name) }}" required>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="role_number" class="form-label">Roll Number</label>
                        <input type="number" name="role_number" class="form-control" id="role_number" placeholder="Enter the Role Number" value="{{ old('role_number',$data->role_number) }}" required>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Enter the Address" value="{{ old('address',$data->address) }}" required>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label for="studentImage" class="form-label">student Image (optional)</label>
                        <input type="file" name="studentImage" class="form-control" id="studentImage" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="mb-3 col-lg-12 text-left">
                        @if($data->studentImage)
                            <img id="imagePreview" src="{{ asset('laravel/public/studentImage/' . $data->studentImage) }}" alt="student Image" class="img-fluid img-thumbnail shadow-lg" style="max-width: 300px;">
                        @else
                            <img id="imagePreview" src="#" alt="student Image" class="img-fluid img-thumbnail shadow-lg" style="max-width: 300px; display: none;">
                        @endif
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
