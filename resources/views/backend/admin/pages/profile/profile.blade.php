@extends('layout.main')
@section('title', 'Edit Profile')
@section('content')
<div class="container mt-5">
    <!-- Card with shadow for better visual appeal -->
    <div class="card shadow mb-4">
        <!-- Card Header with optional actions -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Update Profile</h3>
            <a class="btn btn-light btn-sm" href="{{ route('user.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Profile Update Form Start -->
        <div class="card-body">
            <form action="{{ route('profile.update', $datas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- User Name Field -->
                    <div class="mb-3 col-lg-6">
                        <label for="userName" class="form-label">User Name</label>
                        <input type="text" name="name" class="form-control" id="userName" 
                               placeholder="Enter the Name"
                               value="{{ old('name', $datas->name) }}" required>
                    </div>
                    <!-- User Email Field -->
                    <div class="mb-3 col-lg-6">
                        <label for="email" class="form-label">User Email</label>
                        <input type="email" name="email" class="form-control" id="email" 
                               placeholder="Enter the Email"
                               value="{{ old('email', $datas->email) }}" required>
                    </div>
                    <!-- User Address Field -->
                    <div class="mb-3 col-lg-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" id="address" 
                               placeholder="Enter the Address"
                               value="{{ old('address', $datas->address) }}" required>
                    </div>
                    <!-- User Phone Number Field -->
                    <div class="mb-3 col-lg-6">
                        <label for="number" class="form-label">Phone Number</label>
                        <input type="text" name="number" class="form-control" id="number" 
                               placeholder="Enter the Phone Number"
                               value="{{ old('number', $datas->number) }}" required>
                    </div>
                    <!-- User Subject Field -->
                    <!-- <div class="mb-3 col-lg-6">
                        <label for="Subject" class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" id="subject" 
                               placeholder="Enter the Subject"
                               value="{{ old('subject', $datas->subject) }}" required>
                    </div> -->

                    <!-- Profile Picture Update (Optional) -->
                    <div class="mb-3 col-lg-12">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control" id="profile_picture" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <!-- Image Preview (if existing profile picture) -->
                    <div class="mb-3 col-lg-12">
                        @if($datas->profile_picture)
                            <img id="imagePreview" src="{{ asset('laravel/public/profile_picture/' .  $datas->profile_picture) }}" alt="Profile Image" class="img-fluid img-thumbnail shadow-lg" style="max-width: 300px; border-radius: 10px; border: 3px solid #007bff;">
                        @else
                            No image
                        @endif
                    </div>
                </div>

                <!-- Submit Button for Profile Update -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card for Password Update -->
    <div class="card shadow">
        <!-- Card Header -->
        <div class="card-header bg-warning text-white">
            <h3 class="mb-0">Change Password</h3>
        </div>

        <!-- Password Update Form Start -->
        <div class="card-body">
            <form action="{{ route('profile_password_update', $datas->id) }}" method="POST">
                @csrf
                <div class="row">
                    <!-- New Password Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter the new password" required>
                    </div>

                    <!-- Confirm New Password Input -->
                    <div class="mb-3 col-lg-6">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Re-type the new password" required>
                    </div>
                </div>

                <!-- Submit Button for Password Update -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning btn-block">
                        <i class="fas fa-key"></i> Update Password
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
