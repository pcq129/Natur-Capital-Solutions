@extends('layouts.app')

@php
    use App\Constants\SubCategoryConstants as CONSTANTS;
    use App\Enums\Status;
@endphp


@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1>Product Sub-Categories</h1>
        </div>
        <div>
            <button type="button" class="btn btn-success bi bi-plus me-4" data-target="#addSubCategoryModal"
                id="createSubCategoryButton" data-toggle="modal" {{ $categories->count() == 0 ? 'disabled' : '' }}></button>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered display table-striped table-hover" id="subCategoryTable"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    {{-- add sub category modal --}}
    <form data-validate action="{{ route('sub-categories.store') }}" method="POST" id="createSubCategoryForm">
        @csrf
        @method('POST')
        <div class="modal fade" id="addSubCategoryModal" tabindex="-1" role="dialog"
            aria-labelledby="centeredAddSubCategoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubCategoryModalTitle">Add Sub-Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="subCategoryName">Name</label>
                            <input type="text" name="name" class="form-control" id="subCategoryName"
                                aria-describedby="subCategoryHelp" required
                                placeholder="Name of the Sub-Category to be added.">
                        </div>
                        <label for="categorySelector">Category</label>
                        <select required class="custom-select" id="categorySelector" name="category_id">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Sub Category</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- update sub category modal --}}
    <form data-validate method="POST" id="updateSubCategoryForm">
        @csrf
        @method('PUT')
        <div class="modal fade" id="{{ CONSTANTS::UPDATE_SUB_CATEGORY_MODAL }}" tabindex="-1" role="dialog"
            aria-labelledby="centeredAddSubCategoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubCategoryModalTitle">Add Sub Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select required class="custom-select" id="categorySelector" name="category_id">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subCategoryName">Sub-Category Name</label>
                            <input type="text" name="name" class="form-control" id="updateModalSubCategoryName"
                                aria-describedby="subCategoryHelp" placeholder="Name of the Sub-Category to be added.">

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
                        <button type="submit" class="btn btn-primary">Update Sub Category</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Delete confirmation dialog --}}
    <div class="modal fade" id="{{ CONSTANTS::DELETE_SUB_CATEGORY_MODAL }}" tabindex="-1"
        aria-labelledby="deleteSubCategoryModal" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteSubCategoryForm">
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
                      <span id="subCategoryDeleteModalContent"></span>
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

@once
    @push('js')
        @trixassets

        <!-- DataTables Core -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- DataTables Bootstrap 4 Integration -->
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize DataTable
                let table = $('#subCategoryTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: '{{ route('sub-categories.index') }}',
                        type: 'GET',
                        headers: {
                            'Accept': 'Application/JSON'
                        },
                        data: function(d) {
                            d.status = $('#statusFilter').val();
                        }
                    },
                    columns: [{
                            data: 'name',
                            name: 'name',
                            className: 'text-center'
                        },
                        {
                            data: 'category',
                            name: 'category',
                            className: 'text-center',
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ]
                });


                // Handle Create Modal Show
                $('#createSubCategoryButton').on('click', function() {
                    const categoriesCount = {{ $categories->count() }};
                    if (categoriesCount === 0) {
                        toastr.error('No categories found');
                    } else {
                        const modal = $('#addSubCategoryModal');
                        console.log('Trying to open modal:', modal, typeof modal.modal);
                        if (modal.length && typeof modal.modal === 'function') {
                            modal.modal('show');
                        } else {
                            console.error('Modal not available or not initialized.');
                        }
                    }
                });



                // Handle Delete Modal Show
                // $("#{{ CONSTANTS::DELETE_SUB_CATEGORY_MODAL }}").on('show.bs.modal', function(event) {
                //     const button = $(event.relatedTarget);
                //     const subCategoryId = button.data('id');
                //     const subCategoryName = button.data('name');
                //     const form = $('#deleteSubCategoryForm');
                //     const action = '{{ route('sub-categories.destroy', ':id') }}'.replace(':id',
                //         subCategoryId);
                //     form.attr('action', action);
                //     $('#modalSubCategoryName').text(subCategoryName);
                // });


                $(document).on('click', '.open-delete-modal-btn', function(event) {
                event.preventDefault();

                const button = $(this);
                const subCategoryId = button.data('id');
                // const categoryName = button.data('name');
                const form = $('#deleteCategoryForm');
                const modal = $('#{{ CONSTANTS::DELETE_SUB_CATEGORY_MODAL }}');
                const action = '{{ route('sub-categories.destroy', ':id') }}'.replace(':id', subCategoryId);

                const validationUrl = '{{ route('sub-category.validatedelete', ':id') }}'.replace(':id',
                    subCategoryId);

                const spinner = button.find('#deleteCategorySpinner');
                const deleteIcon = button.find('#deleteCategoryIcon');

                // Show spinner, hide icon
                deleteIcon.addClass('d-none');
                spinner.removeClass('d-none');

                // Do AJAX check first
                $.ajax({
                    url: validationUrl,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log(response.status );
                            form.attr('action', action);
                            // $('#modalCategoryName').text(categoryName);
                            $('#subCategoryDeleteModalContent').text(response.message);
                            modal.modal('show');
                            // Now show modal

                    },
                    error: function(xhr) {



                    },
                    complete: function() {
                        // Restore UI
                        deleteIcon.removeClass('d-none');
                        spinner.addClass('d-none');
                    }
                });




                form.attr('action', validationUrl);
                $('#modalSubCategoryName').text(subCategoryName);
                $('#subCategoryDeleteModalContent').text('Are you sure to delete')
            });




                // Handle Update Modal Show
                $("#{{ CONSTANTS::UPDATE_SUB_CATEGORY_MODAL }}").on('show.bs.modal', function(event) {
                    const button = $(event.relatedTarget);
                    const subCategoryId = button.data('id');
                    const subCategoryName = button.data('name');
                    const subCategoryStatus = button.data('status');
                    const categoryId = button.data('category_id');
                    const form = $('#updateSubCategoryForm');
                    form.find('.text-danger').remove();
                    const action = '{{ route('sub-categories.update', ':id') }}'.replace(':id', subCategoryId);

                    form.attr('action', action);
                    $('#updateModalSubCategoryName').val(subCategoryName);
                    $('#updateModalSubCategoryName').prop('required', true);
                    $(`input[name="status"][value="${subCategoryStatus}"]`).prop('checked', true);

                    $('#categorySelector').each(function() {
                        $(this).prop('selected', $(this).val() == categoryId.toString());
                    });

                    // One-time submit handler
                    form.off('submit').on('submit', function(e) {
                        e.preventDefault();
                        const validationUrl = '{{ route('sub-categories.validate', ':id') }}'.replace(
                            ':id', subCategoryId);

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
                                    form.off('submit').submit(); // Submit natively
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    form.find('.text-danger').remove();
                                    $.each(xhr.responseJSON.errors, function(key,
                                        messages) {
                                        const field = form.find(`[name="${key}"]`);
                                        field.after(
                                            `<div class="text-danger">${messages[0]}</div>`
                                        );
                                    });
                                }
                            }
                        });
                    });
                });

                // Handle Create Form Submit
                $('#createSubCategoryForm').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const validationUrl = '{{ route('sub-categories.validateStore') }}';

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
                                form.off('submit').submit(); // Native form submission
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                form.find('.text-danger').remove();
                                $.each(xhr.responseJSON.errors, function(key, messages) {
                                    const field = form.find(`[name="${key}"]`);
                                    field.after(
                                        `<div class="text-danger">${messages[0]}</div>`);
                                });
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
