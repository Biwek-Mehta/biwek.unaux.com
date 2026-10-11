<?php

namespace App\Http\Controllers\Admin;

use Hash;
use App\Models\City;
// use App\Models\Role;
use App\Models\Role;
use App\Models\User;
use App\Models\RtoRule;
use App\Models\BaseFare;
use App\Models\Complain;
use App\Models\Feedback;
use App\Models\Permission;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Models\ImportantNumber;
use App\Models\AboutApplication;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard(){
    
            
        return view('backend.admin.dashboard');
    }

    public function add_role(Request $request){


        return view('backend.admin.pages.role.add_role');
    }
    public function added_role(Request $request){

        $role=new Role;
        $role->name=$request->name;
        $role->slug=$request->slug;
        $role->save();
        toastr()->success('Role Added Successful');
        return redirect()->back();
    }
    public function view_role(Request $request){

        $roles=Role::all();


        return view('backend.admin.pages.role.view_role',compact('roles'));
    }
    public function delete_role($id){

        $roles=Role::find($id)->delete();



        toastr()->success('Role  Deleted Successfully');
        return redirect()->back();
    }
    public function assign_role(){
        $roles=Role::all();
        $users=User::all();
        return view('admin.backend.assign_role',compact('users','roles'));
    }
    public function assigned_role($id, Request $request){
        $user = User::findOrFail($id); // Assuming $id is the user ID

    // Detach existing roles
    $user->roles()->detach();

    // Find the new role
    $role = Role::where('slug', $request->change_role)->firstOrFail();

    // Attach the new role
    $user->roles()->attach($role);

    toastr()->success('Role Assign Successful');
    return redirect()->back();
    }
    public function view_user(){
        $datas=User::all();
        return view('admin.backend.view_user',compact('datas'));
    }
    public function add_user(){

        return view('admin.backend.add_user');
    }
    public function added_user(Request $request){

        try {
            $validated = $request->validate([
                'username'=>'required|min:4|max:100',
                'email'=>'required|unique:users',
                'password'=>'required|confirmed|min:6',
            ]);

            $datas=new User;
            $datas->name=$request->username;
            $datas->email=$request->email;
            $datas->password=Hash::make($request->password);

            $datas->save();
            return $this->assignRole($request->email);


        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
            return redirect()->back()->withErrors($request->validate([
                'username'=>'required|min:4|max:100',
                'email'=>'required|unique:users',
                'password'=>'required|confirmed|min:6',
            ]))->withInput();
        }
    }

    public function assignRole($request){
        $user = User::where('email', $request)->first();

        $role=Role::where('slug','subadmin')->get();
         $user->roles()->attach($role);
        //  dd($user->roles);
         toastr()->success('User Register Successful');
         return redirect()->back();


    }
    public function delete_user($id){
        $datas=User::find($id)->delete();
        toastr()->success('User Delete Successful');
        return redirect()->back();
    }
    public function edit_user($id){
        $user=User::where('id',$id)->get()->first();

        return view('admin.backend.edit_user',compact('user'));
    }
    public function edited_user($id, Request $request){
        try {
            $validated = $request->validate([
                'username'=>'required|min:4|max:100',
                'email'=>"required|unique:users,email,{$id}",
                'password'=>'required|confirmed|min:6',
            ]);

            $user = User::findOrFail($id);
            $user->name = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->save();

            toastr()->success('User Updated Successful');
            return redirect(route('admin.view.user'));

        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
            return redirect()->back();
        }
    }




    public function permission_user($id){

        $user=User::where('id',$id)->get()->first();
        $permissions = Permission::all();



        return view('admin.backend.permission_user',compact('user','permissions'));
    }
    public function permission_added($id,Request $request){
        try {
            $user = User::findOrFail($id);
              // Detach existing roles
            $user->permissions()->detach();

            // Get permission IDs from the request
            $permissionSlugs = $request->input('permission', []);

            // Find permission models by slug
            $permissions = Permission::whereIn('slug', $permissionSlugs)->get();

            // Attach permissions to the user
            $user->permissions()->attach($permissions);
            // dd($user->permissions);

            // Redirect back to permission management page or any other page as needed
            toastr()->success('Permission Added Successful');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Permission  Not Found','Error!');
            return redirect()->back();
        }


    }


    public function permission_view(){
        $datas=Permission::all();

        return view('admin.backend.permission_view',compact('datas'));
    }

    public function permission_add(){


        return view('admin.backend.permission_add');
    }
    public function permission_adds(Request $request){
        try {
            $validated = $request->validate([
                'name'=>'required',
                'slug'=>'required',
            ]);

            $datas=new Permission;
            $datas->name=$request->name;
            $datas->slug=$request->slug;

            $datas->save();
            toastr()->success('Permission  add success','Success!');
            return redirect(route( 'admin.view.permission'));


        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
            return redirect()->back()->withErrors($request->validate([
                'name'=>'required',
                'slug'=>'required',
            ]))->withInput();
        }



    }
    public function permission_delete($id){


        $datas=Permission::find($id)->delete();
        toastr()->success('Permission  Delete Success','Success!');
        return redirect()->back();
    }
    public function view_city(){
        $datas=City::all();
        return view('admin.backend.city.view_city',compact('datas'));
    }
   public function profile(){
          $user=auth()->user();

     return view('admin.backend.profiles.profile',compact('user'));




    }

   public function profile_update($id, Request $request){
        try {
            $validated = $request->validate([
                'username'=>'required|min:4|max:100',
                'email'=>"required|unique:users,email,{$id}",
                'password'=>'required|confirmed|min:6',
            ]);

            $user = User::findOrFail($id);
            $user->name = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->save();

            toastr()->success('Profile Updated Successful');
           return redirect()->back();

        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
          return redirect()->back();


        }
    }





    public function add_city(){
        return view('admin.backend.city.add_city');
    }

    public function added_city(Request $request){
        try {
            $validated = $request->validate([
                'name'=>'required',

            ]);

            $datas=new City;
            $datas->city=$request->name;


            $datas->save();
            toastr()->success('City  add success','Success!');
            return redirect(route( 'admin.view.city'));


        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
            return redirect()->back()->withErrors($request->validate([
                'name'=>'required',

            ]))->withInput();
        }




    }

    public function delete_city($id){
        $data=City::findOrFail($id);
        if (!is_null($data)){
            $data->delete();
            toastr()->success('City Delete success','Success!');
            return  redirect()->back();
        }else{
            toastr()->Error('Not Found','Error!');
            return  redirect()->back();
        }



    }
    public function edit_city($id){
        $datas=City::where('id',$id)->get()->first();
        return view('admin.backend.city.edit',compact('datas'));


    }
    public function edited_city($id,Request $request){
        try {
            $validated = $request->validate([
                'name'=>'required',

            ]);

            $datas=City::where('id',$id)->get()->first();
            $datas->city=$request->name;


            $datas->save();
            toastr()->success('Update successfull','Success!');
            return redirect()->back();
            // return redirect(route( 'admin.view.rto.rules'));


        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
            return redirect()->back()->withErrors($request->validate([
                'name'=>'required',
            ]))->withInput();
        }




    }


    public function view_vehicle(){
        $datas=VehicleType::all();

        return view('admin.backend.vehicle.view_vehicle',compact('datas'));



    }
    public function add_vehicle(){
        $citys = City::get();
        return view('admin.backend.vehicle.add_vehicle',compact('citys'));



    }
    public function added_vehicle(Request $request){

        try {
            $validated = $request->validate([
                'name'=>'required',
                'city'=>'required'

            ]);

            $datas=new VehicleType;
            $datas->vehicleName=$request->name;
            $datas->allocated_city=$request->city;

            $datas->save();
            toastr()->success('Vehicle  add success','Success!');
            return redirect()->route('admin.view.vehicle');


        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
            return redirect()->back()->withErrors($request->validate([
                'name'=>'required',

            ]))->withInput();
        }



    }


    public function delete_vehicle($id){

        $data=VehicleType::findOrFail($id);
        if (!is_null($data)){
            $data->delete();
            toastr()->success('Vehicle Delete success','Success!');
            return  redirect()->back();
        }else{
            toastr()->Error('Not Found','Error!');
            return  redirect()->back();
        }



    }
    public function edit_vehicle($id){
        $datas=VehicleType::where('id',$id)->first();
        $citys = City::get();
        return view('admin.backend.vehicle.edit',compact('datas','citys'));

    }
    public function edited_vehicle($id,Request  $request){
        try {
            $validated = $request->validate([
                'name'=>'required',
                'city'=>'required'

            ]);

            $datas = VehicleType::find($id);
            $datas->vehicleName = $request->name;
            $datas->allocated_city = $request->city;
            $datas->update();
            toastr()->success(' Update success','Success!');
            return redirect()->route('admin.view.vehicle');
            // return redirect(route( 'admin.view.rto.rules'));


        } catch (\Exception $e) {
            toastr()->error($e->getMessage(), 'Error');
            return redirect()->back()->withErrors($request->validate([
                'name'=>'required',

            ]))->withInput();
        }




    }

    public function base_fare(){

        $datas=BaseFare::all();

        return view('admin.backend.base_fare.view',compact('datas'));
  }

  public function add_base_fare(){
    $citys=City::all();
    $vehicles=VehicleType::all();

    return view('admin.backend.base_fare.add',compact( 'citys','vehicles'));

}
public function added_base_fare(request $request) {
    // dd($request);
    try {
        // Handle file upload

        $image_url = '';
        if ($file = $request->file('vehicleImage')) {
            // If an image file is uploaded
            $image_name = md5(rand(1000, 10000));
            $ext = strtolower($file->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $upload_path = 'vehicleImage/';
            $image_url = $upload_path . $image_full_name;
            $file->move($upload_path, $image_full_name);
        }
        // Create BaseFare
        BaseFare::create([
            'city' => $request->city,
            'vehicle' => $request->vehicle,
            'base_fare' => $request->base_fare_rate,
            'distance_for_applicable_base_fare' => $request->aaplicatble_distance_for_base_fare, // Fixed typo here
            'hike_per_km' => $request->hike_rate_per_km,
            'hike_shown_after_distance_in_meter' => $request->hike_shown_distance_meter,
            'mid_night_start_timing' => $request->mid_night_start_timing,
            'mid_night_end_timing' => $request->mid_night_end_timing,
            'mid_night_fare' => $request->mid_night_fare,
            'vehicleImage' => $image_url,
        ]);

        toastr()->success('Base Fare added successfully', 'Success!');
        return redirect()->route('admin.view.base.fare');
    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back();
    }
}

public function delete_base_fare($id){
    $data=BaseFare::findOrFail($id);
    if (!is_null($data)){
        $data->delete();
        toastr()->success('Base Fare Delete success','Success!');
        return  redirect()->back();
    }else{
        toastr()->Error('Not Found','Error!');
        return  redirect()->back();
    }

}
public function edit_base_fare($id){
    $citys=City::all();
    $vehicles=VehicleType::all();
    $datas=BaseFare::where('id',$id)->get()->first();

    return view('admin.backend.base_fare.edit',compact( 'citys','vehicles','datas'));


}
public function edited_base_fare($id, Request $request) {
    try {
        $validated = $request->validate([
            'city' => 'required',
            'vehicle' => 'required',
            'base_fare_rate' => 'required',
            'aaplicatble_distance_for_base_fare' => 'required',
            'hike_rate_per_km' => 'required',
            'hike_shown_distance_meter' => 'required',
            'mid_night_start_timing' => 'required',
            'mid_night_end_timing' => 'required',
            'mid_night_fare' => 'required',

        ]);

        $datas = BaseFare::findOrFail($id); // Use findOrFail to throw an exception if not found

        if ($file = $request->vehicleImage) {
            // If an image file is uploaded

            $image_name = md5(rand(1000, 10000));
            $ext = strtolower($file->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $upload_path = 'vehicleImage/';
            $image_url = $upload_path . $image_full_name;
            $file->move(public_path($upload_path), $image_full_name);  // Corrected to use public_path

            // Save image URL to database
            $datas->vehicleImage = $image_url;
        }

        $datas->city = $request->city;
        $datas->vehicle = $request->vehicle;
        $datas->base_fare = $request->base_fare_rate;
        $datas->distance_for_applicable_base_fare = $request->aaplicatble_distance_for_base_fare;
        $datas->hike_per_km = $request->hike_rate_per_km;
        $datas->hike_shown_after_distance_in_meter = $request->hike_shown_distance_meter;
        $datas->mid_night_start_timing = $request->mid_night_start_timing;
        $datas->mid_night_end_timing = $request->mid_night_end_timing;
        $datas->mid_night_fare = $request->mid_night_fare;

        $datas->save();
        toastr()->success('Updated successfully', 'Success!');
        return redirect(route('admin.view.base.fare'));

    } catch (\Exception $e) {
        toastr()->error('Enternal Server Error', 'Error!');
        return redirect()->back();
    }
}




public function rto_rules(){
   return view('admin.backend.rto_rule.rto_rule');


}
public function store_rto_rules(Request $request){
    try {
        $validated = $request->validate([
            'name'=>'required',
            'description'=>'required',
        ]);

        $datas=new RtoRule;
        $datas->name=$request->name;
        $datas->description=$request->description;


        $datas->save();
        toastr()->success('RTO Rules add success','Success!');
        return redirect()->back();
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'description'=>'required',
        ]))->withInput();
    }

 }

 public function view_rto_rules(){
    $datas=RtoRule::all();

    return view('admin.backend.rto_rule.view_rto_rule',compact('datas'));


 }


 public function delete_rto($id){
    $data=RtoRule::findOrFail($id);
    if (!is_null($data)){
        $data->delete();
        toastr()->success('RTO Delete success','Success!');
        return  redirect()->back();
    }else{
        toastr()->Error('Not Found','Error!');
        return  redirect()->back();
    }

}


