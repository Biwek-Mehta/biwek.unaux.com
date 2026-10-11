@extends('layout.main')
@section('title', 'Edit Attendance')
@section('content')

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Edit Attendance</h3>
            <a class="btn btn-light btn-sm" href="{{ route('attendance.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                @csrf
               

                <div class="mb-3">
                    <label for="student_name" class="form-label">Date</label>
                 
                    <input type="text" class="form-control" name="date" id="date" value="{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}" readonly>


                </div>
                <div class="mb-3">
                    <label for="student_name" class="form-label">Student Name</label>
                    <input type="text" class="form-control" name="student_name" id="student_name" value="{{ $attendance->student_name }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Present" {{ $attendance->status === 'Present' ? 'selected' : '' }}>Present</option>
                        <option value="Absent" {{ $attendance->status === 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Late" {{ $attendance->status === 'Late' ? 'selected' : '' }}>Late</option>
                    </select>
                </div>

                

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
