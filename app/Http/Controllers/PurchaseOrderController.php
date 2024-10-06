<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('orderLines')->get();
        return response()->json($orders);
    }

//    public function store(Request $request)
// {

//   dd($request->all());
//     // Create the purchase order
//     $order = PurchaseOrder::create($request->order);

//     // Create the associated order lines
//     foreach ($request->order_lines as $line) {
//         $order->orderLines()->create($line);
//     }

//     return response()->json($order->load('orderLines'), 201);
// }

public function store(Request $request)
{

    $order=PurchaseOrder::forceCreate($request->order);

    foreach ($request->order_lines as $line) {

        PurchaseOrderLine::forceCreate(['item_no'=>$line['item_no'],
                                          'order_id'=>$order->id,
                                          'order_qty'=>$line['order_qty']
                                        ]);
    }

    // Return the order with the created order lines
    return response()->json($order->load('orderLines'), 201);
}



    public function show($id)
    {
        $order = PurchaseOrder::with('orderLines')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->update($request->all());

        $order->orderLines()->delete(); // Remove old lines
        foreach ($request->order_lines as $line) {
            $order->orderLines()->create($line); // Add new lines
        }

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->delete();
        return response()->json(null, 204);
    }
}
