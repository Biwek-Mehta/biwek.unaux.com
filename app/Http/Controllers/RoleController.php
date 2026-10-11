<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Controllers\Controller; // Ensure this line is included


class RoleController extends Controller
{
    // Middleware handled by AdminMiddleware in routes
    // this method will show role in page
    public function index(Request $request)
    {
        $query = Role::query();

        // If a search query is provided, filter the results by permission name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Paginate the results
        $datas = $query->paginate(8);

        return view('backend.admin.pages.role.list', compact('datas'));
    }
    // this method will show create role in page
    public function create()
    {
        $permissions=Permission::all();
        return view('backend.admin.pages.role.add',compact('permissions'));
    }
    // this method will insert a role into DB
    public function store(Request $request)
    {
       
        try {
            $validated = $request->validate([
                'name' => 'required|unique:permissions|min:3',

            ]);

            $role = new Role;
            $role->name = $request->name;
             $role->save();

             if(!empty($request->permission)){
                foreach($request->permission as $name){
                    $role->givePermissionTo($name);
                }
             }

            toastr()->success('Role  added Successfully');

            return redirect(route('role.list'));
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
                'name' => 'required|unique:permissions|min:3',

            ]))->withInput();
        }
    }
    // this method will show edit role in page
    public function edit($id)
    {
        $datas = Role::where('id', $id)->get()->first();
        $permissions=Permission::all();
        return view('backend.admin.pages.role.edit', compact('datas','permissions'));
    }
    // this method will update role
    public function update($id, Request $request)
    {
        // dd($request->permission);
        try {
            $validated = $request->validate([
                'name' => 'required|unique:permissions|min:3',

            ]);

            $datas = Role::where('id', $id)->get()->first();
            $datas->name = $request->name;


            $datas->save();
            if(!empty($request->permission)){
                // foreach($request->permission as $name){
                //     $datas->givePermissionTo($name);
                // }

                $datas->syncPermissions($request->permission);
             }
            toastr()->success('Role  Updated Successfully');

            return redirect(route('role.list'));
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withErrors($request->validate([
                'name' => 'required|unique:permissions|min:3',

            ]))->withInput();
        }
    }
    // this method will show delete role from DB
    public function destroy($id)
    {
        $datas = Role::find($id)->delete();
        toastr()->success('Role  Deleted Successfully');
        return redirect()->back();
    }
}
