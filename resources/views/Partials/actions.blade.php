
<a href="{{ $edit }}" class="btn btn-primary actionsButtonOne">
    <i class="bi bi-pen-fill text-light"></i>
</a>
<button type="button" class="btn btn-danger actionsButtonTwo" data-toggle="modal"
    data-target="#{{ $target ?? '' }}"
    data-id="{{ $row->id }}"
    data-name="{{ $row->name }}">
    <i class="{{ $action ?? 'bi bi-trash3-fill' }} text-light"></i>
</button>

