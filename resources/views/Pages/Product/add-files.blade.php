@extends('layouts.app')

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>Add Files to Product</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form data-validate id="addProductFiles" action="{{ route('products.add-files', ['product' => $productId]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group productVideo h-50">
                    <h5><strong>Video Instruction</strong></h5>
                    <label class="border border-secondary px-3 rounded" for="videoInstruction">Select Video</label>
                    <input type="file" class="form-control border border-secondary" id="videoInstruction"
                        name="videoInstruction" accept=".mp4">
                    <small id='warning' class="form-text text-danger" hidden></small>
                </div>

                <div class="form-group productDocuments h-50">
                    <h5><strong>Documents/Downloads</strong></h5>
                    <label class="border border-secondary px-3 rounded" for="files">Select Files</label>
                    <input type="file" class="form-control border border-secondary" id="files" name="files[]"
                        accept=".pdf" multiple required>
                    <small class="form-text text-muted">You can upload multiple documents (max size: 8 MB each).</small>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Upload Files</button>
            </form>
        </div>
    </div>
@stop


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const videoInput = document.getElementById('videoInstruction');
    const filesInput = document.getElementById('files');

    // Video preview
    videoInput.addEventListener('change', function () {
        // Remove old preview if any
        const oldPreview = document.querySelector('.video-preview');
        if (oldPreview) oldPreview.remove();

        const file = this.files[0];
        if (!file) return;

        const previewContainer = document.createElement('div');
        previewContainer.classList.add('video-preview', 'mt-3');

        const video = document.createElement('video');
        video.controls = true;
        video.width = 400;

        const source = document.createElement('source');
        source.src = URL.createObjectURL(file);
        source.type = file.type;

        video.appendChild(source);
        previewContainer.appendChild(video);

        document.querySelector('.productVideo').appendChild(previewContainer);
    });

    // Document filenames preview
    filesInput.addEventListener('change', function () {
        // Remove old file list if any
        const oldList = document.querySelector('.file-name-list');
        if (oldList) oldList.remove();

        const files = this.files;
        if (!files.length) return;

        const listContainer = document.createElement('ul');
        listContainer.classList.add('file-name-list', 'mt-2');

        for (let i = 0; i < files.length; i++) {
            const li = document.createElement('li');
            li.textContent = files[i].name;
            listContainer.appendChild(li);
        }

        document.querySelector('.productDocuments').appendChild(listContainer);
    });
});
</script>
@endpush

