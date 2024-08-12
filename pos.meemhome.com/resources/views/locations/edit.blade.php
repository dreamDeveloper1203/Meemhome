@extends('layouts.app')
@section('title', __('Edit') . ' ' . __('Location'))

@section('content')
    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="flex-grow-1">
            <x-page-title>@lang('Edit Location')</x-page-title>
        </div>
        <x-back-btn href="{{ route('locations.index') }}" />
    </div>
    <x-card>
        @include('locations.partials.form')
    </x-card>

@endsection
