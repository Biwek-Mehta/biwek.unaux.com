<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller
{
    // Middleware handled by AdminMiddleware in routes
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = User::query();

    // Exclude the authenticated user
    $query->where('id', '!=', auth()->id());

    // Search by name, email, address, or number
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('address', 'like', '%' . $search . '%')
              ->orWhere('number', 'like', '%' . $search . '%');
        });
    }

    $datas = $query->paginate(8);

    return view('backend.admin.pages.user.list', compact('datas'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('backend.admin.pages.user.add', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        try {
            $validated = $request->validate([
                'name' => 'required|min:4|max:100',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
                'role' => 'required',
                'address' => 'required',
                'number' => 'required|digits:10|unique:users',
                'profile_picture' => 'required',
            ]);

            $datas = new User;
            $datas->name = $request->name;
            $datas->email = $request->email;
            $datas->password = Hash::make($request->password);
            $datas->address = $request->address;
            $datas->number = $request->number;

            $profile_picture = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('profile_picture'), $profile_picture);
            $datas->profile_picture = $profile_picture;

            $datas->save();

            $datas->syncRoles($request->role); // syncRoles accepts an array

            toastr()->success('User Register Successful');
            return redirect()->back();

            // toastr()->success('User Register Successful');
            // return redirect()->back();

            // return $this->assignRole($request->email,$request->role);


        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
                'name' => 'required|min:4|max:100',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
                'role' => 'required',
                'address' => 'required',
                'number' => 'required|digits:10|unique:users',
                'profile_picture' => 'required',
            ]))->withInput();
        }
    }
    // public function assignRole($request){
    //     $user = User::where('email', $request)->first();

    //     // $role=Role::where('name','user')->get();
    //      $user->roles()->attach($request->role);
    //     //  dd($user->roles);
    //      toastr()->success('User Register Successful');
    //      return redirect()->back();


    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $datas = User::where('id', $id)->get()->first();
        $roles = Role::all();
        // Collect only the role IDs that the user has
        $hasRoles = $datas->roles->pluck('id')->toArray();

        return view('backend.admin.pages.user.edit', compact('datas', 'roles', 'hasRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd( $request);
        try {
            $validated = $request->validate([
                'name' => 'required|min:4|max:100',
                'email' => 'required|email|unique:users,email,' . $id, // Allow the current email
               

                'role' => 'required',
                'address' => 'required',
                'number' => 'required|digits:10',
              
            ]);
            $datas = User::where('id', $id)->get()->first();
            $datas->name = $request->name;
            $datas->email = $request->email;
           
            $datas->address = $request->address;
            $datas->number = $request->number;
            if ($request->hasFile('profile_picture')) {

                $profile_picture = time() . '.' . $request->profile_picture->extension();
                $request->profile_picture->move(public_path('profile_picture'), $profile_picture);
                $datas->profile_picture = $profile_picture;
            }


            $datas->save();

            $datas->syncRoles($request->role); // syncRoles accepts an array

            toastr()->success('User  Updated Successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
                'name' => 'required|min:4|max:100',
                'email' => 'required|email|unique:users,email,' . $id, // Allow the current email
               

                'role' => 'required',
                'address' => 'required',
                'number' => 'required|digits:10',
               
            ]))->withInput();
        }
    }
    public function user_change_password(Request $request, string $id)
    {
       
        try {
            $validated = $request->validate([
              
                'password' => 'required|confirmed|min:6',

               
            ]);
            $datas = User::where('id', $id)->get()->first();
           
           
            $datas->password = Hash::make($request->password);
          

            $datas->save();

        

            toastr()->success('Password  Updated Successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
               
                'password' => 'required|confirmed|min:6',

               
            ]))->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $datas = User::find($id)->delete();
        toastr()->success('User  Deleted Successfully');
        return redirect()->back();
    }
}
