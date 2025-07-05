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
            $table->string('order_number')->unique(); // Nomor order unik
            $table->string('customer_name'); // Nama customer
            $table->string('customer_phone'); // Nomor telepon customer
            $table->string('customer_email')->nullable(); // Email customer
            $table->text('customer_address'); // Alamat pengiriman
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'processing',
                'success',
                'cancelled'
            ])->default('pending'); // Status order
            $table->enum('payment_status', [
                'pending',
                'paid',
                'refunded',
                'cancelled'
            ])->default('pending'); // Status pembayaran
            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'cod'
            ])->nullable(); // Metode pembayaran
            $table->decimal('subtotal', 12, 2); // Subtotal sebelum pajak dan ongkir
            $table->decimal('total_amount', 12, 2); // Total keseluruhan
            $table->date('order_date'); // Tanggal order
            $table->text('notes')->nullable(); // Catatan khusus
            $table->timestamps();

            // Indexes untuk optimasi query
            $table->index('order_status');
            $table->index('payment_status');
            $table->index('order_date');
            $table->index('customer_phone');
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
