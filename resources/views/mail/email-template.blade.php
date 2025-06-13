@extends('mail.common.layout')

@section('message')
    {{-- <x-mail::table>
        |Laravel|Table|Example|
        |:-|:-:|-:|
        |Col 2 is|Centered|$10|
        |Col 3 is|Right-Aligned|$20|
    </x-mail::table> --}}
    {{-- <x-mail::button :url="'adsafsgd'">
View Order
</x-mail::button> --}}

    {!! $htmlContent !!}
@endsection


@section('subject')
    {!! $emailSubject !!}
@endsection

@section('greeting')
    Hello&nbsp;<strong>{{ $user ?? '[USER_NAME]' }}</strong>
@endsection
