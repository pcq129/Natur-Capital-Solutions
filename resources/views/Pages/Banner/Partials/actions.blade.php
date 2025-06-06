@php
    use App\Models\Banner;
@endphp

<a href="{{ $edit }}" class="btn btn-primary">
    <i class="bi bi-pen-fill text-light"></i>
</a>
<button type="button" class="btn btn-danger" data-toggle="modal"
    data-target="{!! Banner::DELETE_MODAL_ID !!}"
    data-id="{{ $row->id }}"
    data-name="{{ $row->name }}">
    <i class="bi bi-trash3-fill text-light"></i>
</button>

