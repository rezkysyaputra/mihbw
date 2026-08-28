@php
    $required = $required ?? false;
@endphp
<label class="form-label">
    <span>
        {{ $label }}
        @if($required)
            <span class="text-red-600" aria-hidden="true">*</span>
        @endif
    </span>
    <textarea
        name="{{ $name }}"
        rows="3"
        class="form-control"
        @if($required) required @endif
        @error($name) aria-invalid="true" @enderror
    >{{ old($name) }}</textarea>
    @error($name)<span class="field-error">{{ $message }}</span>@enderror
</label>
