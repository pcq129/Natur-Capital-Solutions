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
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped border-bottom">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 10%">Office Name</th>
                        <th style="width: 20%">Address</th>
                        <th style="width: 20%">Email</th>
                        <th style="width: 10%">Mobile</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 15%">Location</th>
                        <th style="width: 10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data->isNotEmpty())

                        @foreach ($data as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>
                                    {{ $branch->address }}
                                </td>
                                <td>
                                    {{ $branch->email }}
                                </td>
                                <td>
                                    {{ $branch->mobile }}
                                </td>
                                <td>
                                    @if ($branch->status == Status::Active)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                <td>{{ $branch->location }}</td>
                                <td class="d-flex justify-content-start gap-2">
                                    <a type="submit" class="btn btn-primary"
                                        href="{{ route('branchoffices.edit', $branch->id) }}">
                                        <i class="bi bi-pen-fill text-light"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#deleteBranchModal" data-id={{ $branch->id }}
                                        data-name={{ $branch->name }}>
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

@push('js')
    <script>
        $('#deleteBranchModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const branchId = button.data('id');
            const branchName = button.data('name');

            const form = $('#deleteBranchForm');
            const action = '{{ route('branchoffices.destroy', ':id') }}'.replace(':id', branchId);
            form.attr('action', action);

            $('#branchName').text(branchName);
        });
    </script>
@endpush

