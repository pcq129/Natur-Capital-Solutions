@php
    use App\Enums\Status;
@endphp



<label for="status">Status</label><br>
<select id="status" name="status" class="custom-select w-100" required>
    <option value="{{ Status::INACTIVE }}"
        {{ (optional($status) ?? old('status'))->value == Status::INACTIVE->value ? 'selected' : '' }}>
        Inactive
    </option>
    <option value="{{ Status::ACTIVE }}"
        {{ (optional($status) ?? old('status'))->value == Status::ACTIVE->value ? 'selected' : '' }}>
        Active
    </option>
</select>
@error('status')
    <div class="text-danger">{{ $message }}</div>
@enderror
