@extends('layouts.app')

@php
    use App\Enums\Status;
    use App\Models\Product;
    use App\Constants\ContactDetailConstants as CONSTANTS;
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1><strong>Contact Details</strong></h1>
        </div>
        <div>
            <a class="btn btn-success bi bi-plus" href="{{ route('contact-details.create') }}"></a>
        </div>
    </div>
@stop


@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered display table-striped table-hover" id="contactDetailTable"
                    style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Contact Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="{{ CONSTANTS::CONTACT_DETAIL_DELETE_MODAL_ID }}" tabindex="-1"
        aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteProductForm">
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
                        Are you sure you want to delete <strong id="productName"></strong>?
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
            let table = $('#contactDetailTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('contact-details.index') }}',
                    type: 'GET',
                    headers: {
                        'Accept': 'Application/JSON'
                    }
                },
                columns: [{
                        name: 'name',
                        data: 'name',
                        className: 'text-center',
                    }, {
                        name: 'contact_type',
                        data: 'contact_type',
                        className: 'text-center'
                    }, {
                        name: 'status',
                        data: 'status',
                        className: 'text-center'
                    }, {
                        name: 'priority',
                        data: 'priority',
                        className: 'text-center'
                    },
                    {
                        name: 'actions',
                        data: 'actions',
                        className: 'text-center'
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            })

            $("#{{ CONSTANTS::CONTACT_DETAIL_DELETE_MODAL_ID }}").on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const productId = button.data('id');
                const productName = button.data('name');

                const form = $('#deleteProductForm');
                const action = '{{ route('contact-details.destroy', ':id') }}'.replace(':id', productId);
                form.attr('action', action);

                $('#productName').text(productName);
            });
        })
    </script>
@endpush
