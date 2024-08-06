<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $quotations = Order::whereHas('order_details', function ($query) {
            $query->whereNotNull('quotation');
        })->selectRaw('*, ROW_NUMBER() over (order by id) AS index_number')->get();

        return view('quotations.index', [
            'quotations' => $quotations
        ]);
    }

    public function create()
    {
        $orders = Order::whereHas('order_details', function ($query) {
            $query->whereNull('quotation');
        })->get();
        return view('quotations.create', [
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice' => ['nullable', 'string'],
            'quo' => ['nullable', 'string'],
        ]);
        $order = Order::where('id', $request->invoice)->first(); // check item if valid
        $order->quotation = $request->quo;
        $order->save();

        return Redirect::back()->with("success", __("Created"));
    }

    public function edit(Order $quotation)
    {
        return view('quotations.edit', [
            'quotation' => $quotation,
        ]);
    }

    public function update(Request $request, Order $quotation)
    {
        $request->validate([
            'quo' => ['nullable', 'string'],
        ]);

        $quotation->quotation = $request->quo;
        $quotation->save();

        return Redirect::back()->with("success", __("Created"));
    }
    /**
     * Delete resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $quotation)
    {
        $quotation->quotation = null;
        $quotation->save();
        return Redirect::back()->with("success", __("Deleted"));
    }

    public function getInvoice(Request $request){
        $order = Order::with('customer', 'user', 'order_details', 'order_details.product.category')
            ->findOrFail($request -> orderID);
        return response() -> json($order -> order_details);
    }

}
