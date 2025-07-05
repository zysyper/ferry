@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('header', 'Order Details')

@section('content')
    <div class="space-y-6">
        <!-- Header with Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h1>
                    <p class="text-gray-600 mt-1">Created on {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex space-x-3">
                    @if ($order->order_status === 'pending')
                        <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                                onclick="return confirm('Confirm this order?')">
                                <i class="fas fa-check mr-1"></i> Confirm Order
                            </button>
                        </form>
                    @endif

                    @if ($order->canBeCancelled())
                        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                onclick="return confirm('Cancel this order?')">
                                <i class="fas fa-times mr-1"></i> Cancel Order
                            </button>
                        </form>
                    @endif

                    @if (in_array($order->order_status, ['pending', 'confirmed']))
                        <a href="{{ route('admin.orders.edit', $order) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fas fa-edit mr-1"></i> Edit Order
                        </a>
                    @endif

                    <a href="{{ route('admin.orders.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Status:</span>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-purple-100 text-purple-800',
                                        'shipped' => 'bg-indigo-100 text-indigo-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->order_status] }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </div>

                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST"
                                class="flex space-x-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm">
                                    <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed</option>
                                    <option value="processing"
                                        {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>
                                        Shipped</option>
                                    <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                                        Delivered</option>
                                    <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                                <button type="submit"
                                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                @php
                                    $paymentColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'refunded' => 'bg-purple-100 text-purple-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $paymentColors[$order->payment_status] }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>

                            <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST"
                                class="flex space-x-2">
                                @csrf
                                @method('PATCH')
                                <select name="payment_status"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>
                                        Failed</option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                        Refunded</option>
                                    <option value="cancelled"
                                        {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit"
                                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Weight</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->product->name ?? 'Product not found' }}</div>
                                            @if ($item->special_instructions)
                                                <div class="text-sm text-gray-500">Special:
                                                    {{ $item->special_instructions }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ number_format($item->total_weight, 2) }} kg</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp
                                            {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                        Subtotal:</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp
                                        {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Total
                                        Weight:</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ number_format($order->total_weight, 2) }} kg</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-lg font-bold text-gray-900 text-right">Total
                                        Amount:</td>
                                    <td class="px-6 py-4 text-lg font-bold text-gray-900">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer & Order Info -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Name:</label>
                            <p class="text-sm text-gray-900">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone:</label>
                            <p class="text-sm text-gray-900">{{ $order->customer_phone }}</p>
                        </div>
                        @if ($order->customer_email)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Email:</label>
                                <p class="text-sm text-gray-900">{{ $order->customer_email }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-600">Address:</label>
                            <p class="text-sm text-gray-900">{{ $order->customer_address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Order Date:</span>
                            <span class="text-sm text-gray-900">{{ $order->order_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Payment Method:</span>
                            <span class="text-sm text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Items:</span>
                            <span class="text-sm text-gray-900">{{ $order->total_items }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Weight:</span>
                            <span class="text-sm text-gray-900">{{ number_format($order->total_weight, 2) }} kg</span>
                        </div>
                        <hr>
                        <div class="flex justify-between font-medium">
                            <span class="text-sm text-gray-900">Total Amount:</span>
                            <span class="text-sm text-gray-900">Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if ($order->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-600">Notes:</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
