<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\StudyMaterialController;


// clear project
Route::get('clear', function () {
    // Artisan::call('storage:link');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    // Artisan::call('clear-compiled');
    // Artisan::call('optimize:clear');
    toastr()->success('Project refreshed successfully.');
   
    return redirect('/');
   
});



// For Logout 
Route::get('/logout', function () {
    Auth::logout();
    Session::flush();
     // Set a success toast, with a title
     toastr()->success('Logout Successful !');
   
    return redirect('/');
})->name('logout');



Route::get('/',[AuthController::class,'loadLogin'])->name('loadLogin');
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/login',[AuthController::class,'login_check'])->name('login-check');
Route::get('/register',[AuthController::class,'register'])->name('goto-register-page');
Route::post('/register',[AuthController::class,'store_register'])->name('store.register');
Route::get('/reset-password',[AuthController::class,'reset_password'])->name('goto-reset-password');
Route::post('/request-new-password',[AuthController::class,'request_new_password'])->name('request.new.password');
Route::post('/verifiedOtp',[AuthController::class,'verifiedOtp'])->name('verifiedOtp');
Route::get('/resendOtp',[AuthController::class,'resendOtp'])->name('resendOtp');
Route::get('/update_password/{id}',[AuthController::class,'update_password']);
Route::post('/password_updated/{id}',[AuthController::class,'password_updated']);






// *********************** For Admin************************

Route::group(['middleware' => 'AdminMiddleware'], function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // user
    Route::any('/user-list', [UserController::class, 'index'])->name('user.list');
    Route::get('/user-create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user-store', [UserController::class, 'store'])->name('user.store');
     Route::get('/user-destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/user-edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user-update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('/user-user_change_password/{id}', [UserController::class, 'user_change_password'])->name('user_change_password');
    
    // roles
    Route::any('/role-list', [RoleController::class, 'index'])->name('role.list');
    Route::get('/role-create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role-store', [RoleController::class, 'store'])->name('role.store');
     Route::get('/role-destroy/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
    Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::post('/role-update/{id}', [RoleController::class, 'update'])->name('role.update');

    // permission
    
    Route::any('/permission-list', [PermissionController::class, 'index'])->name('permission.list');
    Route::get('/permission-create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('/permission-store', [PermissionController::class, 'store'])->name('permission.store');
     Route::get('/permission-destroy/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');
    Route::get('/permission-edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::post('/permission-update/{id}', [PermissionController::class, 'update'])->name('permission.update');

    // profile Update
    Route::get('/profile-edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile-edit/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile_password_update/{id}', [ProfileController::class, 'profile_password_update'])->name('profile_password_update');
    
    //  projects
    Route::get('/student-list', [StudentController::class, 'index'])->name('student.list');
    Route::get('/student-create', [StudentController::class, 'create'])->name('student.create');
    Route::post('/student-store', [StudentController::class, 'store'])->name('student.store');
    Route::get('/student-edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::post('/student-update/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::get('/student-delete/{id}', [StudentController::class, 'destroy'])->name('student.destroy');


// AttendanceController
    Route::get('/attendance-list', [AttendanceController::class, 'index'])->name('attendance.list');
    Route::get('/attendance-create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance-store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance-edit/{id}', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::post('/attendance-update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::get('/attendance-delete/{id}', [AttendanceController::class, 'delete'])->name('attendance.destroy');

    // Program Management
    Route::get('/program-list', [ProgramController::class, 'index'])->name('program.list');
    Route::get('/program-create', [ProgramController::class, 'create'])->name('program.create');
    Route::post('/program-store', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/program-edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
    Route::post('/program-update/{id}', [ProgramController::class, 'update'])->name('program.update');
    Route::get('/program-delete/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');
    Route::get('/program-toggle-status/{id}', [ProgramController::class, 'toggleStatus'])->name('program.toggle-status');

    // Academic Year Management
    Route::get('/academic-year-list', [AcademicYearController::class, 'index'])->name('academic_year.list');
    Route::get('/academic-year-create', [AcademicYearController::class, 'create'])->name('academic_year.create');
    Route::post('/academic-year-store', [AcademicYearController::class, 'store'])->name('academic_year.store');
    Route::get('/academic-year-edit/{id}', [AcademicYearController::class, 'edit'])->name('academic_year.edit');
    Route::post('/academic-year-update/{id}', [AcademicYearController::class, 'update'])->name('academic_year.update');
    Route::get('/academic-year-delete/{id}', [AcademicYearController::class, 'destroy'])->name('academic_year.destroy');
    Route::get('/academic-year-toggle-status/{id}', [AcademicYearController::class, 'toggleStatus'])->name('academic_year.toggle-status');

    // Semester Management
    Route::get('/semester-list', [SemesterController::class, 'index'])->name('semester.list');
    Route::get('/semester-create', [SemesterController::class, 'create'])->name('semester.create');
    Route::post('/semester-store', [SemesterController::class, 'store'])->name('semester.store');
    Route::get('/semester-edit/{id}', [SemesterController::class, 'edit'])->name('semester.edit');
    Route::post('/semester-update/{id}', [SemesterController::class, 'update'])->name('semester.update');
    Route::get('/semester-delete/{id}', [SemesterController::class, 'destroy'])->name('semester.destroy');
    Route::get('/semester-toggle-status/{id}', [SemesterController::class, 'toggleStatus'])->name('semester.toggle-status');

    // Subject Management
    Route::get('/subject-list', [SubjectController::class, 'index'])->name('subject.list');
    Route::get('/subject-create', [SubjectController::class, 'create'])->name('subject.create');
    Route::post('/subject-store', [SubjectController::class, 'store'])->name('subject.store');
    Route::get('/subject-edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');
    Route::post('/subject-update/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::get('/subject-delete/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');
    Route::get('/subject-toggle-status/{id}', [SubjectController::class, 'toggleStatus'])->name('subject.toggle-status');

    // Document Management
    Route::get('/document-list', [DocumentController::class, 'index'])->name('document.list');
    Route::get('/document-create', [DocumentController::class, 'create'])->name('document.create');
    Route::post('/document-store', [DocumentController::class, 'store'])->name('document.store');
    Route::get('/document-edit/{id}', [DocumentController::class, 'edit'])->name('document.edit');
    Route::post('/document-update/{id}', [DocumentController::class, 'update'])->name('document.update');
    Route::get('/document-delete/{id}', [DocumentController::class, 'destroy'])->name('document.destroy');
    Route::get('/document-view/{id}', [DocumentController::class, 'view'])->name('document.view');
    Route::get('/document-download/{id}', [DocumentController::class, 'download'])->name('document.download');
    Route::get('/document-toggle-status/{id}', [DocumentController::class, 'toggleStatus'])->name('document.toggle-status');
    Route::get('/api/subjects', [DocumentController::class, 'getSubjectsByFilters'])->name('document.get-subjects');

});

// Frontend Study Materials
Route::get('/study-materials', [StudyMaterialController::class, 'index'])->name('study-materials.index');
Route::get('/study-materials/view/{id}', [StudyMaterialController::class, 'view'])->name('study-materials.view');
Route::get('/study-materials/download/{id}', [StudyMaterialController::class, 'download'])->name('study-materials.download');
Route::get('/api/frontend/subjects', [StudyMaterialController::class, 'getSubjectsByFilters'])->name('study-materials.get-subjects');



