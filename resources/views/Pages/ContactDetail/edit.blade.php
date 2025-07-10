@extends('layouts.app')

@php
    use App\Enums\ContactType;
@endphp

@section('content_header')
    <div class="row mb-2 justify-content-between">
        <div class="col-sm-6">
            <h1>Edit Contact Detail</h1>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-8">
            <form data-validate class="card p-3" id="newBranchForm"
                action="{{ route('contact-details.update', $contactDetail->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" value="{{ $contactDetail->id }}" name="contactDetailId">

                <div class="form-row">
                    <div class="form-group col">
                        <label for="name" class="col col-form-label">Heading*</label>
                        <div class="col">
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name') ?? $contactDetail->name }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col">
                        <label for="buttonText" class="col  col-form-label">Button Text*</label>
                        <div class="col">
                            <input type="text" name="buttonText" class="form-control" id="buttonText"
                                value="{{ old('buttonText') ?? $contactDetail->button_text }}" required>
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
                            @if (old('contactType')!==null)

                                @foreach (ContactType::cases() as $case)
                                    <option {{ $case->value == old('contactType') ? 'selected' : '' }}
                                        value="{{ $case->value }}">{{ $case->name }}</option>
                                @endforeach
                            @else
                                @foreach (ContactType::cases() as $case)
                                    <option {{ $case->value == $contactDetail->contact_type->value ? 'selected' : '' }}
                                        value="{{ $case->value }}">{{ $case->name }}</option>
                                @endforeach
                            @endif

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
                            value="{{ old('contactInput') ?? $contactDetail->contact }}" required>
                        @error('contactInput')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="actionUrl" class="col  col-form-label">Action URL*</label>
                    <div class="col">
                        <input type="text" name="actionUrl" class="form-control" id="actionUrl"
                            value="{{ old('actionUrl') ?? $contactDetail->action_url }}" required>
                        @error('actionUrl')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="priority" class="col  col-form-label">Priority*</label>
                    <div class="col">
                        <input type="number" name="priority" class="form-control" id="priority" min="1"
                            max="6" value="{{ old('priority') ?? $contactDetail->priority }}" required>
                        @error('priority')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                   <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            {{ $contactDetail->status->value == '1' ? 'checked' : '' }} value="1" id="status"
                            name="status">
                        <label class="form-check-label " for="status">
                            Active
                        </label>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                   </div>
                </div>

                <button type="submit" id="newBranchFormSubmit" class="btn btn-primary m-2">Update</button>
            </form>
        </div>
    </div>
@stop
