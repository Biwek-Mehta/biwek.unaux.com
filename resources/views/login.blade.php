<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Study Materials Portal - Login</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .login-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      overflow: hidden;
      max-width: 1000px;
      width: 100%;
      margin: 20px;
    }
    .login-image {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: white;
      position: relative;
      overflow: hidden;
    }
    .login-image::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    .login-image-content {
      position: relative;
      z-index: 1;
    }
    .login-form {
      padding: 60px 50px;
    }
    .login-logo {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .login-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 30px;
    }
    .feature-item {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      padding: 15px;
      background: rgba(255,255,255,0.1);
      border-radius: 10px;
      backdrop-filter: blur(10px);
    }
    .feature-icon {
      width: 50px;
      height: 50px;
      background: rgba(255,255,255,0.2);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 1.3rem;
    }
    .feature-text h4 {
      font-size: 1rem;
      margin: 0 0 5px 0;
      font-weight: 600;
    }
    .feature-text p {
      font-size: 0.85rem;
      margin: 0;
      opacity: 0.8;
    }
    .form-control {
      padding: 15px 20px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .input-group-text {
      background: #f8f9fa;
      border: 2px solid #e0e0e0;
      border-left: none;
      border-radius: 0 10px 10px 0;
      padding: 15px 20px;
    }
    .input-group .form-control {
      border-right: none;
      border-radius: 10px 0 0 10px;
    }
    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 15px 30px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }
    .form-check-input:checked {
      background-color: #667eea;
      border-color: #667eea;
    }
    .login-title {
      font-size: 2rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
    }
    .login-desc {
      color: #666;
      margin-bottom: 30px;
    }
    .floating-shapes {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }
    .shape {
      position: absolute;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
    }
    .shape-1 {
      width: 100px;
      height: 100px;
      top: 10%;
      left: 10%;
      animation: float 6s ease-in-out infinite;
    }
    .shape-2 {
      width: 150px;
      height: 150px;
      bottom: 20%;
      right: 10%;
      animation: float 8s ease-in-out infinite reverse;
    }
    .shape-3 {
      width: 80px;
      height: 80px;
      top: 60%;
      left: 30%;
      animation: float 7s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }
    @media (max-width: 768px) {
      .login-image {
        display: none;
      }
      .login-form {
        padding: 40px 30px;
      }
    }
  </style>
</head>
<body>
<div class="login-container">
  <div class="row g-0">
    <!-- Left Side - Image & Features -->
    <div class="col-lg-6">
      <div class="login-image">
        <div class="floating-shapes">
          <div class="shape shape-1"></div>
          <div class="shape shape-2"></div>
          <div class="shape shape-3"></div>
        </div>
        <div class="login-image-content">
          <div class="login-logo">
            <i class="fas fa-graduation-cap"></i> Study Portal
          </div>
          <p class="login-subtitle">Your Gateway to Academic Excellence</p>

          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-book"></i>
            </div>
            <div class="feature-text">
              <h4>Study Materials</h4>
              <p>Access comprehensive academic resources</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-folder-open"></i>
            </div>
            <div class="feature-text">
              <h4>Organized Content</h4>
              <p>Browse by program, year & semester</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-download"></i>
            </div>
            <div class="feature-text">
              <h4>Easy Downloads</h4>
              <p>Get documents in multiple formats</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <div class="feature-text">
              <h4>Secure Access</h4>
              <p>Admin-managed content system</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="col-lg-6">
      <div class="login-form">
        <h2 class="login-title">Welcome Back!</h2>
        <p class="login-desc">Sign in to access your study materials</p>

        @if(session('loginerror'))
        <div class="alert alert-danger" role="alert">
          {{session('loginerror')}}
        </div>
        @endif

        <form action="{{route('login-check')}}" method="post" class="loginform_to_local">
          @csrf

          <div class="mb-4">
            <label class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
              <input type="text" name="email" class="form-control emailStoreInLocal" placeholder="Enter your email" required>
              <div class="input-group-text">
                <i class="fas fa-envelope text-muted"></i>
              </div>
            </div>
            @error('email') <div class="text-danger mt-1">{{$message}}</div> @enderror
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control passwordStoreInLocal login_field__password1" placeholder="Enter your password" required>
              <div class="input-group-text" style="cursor: pointer;" id="login-toggle-password1">
                <i class="fas fa-eye text-muted"></i>
              </div>
            </div>
            @error('password') <div class="text-danger mt-1">{{$message}}</div> @enderror
          </div>

          <div class="mb-4">
            <div class="form-check">
              <input type="checkbox" id="remember" class="form-check-input remember_mee">
              <label for="remember" class="form-check-label">
                Remember Me
              </label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fas fa-sign-in-alt me-2"></i> Sign In
          </button>

          <div class="text-center">
            <a href="{{route('goto-reset-password')}}" class="text-decoration-none">Forgot Password?</a>
          </div>

          <hr class="my-4">

          <div class="text-center">
            <p class="mb-2">Don't have an account?</p>
            <a href="{{route('goto-register-page')}}" class="btn btn-outline-primary">
              <i class="fas fa-user-plus me-2"></i> Register New User
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>

