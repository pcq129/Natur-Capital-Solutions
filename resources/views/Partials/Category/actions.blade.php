
<button class="btn btn-primary actionsButtonOne" data-toggle="modal"
data-target="#{{ $targetEdit ?? '' }}"
data-id="{{ $row->id }}"
data-status="{{ $row->status }}"
data-name="{{ $row->name }}"
data-category_id="{{ $row->category_id }}">
    <i class="bi bi-pen-fill text-light"></i>
</button>
{{-- data-toggle="modal" --}}
<button type="button" class="btn btn-danger actionsButtonTwo open-delete-modal-btn"
    data-target="#{{ $targetDelete ?? '' }}"
    data-id="{{ $row->id }}"
    data-name="{{ $row->name }}">
    <i id="deleteCategoryIcon" class="{{ $action ?? 'bi bi-trash3-fill' }} text-light"></i>

    <div id="deleteCategorySpinner" class="spinner-border d-none" style="height:15px;width:15px;align-content:center;">
        <span id="deleteCategorySpinner" class="sr-only">Loading...</span>
      </div>
</button>

