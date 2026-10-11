<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class StudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view student',only: ['index']),
            new Middleware('permission:edit student', only: ['edit']),
            new Middleware('permission:create student',  only: ['create']),
            new Middleware('permission:delete student',  only:['destroy']),
        ];

    }


    public function index(Request $request)
    {
      
        $query = Student::query();
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
                  ->orWhere('address', 'like', '%' . $search . '%')
                //   ->orWhere('url', 'like', '%' . $search . '%')
                  ;
            });
        }
       
        if (auth()->user()->hasRole('admin')) {
           $users=User::all();
        }
        if ($request->has('user_id')) {
            $user = $request->input('user_id');
            // dd($user);
            $query->where('auth_user_id',$user)->get();
            
        }
    
        // Paginate the filtered results
        $datas = $query->paginate(8);
        // dd( $datas);
        
    
        return view('backend.admin.pages.student.list', compact('datas','users'));
    }
    
    
    
    public function create(){
        return view('backend.admin.pages.student.add');

    }
    public function store(Request $request){
      
       try {
        $validated = $request->validate([
            'student_name' => 'required',
            'role_number' => 'required',
            'address' => 'required',
            'studentImage' => 'required',
           
        ]);

        $datas=new Student;
       $datas->auth_user_id=$request->auth_user_id;
       $datas->student_name=$request->student_name;
       $datas->role_number=$request->role_number;
       $datas->address=$request->address;

       $studentImage = time() . '.' . $request->studentImage->extension();
       $request->studentImage->move(public_path('studentImage'), $studentImage);
       $datas->studentImage = $studentImage;
       $datas->save();
       toastr()->success('Data Store Successfully');
       return redirect()->back();

    } catch (\Exception $e) {
        toastr()->error($e->getMessage());
        return redirect()->back()->withErrors($request->validate([
            'studentImage' => 'required',
            'address' => 'required',
            'role_number' => 'required',
            'student_name' => 'required',
        ]))->withInput();
    }

    }
    public function edit($id)
{
    // Find the project by ID
    $data = Student::findOrFail($id);
    
    // Return the edit view with the project data
    return view('backend.admin.pages.student.edit', compact('data'));
}

public function update(Request $request, $id)
{
    // Validate incoming request
    $request->validate([
        'student_name' => 'required',
            'role_number' => 'required',
            'address' => 'required',
            // 'studentImage' => 'required',
    ]);

    try {
        // Find the project by ID
        $data = Student::findOrFail($id);
        
        $data->student_name=$request->student_name;
        $data->role_number=$request->role_number;
        $data->address=$request->address;

        // If a new image is uploaded, handle the image upload
        if ($request->hasFile('studentImage')) {
            // Delete the old image if necessary
            if ($data->studentImage && file_exists(public_path('studentImage/' . $data->studentImage))) {
                unlink(public_path('studentImage/' . $data->studentImage));
            }

            // Store the new image
            $studentImage = time() . '.' . $request->studentImage->extension();
            $request->studentImage->move(public_path('studentImage'), $studentImage);
            $data->studentImage = $studentImage;
        }

        // Save the updated project
        $data->save();

        toastr()->success('Data updated successfully');
        return redirect()->back(); // Redirect to the project list

    } catch (\Exception $e) {
        toastr()->error('Error updating data: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }
}



    public function destroy(string $id)
    {
        $datas = Student::find($id)->delete();
        toastr()->success('Data  Deleted Successfully');
        return redirect()->back();
    }
}
