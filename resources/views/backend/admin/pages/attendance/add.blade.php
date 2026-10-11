@extends('layout.main')
@section('title', 'Add Attendance')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Record Attendance</h3>
            <a class="btn btn-light btn-sm" href="{{ route('attendance.list') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            @if ($already_attendance)
            <div class="alert alert-info">
                You have already marked your attendance for today {{ \Carbon\Carbon::parse($already_attendance->date)->format('Y-m-d') }}.
            </div>
            @else
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="hidden" id="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    <input type="hidden" name="auth_user_id" value="{{ auth()->user()->id }}">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td>
                                    <input type="text" name="date" value="{{ now()->format('Y-m-d') }}" disabled class="form-control-plaintext">
                                </td>
                                <td>{{ $student->student_name }}</td>
                                <td>
                                    <select name="attendance[{{ $student->student_name }}][status]" class="form-select" required>
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                        <option value="Late">Late</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-check"></i> Submit
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