public function edit_rto($id){
    $datas=RtoRule::findOrFail($id);
    if (!is_null($datas)){
        return view('admin.backend.rto_rule.edit_rto_rule',compact('datas'));


    }else{
        toastr()->Error('Not Found','Error!');
        return  redirect()->back();
    }

}

public function edited_rto($id,Request $request){
    try {
        $validated = $request->validate([
            'name'=>'required',
            'description'=>'required',
        ]);

        $datas=RtoRule::where('id',$id)->get()->first();
        $datas->name=$request->name;
        $datas->description=$request->description;


        $datas->save();
        toastr()->success('RTO Rules Update success','Success!');
        return redirect()->back();
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'description'=>'required',
        ]))->withInput();
    }

 }

 public function important_number(){

    $datas=ImportantNumber::all();
    return view('admin.backend.important_number.important_number',compact('datas'));


 }
 public function add_important_number(){

    return view('admin.backend.important_number.add_important_number');


 }
 public function added_important_number(Request $request){
    try {
        $validated = $request->validate([
            'name'=>'required',
            'number'=>'required',
        ]);

        $datas=new ImportantNumber;
        $datas->name=$request->name;
        $datas->number=$request->number;


        $datas->save();
        toastr()->success(' added  successful','Success!');
        return redirect(route('admin.important.number'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'number'=>'required',
        ]))->withInput();
    }

 }

 public function deleteadded_important_number($id){

    $data=ImportantNumber::findOrFail($id);
    if (!is_null($data)){
        $data->delete();
        toastr()->success('Data  Deleted successfull','Success!');
        return  redirect()->back();
    }else{
        toastr()->Error('Not Found','Error!');
        return  redirect()->back();
    }


}
 public function edit_important_number($id){
    $datas=ImportantNumber::where('id',$id)->get()->first();
    return view('admin.backend.important_number.edit',compact('datas'));


}
 public function edited_important_number($id,Request $request){
    try {
        $validated = $request->validate([
            'name'=>'required',
            'number'=>'required',
        ]);

        $datas=ImportantNumber::where('id',$id)->get()->first();
        $datas->name=$request->name;
        $datas->number=$request->number;


        $datas->save();
        toastr()->success(' Update success','Success!');
        return redirect(route('admin.important.number'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'number'=>'required',
        ]))->withInput();
    }


}




