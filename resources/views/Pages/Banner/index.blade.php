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
                <div class="col-sm-4 input-group input-group-sm h-100" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <a class="btn ms-6 btn-success bi bi-plus" href="{{ route('banners.create') }}"></a>
                </div>

            </div>
        </div>
    </section>
@stop

@section('content')

    <div>
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped border-bottom" id="bannerDataTable">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 10%">Name</th>
                            <th style="width: 20%">Banner Link</th>
                            <th style="width: 20%">Heading</th>
                            <th style="width: 10%">Priority</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 15%">Image</th>
                            <th style="width: 10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($data->isNotEmpty())

                            @foreach ($data as $banner)
                                <tr>
                                    <td>{{ $banner->id }}</td>
                                    <td>{{ $banner->name }}</td>
                                    <td>
                                        {{ $banner->banner_link }}
                                    </td>
                                    <td>
                                        {{ $banner->overlay_heading }}
                                    </td>
                                    <td>
                                        {{ $banner->priority }}
                                    </td>
                                    <td>
                                        @if ($banner->status == Status::Active)
                                            Active
                                        @else
                                            Inactive
                                        @endif
                                    </td>
                                    <td><img src="{{ $banner->image }}" height="50px"></td>
                                    <td class="d-flex justify-content-start gap-2">
                                        <a type="submit" class="btn btn-primary"
                                            href="{{ route('banners.edit', $banner->id) }}">
                                            <i class="bi bi-pen-fill text-light"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#deleteBannerModal" data-id={{ $banner->id }}
                                            data-name={{ $banner->name }}>
                                            <i class="bi bi-trash3-fill text-light"></i>
                                        </button>


                                    </td>

                                </tr>
                            @endforeach
                        @endif
                        {{-- {{ $data }} --}}

                    </tbody>
                </table>
                {{-- {{ $data }} --}}
                <div class="m-4 justify-content-center d-flex">

                    {{-- <span class="me-4 my-auto">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries.</span> --}}
                    <div class="paginator">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
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


    </div>


@stop

@push('js')
    <script>
        $(document).ready(function() {
            $('#bannerDataTable').DataTable({
                paging: true,
            });
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
    </script>
@endpush
