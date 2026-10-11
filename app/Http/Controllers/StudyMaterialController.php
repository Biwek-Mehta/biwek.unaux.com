<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudyMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['program', 'academicYear', 'semester', 'subject'])
            ->where('status', true);

        if ($request->has('program_id') && $request->program_id) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->has('academic_year_id') && $request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->has('semester_id') && $request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }
        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(12);
        $programs = Program::where('status', true)->get();
        $academicYears = AcademicYear::where('status', true)->get();
        $semesters = Semester::where('status', true)->get();

        return view('study_materials.index', compact('documents', 'programs', 'academicYears', 'semesters'));
    }

    public function view($id)
    {
        $document = Document::with(['program', 'academicYear', 'semester', 'subject'])
            ->where('status', true)
            ->findOrFail($id);
        $filePath = storage_path('app/public/' . $document->file_path);
        return response()->file($filePath);
    }

    public function download($id)
    {
        $document = Document::where('status', true)->findOrFail($id);
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function getSubjectsByFilters(Request $request)
    {
        $query = Subject::where('status', true);

        if ($request->has('program_id') && $request->program_id) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->has('academic_year_id') && $request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->has('semester_id') && $request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }

        $subjects = $query->get(['id', 'name']);
        return response()->json($subjects);
    }
}