public function feedback(){
    $datas=Feedback::all();
    return view('admin.backend.feedback.feedback',compact('datas'));


}


public function add_feedback(){
    return view('admin.backend.feedback.add');


}
public function added_feedback( Request $request){
    try {
        $validated = $request->validate([
            'name'=>'required',
            'number'=>'required',
            'email'=>'required',
            'message'=>'required',
        ]);

        $datas=new Feedback;
        $datas->name=$request->name;
        $datas->number=$request->number;
        $datas->email=$request->email;
        $datas->message=$request->message;


        $datas->save();
        toastr()->success(' added  successful','Success!');
        return redirect(route('admin.feedback'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'number'=>'required',
            'email'=>'required',
            'message'=>'required',
        ]))->withInput();
    }
}



public function delete_feedback($id){

    $data=Feedback::findOrFail($id);
    if (!is_null($data)){
        $data->delete();
        toastr()->success('Data  Deleted successfull','Success!');
        return  redirect()->back();
    }else{
        toastr()->Error('Not Found','Error!');
        return  redirect()->back();
    }


}


public function edit_feedback($id){
    $datas=Feedback::where('id',$id)->get()->first();
    return view('admin.backend.feedback.edit',compact('datas'));


}

public function edited_feedback($id, Request $request){
    try {
        $validated = $request->validate([
            'name'=>'required',
            'number'=>'required',
            'email'=>'required',
            'message'=>'required',
        ]);

        $datas=Feedback::where('id',$id)->get()->first();
        $datas->name=$request->name;
        $datas->number=$request->number;
        $datas->email=$request->email;
        $datas->message=$request->message;


        $datas->save();
        toastr()->success(' Update  successful','Success!');
        return redirect(route('admin.feedback'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'number'=>'required',
            'email'=>'required',
            'message'=>'required',
        ]))->withInput();
    }
}


