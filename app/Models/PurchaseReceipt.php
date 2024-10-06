<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceipt extends Model
{
    use HasFactory;
     protected $guarded='id';
     public function receipt_lines()
     {
       return $this->hasMany(PurchaseReceiptLine::class);
     }
     public function order()
     {
      return $this->belongsTo(PurchaseOrder::class);
     }

}
