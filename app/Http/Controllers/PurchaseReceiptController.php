<?php

namespace App\Http\Controllers;

use App\Models\{PurchaseOrder, PurchaseReceipt,PurchaseReceiptLine};
use Illuminate\Http\Request;

class PurchaseReceiptController extends Controller
{

    public function index()
    {
        // Eager load the receipt lines and the corresponding order lines
        $receipts = PurchaseReceipt::latest()
                    ->with('receiptLines.orderLine') // Use the correct relationship names
                    ->get();

        return response()->json($receipts);
    }


public function store(Request $request)
{
    // Mass assign receipt using request data (make sure to remove 'id' if necessary)

    $r=$request->receipt;

   $receipt = PurchaseReceipt::create([
                                        'receipt_no' => $r['receipt_no'],
                                        'order_no' => $r['order_no'],
                                        'purchase_order_id'=>$r['purchase_order_id'],
                                        'posting_date' => $r['posting_date'],
                                        'delivery_mode' => $r['delivery_mode'],
                                        'delivery_date' => $r['delivery_date'],
                                        'status' => $r['status'],
                                        'delivery_time' => $r['delivery_time'],
                                        'vendor_no' => $r['vendor_no'],
                                        'vendor_name' => $r['vendor_name'],
                                        'mode_of_payment' => $r['mode_of_payment'],
                                        'phone_no' => $r['phone_no'],
                                        'contact_name' => $r['contact_name'],
                                        'county' => $r['county'],
                                        'mileage_code' => $r['mileage_code'],
                                        'cess_paid' => $r['cess_paid'],
                                        'no_objection' => $r['no_objection'],
                                    ]);



    foreach ($request->receipt_lines as $line) {
        $receipt->receiptLines()->create([
                                            'item_no' => $line['item_no'],
                                            'received_qty' => $line['received_qty'],
                                            'order_qty' => $line['order_qty'],
                                            'identifier' => $line['identifier'],
                                            'purchase_order_line_id' => $line['order_line_id'],
                                        ]);
    }

    // Return response with the receipt and its related receipt lines
    return response()->json($receipt->load('receiptLines'), 201);
}




    public function show($id)
    {
        $receipt = PurchaseReceipt::with('ReceiptLines')->findOrFail($id);
        return response()->json($receipt);
    }

    public function update(Request $request, $id)
    {
        $receipt = PurchaseReceipt::findOrFail($id);
        $receipt->update($request->all());

        $receipt->ReceiptLines()->delete(); // Remove old lines
        foreach ($request->Receipt_lines as $line) {
            $receipt->ReceiptLines()->create($line); // Add new lines
        }

        return response()->json($receipt);
    }

    public function destroy($id)
    {
        $receipt = PurchaseReceipt::findOrFail($id);
        $receipt->delete();
        return response()->json(null, 204);
    }

}
