@extends('layout.main')
@section('title', 'Add User')
@section('content')
<div class="container mt-5">
    <!-- Card for Role Creation -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Create User</h3>
            <a class="btn btn-light btn-sm" href="{{ route('user.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Form Start -->
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!--  Name Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="roleName" class="form-label">User Name</label>
                        <input type="text" name="name" class="form-control" id="userName" placeholder="Enter the Name" value="{{ old('name') }}" required>
                    </div>
                    <!--  Email Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="email" class="form-label"> Email</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Enter the Email" value="{{ old('email') }}" required>
                    </div>
                    <!--  Address Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="address" class="form-label">Address </label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Enter the Address" value="{{ old('address') }}" required>
                    </div>
                    <!--  Phone Number Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="number" class="form-label">Phone Number </label>
                        <input type="text" name="number" class="form-control" id="number" placeholder="Enter the Phone Number" value="{{ old('number') }}" required>
                    </div>
                    <!--   Role Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="number" class="form-label">Select Role </label>
                        <select name="role" id="" class="form-control">
                            <option value="">--Select Role--</option>
                            @foreach ($roles as $role)
                            <option value="{{$role->name}}">{{$role->name}}</option>
                                
                            @endforeach
                        </select>
                    </div>
                    <!--  Password Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="password" class="form-label"> Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter the password" value="{{ old('password') }}" required>
                    </div>
                    <!--  Confirm Password Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="conform-password" class="form-label"> Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter the Re-Type Password" value="{{ old('password_confirmation') }}" required>
                    </div>
                    
                    <!--  Profile Picture Input -->
                    <div class="mb-3 col-lg-12">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control" id="profile_picture" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <!-- Image Preview -->
                    <div class="mb-3 col-lg-12 text-center">
                        <img id="imagePreview" src="#" alt="Profile Image" class="img-fluid img-thumbnail shadow-lg" style="max-width: 300px; display: none; border-radius: 10px; border: 3px solid #007bff;">
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> Add User
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
