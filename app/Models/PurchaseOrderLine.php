<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLine extends Model
{
    use HasFactory;

    protected $guarded='id';
    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class);

    }
    public function receipt_lines()
    {
     return $this->hasMany(PurchaseReceiptLine::class,'purchase_order_line_id');
    }
}
