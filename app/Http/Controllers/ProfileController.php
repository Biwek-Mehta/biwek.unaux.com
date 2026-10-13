<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit($id){
        $datas=User::where('id',$id)->get()->first();
        $admins = User::role('admin')->first();
        return view('backend.admin.pages.profile.profile', compact('datas', 'admins'));
    }

    
    public function update($id,Request $request){
        // dd( $request);
        try {
            $validated = $request->validate([
                'name' => 'required|min:4|max:100',
                'email' => 'required|email|unique:users,email,' . $id, // Allow the current email
               

                // 'role' => 'required',
                'address' => 'required',
                'number' => 'required|digits:10',
                // 'subject' => 'required',
               
            ]);
            //  dd( $request->subject);
            $datas = User::where('id', $id)->get()->first();
            $datas->name = $request->name;
            $datas->email = $request->email;
           
            $datas->address = $request->address;
            $datas->number = $request->number;
            // $datas->subject = $request->subject;
            if ($request->hasFile('profile_picture')) {

                $profile_picture = time() . '.' . $request->profile_picture->extension();
                $request->profile_picture->move(public_path('profile_picture'), $profile_picture);
                $datas->profile_picture = $profile_picture;
            }

            $datas->save();

            // $datas->syncRoles($request->role); // syncRoles accepts an array

            toastr()->success('Profile  Updated Successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
                'name' => 'required|min:4|max:100',
                'email' => 'required|email|unique:users,email,' . $id, // Allow the current email
              

                // 'role' => 'required',
                'address' => 'required',
                'number' => 'required|digits:10',
              
            ]))->withInput();
       
    }
}


public function profile_password_update(Request $request, $id)
{
    try {
        $validated = $request->validate([
           
            'password' => 'required|confirmed|min:6',

         ]);

    $user = User::find($id);
    $user->password = Hash::make($request->input('password'));
    $user->save();

   
    toastr()->success('Password  Updated Successfully');

    return redirect()->back();
        }
        catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
              
                'password' => 'required|confirmed|min:6',

            ]))->withInput();
       
    }
}
}
