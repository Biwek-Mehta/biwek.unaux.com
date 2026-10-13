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
                    <form id="documentForm" action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label>File (PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX, ZIP, RAR - Max 50MB)</label>
                            <div class="btn-group mb-2" role="group">
                                <button type="button" class="btn btn-info" onclick="showFileUpload()">
                                    <i class="fas fa-upload"></i> Upload File
                                </button>
                                <button type="button" class="btn btn-success" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Scan/Capture
                                </button>
                            </div>

                            <div id="fileUploadSection" class="mt-2">
                                <input type="file" id="upload_file" name="file" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.zip,.rar,image/*" required>
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

    // File input change handler
    $('#upload_file').on('change', function() {
        var fileInput = this;
        if (fileInput.files && fileInput.files.length > 0) {
            var file = fileInput.files[0];
            var fileSizeMB = file.size / (1024 * 1024);
            console.log('Selected file:', file.name, 'Size:', fileSizeMB.toFixed(2) + 'MB');
        }
    });
});
</script>
@endpush

@endsection
