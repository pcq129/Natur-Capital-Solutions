@extends('mail.common.layout')

{{--
@section('subject')
    {!! $emailSubject !!}
@endsection --}}

@section('greeting')
    {{-- @if (isset($data['user'])) --}}
        Hello&nbsp;<strong>{{ $data['user'] ?? '[USER_NAME]' }}</strong>
    {{-- @endif --}}
@endsection

@section('message')
    {!! $htmlContent !!}
@endsection
