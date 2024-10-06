<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder; // Use the PurchaseOrder model
use App\Models\PurchaseOrderLine; // Use the PurchaseOrderLine model
use Carbon\Carbon;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Array of order data without the `order_lines` field
        $orders = [
            [
                'order_no' => 'O1001',
                'vendor_no' => 'V1001',
                'vendor_name' => 'Vendor 1',
                'posting_date' => Carbon::parse('2024-10-01'),
                'mode_of_payment' => 'EFT',
                'mileage_code' => 'T060',
                'delivery_date' => Carbon::parse('2024-10-12'),
                'delivery_time' => '10:30:00',
                'county' => 'C001',
                'delivery_mode' => 'SELF',
                'haulier_no' => 'KAR 9901',
                'contact_name' => 'Jane Doe',
                'phone_no' => '098-299-120',
                'status' => 'Pending',
            ],
            [
                'order_no' => 'O1002',
                'vendor_no' => 'V1002',
                'vendor_name' => 'Vendor 2',
                'posting_date' => Carbon::parse('2024-10-02'),
                'mode_of_payment' => 'Cheque',
                'mileage_code' => 'T061',
                'delivery_date' => Carbon::parse('2024-10-13'),
                'delivery_time' => '11:00:00',
                'county' => 'C002',
                'delivery_mode' => 'THIRD_PARTY',
                'haulier_no' => 'KAR 8802',
                'contact_name' => 'John Doe',
                'phone_no' => '098-299-121',
                'status' => 'Received',
            ],
            [
                'order_no' => 'O1003',
                'vendor_no' => 'V1003',
                'vendor_name' => 'Vendor 3',
                'posting_date' => Carbon::parse('2024-10-03'),
                'mode_of_payment' => 'Cash',
                'mileage_code' => 'T062',
                'delivery_date' => Carbon::parse('2024-10-14'),
                'delivery_time' => '12:30:00',
                'county' => 'C003',
                'delivery_mode' => 'SELF',
                'haulier_no' => 'KAR 9903',
                'contact_name' => 'Alice Smith',
                'phone_no' => '098-299-122',
                'status' => 'Pending',
            ],
            [
                'order_no' => 'O1004',
                'vendor_no' => 'V1004',
                'vendor_name' => 'Vendor 4',
                'posting_date' => Carbon::parse('2024-10-04'),
                'mode_of_payment' => 'EFT',
                'mileage_code' => 'T063',
                'delivery_date' => Carbon::parse('2024-10-15'),
                'delivery_time' => '13:00:00',
                'county' => 'C004',
                'delivery_mode' => 'THIRD_PARTY',
                'haulier_no' => 'KAR 9904',
                'contact_name' => 'Bob Johnson',
                'phone_no' => '098-299-123',
                'status' => 'Received',
            ],
        ];

        // Array of order lines data for each order
        $orderLines = [
            'O1001' => [
                ['item_no' => 'G0101', 'order_qty' => 50],
                ['item_no' => 'G0102', 'order_qty' => 5],
            ],
            'O1002' => [
                ['item_no' => 'G0101', 'order_qty' => 100],
                ['item_no' => 'G0103', 'order_qty' => 2],
            ],
            'O1003' => [
                ['item_no' => 'G0101', 'order_qty' => 75],

            ],
            'O1004' => [
                ['item_no' => 'G0101', 'order_qty' => 150],

            ],
        ];

        // Insert orders and associated order lines
        foreach ($orders as $orderData) {
            // Insert the purchase order
            $purchaseOrder = PurchaseOrder::create($orderData);

            // Insert the associated order lines for the current order
            foreach ($orderLines[$orderData['order_no']] as $lineData) {
                $purchaseOrder->orderLines()->create($lineData);
            }
        }
    }
}
