@php
    use App\Enums\Language;
@endphp


<label for="language">Langauge</label><br>
<select id="language" name="language" class="custom-select w-100" required>
    <option value="{{ Language::ENGLISH }}"
        {{ (optional($language) ?? old('language'))->value == Language::ENGLISH->value ? 'selected' : '' }}>
        English
    </option>
    <option value="{{ Language::FRENCH }}"
        {{ (optional($language) ?? old('language'))->value == Language::FRENCH->value ? 'selected' : '' }}>
        French
    </option>
</select>
@error('language')
<div class="text-danger">{{ $message }}</div>
@enderror
