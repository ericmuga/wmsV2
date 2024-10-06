<?php

use App\Models\PurchaseOrderLine;
use App\Models\PurchaseReceipt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_receipt_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PurchaseReceipt::class);
            $table->foreignIdFor(PurchaseOrderLine::class);
            $table->string('item_no');  // Item number
            $table->integer('order_qty')->nullable();  // Quantity, nullable in case not set yet
            $table->integer('received_qty')->nullable();
            $table->timestamps();  // Created at and updated at timestamps
            // $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_receipt_lines');
    }
};