public function about(){
    $datas=AboutApplication::all();

    return view('admin.backend.about.about',compact('datas'));

}
public function add_about(){

    return view('admin.backend.about.add');

}
public function added_about(Request $request){

    try {
        $validated = $request->validate([
            'about'=>'required',

        ]);

        $datas=new AboutApplication;
        $datas->about=$request->about;



        $datas->save();
        toastr()->success(' added  successful','Success!');
        return redirect(route('admin.about'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'about'=>'required',

        ]))->withInput();
    }

}

public function delete_about($id){

    $data=AboutApplication::findOrFail($id);
    if (!is_null($data)){
        $data->delete();
        toastr()->success('Data  Deleted successfull','Success!');
        return  redirect()->back();
    }else{
        toastr()->Error('Not Found','Error!');
        return  redirect()->back();
    }


}
public function edit_about($id){
    $datas=AboutApplication::where('id',$id)->first();
    return view('admin.backend.about.edit',compact( 'datas'));



}
public function edited_about($id,Request $request){
    try {
        $validated = $request->validate([
            'about'=>'required',

        ]);

        $datas=AboutApplication::where('id',$id)->get()->first();
        $datas->about=$request->about;



        $datas->save();
        toastr()->success(' Update  successful','Success!');
        return redirect(route('admin.about'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',

        ]))->withInput();
    }


}




  public function complain(){
    $datas=Complain::all();

    return view('admin.backend.complain.complain',compact('datas'));

}

