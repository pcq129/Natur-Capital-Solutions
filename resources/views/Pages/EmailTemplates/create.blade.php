@extends('layouts.app')

@php
    use App\Models\EmailTemplate;
    use App\Enums\Language;
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>Create Email Template</h1>
        </div>
    </div>
@stop

@section('content')

    <div class="EmailTemplateContent p-2 w-75 card col">

        <form method="POST" action="{{ route('email-templates.store') }}" id="emailTemplateForm">
            @csrf
            @method('POST')

            <div class="m-2 row form-group">
                <div class="col">

                    <label for="name" class="form-label">Email Template Name*</label>
                    <input name="name" type="text" value="{{ old('name') }}" id="name" class="form-control"
                        required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col">

                    <label for="subject" class="form-label">Email Subject*</label>
                    <input name="subject" type="text" value="{{ old('subject') }}" id="subject" class="form-control"
                        required>
                    @error('subject')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>
            </div>
            <div class="m-2 row form-group">
                <div class="col">
                    @include('Partials.language-dropdown', ['language' => ''])
                </div>

                <div class="col">
                    @include('Partials.role-dropdown', ['role' => ''])
                    <span class="text-secondary">Role of user allowed to receive the email</span>
                </div>
            </div>

            <div class="row m-2 mb-3">
                <div class="col">
                    <label for="Content" class="form-label">Content*</label>
                    @trix(\App\Models\EmailTemplate::class, 'EmailTemplateContent', ['hideButtonIcons' => ['attach']])
                </div>
            </div>
            <div class="row m-2 mb-3">
                <div class="col">
                    @error('emailtemplate-trixFields.EmailTemplateContent')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row m-2 mb-3">
                <button type="submit" id="updateEmailTemplateSubmit" class="btn btn-primary w-100 m-2">Create</button>
            </div>
        </form>
    </div>
@stop

@push('js')
    @trixassets
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('emailTemplateForm');
            const submitBtn = document.getElementById(
            'updateEmailTemplateSubmit');
            const trixInput = document.getElementById('emailtemplate-EmailTemplateContent-new-model');

            function validateTrixContent() {
                const html = trixInput ? trixInput.value : '';
                const plainText = html.replace(/<[^>]*>?/gm, '').trim();
                const isValid = plainText.length >= 20;



                submitBtn.disabled = !isValid;
                return isValid;
            }

            // Validate on initial load
            validateTrixContent();

            // Validate whenever the Trix editor content changes
            document.addEventListener('trix-change', function() {
                validateTrixContent();
            });

            // Validate again on submit
            form.addEventListener('submit', function(e) {
                if (!validateTrixContent()) {
                    e.preventDefault();
                    alert('Email content must be at least 20 characters long.');
                }
            });
        });
    </script>
@endpush

@push('css')
    <style>
        trix-editor {
            min-height: 25em !important;
            max-height: 25em;
            overflow-y: auto;
        }
    </style>
@endpush
