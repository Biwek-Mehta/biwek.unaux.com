<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PermissionController extends Controller
{
    // Middleware handled by AdminMiddleware in routes
    // this method will show permission in page
    public function index(Request $request)
    {
        $query = Permission::query();
    
        // If a search query is provided, filter the results by permission name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }
    
        // Paginate the results
        $datas = $query->paginate(8);
    
        return view('backend.admin.pages.permission.list', compact('datas'));
    }
    // this method will show create permission in page
    public function create(){
        return view('backend.admin.pages.permission.add');
    }
    // this method will insert a permission into DB
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name'=>'required|unique:permissions|min:3',
               
            ]);

            $datas=new Permission;
            $datas->name=$request->name;
           

            $datas->save();
            toastr()->success('Permission  added Successfully');
           
            return redirect(route( 'permission.list'));


        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
                'name'=>'required|unique:permissions|min:3',
                
            ]))->withInput();
        }

    }
    // this method will show edit permission in page
    public function edit($id){
        $datas=Permission::where('id',$id)->get()->first();
        return view('backend.admin.pages.permission.edit',compact('datas'));

    }
    // this method will update permission
    public function update($id,Request $request){
        try {
            $validated = $request->validate([
                'name'=>'required|unique:permissions|min:3',
               
            ]);

            $datas=Permission::where('id',$id)->get()->first();
            $datas->name=$request->name;
           

            $datas->save();
            toastr()->success('Permission  Updated Successfully');
           
            return redirect(route( 'permission.list'));


        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
                'name'=>'required|unique:permissions|min:3',
                
            ]))->withInput();
        }

    }
    // this method will show delete permission from DB
    public function destroy($id){
        $datas=Permission::find($id)->delete();
        toastr()->success('Permission  Deleted Successfully');
        return redirect()->back();

    }
}
