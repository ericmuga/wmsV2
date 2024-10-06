<?php

use App\Models\PurchaseOrder;
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
        Schema::create('purchase_receipts', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(PurchaseOrder::class);
                $table->date('posting_date');  // Posting date
                $table->string('status')->default('Open');  // Status with default value 'Open'
                $table->string('delivery_mode')->nullable();
                $table->date('delivery_date')->nullable();  // Delivery date
                $table->time('delivery_time')->nullable();  // Delivery time
                $table->string('vendor_no')->nullable();  // Vendor number
                $table->string('vendor_name');  // Vendor name
                $table->string('mode_of_payment')->nullable();  // Mode of payment
                $table->string('haulier_no')->nullable();  // Haulier number
                $table->string('phone_no')->nullable();  // Phone number
                $table->string('contact_name')->nullable();  // Contact name
                $table->string('county')->nullable();  // County
                $table->string('mileage_code')->nullable();  // Mileage code
                $table->timestamps();  // Created at and updated at t

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_receipts');
    }
};
