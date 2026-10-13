@extends('layout.main')
@section('title', 'Add Personal Data')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Add Personal Data</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('personal-data.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>File (JPG, PDF, Document, Scanned File - Max 50MB)</label>
                            <div class="btn-group mb-2" role="group">
                                <button type="button" class="btn btn-info" onclick="showFileUpload()">
                                    <i class="fas fa-upload"></i> Upload File
                                </button>
                                <button type="button" class="btn btn-success" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Scan/Capture
                                </button>
                            </div>

                            <div id="fileUploadSection" class="mt-2">
                                <input type="file" class="form-control-file @error('file') is-invalid @enderror" id="file" name="file" accept="image/*,.pdf">
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('personal-data.list') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
            document.getElementById('file').files = dataTransfer.files;
        }, 'image/jpeg', 0.9);
    }

    function retakePhoto() {
        document.getElementById('capturedImagePreview').style.display = 'none';
        document.getElementById('video').style.display = 'block';
        capturedImageData = null;
        document.getElementById('file').value = '';
    }
</script>
@endsection
