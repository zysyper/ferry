<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'unit_weight',
        'total_weight',
        'total_price',
        'special_instructions',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'unit_weight' => 'decimal:2',
        'total_weight' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relasi dengan Order

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            $orderItem->calculateTotals();
        });

        static::updating(function ($orderItem) {
            if ($orderItem->isDirty(['quantity', 'unit_price', 'unit_weight'])) {
                $orderItem->calculateTotals();
            }
        });
    }

    // Method untuk menghitung total berdasarkan quantity
    private function calculateTotals()
    {
        $this->total_weight = $this->unit_weight * $this->quantity;
        $this->total_price = $this->unit_price * $this->quantity;
    }

    // Method untuk set data dari product
    public function setFromProduct(Product $product, int $quantity)
    {
        $this->product_id = $product->id;
        $this->quantity = $quantity;
        $this->unit_price = $product->total_price;
        $this->unit_weight = $product->weight;
        $this->calculateTotals();
    }

    // Accessor untuk mendapatkan nama produk
    public function getProductNameAttribute()
    {
        return $this->product ? $this->product->name : 'Product not found';
    }


    // Method untuk format display
    public function getFormattedDetailsAttribute()
    {
        return [
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'unit_price' => number_format($this->unit_price, 2),
            'total_weight' => number_format($this->total_weight, 2) . ' kg',
            'total_price' => number_format($this->total_price, 2),
            'special_instructions' => $this->special_instructions,
        ];
    }
}
