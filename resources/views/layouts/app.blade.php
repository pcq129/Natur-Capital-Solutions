@extends('adminlte::page')
@vite('resources/css/app.css')

@push('js')
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
