@extends('layouts.app')

@php
    use App\Models\EmailTemplate;
    use App\Enums\Language;
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>Edit Email Template</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="EmailTemplateContent p-2 w-75 card col">
        <form data-validate method="POST" action="{{ route('email-templates.update', $data->id) }}" id="emailTemplateForm">
            @csrf
            @method('PUT')

            <div class="m-2 row form-group">
                <div class="col">

                    <label for="name" class="form-label">Email Template Name*</label>
                    <input name="name" type="text" value="{{ $data->name ?? old('name') }}" id="name"
                        class="form-control" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="col">

                    <label for="subject" class="form-label">Subject*</label>
                    <input name="subject" type="text" value="{{ $data->subject ?? old('subject') }}" id="subject"
                        class="form-control" required>
                    @error('subject')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>
            </div>
            <div class="m-2 row form-group">
                <div class="col">
                    @include('Partials.language-dropdown', ['language' => $data->language])
                </div>

                <div class="col">
                    @include('Partials.status-dropdown', ['status' => $data->status])
                </div>

                {{-- <div class="col">
                    @include('Partials.role-dropdown', ['role' => $data->send_to])
                </div> --}}
            </div>

            <div class="row m-2 mb-3">
                <div class="col">
                    <label for="language" class="form-label">Content*</label>
                    {!! $data->trix('EmailTemplateContent', ['hideButtonIcons' => ['attach']]) !!}
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
                <button type="submit" id="updateEmailTemplateSubmit" class="btn btn-primary w-100 m-2">Update</button>
            </div>
        </form>
    </div>
@stop

@push('js')
    @trixassets
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
