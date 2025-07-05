<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.product']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter berdasarkan payment status
        if ($request->filled('payment_status')) {
            $query->byPaymentStatus($request->payment_status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->betweenDates($request->date_from, $request->date_to);
        }

        // Search customer
        if ($request->filled('search')) {
            $query->searchCustomer($request->search);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::byStatus('pending')->count(),
            'confirmed_orders' => Order::byStatus('confirmed')->count(),
            'completed_orders' => Order::byStatus('completed')->count(),
            'cancelled_orders' => Order::byStatus('cancelled')->count(),
            'total_revenue' => Order::byStatus('completed')->sum('total_amount'),
            'today_orders' => Order::today()->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $products = Product::active()->inStock()->get();
        return view('admin.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'required|string',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            $totalWeight = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product->hasEnoughStock($item['quantity'])) {
                    throw new \Exception("Stock not enough for product: {$product->name}");
                }
                $subtotal += $product->total_price * $item['quantity'];
                $totalWeight += $product->weight * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'payment_method' => $request->payment_method,
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'total_amount' => $subtotal, // Bisa ditambah tax, shipping dll
                'order_date' => now(),
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->setFromProduct($product, $item['quantity']);
                $orderItem->special_instructions = $item['special_instructions'] ?? null;
                $orderItem->save();

                // Decrease stock
                $product->decreaseStock($item['quantity']);
            }

            DB::commit();
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function edit(Order $order)
    {
        if (!in_array($order->order_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Order cannot be edited in current status.');
        }

        $order->load(['orderItems.product']);
        $products = Product::active()->get();
        return view('admin.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        if (!in_array($order->order_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Order cannot be updated in current status.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'required|string',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'notes' => 'nullable|string',
        ]);

        $order->update($request->only([
            'customer_name',
            'customer_phone',
            'customer_email',
            'customer_address',
            'payment_method',
            'notes'
        ]));

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled'
        ]);

        $order->updateStatus($request->status);

        return back()->with('success', 'Order status updated successfully!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded,cancelled'
        ]);

        $order->updatePaymentStatus($request->payment_status);

        return back()->with('success', 'Payment status updated successfully!');
    }

    public function confirm(Order $order)
    {
        if ($order->confirm()) {
            return back()->with('success', 'Order confirmed successfully!');
        }

        return back()->with('error', 'Order cannot be confirmed.');
    }

    public function cancel(Order $order)
    {
        if ($order->cancel()) {
            return back()->with('success', 'Order cancelled successfully!');
        }

        return back()->with('error', 'Order cannot be cancelled.');
    }

    public function destroy(Order $order)
    {
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Order cannot be deleted in current status.');
        }

        // Return stock before deleting
        foreach ($order->orderItems as $item) {
            $item->product->increaseStock($item->quantity);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }
}
