 <!-- partial:partials/_sidebar.html -->
 <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{route('dashboard')}}">
          <i class="icon-grid menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      @if (auth()->check() && auth()->user()->hasRole('admin'))
        
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
          <i class="icon-head menu-icon"></i>
          <span class="menu-title">Manage User</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link {{ request()->is('permission-list') ? 'text-danger' : '' }}" href="{{route('permission.list')}}">Permission </a></li>
            <li class="nav-item"> <a class="nav-link {{ request()->is('role-list') ? 'text-danger' : '' }}" href="{{route('role.list')}}"> Role </a></li>
            <li class="nav-item"> <a class="nav-link {{ request()->is('user-list') ? 'text-danger' : '' }}" href="{{route('user.list')}}"> User </a></li>
          </ul>
        </div>
      </li>
      @endif

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
          <i class="icon-columns menu-icon"></i>
          <span class="menu-title">Student</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
           
            <li class="nav-item"><a class="nav-link {{ request()->is('student-list') ? 'text-danger' : '' }}" href="{{route('student.list')}}">view student</a></li>
            {{-- <li class="nav-item"><a class="nav-link {{ request()->is('-project') ? 'text-danger' : '' }}" href="">Add Project</a></li> --}}

          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
          <i class="icon-bar-graph menu-icon"></i>
          <span class="menu-title">Attendance</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="charts">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link {{ request()->is('attendance-list') ? 'text-danger' : '' }}" href="{{route('attendance.list')}}">Attendance</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#study-materials" aria-expanded="false" aria-controls="study-materials">
          <i class="icon-book menu-icon"></i>
          <span class="menu-title">Study Materials</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="study-materials">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link {{ request()->is('program-list') ? 'text-danger' : '' }}" href="{{route('program.list')}}">Programs</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('academic-year-list') ? 'text-danger' : '' }}" href="{{route('academic_year.list')}}">Academic Years</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('semester-list') ? 'text-danger' : '' }}" href="{{route('semester.list')}}">Semesters</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('subject-list') ? 'text-danger' : '' }}" href="{{route('subject.list')}}">Subjects</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('document-list') ? 'text-danger' : '' }}" href="{{route('document.list')}}">Documents</a></li>
          </ul>
        </div>
      </li>

      <!-- @if (auth()->check() && (auth()->user()->can('view personal data') || auth()->user()->hasRole('admin'))) -->
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#personal-data" aria-expanded="false" aria-controls="personal-data">
          <i class="icon-user menu-icon"></i>
          <span class="menu-title">Personal Data</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="personal-data">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link {{ request()->is('personal-data-list') ? 'text-danger' : '' }}" href="{{route('personal-data.list')}}">Manage Personal Data</a></li>
          </ul>
        </div>
      </li>
      <!-- @endif -->

      



    </ul>
  </nav>