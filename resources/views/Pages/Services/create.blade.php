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
    <div class="card">
        <form enctype="multipart/form-data" id="newServiceForm" name="newServiceForm" method="POST"
            action="{{ route('services.store') }}" class="p-2 mb-0 mt-1">
            @csrf
            @method('POST')
            <div class="col">
                <div class="form-group">
                    <label for="serviceName">Service Name*</label>
                    <input name="serviceName" id="serviceName" value="{{ old('serviceName') }}" type="text"
                        class="form-control" required>
                    @error('serviceName')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="serviceDescription">Description*</label>
                    <textarea name="serviceDescription" id="serviceDescription" value="{{ old('serviceName') }}" type="text"
                        class="form-control" required></textarea>
                    @error('buttonText')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Service Icon*</label>
                    <div class="d-flex justify-content-between">
                        <div class="input-group w-50">
                            <div class="serviceIcon">
                                <input type="file" name="serviceIcon" accept=".jpg, .jpeg, .png" id="serviceIcon">
                                <label class="border rounded p-1" for="serviceIcon">Choose file</label>
                                <br>
                                <span class="text-danger" id="warning"></span>
                                @error('serviceIcon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div id="serviceIconPreviewContainer">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <label class="form-label px-auto mb-0 mt-1">Sections</label>
                    <button type="button" class="btn" id="add-field-btn">+ Add Field</button>
                </div>
                <div class="form-group">
                    <div id="dynamic-fields-wrapper">
                        <div class="mt-3">Section 1</div>

                        <input id="sectionName[0]" value="{{ old('serviceName') }}" name="sectionName[0]" type="text"
                            class="form-control mb-2 mt-2" placeholder="Section Name" required>
                        @trix(\App\Models\ServiceSection::class, '0')
                    </div>
                    @error('sectionName[0]')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <template id="section-template">
                    <div class="form-group section-body">
                        <div class="mt-3 d-flex justify-content-between">
                            <span>Section __INDEX__</span>
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
                    <button type="submit" class="btn btn-success w-100" id="ServiceFinalSubmission">Submit</button>
                </div>
            </div>
        </form>
    </div>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
@endpush



@push('js')
    @trixassets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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



            $(document).on('click', '.bi-x', function() {
                $(this).closest('.form-group').remove();
            });

            let counter = 2;

            document.getElementById('add-field-btn').addEventListener('click', function() {
                const wrapper = document.getElementById('dynamic-fields-wrapper');
                const template = document.getElementById('section-template').innerHTML;
                const html = template.replace(/__INDEX__/g, counter);
                wrapper.insertAdjacentHTML('beforeend', html);
                counter++;
            });


            const finalSubmit = $('#ServiceFinalSubmission');
            const newServiceForm = $('#newServiceForm');
            newServiceForm.on('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                console.log('start');
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
                            toastr.error('An unexpected error occurred. Please try again later.');
                            console.log(response);
                        }
                    },
                    success: function(data) {
                        window.location.href = "{{ route('services.index') }}";
                    }
                });
            });
        });
    </script>
@endpush
