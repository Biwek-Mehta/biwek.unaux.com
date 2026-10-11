<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\WebsiteLogo;
use Illuminate\Http\Request;
use App\Models\HomepageImage;
use Illuminate\Support\Facades\Mail;


class WebController extends Controller
{
        public function message_store(Request $request){
               
               try {
                $validated = $request->validate([
                    'name' => 'required',
                    'email' => 'required',
                    'message' => 'required',
                ]);
    
                $datas=new Message;
                $datas->name=$request->name;
                $datas->email=$request->email;
                $datas->message=$request->message;
                $datas->save();
                
                $data['email'] =$request->email;
                $data['name'] =$request->name;
                $data['title'] = 'Mail From Biwek';
        
                $data['body'] = 'We Will Contact you Soon ';
        
                Mail::send('backend.admin.pages.sendMail.mailSendWhenContactMe', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });
                toastr()->success('Message Send Successful');
                return redirect()->back();
             
    
    
            } catch (\Exception $e) {
                toastr()->error($e->getMessage());
                return redirect()->back()->withErrors($request->validate([
                        'name' => 'required',
                        'email' => 'required',
                        'message' => 'required',
                ]))->withInput();
            }

        }

        public function user_message(Request $request) {
                // Get filter inputs
                $search = $request->input('search');
            
                // Retrieve filtered messages
                $datas = Message::when($search, function($query, $search) {
                                return $query->where('name', 'like', "%{$search}%")
                                             ->orWhere('email', 'like', "%{$search}%")
                                             ->orWhere('message', 'like', "%{$search}%");
                            })
                            ->paginate(10);
            
                // Return the view with the filtered data
                return view('backend.admin.pages.user_message.view', compact('datas'));
            }
        public function destroy(string $id)
        {
            $datas = Message::find($id)->delete();
            toastr()->success('Data  Deleted Successfully');
            return redirect()->back();
        }


        public function view_logo(){
            $data = WebsiteLogo::latest()->first();

            return view('backend.admin.pages.companyLogo.view',compact('data'));
        }

        public function updateLogo(Request $request){
               $datas=new WebsiteLogo;
            if ($request->hasFile('WebsiteLogo')) {

                $WebsiteLogo = time() . '.' . $request->WebsiteLogo->extension();
                $request->WebsiteLogo->move(public_path('WebsiteLogo'), $WebsiteLogo);
                $datas->WebsiteLogo = $WebsiteLogo;
            }
            $datas->save();
            toastr()->success('Logo updated Successful');
            return redirect()->back();
        }
        public function homepage_image(){
            $data = HomepageImage::latest()->first();

            return view('backend.admin.pages.home_page_image.view',compact('data'));
        }

        public function update_homepage_image(Request $request){
               $datas=new HomepageImage;
            if ($request->hasFile('home_page_image')) {

                $home_page_image = time() . '.' . $request->home_page_image->extension();
                $request->home_page_image->move(public_path('home_page_image'), $home_page_image);
                $datas->home_page_image = $home_page_image;
            }
            $datas->save();
            toastr()->success('Image updated Successful');
            return redirect()->back();
        }
}
