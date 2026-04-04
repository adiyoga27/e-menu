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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->integer('queue_number');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->integer('total_amount');
            $table->string('payment_method'); // cashier, qris
            $table->string('payment_status'); // unpaid, paid, cancelled
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->string('invoice_id')->nullable(); // for bayar.gg
            $table->string('payment_url')->nullable(); // for bayar.gg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
