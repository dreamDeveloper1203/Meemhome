<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order №{{ $order->number }}</title>
    <link rel="preload" href="{{ mix('css/app-admin.css') }}" as="style" />
    <link rel="stylesheet" href="{{ mix('css/app-admin.css') }}">
</head>

<body>
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="card-title h5 mb-3">Customer information</div>
            <div class="mb-2">
                <span class="me-2">Name:</span>{{ $order->name }}
            </div>
            <div class="mb-2">
                <span class="me-2">Email:</span>
                <a class="link-primary" href="mailto:{{ $order->email }}">{{ $order->email }}</a>
            </div>
            <div class="mb-2">
                <span class="me-2">Phone:</span>
                <a class="link-primary" href="tel:{{ $order->phone }}">{{ $order->phone }}</a>
            </div>
            @if ($order->comment)
                <hr>
                <div class="mb-2">
                    <span class="me-2">Comment:</span>{{ $order->comment }}
                </div>
            @endif
        </div>
    </div>


    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="card-title h5 mb-3">Payment</div>
            <div class="mb-2">
                {{ $order->payment_method->name }}
            </div>
            <div class="mb-2">
                <span class="me-2">Subtotal:</span>{{ $order->display_subtotal }}
            </div>
            @if ($order->coupon)
                <div class="mb-2">
                    <span class="me-2">Coupon:</span>{{ $order->coupon->code }}
                </div>
                <div class="mb-2">{{ $order->coupon->des }}</div>
            @endif
            @if ($order->discount > 0)
                <div class="mb-2">
                    <span class="me-2">Discount:</span>{{ $order->display_discount }}
                </div>
            @endif
            @if ($order->is_delivery)
                <div class="mb-2">
                    <span class="me-2">Delivery Charge:</span>{{ $order->display_delivery_fee }}
                </div>
            @endif
            <div class="fw-bold">
                <span class="me-2">Total:</span>{{ $order->display_total }}
            </div>
        </div>
    </div>


    @if ($order->is_delivery)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="card-title h5 mb-3">Delivery Address</div>
                <div class="mb-2">
                    <span class="me-2">Area:</span>{{ $order->area->name }}
                </div>
                <div class="mb-2">
                    <span class="me-2">Address:</span>{{ $order->address }}
                </div>
                <div class="mb-2">
                    <span class="me-2">DeliveryTime:</span>{{ $order->area->view_time }}
                </div>
            </div>
        </div>
    @endif
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="card-title h5 mb-3">Order Details</div>
            <table class=" table table-bordered w-100">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Barcode</th>
                        <!-- <th>Remaining Stock</th> -->
                        <th>Location</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->order_detail as $item)
                        <tr>
                            <td>
                                <img src="{{ $item->item->image_url }}" alt="{{ $item->item->name }}"
                                    style="width: auto; height: 60px;">
                            </td>
                            <td>{{ $item->item->name }}</td>
                            <td style="text-align: center">
                                <img width="200" src="https://barcode.orcascan.com/?type=code128&format=png&data={{$item->item ? $item->item->barcode ? $item->item->barcode : '' : ''}}"/>
                                <div>
                                    {{$item->item ? $item->item->barcode ? $item->item->barcode : '' : ''}}
                                </div>
                            </td>
                            <!-- <td>{{ $item->item->in_stock }}</td> -->
                            <td>{{ $item->item->location_name }}</td>
                            <td>{{ $item->item->category->name }}</td>
                            <td>x{{ $item->quantity }}</td>
                            <td>{{ $item->display_subtotal }}</td>
                            <td>{{ $item->display_total }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-end">
                            Subtotal
                        </td>

                        <td>{{ $order->display_order_details_total }}</td>
                    </tr>

                    @if ($order->discount > 0)
                        <tr>
                            <td colspan="7" class="text-end">
                                Discount
                            </td>

                            <td>{{ $order->display_discount }}</td>
                        </tr>
                    @endif
                    @if ($order->is_delivery)
                        <tr>
                            <td colspan="7" class="text-end">
                                Delivery Charge
                            </td>

                            <td>{{ $order->display_delivery_fee }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="7" class="text-end">
                            Total
                        </td>

                        <td>{{ $order->display_total }}</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</body>

</html>
<script>
    window.print();
</script>
