@extends('layouts.app')
@php
    use App\Enums\Status;
@endphp
@section('content_header')
    <div class="row mb-2 justify-content-between">
        <div class="col-sm-6">
            <h1>Branch and Offices</h1>
        </div>

        <a class="btn ms-6 btn-success  bi bi-plus my-auto" href="{{ route('branchoffices.create') }}"></a>

    </div>
@stop
@section('content')


    <div class="card p-3">
        <div class="row align-items-center">
            <div class="col-auto d-flex align-items-center">
                <label for="statusFilter" class="mb-0 me-2 col">Filter by Status:</label>
                <select id="statusFilter" class="form-control ms-2" style="width: 200px;">
                    <option value="">All</option>
                    <option value="{{ Status::Active }}">Active</option>
                    <option value="{{ Status::Inactive }}">Inactive</option>
                    {{-- <option value="-1">Deleted</option> --}}
                    {{-- not accessing deleted values for now --}}
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered display table-striped table-hover" id="branchOfficeTable"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Office Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteBranchModal" tabindex="-1" aria-labelledby="deleteBranchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteBranchForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBranchModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="branchName"></strong>?
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
    <!-- Bootstrap 4 + DataTables Bootstrap 4 theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = $('#branchOfficeTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('branchoffices.index') }}',
                    type: 'GET',
                    headers: {
                        'Accept': 'Application/JSON'
                    },
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-center'
                    },
                    {
                        data: 'address',
                        name: 'address',
                        className: 'text-center'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'text-center'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'location',
                        name: 'location',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        className: 'text-center'
                    },
                ]
            })


            $('#statusFilter').on('change', function() {
                table.ajax.reload();
            })

            $('#deleteBranchModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const branchId = button.data('id');
                const branchName = button.data('name');

                const form = $('#deleteBranchForm');
                const action = '{{ route('branchoffices.destroy', ':id') }}'.replace(':id', branchId);
                form.attr('action', action);

                $('#branchName').text(branchName);
            });
        });
    </script>
@endpush
