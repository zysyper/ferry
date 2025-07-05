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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relasi ke orders
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Relasi ke products
            $table->integer('quantity'); // Jumlah item yang dipesan
            $table->decimal('unit_price', 10, 2); // Harga per unit saat order
            $table->decimal('unit_weight', 8, 2); // Berat per unit saat order
            $table->decimal('total_weight', 8, 2); // Total berat item
            $table->decimal('total_price', 12, 2); // Total harga item
            $table->text('special_instructions')->nullable(); // Instruksi khusus untuk item
            $table->timestamps();

            // Indexes untuk optimasi query
            $table->index(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
