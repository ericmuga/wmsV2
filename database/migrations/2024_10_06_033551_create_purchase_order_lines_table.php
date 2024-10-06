<?php

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
        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('purchase_orders')->onDelete('cascade');  // Foreign key referencing orders table
            $table->string('item_no');  // Item number
            // $table->string('item_description');  // Item description
            $table->integer('order_qty')->nullable();  // Quantity, nullable in case not set yet
            $table->timestamps();  // Created at and updated at timestamps
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_lines');
    }
};
