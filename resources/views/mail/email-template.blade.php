@extends('mail.common.layout')

{{--
@section('subject')
    {!! $emailSubject !!}
@endsection --}}

@section('greeting')
    Hello&nbsp;<strong>{{ $data['user'] ?? '[USER_NAME]' }}</strong>,
@endsection

@section('message')
    {!! $htmlContent !!}
@endsection
