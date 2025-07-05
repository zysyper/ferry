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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama produk ayam
            $table->text('description')->nullable(); // Deskripsi produk
            $table->enum('type', ['whole_chicken', 'breast', 'thigh', 'wing', 'drumstick']); // Jenis produk ayam
            $table->decimal('weight', 8, 2); // Berat dalam kg
            $table->decimal('total_price', 10, 2); // Total harga
            $table->integer('stock_quantity')->default(0); // Stok tersedia
            $table->boolean('is_halal')->default(true); // Status halal
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active'); // Status produk
            $table->text('storage_instructions')->nullable(); // Instruksi penyimpanan
            $table->string('image_path')->nullable(); // Path gambar produk
            $table->timestamps();

            // Indexes untuk optimasi query
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
