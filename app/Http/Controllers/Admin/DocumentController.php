<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DocumentController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view document',only: ['index']),
            new Middleware('permission:edit document', only: ['edit']),
            new Middleware('permission:create document',  only: ['create']),
            new Middleware('permission:delete document',  only:['delete']),
        ];

    }

    public function index(Request $request)
    {
        
        $query = Document::with(['program', 'academicYear', 'semester', 'subject']);

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
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
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->has('file_type') && $request->file_type) {
            $query->where('file_type', $request->file_type);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        $programs = Program::where('status', true)->get();
        $academicYears = AcademicYear::where('status', true)->get();
        $semesters = Semester::where('status', true)->get();
        $fileTypes = Document::select('file_type')->distinct()->pluck('file_type');

        return view('backend.admin.pages.document.index', compact('documents', 'programs', 'academicYears', 'semesters', 'fileTypes'));
    }

    public function create()
    {
        $programs = Program::where('status', true)->get();
        $academicYears = AcademicYear::where('status', true)->get();
        $semesters = Semester::where('status', true)->get();
        return view('backend.admin.pages.document.create', compact('programs', 'academicYears', 'semesters'));
    }

    public function store(StoreDocumentRequest $request)
    {
        try {
            $file = $request->file('file');

            if (!$file) {
                toastr()->error('No file uploaded. Please select a file.');
                return redirect()->back()->withInput();
            }

            // Check for upload errors
            if ($file->getError() !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds the maximum upload size on the server. Please configure php.ini: upload_max_filesize=50M and post_max_size=50M',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds the maximum upload size specified in the form.',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                ];
                $errorMessage = $errorMessages[$file->getError()] ?? 'Unknown upload error.';
                toastr()->error($errorMessage);
                return redirect()->back()->withInput();
            }

            if (!$file->isValid()) {
                toastr()->error('Invalid file upload. Error: ' . $file->getErrorMessage());
                return redirect()->back()->withInput();
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $file->getClientOriginalExtension();

            // Check file size in bytes (50MB = 52428800 bytes)
            if ($fileSize > 52428800) {
                toastr()->error('File size exceeds 50MB limit.');
                return redirect()->back()->withInput();
            }

            
            $program = Program::findOrFail($request->program_id);
            $academicYear = AcademicYear::findOrFail($request->academic_year_id);
            $semester = Semester::findOrFail($request->semester_id);

            $directory = "documents/{$program->name}/{$academicYear->name}/{$semester->name}";

            $filePath = $file->storeAs(
                $directory,
                $fileName,
                'public'
            );

            if (!$filePath) {
                toastr()->error('Failed to store file. Please check storage permissions.');
                return redirect()->back()->withInput();
            }

            Document::create([
                'program_id' => $request->program_id,
                'academic_year_id' => $request->academic_year_id,
                'semester_id' => $request->semester_id,
                'subject_id' => $request->subject_id,
                'title' => $request->title,
                'description' => $request->description,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'status' => $request->has('status') ? $request->status : true,
            ]);

            toastr()->success('Document uploaded successfully.');
            return redirect()->route('document.list');
        } catch (\Exception $e) {
            toastr()->error('Error uploading file: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $document = Document::with(['program', 'academicYear', 'semester', 'subject'])->findOrFail($id);
        $programs = Program::where('status', true)->get();
        $academicYears = AcademicYear::where('status', true)->get();
        $semesters = Semester::where('status', true)->get();
        $subjects = Subject::where('program_id', $document->program_id)
            ->where('academic_year_id', $document->academic_year_id)
            ->where('semester_id', $document->semester_id)
            ->where('status', true)
            ->get();
        return view('backend.admin.pages.document.edit', compact('document', 'programs', 'academicYears', 'semesters', 'subjects'));
    }

    public function update(UpdateDocumentRequest $request, $id)
    {
        try {
            $document = Document::findOrFail($id);

            if ($request->hasFile('file')) {
                Storage::disk('public')->delete($document->file_path);

                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getClientOriginalExtension();

                $program = Program::findOrFail($request->program_id);
                $academicYear = AcademicYear::findOrFail($request->academic_year_id);
                $semester = Semester::findOrFail($request->semester_id);

                $filePath = $file->storeAs(
                    "documents/{$program->name}/{$academicYear->name}/{$semester->name}",
                    $fileName,
                    'public'
                );

                $document->file_name = $fileName;
                $document->file_path = $filePath;
                $document->file_type = $fileType;
                $document->file_size = $fileSize;
            }

            $document->program_id = $request->program_id;
            $document->academic_year_id = $request->academic_year_id;
            $document->semester_id = $request->semester_id;
            $document->subject_id = $request->subject_id;
            $document->title = $request->title;
            $document->description = $request->description;
            if ($request->has('status')) {
                $document->status = $request->status;
            }
            $document->save();

            toastr()->success('Document updated successfully.');
            return redirect()->route('document.list');
        } catch (\Exception $e) {
            toastr()->error('Error updating document: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        toastr()->success('Document deleted successfully.');
        return redirect()->back();
    }

    public function view($id)
    {
        $document = Document::findOrFail($id);
        $filePath = storage_path('app/public/' . $document->file_path);
        return response()->file($filePath);
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function toggleStatus($id)
    {
        $document = Document::findOrFail($id);
        $document->status = !$document->status;
        $document->save();
        toastr()->success('Document status updated successfully.');
        return redirect()->back();
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
