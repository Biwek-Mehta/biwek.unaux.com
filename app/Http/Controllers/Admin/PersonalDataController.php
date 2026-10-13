<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PersonalDataController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view personal data', only: ['index']),
            new Middleware('permission:create personal data', only: ['create', 'store']),
            new Middleware('permission:edit personal data', only: ['edit', 'update']),
            new Middleware('permission:delete personal data', only: ['destroy']),
        ];
    }

    public function index()
    {
        $personalData = PersonalData::all();
        return view('backend.admin.pages.personal-data.index', compact('personalData'));
    }

    public function create()
    {
        return view('backend.admin.pages.personal-data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:51200',
            'status' => 'sometimes|boolean',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $fileType = $file->getClientOriginalExtension();

        $filePath = $file->storeAs('personal-data/files', $fileName, 'public');

        PersonalData::create([
            'title' => $validated['title'],
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'status' => $request->has('status') ? $request->status : true,
        ]);

        toastr()->success('Personal data created successfully.');
        return redirect()->route('personal-data.list');
    }

    public function edit($id)
    {
        $personalData = PersonalData::findOrFail($id);
        return view('backend.admin.pages.personal-data.edit', compact('personalData'));
    }

    public function update(Request $request, $id)
    {
        $personalData = PersonalData::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|max:51200',
            'status' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('file')) {
            if ($personalData->file_path) {
                Storage::disk('public')->delete($personalData->file_path);
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $file->getClientOriginalExtension();
            $filePath = $file->storeAs('personal-data/files', $fileName, 'public');
            $personalData->file_name = $fileName;
            $personalData->file_path = $filePath;
            $personalData->file_type = $fileType;
            $personalData->file_size = $fileSize;
        }

        $personalData->title = $validated['title'];
        $personalData->status = $request->has('status') ? $request->status : true;
        $personalData->save();

        toastr()->success('Personal data updated successfully.');
        return redirect()->route('personal-data.list');
    }

    public function destroy($id)
    {
        $personalData = PersonalData::findOrFail($id);
        if ($personalData->file_path) {
            Storage::disk('public')->delete($personalData->file_path);
        }
        $personalData->delete();
        toastr()->success('Personal data deleted successfully.');
        return redirect()->back();
    }

    public function toggleStatus($id)
    {
        $personalData = PersonalData::findOrFail($id);
        $personalData->status = !$personalData->status;
        $personalData->save();
        toastr()->success('Personal data status updated successfully.');
        return redirect()->back();
    }

    public function view($id)
    {
        $personalData = PersonalData::findOrFail($id);
        $filePath = storage_path('app/public/' . $personalData->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath);
    }

    public function download($id)
    {
        $personalData = PersonalData::findOrFail($id);
        $filePath = storage_path('app/public/' . $personalData->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $personalData->file_name);
    }
}
