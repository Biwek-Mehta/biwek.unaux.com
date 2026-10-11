<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Http\Requests\StoreSemesterRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SemesterController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view semester',only: ['index']),
            new Middleware('permission:edit semester', only: ['edit']),
            new Middleware('permission:create semester',  only: ['create']),
            new Middleware('permission:delete semester',  only:['delete']),
        ];

    }

    public function index()
    {
        $semesters = Semester::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.admin.pages.semester.index', compact('semesters'));
    }

    public function create()
    {
        return view('backend.admin.pages.semester.create');
    }

    public function store(StoreSemesterRequest $request)
    {
        Semester::create($request->validated());
        toastr()->success('Semester created successfully.');
        return redirect()->route('semester.list');
    }

    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        return view('backend.admin.pages.semester.edit', compact('semester'));
    }

    public function update(StoreSemesterRequest $request, $id)
    {
        $semester = Semester::findOrFail($id);
        $semester->update($request->validated());
        toastr()->success('Semester updated successfully.');
        return redirect()->route('semester.list');
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();
        toastr()->success('Semester deleted successfully.');
        return redirect()->back();
    }

    public function toggleStatus($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->status = !$semester->status;
        $semester->save();
        toastr()->success('Semester status updated successfully.');
        return redirect()->back();
    }
}
