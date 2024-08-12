<form action="{{ isset($location) ? route('locations.update', $location) : route('locations.store') }}" method="POST"
    enctype="multipart/form-data" role="form">
    @csrf
    @isset($location)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="name" class="form-label">@lang('Category Name')</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
            value="{{ old('name', isset($location) ? $location->name : '') }}">
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">@lang('status.text')</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
            @isset($location)
                <option value="available" @if ($location->is_active) selected @endif>@lang('Visible')</option>
                <option value="unavailable" @if (!$location->is_active) selected @endif>@lang('Hidden')</option>
            @else
                <option value="available">@lang('Visible')</option>
                <option value="unavailable">@lang('Hidden')</option>
            @endisset
        </select>
        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @else
            <div id="categoryStatusHelp" class="form-text">
                @lang('If set to hidden, all items of this category, will not appear in the POS.')
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <x-save-btn>
            @lang(isset($location) ? 'Update Location' : 'Save Location')
        </x-save-btn>
    </div>
</form>
