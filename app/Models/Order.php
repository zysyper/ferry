<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'order_status',
        'payment_status',
        'payment_method',
        'subtotal',
        'total_amount',
        'order_date',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'delivery_proof' => 'array',
    ];

    // Relasi dengan OrderItem
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope untuk filter berdasarkan status order
    public function scopeByStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    // Scope untuk filter berdasarkan status pembayaran
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    // Scope untuk order hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('order_date', today());
    }

    // Scope untuk order dalam rentang tanggal
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_date', [$startDate, $endDate]);
    }

    // Scope untuk pencarian customer
    public function scopeSearchCustomer($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('customer_name', 'like', "%{$search}%")
                ->orWhere('customer_phone', 'like', "%{$search}%")
                ->orWhere('customer_email', 'like', "%{$search}%");
        });
    }

    // Boot method untuk generate order number otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = $order->generateOrderNumber();
            }
        });
    }

    // Method untuk generate order number
    private function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -3) + 1 : 1;

        return 'ORD-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    // Method untuk menghitung total berat order
    public function getTotalWeightAttribute()
    {
        return $this->orderItems->sum('total_weight');
    }

    // Method untuk menghitung total item
    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    // Method untuk update status order
    public function updateStatus($status)
    {
        $this->update(['order_status' => $status]);

        // Log status change atau trigger event jika diperlukan
        // event(new OrderStatusChanged($this, $status));
    }

    // Method untuk update status pembayaran
    public function updatePaymentStatus($status)
    {
        $this->update(['payment_status' => $status]);

        // Log payment status change atau trigger event jika diperlukan
        // event(new PaymentStatusChanged($this, $status));
    }

    // Method untuk cek apakah order bisa dibatalkan
    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'confirmed']) &&
            $this->payment_status !== 'paid';
    }

    // Method untuk cancel order
    public function cancel()
    {
        if ($this->canBeCancelled()) {
            $this->update([
                'order_status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            // Kembalikan stock produk
            foreach ($this->orderItems as $item) {
                $item->product->increaseStock($item->quantity);
            }

            return true;
        }

        return false;
    }

    // Method untuk konfirmasi order
    public function confirm()
    {
        if ($this->order_status === 'pending') {
            $this->updateStatus('confirmed');
            return true;
        }

        return false;
    }
}
