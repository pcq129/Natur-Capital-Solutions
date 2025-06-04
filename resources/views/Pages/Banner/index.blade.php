@extends('layouts.app')

@php
    use App\Enums\Status;
@endphp

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-between">
                <div class="col-sm-6">
                    <h1>Banners</h1>
                </div>
                <div>
                    <a class="btn btn-success bi bi-plus" href="{{ route('banners.create') }}"></a>
                </div>
            </div>
        </div>
    </section>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive" >
                <table class="table table-bordered display table-striped table-hover" id="bannerDataTable"
                    style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Banner Link</th>
                            <th>Heading</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteBannerModal" tabindex="-1" aria-labelledby="deleteBannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteBannerForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBannerModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="bannerName"></strong>?
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
    <!-- Required Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = $('#bannerDataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('banners.index') }}',
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                },
                columns: [
                    {
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
                        data: 'banner_link',
                        name: 'banner_link',
                        className: 'text-center'
                    },
                    {
                        data: 'overlay_heading',
                        name: 'overlay_heading',
                        className: 'text-center'
                    },
                    {
                        data: 'priority',
                        name: 'priority',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]

            });

            $('#deleteBannerModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bannerId = button.data('id');
                const bannerName = button.data('name');
                const form = $('#deleteBannerForm');
                const action = '{{ route('banners.destroy', ':id') }}'.replace(':id', bannerId);
                form.attr('action', action);
                $('#bannerName').text(bannerName);
            });
        });
    </script>
@endpush
