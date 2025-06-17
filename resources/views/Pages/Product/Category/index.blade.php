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
            {{-- <a class="btn btn-success bi bi-plus" href="{{ route('categories.create') }}"></a> --}}
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
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog"
            aria-labelledby="centeredAddCategoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalTitle">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <input type="text" name="name" class="form-control" id="categoryName"
                                aria-describedby="categoryHelp">
                            <small id="categoryHelp" class="form-text text-muted">Name of the category to be added.</small>
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


    <form method="POST" id="updateCategoryForm">
        @csrf
        @method('PUT')
        <div class="modal fade" id="{{ CONSTANTS::UPDATE_CATEGORY_MODAL }}" tabindex="-1" role="dialog"
            aria-labelledby="centeredAddCategoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalTitle">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <input type="text" name="name" class="form-control" id="updateModalCategoryName"
                                aria-describedby="categoryHelp">
                            <small id="categoryHelp" class="form-text text-muted">Name of the category to be added.</small>
                        </div>
                        <label for="updateModalCategoryStatus">Status</label><br>
                        <select name="status" id="updateModalCategoryStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Delete confirmation dialog --}}
    <div class="modal fade" id="{{ CONSTANTS::DELETE_CATEGORY_MODAL }}" tabindex="-1" aria-labelledby="deleteCategoryModal"
        aria-hidden="true">
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
            let table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('categories.index') }}',
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
                }, {
                    data: 'status',
                    name: 'status',
                    className: 'text-center'
                }, {
                    data: 'actions',
                    name: 'actions',
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                }, ],
                order: [
                    [0, 'desc']
                ]
            })



            $("#{{ CONSTANTS::DELETE_CATEGORY_MODAL }}").on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const categoryId = button.data('id');
                console.log('clicked delete');
                const categoryName = button.data('name');
                const form = $('#deleteCategoryForm');
                const action = '{{ route('categories.destroy', ':id') }}'.replace(':id', categoryId);
                form.attr('action', action);

                $('#modalCategoryName').text(categoryName);
            });


            // $("#{{ CONSTANTS::UPDATE_CATEGORY_MODAL }}").on('show.bs.modal', function(event) {
            //     const button = $(event.relatedTarget);
            //     const categoryId = button.data('id');
            //     const categoryName = button.data('name');
            //     const categoryStatus = button.data('status');
            //     const form = $('#updateCategoryForm');
            //     const action = '{{ route('categories.update', ':id') }}'.replace(':id', categoryId);
            //     form.attr('action', action);
            //     const categoryNameInput = $('#updateModalCategoryName');
            //     // const categoryStatusInput = form.elements["status"];
            //     categoryNameInput.value = categoryName;
            //     // categoryStatusInput = categoryStatusInput;
            // });

            $("#{{ CONSTANTS::UPDATE_CATEGORY_MODAL }}").on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const categoryId = button.data('id');
                const categoryName = button.data('name');
                const categoryStatus = button.data('status');
                console.log(categoryStatus);
                const form = $('#updateCategoryForm');
                const action = '{{ route('categories.update', ':id') }}'.replace(':id', categoryId);
                form.attr('action', action);

                // Set category name
                $('#updateModalCategoryName').val(categoryName);

                // Set category status
                $('#updateModalCategoryStatus').val(categoryStatus);
            });

        })
    </script>
@endpush
