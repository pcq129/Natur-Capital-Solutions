@extends('layouts.app')

@php
    // use App\Models\EmailTemplate;
    // use App\Enums\Status;

    //* or any other dependencies
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1><strong>Services</strong></h1>
        </div>
        <div>
            <a class="btn btn-success bi bi-plus" href="{{ route('services.create') }}"></a>
        </div>
    </div>
@stop


@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered display table-striped table-hover" id="servicesTable" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            {{-- <th>Id</th> --}}
                            <th class="text-center">Product Name</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Minimum Quantity</th>
                            <th class="text-center">Featured</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop



@push('js')
<script>
    $(document).ready(function(){
        let table = $('#servicesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            
        })
    })
</script>
@endpush
