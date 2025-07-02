boilerplate for new view

@extends('layouts.app')

@php
    // use App\Models\EmailTemplate;
    // use App\Enums\Status;

    //* or any other dependencies
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>CMS Pages</h1>
        </div>
        <div>
            <a class="btn btn-success bi bi-plus" href="{{ route('products.create') }}"></a>
        </div>
    </div>
@stop


@section('content')
@stop

@push('css')
@endpush

@push('js')
@endpush