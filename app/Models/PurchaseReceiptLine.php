<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceiptLine extends Model
{
    use HasFactory;

   protected $guarded = ['id'];
//   protected $fillable = [
//         'item_no', 'received_qty', 'order_qty', 'identifier', 'purchase_order_line_id', 'receipt_id'
//     ];


   public function receipt()
   {
    return $this->belongsTo(PurchaseReceipt::class);
   }

   public function orderLine()
    {
        return $this->belongsTo(PurchaseOrderLine::class, 'purchase_order_line_id');
    }
}
