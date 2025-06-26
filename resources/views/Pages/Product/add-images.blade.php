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
            <form data-validate id="addProductFiles" action="{{ route('products.add-images', ['product' => $productId]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="form-group productMainImage h-50">
                    <h5><strong>List-Page/Main Image</strong></h5>
                    <label class="border border-secondary px-3 rounded" for="productImage">Select Product Image</label>
                    <input type="file" class="form-control border border-secondary" id="productImage" name="productImage"
                        accept=".jpg,.jpeg,.png" required>
                    <small id='warning' class="form-text text-danger" hidden></small>
                </div>



                <div class="form-group productDetailImages h-50">
                    <h5><strong>Product Details Image</strong></h5>
                    <label class="border border-secondary px-3 rounded" for="productDetailImages">Select Extra Images</label>
                    <input type="file" class="form-control border border-secondary" id="productDetailImages" name="productDetailImages[]"
                        accept=".jpg,.jpeg,.png" multiple required>
                    <small class="form-text text-muted">You can upload multiple images (max size: 8 MB each).</small>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Upload Images</button>
            </form>
        </div>
    </div>
@stop


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('productImage').addEventListener('change', function() {
                // Remove existing preview if any
                const oldPreview = document.querySelector('.productImagePreview');
                if (oldPreview) oldPreview.remove();

                const file = this.files[0];
                if (!file) return;

                const maxSizeMB = 8;
                const maxSizeBytes = maxSizeMB * 1024 * 1024;

                if (file.size > maxSizeBytes) {
                    $('#warning').removeAttr('hidden');
                    $('#warning').text(`File size exceeds ${maxSizeMB} MB. Please upload a smaller file.`);
                    this.value = ''; // Clear the file input
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.classList.add('productImagePreview', 'image-preview');

                    const wrapper = document.createElement('div');
                    wrapper.classList.add('image-wrapper', 'm-1', 'd-inline');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '200px';

                    wrapper.appendChild(img);
                    previewContainer.appendChild(wrapper);
                    document.querySelector('.productMainImage').appendChild(previewContainer);
                };

                reader.readAsDataURL(file);
            });





            document.getElementById('files').addEventListener('change', function() {
                // Remove any previous previews
                const oldPreview = document.querySelector('.extraImagesPreview');
                if (oldPreview) oldPreview.remove();

                const previewContainer = document.createElement('div');
                previewContainer.classList.add('extraImagesPreview', 'image-preview', 'd-flex',
                    'flex-wrap');
                document.querySelector('.productDetailImages').appendChild(previewContainer);

                const maxSizeMB = 8;
                const maxSizeBytes = maxSizeMB * 1024 * 1024;

                const files = this.files;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    if (file.size > maxSizeBytes) {
                        alert(`File "${file.name}" exceeds ${maxSizeMB} MB limit and will be skipped.`);
                        continue;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('image-wrapper', 'm-1', 'd-inline');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-thumbnail');
                        img.style.width = '200px';

                        wrapper.appendChild(img);
                        previewContainer.appendChild(wrapper);
                    };

                    reader.readAsDataURL(file);
                }
            });

        });
    </script>
@endpush
