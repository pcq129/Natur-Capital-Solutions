@extends('layouts.app')

@php
    use App\Constants\ServiceConstants as CONSTANTS;
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
    <div class="modal fade" id="{{ CONSTANTS::SERVICE_DELETE_MODAL_ID }}" tabindex="-1"
        aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteServiceForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="serviceName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
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
                }, {
                    name: 'actions',
                    data: 'actions',
                    className: 'text-center'
                }],
                order: [
                    [0, 'desc']
                ]
            })

            $("#{{ CONSTANTS::SERVICE_DELETE_MODAL_ID }}").on('show.bs.modal', function(event) {
                console.log('event triggered');
                const button = $(event.relatedTarget);
                const serviceId = button.data('id');
                const serviceName = button.data('name');

                const form = $('#deleteServiceForm');
                const action = '{{ route('services.destroy', ':id') }}'.replace(':id', serviceId);
                form.attr('action', action);

                $('#serviceName').text(serviceName);
            });
        })
    </script>
@endpush