public function add_complain(){

    return view('admin.backend.complain.add');

}



public function added_complain(Request $request){

    try {
        $validated = $request->validate([
            'name'=>'required',
            'number'=>'required',
            'car_reg_no'=>'required',
            'image'=>'required',
            'complain'=>'required',

        ]);



        $datas=new Complain();
        $datas->name=$request->name;
        $datas->number=$request->number;
        $datas->car_reg_no=$request->car_reg_no;
        $datas->complain=$request->complain;
        $imagename = time() . '.' . $request->image->extension();
		$request->image->move(public_path('complain'), $imagename);
        $datas->image = $imagename;

        $datas->save();
        toastr()->success(' added  successful','Success!');
        return redirect(route('admin.complain'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'number'=>'required',
            'car_reg_no'=>'required',
            'image'=>'required',
            'complain'=>'required',

        ]))->withInput();
    }

}


public function delete_complain($id){

    $data=Complain::findOrFail($id);
    if (!is_null($data)){
        $data->delete();
        toastr()->success('Data  Deleted successfull','Success!');
        return  redirect()->back();
    }else{
        toastr()->Error('Not Found','Error!');
        return  redirect()->back();
    }


}


public function edit_complain($id){
    $datas=Complain::where('id',$id)->first();
    return view('admin.backend.complain.edit',compact( 'datas'));



}
public function edited_complain($id,Request $request){
    try {
        $validated = $request->validate([
            'name'=>'required',
            'number'=>'required',
            'car_reg_no'=>'required',
            'image'=>'required',
            'complain'=>'required',

        ]);



        $datas=Complain::where('id',$id)->get()->first();
        $datas->name=$request->name;
        $datas->number=$request->number;
        $datas->car_reg_no=$request->car_reg_no;
        $datas->complain=$request->complain;
        $imagename = time() . '.' . $request->image->extension();
		$request->image->move(public_path('complain'), $imagename);
        $datas->image = $imagename;

        $datas->save();
        toastr()->success(' Update  successful','Success!');
        return redirect(route('admin.complain'));
        // return redirect(route( 'admin.view.rto.rules'));


    } catch (\Exception $e) {
        toastr()->error($e->getMessage(), 'Error');
        return redirect()->back()->withErrors($request->validate([
            'name'=>'required',
            'number'=>'required',
            'car_reg_no'=>'required',
            'image'=>'required',
            'complain'=>'required',

        ]))->withInput();
    }



}




}

