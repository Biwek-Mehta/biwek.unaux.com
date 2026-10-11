<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ProgramController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view program',only: ['index']),
            new Middleware('permission:edit program', only: ['edit']),
            new Middleware('permission:create program',  only: ['create']),
            new Middleware('permission:delete program',  only:['delete']),
        ];

    }
    public function index()
    {
        $programs = Program::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.admin.pages.program.index', compact('programs'));
    }

    public function create()
    {
        return view('backend.admin.pages.program.create');
    }

    public function store(StoreProgramRequest $request)
    {
        Program::create($request->validated());
        toastr()->success('Program created successfully.');
        return redirect()->route('program.list');
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('backend.admin.pages.program.edit', compact('program'));
    }

    public function update(UpdateProgramRequest $request, $id)
    {
        $program = Program::findOrFail($id);
        $program->update($request->validated());
        toastr()->success('Program updated successfully.');
        return redirect()->route('program.list');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();
        toastr()->success('Program deleted successfully.');
        return redirect()->back();
    }

    public function toggleStatus($id)
    {
        $program = Program::findOrFail($id);
        $program->status = !$program->status;
        $program->save();
        toastr()->success('Program status updated successfully.');
        return redirect()->back();
    }
}
