@extends('layout.main')
@section('title', 'Edit Document')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Edit Document</h3>
                </div>
                <div class="card-body">
                    <form id="documentForm" action="{{ route('document.update', ['id' => $document->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group mb-3">
                            <label for="program_id">Program</label>
                            <select class="form-control @error('program_id') is-invalid @enderror" id="program_id" name="program_id" required>
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id', $document->program_id) == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
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
                                    <option value="{{ $year->id }}" {{ old('academic_year_id', $document->academic_year_id) == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
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
                                    <option value="{{ $semester->id }}" {{ old('semester_id', $document->semester_id) == $semester->id ? 'selected' : '' }}>{{ $semester->name }}</option>
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
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $document->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Document Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $document->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>File (Leave empty to keep current file - PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX, ZIP, RAR - Max 50MB)</label>
                            <div class="btn-group mb-2" role="group">
                                <button type="button" class="btn btn-info" onclick="showFileUpload()">
                                    <i class="fas fa-upload"></i> Upload File
                                </button>
                                <button type="button" class="btn btn-success" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Scan/Capture
                                </button>
                            </div>

                            <div id="fileUploadSection" class="mt-2">
                                <input type="file" id="upload_file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.zip,.rar,image/*">
                                @if($document->file_name)
                                    <small class="text-muted">Current file: {{ $document->file_name }}</small>
                                @endif
                                @error('file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="cameraSection" class="mt-2" style="display: none;">
                                <video id="video" autoplay style="width: 100%; max-height: 400px; border: 1px solid #ddd; border-radius: 5px;"></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-primary" onclick="captureImage()">
                                        <i class="fas fa-camera"></i> Capture
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="stopCamera()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                                <div id="capturedImagePreview" class="mt-2" style="display: none;">
                                    <img id="capturedImage" style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 5px;">
                                    <br>
                                    <button type="button" class="btn btn-warning btn-sm mt-2" onclick="retakePhoto()">
                                        <i class="fas fa-redo"></i> Retake
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ old('status', $document->status) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('status', $document->status) ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
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
let videoStream = null;
let capturedImageData = null;

function showFileUpload() {
    document.getElementById('fileUploadSection').style.display = 'block';
    document.getElementById('cameraSection').style.display = 'none';
    stopCamera();
}

function startCamera() {
    document.getElementById('fileUploadSection').style.display = 'none';
    document.getElementById('cameraSection').style.display = 'block';
    document.getElementById('capturedImagePreview').style.display = 'none';

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            videoStream = stream;
            document.getElementById('video').srcObject = stream;
        })
        .catch(function(err) {
            alert('Camera access denied or not available. Please check permissions.');
            console.error('Error accessing camera:', err);
            showFileUpload();
        });
}

function stopCamera() {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
    document.getElementById('video').srcObject = null;
}

function captureImage() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    capturedImageData = canvas.toDataURL('image/jpeg', 0.9);
    document.getElementById('capturedImage').src = capturedImageData;
    document.getElementById('capturedImagePreview').style.display = 'block';
    document.getElementById('video').style.display = 'none';

    // Convert to file and set to hidden input
    canvas.toBlob(function(blob) {
        const file = new File([blob], 'scan_' + Date.now() + '.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('upload_file').files = dataTransfer.files;
    }, 'image/jpeg', 0.9);
}

function retakePhoto() {
    document.getElementById('capturedImagePreview').style.display = 'none';
    document.getElementById('video').style.display = 'block';
    capturedImageData = null;
    document.getElementById('upload_file').value = '';
}

$(document).ready(function() {
    function loadSubjects() {
        var programId = $('#program_id').val();
        var academicYearId = $('#academic_year_id').val();
        var semesterId = $('#semesterId').val();

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
                    // Restore selected value if exists
                    var currentSubjectId = "{{ old('subject_id', $document->subject_id) }}";
                    if (currentSubjectId) {
                        $('#subject_id').val(currentSubjectId);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading subjects:', error);
                    console.log('XHR:', xhr);
                    $('#subject_id').empty();
                    $('#subject_id').append('<option value="">Select Subject</option>');
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
