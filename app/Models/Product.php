<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'weight',
        'total_price',
        'stock_quantity',
        'is_halal',
        'status',
        'storage_instructions',
        'image_path',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'total_price' => 'decimal:2',
        'is_halal' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    // Relasi dengan OrderItem
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope untuk filter produk aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope untuk produk halal
    public function scopeHalal($query)
    {
        return $query->where('is_halal', true);
    }

    // Scope untuk produk yang tersedia (stock > 0)
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Accessor untuk harga per kg
    public function getPricePerKgAttribute()
    {
        return $this->weight > 0 ? $this->total_price / $this->weight : 0;
    }


    // Method untuk mengurangi stock
    public function decreaseStock($quantity)
    {
        if ($this->stock_quantity >= $quantity) {
            $this->decrement('stock_quantity', $quantity);
            return true;
        }
        return false;
    }

    // Method untuk menambah stock
    public function increaseStock($quantity)
    {
        $this->increment('stock_quantity', $quantity);
    }

    // Method untuk cek apakah stock mencukupi
    public function hasEnoughStock($quantity)
    {
        return $this->stock_quantity >= $quantity;
    }
}
