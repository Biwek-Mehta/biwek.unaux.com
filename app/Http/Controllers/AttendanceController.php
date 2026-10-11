<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AttendanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view attendance',only: ['index']),
            new Middleware('permission:edit attendance', only: ['edit']),
            new Middleware('permission:create attendance',  only: ['create']),
            new Middleware('permission:delete attendance',  only:['delete']),
        ];

    }

    public function index(Request $request){

        $query = Attendance::query();
        // Check if the authenticated user is an admin
        if (!auth()->user()->hasRole('admin')) {
            // Filter by authenticated user's ID for non-admin users
            $query->where('auth_user_id', auth()->id());
        }
  
      // Search by projectName, details, or url
      if ($request->has('search')) {
          $search = $request->input('search');
          $query->where(function ($q) use ($search) {
              $q->where('student_name', 'like', '%' . $search . '%')
                ->orWhere('date', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ;
          });
      }
      // Order by descending `created_at` column
    $query->orderBy('created_at', 'desc');
  
      // Paginate the filtered results
      $datas = $query->paginate(10);
        
        return view('backend.admin.pages.attendance.list',compact('datas'));

    }
    public function create(){

        $query = Student::query();
        if (!auth()->user()->hasRole('admin')) {
            // Filter by authenticated user's ID for non-admin users
            $query->where('auth_user_id', auth()->id());
        }


        $students=$query->get();
        // Get today's attendance record for the current user
        $today = now()->format('Y-m-d'); // Get today's date in Y-m-d format
        $already_attendance = Attendance::where('auth_user_id', auth()->user()->id)
                                            ->whereDate('date', $today) // Check if attendance exists for today
                                            ->first();
        return view('backend.admin.pages.attendance.add',compact('students','already_attendance'));

    }
    public function store(Request $request)
    {
        // Validate the request
        // $validatedData = $request->validate([
        //     'attendance' => 'required|array',
        //     'attendance.*.status' => 'required|string|in:Present,Absent,Late',
        //     'date' => 'required|date',
        // ]);
        // dd($request->all());
    
        // Loop through attendance and store data
        foreach ($request['attendance'] as $student_name => $attendance) {
            Attendance::create([
                'auth_user_id' => $request->auth_user_id,
                'student_name' => $student_name,
                'status' => $attendance['status'],
                'date' => $request->date,
            ]);
        }
    
        toastr()->success('Attendance recorded successfully!');
        return redirect('/attendance-list');
    }

    public function edit($id)
{
    $attendance = Attendance::where('id',$id)->get()->first(); // Fetch attendance record
    // dd($attendance);
    $students = Student::where('student_name',$attendance->student_name)->first(); // Fetch all students (if needed for dropdowns)

    return view('backend.admin.pages.attendance.edit', compact('attendance', 'students'));
}


public function update(Request $request, $id)
{
    
    $validated = $request->validate([
        'status' => 'required',
        'date' => 'required',
    ]);

    $attendance = Attendance::findOrFail($id);
    $attendance->update([
        'status' => $validated['status'],
        'date' => $validated['date'],
    ]);

    toastr()->success('Attendance updated successfully!');
    return redirect('/attendance-list');
}








    public function delete($id){

        $query = Attendance::where('id',$id)->delete();
       
        toastr()->success('Attendance Deleted successfully!');
        return redirect()->back();

    }


}
