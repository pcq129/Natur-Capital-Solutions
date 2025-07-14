@extends('layouts.app')

@php
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1><strong>Update Service</strong></h1>
        </div>
    </div>
@stop


@section('content')
    <div class="col">
        <div class="card">
            <form data-validate class="p-2" id="newBranchForm" action="{{ route('services.update', $service->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group ">
                    <label for="name" class="col col-form-label">Name*</label>
                    <div class="col">
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ old('name') ?? $service['name'] }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="form-group">
                    <label for="description" class="col col-form-label">Description*</label>
                    <div class="col">
                        <textarea name="description" class="form-control" id="description" required>{{ old('description') ?? $service['description'] }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="form-group">
                    <div class="col">
                        <div>
                            <label for=""> Service Icon*</label>
                            <div class="d-flex justify-content-between">
                                <div class="input-group w-50">
                                    <div class="serviceIcon">
                                        <input type="file" name="serviceIcon" accept=".jpg, .jpeg, .png"
                                            id="serviceIcon">
                                        <label class="" for="serviceIcon">Choose file</label>
                                        <br>
                                        <span class="text-danger" id="warning"></span>
                                        @error('serviceIcon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div id="serviceIconPreviewContainer">

                                        </div>
                                    </div>
                                </div>
                                <div class="previousServiceIconPreview">
                                    <span class="d-block"><strong>Current Icon</strong></span>
                                    <img src="/{{ $service['icon'] }}" height="100px" width="100px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col">
                        <label for="serviceStatus">Active</label>
                        <input type="checkbox" name="status" id="serviceStatus" value="1">
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" id="newBranchFormSubmit" class="btn btn-primary m-2">Create</button>
            </form>
        </div>
    </div>
@stop



@push('css')
@endpush
@push('js')
<script>
    $(document).ready(function() {

        document.getElementById('serviceIcon').addEventListener('change', function(e) {

            const oldPreview = document.querySelector('.serviceIconPreview');
            if (oldPreview) oldPreview.remove();

            const file = this.files[0];
            if (!file) return;

            const maxSizeMB = 2;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;

            if (file.size > maxSizeBytes) {
                $('#warning').removeAttr('hidden');
                $('#warning').text(`File size exceeds ${maxSizeMB} MB. Please upload a smaller file.`);
                this.value = ''; // Clear the file input
                return;
            }else{
                $('#warning').prop('hidden', true);
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.createElement('div');
                previewContainer.classList.add('serviceIconPreview', 'image-preview');

                const wrapper = document.createElement('div');
                wrapper.classList.add('image-wrapper', 'm-1', 'd-inline');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.width = '200px';

                wrapper.appendChild(img);
                previewContainer.appendChild(wrapper);
                document.querySelector('#serviceIconPreviewContainer').appendChild(
                previewContainer);
            };

            reader.readAsDataURL(file);


        });
    })
</script>
@endpush
