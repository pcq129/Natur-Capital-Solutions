@extends('layouts.app')

@php
    use App\Models\CmsPage;
    use App\Enums\Language;
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>CMS Pages</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="CMSContent p-2 w-75 card col">

        <form data-validate method="POST" action="{{ route('cms-pages.update', $data->id) }}" id="cmsPageForm">
            @csrf
            @method('PUT')

            <div class="m-2 row form-group">
                <div class="col">

                    <label for="name" class="form-label">CMS Page Name*</label>
                    <input name="name" type="text" value="{{ $data->name ?? old('name') }}" id="name"
                        class="form-control" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col">
                    <label for="language" class="form-label">Language*</label>
                    <select id="status" name="language" class="custom-select" value="{{ $data->language }}" required>
                        <option selected value="{{ Language::ENGLISH }}">English
                        </option>
                        <option value="{{ Language::FRENCH }}">French</option>
                    </select>
                    @error('language')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col">
                    @include('Partials.status-dropdown', ['status' => $data->status])
                </div>
            </div>

            <div class="row m-2 mb-3">
                <div class="col">
                    <label for="language" class="form-label">Content*</label>
                    {!! $data->trix('cmsText', ['hideButtonIcons' => ['attach']]) !!}
                </div>
            </div>
            <div class="row m-2 mb-3">
                <div class="col">
                    @error('cmspage-trixFields.cmsText')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row m-2 mb-3">
                <button type="submit" id="updateCmsPageSubmit" class="btn btn-primary w-100 m-2">Update</button>
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
