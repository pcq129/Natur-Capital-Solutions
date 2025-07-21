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
            <form data-validate class="p-2" id="updateServiceForm" action="{{ route('services.update', $service->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="removedSections" id="removedSections" value="[]">

                <div class="form-group ">
                    <label for="serviceName" class="col col-form-label">Name*</label>
                    <div class="col">
                        <input type="text" name="serviceName" class="form-control" id="serviceName"
                            value="{{ old('name') ?? $service['name'] }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="form-group">
                    <label for="serviceDescription" class="col col-form-label">Description*</label>
                    <div class="col">
                        <textarea name="serviceDescription" class="form-control" id="serviceDescription" required>{{ old('description') ?? $service['description'] }}</textarea>
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
                                    <i class="bi bi-x btn btn-danger savedSecition" data-id="{{ $section->id }}"></i>
                                </div>


                                <input id="sectionName[{{ $section->id }}]"
                                    value="{{ old('serviceName') ?? $section->heading }}"
                                    name="currentServiceSectionName[{{ $section->id }}]" type="text"
                                    class="form-control mb-2 mt-2" placeholder="Section Name" required>
                                <input id="serviceSection-trixField[{{ $section->id }}]" value="{{ $section->content }}"
                                    type="hidden" name="currentServiceSection-trixFields[{{ $section->id }}]">
                                <trix-editor input="serviceSection-trixField[{{ $section->id }}]"
                                    data-id="{{ $section->id }}"></trix-editor>
                                @error('sectionName[{{ $section->id }}]')
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

                <div class="form-group">
                    <button type="submit" class="btn btn-success w-100" id="updateServiceFinalSubmission">Submit</button>
                </div>
            </form>
        </div>
    </div>
@stop


@push('css')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
@endpush



@push('js')
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            let removedSections = [];
            let deletedFiles = [];
            let addedFiles = [];
            let sectionCount = {{ count($serviceSections) + 1 }};
            $(document).on('click', '.bi-x', function() {

                const sectionId = $(this).data('id');
                console.log(sectionId);
                if (sectionId) {
                    removedSections.push(sectionId);
                    $('#removedSections').val(JSON.stringify(removedSections));
                }
                if (sectionCount <= 2) {
                    toastr.error('At least one section is required.');
                } else {
                    $(this).closest('.form-group').remove();
                    sectionCount--;
                }
            });

            let counter = {{ count($serviceSections) + 1 }};
            document.getElementById('add-field-btn').addEventListener('click', function() {
                console.log('add btn clicked');
                const wrapper = document.getElementById('dynamic-fields-wrapper');
                const template = document.getElementById('section-template').innerHTML;
                const html = template.replace(/__INDEX__/g, counter);
                wrapper.insertAdjacentHTML('beforeend', html);
                counter++;
                sectionCount++;
            });

            document.addEventListener('trix-attachment-remove', function(e) {
                fileUrl = e.attachment.attachment.previewURL;
                // const deleteFileUrl = "{{ route('attachment.remove') }}"+"?file=" + file;
                deletedFiles.push({
                   id: e.target.dataset.id ?? 0,
                   fileUrl:  fileUrl
                });
                return;

                // $.ajax({
                //     type: "GET",
                //     url: deleteFileUrl,
                //     processData: false,
                //     contentType: false,
                //     dataType: 'json',
                //     success: function(response) {
                //         if (response.status === 200) {
                //             console.log('File removed successfully');
                //         } else {
                //             console.log(response);
                //         }
                //     },
                //     error: function(error) {
                //         console.log(error);
                //         toastr.error('Error while removing attachment file');
                //     },

                // });
            })

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

            const finalSubmit = $('#updateServiceFinalSubmission');
            const updateServiceForm = $('#updateServiceForm');
            updateServiceForm.on('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                const trixEditors = $('input[type="hidden"][name^="servicesection-trixFields"]').each(
                    function() {
                        if ($(this).val().trim() === '') {
                            isValid = false;
                            return false;
                        } else {
                            isValid = true;
                        }
                    });
                if (!isValid) {
                    toastr.error('Please fill all the section before proceeding.');
                    return false;
                } else {
                    console.log('submit success')
                }

                var actionUrl = $(this).attr('action');

                var formData = new FormData(this);
                formData.append('deletedFiles', JSON.stringify(deletedFiles));
                formData.append('addedFiles', JSON.stringify(addedFiles));
                console.log(addedFiles, deletedFiles);
                console.log(removedSections);

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    error: function(response) {
                        if (response.status === 400) {
                            const errors = response.responseJSON.message;
                            for (const field in errors) {
                                errors[field].forEach(function(errorMessage) {
                                    toastr.error(errorMessage);
                                });
                            }
                        } else {
                            toastr.error(
                                'An unexpected error occurred. Please try again later.');
                            console.log(response);
                        }
                    },
                    success: function(data) {
                        console.log(data);
                        window.location.href = data.redirect ?? "{{ route('services.index') }}";
                    }
                });
            });

            (function() {

                addEventListener("trix-attachment-add", function(event) {
                    if (event.attachment.file) {
                        console.log(event);
                        uploadFileAttachment(event.attachment, event.target.dataset.id ?? 0);
                    }
                })

                function uploadFileAttachment(attachment, sectionId) {
                    uploadFile(attachment.file, setProgress, setAttributes)

                    function setProgress(progress) {
                        attachment.setUploadProgress(progress)
                    }

                    function setAttributes(attributes) {
                        attachment.setAttributes(attributes)
                        addedFiles.push({
                            id: sectionId,
                            fileUrl: attributes.url,
                        })
                    }

                }

                function uploadFile(file, progressCallback, successCallback) {
                    var key = createStorageKey(file)
                    var formData = createFormData(key, file)

                    $.ajax({
                        type: "POST",
                        url: '{{ route('laravel-trix.store') }}',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();

                            xhr.upload.addEventListener("progress", function(event) {
                                if (event.lengthComputable) {
                                    var progress = (event.loaded / event.total) * 100;
                                    progressCallback(progress);
                                }
                            }, false);

                            return xhr;
                        },
                        success: function(data, textStatus, jqXHR) {
                            if (jqXHR.status === 201) {

                                var attributes = {
                                    url: data.url,
                                    href: data.url
                                };
                                successCallback(attributes);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Upload error:", errorThrown);
                        }

                    });
                }

                function createStorageKey(file) {
                    var date = new Date()
                    var day = date.toISOString().slice(0, 10)
                    var name = date.getTime() + "-" + file.name
                    return ["tmp", day, name].join("/")
                }

                function createFormData(key, file) {
                    var data = new FormData();
                    data.append("key", key);
                    data.append("Content-Type", file.type);
                    data.append("file", file);
                    data.append("modelClass", "App\\Models\\ServiceSection");
                    data.append("field", "{{ $service->id }}");
                    return data;
                }
            })();
        })
    </script>
@endpush
