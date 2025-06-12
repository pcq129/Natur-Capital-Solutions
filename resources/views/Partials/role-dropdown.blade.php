@php
    use App\Enums\Role;
@endphp

<label for="role">Role</label><br>
<select id="role" name="role" class="custom-select w-100" required>
    <option value={{ Role::ADMIN->value }}
        {{ (optional($role) ?? old('role'))->value == Role::USER->value ? 'selected' : '' }}>
        User
    </option>
    <option value={{ Role::USER->value }}
        {{ (optional($role) ?? old('role'))->value == Role::ADMIN->value ? 'selected' : '' }}>
        Admin
    </option>
</select>
@error('role')
<div class="text-danger">{{ $message }}</div>
@enderror