@extends('layouts.app')
@section('title', __('Edit Quotation'))

@section('content')

    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="h4 mb-0 flex-grow-1">@lang('Edit') @lang('Quotation') </div>
        <x-back-btn href="{{ route('quotations.index') }}" />
    </div>

    <x-card>

        <div class="h4 mb-0 flex-grow-1">
            <div class="h4 mb-0">{{ $quotation->number }}</div>
        </div>
        <br/>
        <form action="{{ route('quotations.update', $quotation) }}" method="POST" role="form">
            @csrf

            <div class="mb-3">
                <x-textarea label="Description" name="quo"
                    value="{{ old('quo', isset($quotation) ? $quotation->quotation : null) }}" />
            </div>
            
            <br/>

            <div>
                    <button type="submit" class="btn btn-primary px-4">@lang('Save')</button>
            </div>
        </form>

    </x-card>

@endsection