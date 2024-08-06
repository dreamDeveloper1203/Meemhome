@extends('layouts.app')
@section('title', __('Edit Quotation'))

@section('content')

    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="h4 mb-0 flex-grow-1">@lang('Create') @lang('Quotation') </div>
        <x-back-btn href="{{ route('quotations.index') }}" />
    </div>

    <x-card>

        <form action="{{ route('quotations.store') }}" method="POST" role="form">
            @csrf

            <label for="invoice" class="form-label">@lang('Invoice')</label>
            <select name="invoice" id="invoice" class="form-select" onchange="selectInvoice(this.value)">
                <option value="">@lang('Select Invoice')</option>
                @foreach ($orders as $order)
                    <option value="{{ $order->id }}">{{ $order->number }}</option>
                @endforeach
            </select>

            <br/>

            <div class="mb-3">
                <x-textarea label="Description" name="quo" value="" id="description"/>
            </div>
            
            <br/>

            <div>
                <button type="submit" class="btn btn-primary px-4">@lang('Save')</button>
            </div>
        </form>

    </x-card>

@endsection
@push('script')
    <script type="text/javascript">
        function selectInvoice(id) {
            console.log(id);
            axios.get(`/quotations/getInvoice/${id}`, {
            }).then(response => {
                let orderDetails = response.data;
                let description = "";
                let productNames = [];
                orderDetails.map(item => {
                    productNames.push(item?.product?.name);
                });
                description = productNames.join(',');
                // console.log(document.getElementById('6669d9b609a09'));
                document.getElementById('description').innerHTML = description;
            }).catch(error => {
                console.log(error);
            })
        }
    </script>
@endpush