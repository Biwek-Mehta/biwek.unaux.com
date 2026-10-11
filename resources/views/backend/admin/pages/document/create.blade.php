@extends('layout.main')
@section('title', 'Upload Document')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Upload New Document</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="program_id">Program</label>
                            <select class="form-control @error('program_id') is-invalid @enderror" id="program_id" name="program_id" required>
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                            @error('program_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="academic_year_id">Academic Year</label>
                            <select class="form-control @error('academic_year_id') is-invalid @enderror" id="academic_year_id" name="academic_year_id" required>
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="semester_id">Semester</label>
                            <select class="form-control @error('semester_id') is-invalid @enderror" id="semester_id" name="semester_id" required>
                                <option value="">Select Semester</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                            @error('semester_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="subject_id">Subject</label>
                            <select class="form-control @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Document Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="file">File (PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX - Max 10MB)</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Upload</button>
                            <a href="{{ route('document.list') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    function loadSubjects() {
        var programId = $('#program_id').val();
        var academicYearId = $('#academic_year_id').val();
        var semesterId = $('#semester_id').val();

        if (programId && academicYearId && semesterId) {
            $.ajax({
                url: '{{ route("document.get-subjects") }}',
                type: 'GET',
                data: {
                    program_id: programId,
                    academic_year_id: academicYearId,
                    semester_id: semesterId
                },
                success: function(data) {
                    $('#subject_id').empty();
                    $('#subject_id').append('<option value="">Select Subject</option>');
                    $.each(data, function(key, value) {
                        $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            $('#subject_id').empty();
            $('#subject_id').append('<option value="">Select Subject</option>');
        }
    }

    $('#program_id, #academic_year_id, #semester_id').on('change', loadSubjects);
});
</script>
@endpush

@endsection
