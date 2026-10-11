@extends('layout.main')
@section('title', 'Add Student')
@section('content')
<div class="container mt-5">
    <!-- Card for Role Creation -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Create student</h3>
            <a class="btn btn-light btn-sm" href="{{ route('student.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Form Start -->
        <div class="card-body">
            <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="auth_user_id" value="{{auth()->user()->id}}">
                <div class="row">
                    <!--  Name Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="student_name" class="form-label">student Name</label>
                        <input type="text" name="student_name" class="form-control" id="student_name" placeholder="Enter the student Name" value="{{ old('student_name') }}" required>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="role_number" class="form-label">Roll Number</label>
                        <input type="number" name="role_number" class="form-control" id="role_number" placeholder="Enter the Role Number" value="{{ old('role_number') }}" required>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Enter the Address" value="{{ old('address') }}" required>
                    </div>
                   
                   
                   
                   
                   
                    <!--  Profile Picture Input -->
                    <div class="mb-3 col-lg-12">
                        <label for="studentImage" class="form-label">Profile Picture</label>
                        <input type="file" name="studentImage" class="form-control" id="studentImage" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <!-- Image Preview -->
                    <div class="mb-3 col-lg-12 text-center">
                        <img id="imagePreview" src="#" alt="Profile Image" class="img-fluid img-thumbnail shadow-lg" style="max-width: 300px; display: none; border-radius: 10px; border: 3px solid #007bff;">
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Image Preview -->
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
