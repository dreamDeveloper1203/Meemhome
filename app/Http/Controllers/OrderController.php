<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Settings;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Show order page.
     *
     * @return \Illuminate\View\View
     */
    public function show(): View
    {

        return view('order.show', [
            'address' => Settings::getAddressValue(),
            'gmShareLink' => Settings::getGoogleMapsShareLinkValue()
        ]);
    }

    public function paymentGatway(){
        $order = Order::where('id', request()->query('order_id'))->first();
        $method = PaymentMethod::where('id', request()->query('method'))->first();
        switch ($method->name) {
            case 'Areeba':
                $session_id = $order->getAreebaSession();
                return view('order.payment.areeba', [
                    "order" => $order,
                    "session_id" => $session_id
                ]);

            default:
                break;
        }
    }
}
