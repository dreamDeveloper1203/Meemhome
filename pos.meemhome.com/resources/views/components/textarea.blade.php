@props(['label', 'name', 'value' => '', 'id'])

@php($id1 = uniqid())
<div class="mb-3">
    <x-label for="{{ $id1 }}" value="{{ $label }}" />
    <textarea id="{{ $id ?? $id1 }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror">{{ $value }}</textarea>
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
