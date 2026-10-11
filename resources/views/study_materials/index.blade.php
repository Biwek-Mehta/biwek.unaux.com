<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="fas fa-book-open"></i> Study Materials</a>
            <a href="{{ route('login') }}" class="btn btn-light btn-sm" style="position: relative;left: 20px;"><i class="fas fa-sign-in-alt"></i> Login</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="fas fa-filter"></i> Filter Documents</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('study-materials.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="program_id">Program</label>
                            <select class="form-select" id="program_id" name="program_id">
                                <option value="">All Programs</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="academic_year_id">Academic Year</label>
                            <select class="form-select" id="academic_year_id" name="academic_year_id">
                                <option value="">All Years</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="semester_id">Semester</label>
                            <select class="form-select" id="semester_id" name="semester_id">
                                <option value="">All Semesters</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="subject_id">Subject</label>
                            <select class="form-select" id="subject_id" name="subject_id">
                                <option value="">All Subjects</option>
                                @if(request('subject_id'))
                                    @foreach($documents->pluck('subject')->unique() as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                            <a href="{{ route('study-materials.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            @forelse($documents as $document)
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $document->title }}</h5>
                        <p class="card-text text-muted small">{{ $document->description }}</p>
                        <hr>
                        <p class="mb-1"><strong>Subject:</strong> {{ $document->subject->name ?? '-' }}</p>
                        <p class="mb-1"><strong>Program:</strong> {{ $document->program->name ?? '-' }}</p>
                        <p class="mb-1"><strong>Year:</strong> {{ $document->academicYear->name ?? '-' }}</p>
                        <p class="mb-1"><strong>Semester:</strong> {{ $document->semester->name ?? '-' }}</p>
                        <p class="mb-1"><strong>Type:</strong> <span class="badge bg-info">{{ strtoupper($document->file_type) }}</span></p>
                        <p class="mb-1"><strong>Size:</strong> {{ number_format($document->file_size / 1024, 2) }} KB</p>
                        <p class="mb-1"><strong>Uploaded:</strong> {{ $document->created_at->format('Y-m-d') }}</p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('study-materials.view', ['id' => $document->id]) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('study-materials.download', ['id' => $document->id]) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No documents found for the selected filters.
                </div>
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $documents->links('pagination::bootstrap-5') }}
        </div>

        <footer class="mt-5 py-3 bg-light text-center">
            <div class="container">
                <p class="mb-0 text-muted">Developed by <strong>Biwek Mehta</strong></p>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        function loadSubjects() {
            var programId = $('#program_id').val();
            var academicYearId = $('#academic_year_id').val();
            var semesterId = $('#semester_id').val();

            if (programId && academicYearId && semesterId) {
                $.ajax({
                    url: '{{ route("study-materials.get-subjects") }}',
                    type: 'GET',
                    data: {
                        program_id: programId,
                        academic_year_id: academicYearId,
                        semester_id: semesterId
                    },
                    success: function(data) {
                        $('#subject_id').empty();
                        $('#subject_id').append('<option value="">All Subjects</option>');
                        $.each(data, function(key, value) {
                            $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#subject_id').empty();
                $('#subject_id').append('<option value="">All Subjects</option>');
            }
        }

        $('#program_id, #academic_year_id, #semester_id').on('change', loadSubjects);
    });
    </script>
</body>
</html>
