<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Http\Requests\StoreAcademicYearRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AcademicYearController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view academic-year',only: ['index']),
            new Middleware('permission:edit academic-year', only: ['edit']),
            new Middleware('permission:create proacademic-yeargram',  only: ['create']),
            new Middleware('permission:delete academic-year',  only:['delete']),
        ];

    }

    public function index()
    {
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.admin.pages.academic_year.index', compact('academicYears'));
    }

    public function create()
    {
        return view('backend.admin.pages.academic_year.create');
    }

    public function store(StoreAcademicYearRequest $request)
    {
        AcademicYear::create($request->validated());
        toastr()->success('Academic Year created successfully.');
        return redirect()->route('academic_year.list');
    }

    public function edit($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        return view('backend.admin.pages.academic_year.edit', compact('academicYear'));
    }

    public function update(StoreAcademicYearRequest $request, $id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->update($request->validated());
        toastr()->success('Academic Year updated successfully.');
        return redirect()->route('academic_year.list');
    }

    public function destroy($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->delete();
        toastr()->success('Academic Year deleted successfully.');
        return redirect()->back();
    }

    public function toggleStatus($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->status = !$academicYear->status;
        $academicYear->save();
        toastr()->success('Academic Year status updated successfully.');
        return redirect()->back();
    }
}
