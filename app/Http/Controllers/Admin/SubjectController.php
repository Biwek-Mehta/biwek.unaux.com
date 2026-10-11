<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Http\Requests\StoreSubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SubjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view subject',only: ['index']),
            new Middleware('permission:edit subject', only: ['edit']),
            new Middleware('permission:create subject',  only: ['create']),
            new Middleware('permission:delete subject',  only:['delete']),
        ];

    }

    public function index(Request $request)
    {
        $query = Subject::with(['program', 'academicYear', 'semester']);

        if ($request->has('program_id') && $request->program_id) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->has('academic_year_id') && $request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->has('semester_id') && $request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }

        $subjects = $query->orderBy('created_at', 'desc')->paginate(10);
        $programs = Program::where('status', true)->get();
        $academicYears = AcademicYear::where('status', true)->get();
        $semesters = Semester::where('status', true)->get();

        return view('backend.admin.pages.subject.index', compact('subjects', 'programs', 'academicYears', 'semesters'));
    }

    public function create()
    {
        $programs = Program::where('status', true)->get();
        $academicYears = AcademicYear::where('status', true)->get();
        $semesters = Semester::where('status', true)->get();
        return view('backend.admin.pages.subject.create', compact('programs', 'academicYears', 'semesters'));
    }

    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->validated());
        toastr()->success('Subject created successfully.');
        return redirect()->route('subject.list');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $programs = Program::where('status', true)->get();
        $academicYears = AcademicYear::where('status', true)->get();
        $semesters = Semester::where('status', true)->get();
        return view('backend.admin.pages.subject.edit', compact('subject', 'programs', 'academicYears', 'semesters'));
    }

    public function update(StoreSubjectRequest $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->update($request->validated());
        toastr()->success('Subject updated successfully.');
        return redirect()->route('subject.list');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        toastr()->success('Subject deleted successfully.');
        return redirect()->back();
    }

    public function toggleStatus($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->status = !$subject->status;
        $subject->save();
        toastr()->success('Subject status updated successfully.');
        return redirect()->back();
    }
}
