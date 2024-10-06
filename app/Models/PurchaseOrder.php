<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $table='purchase_orders';
    protected $fillable = ['status', 'posting_date', 'order_no', 'delivery_time', 'vendor_name', 'mode_of_payment', 'haulier_no', 'phone_no', 'contact_name', 'county', 'mileage_code', 'delivery_mode', 'vendor_no', 'delivery_date'];

    // Define the relationship with order lines
    public function orderLines()
    {
        return $this->hasMany(PurchaseOrderLine::class, 'order_id');
    }

    public function receipts()
    {
      return $this->hasMany(PurchaseReceipt::class);
    }
}
