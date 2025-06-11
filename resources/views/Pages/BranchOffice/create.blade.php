@extends('layouts.app')

@section('content_header')
    <div class="row mb-2 justify-content-between">
        <div class="col-sm-6">
            <h1>Add New Branch</h1>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-8">
            <form data-validate class="card p-3" id="newBranchForm" action="{{ route('branchoffices.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col">
                        <label for="name" class="col col-form-label">Office Name*</label>
                        <div class="col">
                            <input type="text" name="name" class="form-control" id="branchName"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col">
                        <label for="branchLocation" class="col  col-form-label">Location*</label>
                        <div class="col">
                            <input type="text" name="location" class="form-control" id="branchLocation"
                                value="{{ old('location') }}" required>
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="branchEmail" class="col  col-form-label">Email*</label>
                    <div class="col">
                        <input type="text" name="email" class="form-control" id="branchEmail"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="branchMobile" class="col  col-form-label">Contact Number*</label>
                    <div class="col">
                        <input type="number" name="mobile" class="form-control" id="branchMobile"
                            value="{{ old('mobile') }}" required>
                        @error('mobile')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="branchAddress" class="col  col-form-label">Address*</label>
                    <div class="col">
                        <textarea required type="text" name="address" class="form-control" id="branchAddress" value="{{ old('address') }}">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" id="newBranchFormSubmit" class="btn btn-primary m-2">Create</button>
            </form>
        </div>
    </div>
@stop
