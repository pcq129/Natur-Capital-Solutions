@extends('layouts.app')

@php

    use App\Constants\ServiceConstants as CONSTANTS;
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
                                        <label class="border rounded p-2" for="serviceIcon">Choose file</label>
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
                                    <img src="{{ $service['icon'] }}" height="100px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex justify-content-between">
                        <label class="form-label px-auto mb-0 mt-1">Sections</label>
                        <button type="button" class="btn" id="add-field-btn">+ Add Field</button>
                    </div>
                </div>
                <div class="col">
                    <div id="dynamic-fields-wrapper">
                        @foreach ($serviceSections as $section)
                            <div class="form-group">

                                <div class="mt-3 d-flex justify-content-between">
                                    <span><strong>Section Name: </strong>{{ $section->heading }}</span>
                                    <i class="bi bi-x btn btn-danger"></i>
                                </div>


                                <input id="sectionName[{{ $section->priority }}]" value="{{ old('serviceName') ?? $section->heading }}"
                                    name="sectionName[{{ $section->priority }}]" type="text" class="form-control mb-2 mt-2"
                                    placeholder="Section Name" required>
                                <input id="serviceSection-trixField[{{ $section->priority }}]" value="{{ $section->content }}" type="hidden" name="serviceSection-trixFields[{{ $section->priority }}]">
                                <trix-editor input="serviceSection-trixField[{{ $section->priority }}]"></trix-editor>
                                @error('sectionName[{{ $section->priority }}]')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                </div>

                <template id="section-template">
                    <div class="form-group section-body">
                        <div class="mt-3 d-flex justify-content-between">
                            <span>Additional Section __INDEX__</span>
                            <i class="bi bi-x btn btn-danger"></i>
                        </div>
                        <div class="dynamic-field-wrapper">
                            <input name="sectionName[__INDEX__]" type="text" class="form-control mb-2 mt-3"
                                placeholder="Section Name" required>
                            @trix(\App\Models\ServiceSection::class, '__INDEX__')
                        </div>
                    </div>
                    @error('sectionName[__INDEX__]')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </template>



                <div class="form-group">
                    <div class="col">
                        <label for="serviceStatus">Active</label>
                        <input type="checkbox" name="status" id="serviceStatus" value="1"
                            {{ $service->status ? 'checked' : '' }}>
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
    @trixassets

    <script>
        $(document).ready(function() {

            $(document).on('click', '.bi-x', function() {
                $(this).closest('.form-group').remove();
            });

            let counter = {{ count($serviceSections) + 1 }};
                document.getElementById('add-field-btn').addEventListener('click', function() {
                console.log('add btn clicked');
                const wrapper = document.getElementById('dynamic-fields-wrapper');
                const template = document.getElementById('section-template').innerHTML;
                const html = template.replace(/__INDEX__/g, counter);
                wrapper.insertAdjacentHTML('beforeend', html);
                counter++;
            });

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
                } else {
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
