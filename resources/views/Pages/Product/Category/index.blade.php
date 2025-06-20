@extends('layouts.app')

@php
    use App\Constants\CategoryConstants as CONSTANTS;
    use App\Enums\Status;
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>Product Categories</h1>
        </div>
        <div>
            <button type="button" class="btn btn-success bi bi-plus me-4" data-toggle="modal"
                data-target="#addCategoryModal"></button>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered display table-striped table-hover" id="categoryTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Category Modal --}}
    <form data-validate action="{{ route('categories.store') }}" id="createCategoryForm" method="POST">
        @csrf
        @method('POST')
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog"
            aria-labelledby="centeredAddCategoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <input type="text" name="name" class="form-control" id="categoryName" required placeholder="Name of the category to be added.">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Update Category Modal --}}
    <form data-validate method="POST" id="updateCategoryForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="category_id" id="updateCategoryId">
        <div class="modal fade" id="{{ CONSTANTS::UPDATE_CATEGORY_MODAL }}" tabindex="-1" role="dialog"
            aria-labelledby="centeredAddCategoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="updateModalCategoryName">Name</label>
                            <input type="text" name="name" class="form-control" id="updateModalCategoryName" placeholder="Name of the category to be added.">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusActive"
                                    value="{{ Status::ACTIVE->value }}">
                                <label class="form-check-label" for="statusActive">
                                    Active
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive"
                                    value="{{ Status::INACTIVE->value }}">
                                <label class="form-check-label" for="statusInactive">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="{{ CONSTANTS::DELETE_CATEGORY_MODAL }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteCategoryForm">
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
                        Are you sure you want to delete <strong id="modalCategoryName"></strong>?
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
            let table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('categories.index') }}',
                    type: 'GET',
                    headers: {
                        'Accept': 'Application/JSON'
                    }
                },
                columns: [
                    { data: 'name', name: 'name', className: 'text-center' },
                    { data: 'status', name: 'status', className: 'text-center' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[0, 'desc']]
            });

            // Delete modal
            $("#{{ CONSTANTS::DELETE_CATEGORY_MODAL }}").on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const categoryId = button.data('id');
                const categoryName = button.data('name');
                const form = $('#deleteCategoryForm');
                const action = '{{ route('categories.destroy', ':id') }}'.replace(':id', categoryId);
                form.attr('action', action);
                $('#modalCategoryName').text(categoryName);
            });

            // Update modal
            $("#{{ CONSTANTS::UPDATE_CATEGORY_MODAL }}").on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const categoryId = button.data('id');
                const categoryName = button.data('name');
                const categoryStatus = button.data('status');

                $('#updateModalCategoryName').val(categoryName);
                $('#updateModalCategoryName').prop('required', true);
                $('#updateCategoryId').val(categoryId);
                $(`input[name="status"][value="${categoryStatus}"]`).prop('checked', true);

                const updateAction = '{{ route("categories.update", ":id") }}'.replace(':id', categoryId);
                $('#updateCategoryForm').attr('action', updateAction).find('.text-danger').remove();
            });

            // Update form validation then submit
            $('#updateCategoryForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const categoryId = $('#updateCategoryId').val();
                const validationUrl = '{{ route("categories.validate", ":id") }}'.replace(':id', categoryId);

                $.ajax({
                    url: validationUrl,
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            form.off('submit').submit();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            form.find('.text-danger').remove();
                            $.each(xhr.responseJSON.errors, function(key, messages) {
                                const field = form.find(`[name="${key}"]`);
                                field.after(`<div class="text-danger">${messages[0]}</div>`);
                            });
                        }
                    }
                });
            });

            $('#createCategoryForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const categoryName = $('#categoryName').val();
                const validationUrl = '{{ route("categories.validateStore") }}';

                $.ajax({
                    url: validationUrl,
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            form.off('submit').submit();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            form.find('.text-danger').remove();
                            $.each(xhr.responseJSON.errors, function(key, messages) {
                                const field = form.find(`[name="${key}"]`);
                                field.after(`<div class="text-danger">${messages[0]}</div>`);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
