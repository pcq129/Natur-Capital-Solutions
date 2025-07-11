@extends('layouts.app')
@php
    // use App\Models\EmailTemplate;
    // use App\Enums\Status;

    //* or any other dependencies
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1><strong>Create new Service</strong></h1>
        </div>
    </div>
@stop


@section('content')
    <div class="row">
        <div class="col-8">
            <form data-validate class="card p-3" id="newBranchForm" action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group ">
                    <label for="name" class="col col-form-label">Name*</label>
                    <div class="col">
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                            required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="form-group">
                    <label for="description" class="col col-form-label">Description*</label>
                    <div class="col">
                        <textarea name="description" class="form-control" id="description" value="{{ old('description') }}" required></textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="form-group">
                    <div class="col">
                        <label for="serviceIcon"> Service Icon*</label>
                        <div class="input-group w-50">
                            <div class="serviceIcon">
                                <input type="file" name="serviceIcon" accept=".jpg, .jpeg, .png" required id="serviceIcon">
                                <label class="" for="serviceIcon">Choose file</label>
                                <br>
                                <span class="text-danger" id="warning"></span>
                                @error('serviceIcon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div id="serviceIconPreviewContainer">
                        </div>
                    </div>
                </div>

                {{-- <div class="form-group">
                    <label for="branchAddress" class="col  col-form-label">Address*</label>
                    <div class="col">
                        <textarea required type="text" name="address" class="form-control" id="branchAddress" value="{{ old('address') }}">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}

                <button type="submit" id="newBranchFormSubmit" class="btn btn-primary m-2">Create</button>
            </form>
        </div>
    </div>
@stop

@push('adminlte_css_pre')
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
