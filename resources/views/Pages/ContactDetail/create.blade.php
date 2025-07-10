@extends('layouts.app')

@php
    use App\Enums\ContactType;
@endphp

@section('content_header')
    <div class="row mb-2 justify-content-between">
        <div class="col-sm-6">
            <h1>New Contact Detail</h1>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-8">
            <form data-validate class="card p-3" id="newBranchForm" action="{{ route('contact-details.store') }}"
                method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col">
                        <label for="name" class="col col-form-label">Heading*</label>
                        <div class="col">
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col">
                        <label for="buttonText" class="col  col-form-label">Button Text*</label>
                        <div class="col">
                            <input type="text" name="buttonText" class="form-control" id="buttonText"
                                value="{{ old('buttonText') }}" required>
                            @error('buttonText')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="contactType" class="col  col-form-label">Contact Type*</label>
                    <div class="col">
                        <select class="custom-select" name="contactType" id="contactType" required>
                            @foreach (ContactType::cases() as $case)
                                <option {{ $case->value == old('contactType') ? 'selected' : '' }}
                                    value="{{ $case->value }}">{{ $case->name }}</option>
                            @endforeach
                        </select>

                        @error('contactType')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="contactInput" class="col  col-form-label">Contact*</label>
                    <div class="col">
                        <input type='text' name="contactInput" class="form-control" id="contactInput"
                            value="{{ old('contactInput') }}" required>
                        @error('contactInput')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="actionUrl" class="col  col-form-label">Action URL*</label>
                    <div class="col">
                        <input type="text" name="actionUrl" class="form-control" id="actionUrl"
                            value="{{ old('actionUrl') }}" required>
                        @error('actionUrl')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="priority" class="col  col-form-label">Priority*</label>
                    <div class="col">
                        <input type="number" name="priority" class="form-control" id="priority" min="1"
                            max="6" value="{{ old('priority') }}" required>
                        @error('priority')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
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
