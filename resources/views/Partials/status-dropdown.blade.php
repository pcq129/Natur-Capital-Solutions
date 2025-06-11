@php
    use App\Enums\Status;
@endphp

<div class="col">
    <label for="status">Status</label><br>
    <select id="status" name="status" class="custom-select w-100" required>
        <option value="{{ Status::INACTIVE }}"
            {{ (optional($subject)->status ?? old('status')) == Status::INACTIVE ? 'selected' : '' }}>
            Inactive
        </option>
        <option value="{{ Status::ACTIVE }}"
            {{ (optional($subject)->status ?? old('status')) == Status::ACTIVE ? 'selected' : '' }}>
            Active
        </option>
    </select>
</div>