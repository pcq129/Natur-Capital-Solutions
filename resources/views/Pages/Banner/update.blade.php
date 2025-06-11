@extends('layouts.app')
@php
    use App\Enums\Status;
@endphp

@section('content_header')
    <div class="row mb-2 justify-content-between">
        <div class="col-sm-6">
            <h1>Edit Banner</h1>
        </div>
    </div>
@stop
@section('content')

    <div class="row">
        <form data-validate class="card ms-4 col-8 p-3" action="{{ route('banners.update', $banner->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $banner->id }}">
            <div class="row mb-3">
                <div class="col-12">
                    <label for="name" class="form-label">Name*</label>
                    <input name="name" type="text" value="{{ old('name') ?? $banner->name }}" id="name"
                        class="form-control" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="overlay_heading" class="form-label">Overlay Heading*</label>
                    <input name="overlay_heading" value="{{ old('overlay_heading') ?? $banner->overlay_heading }}"
                        type="text" id="overlay_heading" class="form-control" required>
                    @error('overlay_heading')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="overlay_text" class="form-label">Overlay Sub-Text</label>
                    <textarea name="overlay_text" type="text" id="overlay_text" class="form-control" rows="4">{{ old('overlay_text') ?? $banner->overlay_text }}</textarea>
                    @error('overlay_text')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="banner_link" class="form-label">Banner Link*</label>
                    <input name="banner_link" value="{{ old('banner_link') ?? $banner->banner_link }}" type="text"
                        id="banner_link" class="form-control" required>
                    @error('banner_link')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 g-3">
                <div class="col-4">
                    <label class="form-label">Image</label>
                    <br>
                    <label for="file-upload" class="bannerImageUpload fw-bold mb-2">Select</label>
                    <input id="file-upload" name="image" type="file" accept=".jpg, .jpeg, .png" class="form-control">
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-4">
                    <label for="status">Status*</label><br>
                    <select id="status" name="status" class="custom-select w-50" required>
                        <option value="{{ Status::INACTIVE }}"
                            {{ $banner->status == Status::INACTIVE ? 'selected' : '' }}>Inactive
                        </option>
                        <option value="{{ Status::ACTIVE }}" {{ $banner->status == Status::ACTIVE ? 'selected' : '' }}>
                            Active</option>
                    </select>
                </div>

                <div class="col-4">
                    <label class="form-label">Priority/Order*</label>
                    <br>
                    <input name="priority" value="{{ old('priority') ?? $banner->priority }}" type="number"
                        class="form-control col-3" min="1" max="10" required>
                    @error('priority')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- not adding status field, considering newly added banner to be active by default --}}

            <div class="row mb-3">
                <div class="col-12">
                    <label for="bannerButtonOneText" class="form-label">Buttons*</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input name="banner_button_one_text"
                                value="{{ old('banner_button_one_text') ?? ($banner->buttons['button_one']['text'] ?? null) }}"
                                type="text" id="bannerButtonOneText" class="form-control" placeholder="Button Text"
                                required>
                            @error('banner_button_one_text')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input name="banner_button_one_action"
                                value="{{ old('banner_button_one_action') ?? ($banner->buttons['button_one']['link'] ?? null) }}"
                                type="text" id="bannerButtonOneAction" class="form-control"
                                placeholder="Button Action (Redirection Link)" required>
                            @error('banner_button_one_action')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <input type="text" name="banner_button_two_text"
                                value="{{ old('banner_button_two_text') ?? ($banner->buttons['button_two']['text'] ?? null) }}"
                                id="bannerButtonTwoText" class="form-control" placeholder="Button 2 Text">
                            @error('banner_button_two_text')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="banner_button_two_action"
                                value="{{ old('banner_button_two_action') ?? ($banner->buttons['button_two']['link'] ?? null) }}"
                                id="bannerButtonTwoAction" class="form-control"
                                placeholder="Button 2 Action (Redirection Link)">
                            @error('banner_button_two_action')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-12">
                    <label for="bannerLinkOneText" class="form-label">Links</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="banner_link_one_text"
                                value="{{ old('banner_link_one_text') ?? ($banner->links['link_one']['text'] ?? null) }}"
                                id="bannerLinkOneText" class="form-control" placeholder="Link Text">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="banner_link_one_action"
                                value="{{ old('banner_link_one_action') ?? ($banner->links['link_one']['link'] ?? null) }}"
                                id="bannerLinkOneAction" class="form-control"
                                placeholder="Link Action (Redirection Link)">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <input type="text" name="banner_link_two_text"
                                value="{{ old('banner_link_two_text') ?? ($banner->links['link_two']['text'] ?? null) }}"
                                id="bannerLinkTwoText" class="form-control" placeholder="Link 2 Text">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="banner_link_two_action"
                                value="{{ old('banner_link_two_action') ?? ($banner->links['link_two']['link'] ?? null) }}"
                                id="bannerLinkTwoAction" class="form-control"
                                placeholder="Link 2 Action (Redirection Link)">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        <banner class="col-3 color-primary m-3">
            {{ $banner->image }}
            <img src="{{ asset($banner->image) }}" alt="image not found or deleted" class="w-100">
        </banner>
    </div>
@stop
