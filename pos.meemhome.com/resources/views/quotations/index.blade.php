@extends('layouts.app')
@section('title', __('Quotations'))

@section('content')
    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="h4 mb-0 flex-grow-1">@lang('Quotations')</div>
        <a href="{{ route('quotations.create') }}" class="btn btn-primary">
            @lang('Create')
        </a>

    </div>
    <div class="card w-100">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Article')</th>
                            <th>@lang('Description')</th>
                            <th>@lang('Unit Price')</th>
                            <th>@lang('Qty')</th>
                            <th>@lang('Total Price')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($quotations as $quotation)
                            <tr>
                                <td class="align-middle">
                                    {{ $quotation->index_number }}
                                </td>
                                <td class="align-middle">
                                    {{ $quotation->quotation }}
                                </td>
                                <td class="align-middle">
                                    {{ $quotation->unit_view }}
                                </td>
                                <td class="align-middle">
                                    {{ $quotation->total_quantity }}
                                </td>
                                <td class="align-middle">
                                    {{ $quotation->total_view }}
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown d-flex">
                                        <button class="btn btn-link me-auto text-black p-0" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <x-heroicon-o-ellipsis-horizontal class="hero-icon" />
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end animate slideIn shadow-sm"
                                            aria-labelledby="dropdownMenuButton1">
                                            @can_edit
                                            <li>
                                                <a class="dropdown-item" href="{{ route('quotations.edit', [$quotation]) }}">
                                                    @lang('Edit')
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <form action="{{ route('quotations.destroy', [$quotation]) }}"
                                                        method="POST" id="form-{{ $quotation->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-link p-0 m-0 w-100 text-start text-decoration-none text-danger"
                                                            onclick="submitDeleteForm('{{ $quotation->id }}')">
                                                            @lang('Delete')
                                                        </button>
                                                    </form>
                                                </a>
                                            </li>
                                            @endcan_edit
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($quotations->isEmpty())
                    <x-no-data />
                @endif
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function submitDeleteForm(id) {
            const form = document.querySelector(`#form-${id}`);
            Swal.fire(swalConfig()).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else {
                    topbar.hide();
                }
            });
        }
    </script>
@endpush