<!-- Boostrap script -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<!-- Boostrap script -->
{{-- toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    @if(Session::has('success'))
    toastr.success("{{session('success')}}")
    @endif
    @if(Session::has('forgot_success'))
    toastr.success("{{session('forgot_success')}}")
    @endif


</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    
  const emailInput = document.querySelector(".emailStoreInLocal");
  const passwordInput = document.querySelector(".passwordStoreInLocal");
  const rememberMeCheckbox = document.querySelector(".remember_mee");
  const loginForm = document.querySelector(".loginform_to_local"); // Use the correct form class


      // Load saved credentials from localStorage
      const savedEmail = localStorage.getItem("emailStoreInLocal");
      const savedPassword = localStorage.getItem("passwordStoreInLocal");
      // console.log(savedPassword);

      // If saved email exists, set it as the value for email input
      if (savedEmail) {
          emailInput.value = savedEmail;
      }

      // If saved password exists, set it as the value for password input
      if (savedPassword) {
          passwordInput.value = savedPassword;
      }

      // If both email and password are saved, check the 'Remember Me' checkbox
      if (savedEmail && savedPassword) {
          rememberMeCheckbox.checked = true;
      }
       // Check the "Remember Me" status (1 if checked, 0 if not checked)
      //   console.log(rememberMeCheckbox.checked);

      // Save credentials to localStorage on form submit
      loginForm.addEventListener("submit", function (event) {
          if (rememberMeCheckbox.checked) {
            
              localStorage.setItem("emailStoreInLocal", emailInput.value);
              localStorage.setItem("passwordStoreInLocal", passwordInput.value); // Warning: Avoid storing password in plain text
          } else {
              localStorage.removeItem("emailStoreInLocal");
              localStorage.removeItem("passwordStoreInLocal");
          }
      });
    
  });
</script>

<script>
    
  // Password Toggle Button
  document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.getElementById('login-toggle-password1'); // Password view button
  const passwordField = document.querySelector('.login_field__password1'); // Select by class
  
  togglePassword.addEventListener('click', function () {
      // alert(passwordField);
      // Toggle the password field type
      if (passwordField.type === "password") {
          passwordField.type = "text"; // Show the password
          togglePassword.classList.remove('fa-eye-slash'); // Remove 'hidden' icon
          togglePassword.classList.add('fa-eye'); // Add 'visible' icon
      } else {
          passwordField.type = "password"; // Hide the password
          togglePassword.classList.remove('fa-eye'); // Remove 'visible' icon
          togglePassword.classList.add('fa-eye-slash'); // Add 'hidden' icon
      }
  });
});


</script>
</body>
</html>
