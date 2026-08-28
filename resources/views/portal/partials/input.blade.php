@php
    $type = $type ?? 'text';
    $hint = $hint ?? null;
    $required = $required ?? false;
@endphp
<label class="form-label">
    <span>
        {{ $label }}
        @if($required)
            <span class="text-red-600" aria-hidden="true">*</span>
        @endif
        @if($hint)
            <span class="field-hint block">{{ $hint }}</span>
        @endif
    </span>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $type === 'file' ? '' : old($name) }}"
        class="form-control {{ $type === 'file' ? 'file-control' : '' }}"
        @if($required) required @endif
        @error($name) aria-invalid="true" @enderror
    >
    @error($name)<span class="field-error">{{ $message }}</span>@enderror
</label>
