

<a href="{{ $edit }}" class="btn btn-primary">
    <i class="bi bi-pen-fill text-light"></i>
</a>
<button type="button" class="btn btn-danger" data-toggle="modal"
    data-target="#{{ $target }}"
    data-id="{{ $row->id }}"
    data-name="{{ $row->template_name }}">
    <i class="bi bi-trash3-fill text-light"></i>
</button>

