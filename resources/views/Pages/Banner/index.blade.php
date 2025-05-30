@extends('layouts.app')
@php
    use App\Enums\Status;
@endphp

@section('content')

    <div>
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
        <div class="card">
            <div class="card-body p-0">
                <table class="table">
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
                                    <td>{{ $loop->iteration }}</td>
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
                                    <td>{{ $banner->image }}</td>
                                    <td class="d-flex justify-content-start gap-2">
                                        {{-- <a href="{{ route('banners.edit', $banner->id) }}" class="btn m-2 btn-primary">
                                            <i class="bi bi-pen-fill text-light"></i>
                                        </a> --}}

                                        <form class="d-flex align-items-center mt-2"
                                            action="{{ route('banners.edit', $banner->id) }}" method="GET">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-pen-fill text-light"></i>
                                            </button>
                                        </form>

                                        <form class="d-flex align-items-center mt-2"
                                            action="{{ route('banners.destroy', $banner->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this banner?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash3-fill text-light"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                        {{-- {{ $data }} --}}

                    </tbody>
                </table>
                {{-- {{ $data }} --}}
                <div class="me-2 float-right">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
@stop
