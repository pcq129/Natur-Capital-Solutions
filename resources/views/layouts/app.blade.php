@extends('adminlte::page')
@vite('resources/css/app.css')

@push('js')
    <script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/45.1.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/45.1.0/"
        }
    }
</script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const forms = document.querySelectorAll('form[data-validate]');

            forms.forEach(form => {
                const submitBtn = form.querySelector('[type="submit"]');

                if (!submitBtn) return;

                function toggleButton() {
                    submitBtn.disabled = !form.checkValidity();
                }

                toggleButton();
                form.addEventListener('input', toggleButton);
            });
        });
    </script>
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.1.0/ckeditor5.css" />
@endpush


{{-- boilerplate for new view --}}

{{-- @extends('layouts.app')

@php
    // use App\Models\EmailTemplate;
    // use App\Enums\Status;

    //* or any other dependencies
@endphp

@section('content_header')
@stop


@section('content')
@stop



@push('js')
@endpush --}}
