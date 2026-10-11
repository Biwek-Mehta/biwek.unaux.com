<?php

namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Project;

use App\Models\Permission;

use App\Models\WebsiteLogo;
// use Auth;
use Illuminate\Http\Request;
use App\Models\HomepageImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\EmailVerification;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loadLogin()
    {
       if(auth()->user() && auth()->user()->hasRole('admin')){
         
            return redirect(route('dashboard'));

        }
        elseif(auth()->user() && auth()->user()->hasRole('teacher')){
           
            return redirect(route('dashboard'));
        }
        else{
           
            return redirect(route('study-materials.index'));
        }



    }

    public function login()
    {
        return view('login');
    }


    public function login_check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:12'
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists
        if ($user) {
          
           
            $admin = Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ]) && auth()->user()->hasRole('admin');

            $user = Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ]) && auth()->user()->hasRole('user');


            // Check if authentication was successful
          if($admin) {


                    // Redirect authenticated user to the appropriate page
                    // Set a success toast, with a title
                    // dd($user->permissions->pluck('slug'));

                    // // dd($user->permissions);
                toastr()->success('Login  Successfully!');

                return redirect(route('dashboard'));// Change 'admin.dashboard' to your admin dashboard route name
            }

                elseif($user) {
                toastr()->success('Login  Successfully!');

                return redirect(route('dashboard'));// Change 'admin.dashboard' to your admin dashboard route name
            }
            else{
                   // If authentication fails or user does not exist, redirect back with an error message
                toastr()->error('Invalid Credentials');
                return redirect()->back();

            }


        }

        else{
            // If authentication fails or user does not exist, redirect back with an error message
         toastr()->error('User not Found');
         return redirect()->back();

        }


    }


    public function register()
    {
        return view('register');
    }


    public function store_register(Request $request)
    {
        // dd($request);

        try {
            $validated = $request->validate([
                'username' => 'required|min:4|max:100',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);

            $datas = new User;
            $datas->name = $request->username;
            $datas->email = $request->email;
            $datas->password = Hash::make($request->password);

            $datas->save();
            // toastr()->success('User Register Successful');
            // return redirect(route('login'));
           
            return $this->assignRole($request->email);


        } catch (\Exception $e) {
            // toastr()->error($e->getMessage(), 'Error');
            return redirect()->back()->withErrors($request->validate([
                'username' => 'required|min:4|max:100',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
            ]))->withInput();
        }
    }

    public function assignRole($request){
        $user = User::where('email', $request)->first();

        $role=Role::where('name','user')->get();
         $user->roles()->attach($role);
        //  dd($user->roles);
         toastr()->success('User Register Successful');
         return redirect(route('login'));


    }


    public function reset_password()
    {
        return view('forgot_password');
    }
    public function request_new_password(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // dd($user);


        if (!$user) {
            return redirect()->back()->with('email_not_found', 'Email not found ');
        } else {
            $email = $user->email;

            $this->sendOtp($user); //OTP SEND

            return view('verification', compact('email'))->with('send_mail', 'A Mail Send to your Gmail');
        }
    }
    public function sendOtp($user)
    {
        $otp = rand(100000, 999999);
        $time = time();

        EmailVerification::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'otp' => $otp,
                'created_at' => $time
            ]
        );

        $data['email'] = $user->email;
        $data['title'] = 'Mail From Vivek';

        $data['body'] = 'Your OTP is:- ' . $otp;

        Mail::send('mailVerification', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])->subject($data['title']);
        });
    }

    public function verifiedOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $otpData = EmailVerification::where('otp', $request->otp)->first();
        if (!$otpData) {
            return response()->json(['success' => false, 'msg' => 'You entered wrong OTP']);
        } else {

            $currentTime = time();
            $time = $otpData->created_at;

            if ($currentTime >= $time && $time >= $currentTime - (90 + 5)) { //90 seconds
                User::where('id', $user->id)->update([
                    'is_verified' => 1
                ]);
                return response()->json([
                    'success' => true,
                    'msg' => 'Mail has Verified',
                    'userId' => $user->id
                ]);
            } else {
                return response()->json(['success' => false, 'msg' => 'Your OTP has been Expired']);
            }
        }
    }






    public function resendOtp(Request $request)
    {

        $user = User::where('email', $request->email)->first();
        $otpData = EmailVerification::where('email', $request->email)->first();

        $currentTime = time();
        $time = $otpData->created_at;

        if ($currentTime >= $time && $time >= $currentTime - (90 + 5)) { //90 seconds
            return response()->json(['success' => false, 'msg' => 'Please try after some time']);
        } else {

            $this->sendOtp($user); //OTP SEND
            return response()->json(['success' => true, 'msg' => 'OTP has been sent']);
        }
    }
    public function update_password($id){

        $datas=User::find($id);

        return view('change_password',compact('datas'));
    }



    public function password_updated($id,Request $request){
        $request->validate([
            'password'=>'required|confirmed|max:20|min:6',
        ]);

        $datas=User::where('id',$id)->update(['password'=>Hash::make($request->password),'reset_pass'=>1]);

        //   toastr()->success('Password Changed  Successful!', 'Success');
        return redirect('/login')->with('forgot_success','Password Changed  Successful');



    }
}
