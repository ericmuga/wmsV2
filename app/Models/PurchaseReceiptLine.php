<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceiptLine extends Model
{
    use HasFactory;

   protected $guarded='id';
   public function receipt()
   {
    return $this->belongsTo(PurchaseReceipt::class);
   }

   public function order_line()
   {
    return $this->belongsTo(PurchaseOrderLine::class);
   }
}
