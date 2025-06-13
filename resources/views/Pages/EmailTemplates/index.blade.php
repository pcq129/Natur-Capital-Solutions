@extends('layouts.app')

@php
    use App\Models\EmailTemplate;
    use App\Enums\Status;
@endphp


@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>Email Templates</h1>
        </div>
        <div>
            <a class="btn btn-success bi bi-plus" href="{{ route('email-templates.create') }}"></a>
        </div>
    </div>
@stop

@section('content')

    <div class="card p-3">
        <div class="row align-items-center">
            <div class="col-auto d-flex align-items-center">
                <label for="statusFilter" class="mb-0 me-2 col">Filter by Status:</label>
                <select id="statusFilter" class="form-control ms-2" style="width: 200px;">
                    <option value="">All</option>
                    <option value="{{ Status::ACTIVE }}">Active</option>
                    <option value="{{ Status::INACTIVE }}">Inactive</option>
                    {{-- <option value="-1">Deleted</option> --}}
                    {{-- not accessing deleted values for now --}}
                </select>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered display table-striped table-hover" id="emailTemplatesTable"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Template Name</th>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Send To</th>
                            <th class="text-center">Language</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Delete confirmation dialog --}}
    <div class="modal fade" id="{{ EmailTemplate::DELETE_MODAL_ID }}" tabindex="-1"
        aria-labelledby="deleteEmailTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteEmailTemplateForm">
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
                        Are you sure you want to delete <strong id="emailTemplateName"></strong>?
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
    @trixassets
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#emailTemplatesTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('email-templates.index') }}',
                    type: 'GET',
                    headers: {
                        'Accept': 'Application/JSON'
                    },
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        className: 'text-center'
                    },{
                        data: 'name',
                        name: 'name',
                        className: 'text-center'
                    }, {
                        data: 'subject',
                        name: 'subject',
                        className: 'text-center'
                    }, {
                        data: 'send_to',
                        name: 'send_to',
                        className: 'text-center'
                    },
                    {
                        data: 'language',
                        name: 'language',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        searchable: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            })

            $("#{{ EmailTemplate::DELETE_MODAL_ID }}").on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const emailTemplateId = button.data('id');
                const emailTemplateName = button.data('name');
                console.log(emailTemplateName);

                const form = $('#deleteEmailTemplateForm');
                const action = '{{ route('email-templates.destroy', ':id') }}'.replace(':id',
                    emailTemplateId);
                form.attr('action', action);

                $('#emailTemplateName').text(emailTemplateName);
            });

            $('#statusFilter').on('change', function() {
                table.ajax.reload();
            })
        })
    </script>
@endpush

@push('css')
@endpush
