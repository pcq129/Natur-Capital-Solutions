@extends('layouts.app')

@php
    // use App\Models\EmailTemplate;
    // use App\Enums\Status;

    //* or any other dependencies
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1><strong>Services</strong></h1>
        </div>
        <div>
            <a class="btn btn-success bi bi-plus" href="{{ route('services.create') }}"></a>
        </div>
    </div>
@stop


@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered display table-striped table-hover" id="servicesTable" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            {{-- <th>Id</th> --}}
                            <th class="text-center">Service Name</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Icon</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop


@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#servicesTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('services.index') }}',
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                },
                columns: [{
                    name: 'name',
                    data: 'name',
                    className: 'text-center',
                }, {
                    name: 'description',
                    data: 'description',
                    className: 'text-center'
                }, {
                    name: 'icon',
                    data: 'icon',
                    className: 'text-center'
                },{
                    name: 'actions',
                    data: 'actions',
                    className: 'text-center'
                }],
                order: [
                    [0, 'desc']
                ]
            })
        })
    </script>
@endpush
