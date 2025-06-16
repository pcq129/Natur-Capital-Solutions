@extends('layouts.app')

@section('content_header')
<div class="row mb-2 text-dark justify-content-between">
    <div class="col-sm-6">
        <h1>Email Preview</h1>
    </div>
</div>
@stop

@section('content')
<div class="card">
    {!! $emailHtml !!}
</div>
@stop

@push('css')

@endpush

@push('js')

@endpush
