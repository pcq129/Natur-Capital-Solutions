
<button class="btn btn-primary actionsButtonOne" data-toggle="modal"
data-target="#{{ $targetEdit ?? '' }}"
data-id="{{ $row->id }}"
data-status="{{ $row->status }}"
data-name="{{ $row->name }}">
    <i class="bi bi-pen-fill text-light"></i>
</button>
<button type="button" class="btn btn-danger actionsButtonTwo" data-toggle="modal"
    data-target="#{{ $targetDelete ?? '' }}"
    data-id="{{ $row->id }}"
    data-name="{{ $row->name }}">
    <i class="{{ $action ?? 'bi bi-trash3-fill' }} text-light"></i>
</button>

