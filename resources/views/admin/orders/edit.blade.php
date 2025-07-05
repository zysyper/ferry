@extends('admin.layouts.app')

@section('title', 'Edit Order #' . $order->id)

@section('header', 'Edit Order #' . $order->id)

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.show', $order) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Order Details
            </a>
        </div>

        <!-- Order Status Alert -->
        @if (!in_array($order->order_status, ['pending', 'confirmed']))
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Limited Editing</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This order is currently in "{{ ucfirst($order->order_status) }}" status. Only customer
                                information can be edited.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="orderForm">
            @csrf
            @method('PUT')

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror">
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="text" name="customer_phone"
                            value="{{ old('customer_phone', $order->customer_phone) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_phone') border-red-500 @enderror">
                        @error('customer_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="customer_email"
                            value="{{ old('customer_email', $order->customer_email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_email') border-red-500 @enderror">
                        @error('customer_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select name="payment_method" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('payment_method') border-red-500 @enderror">
                            <option value="">Select Payment Method</option>
                            <option value="cash"
                                {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="bank_transfer"
                                {{ old('payment_method', $order->payment_method) == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer</option>
                            <option value="cod"
                                {{ old('payment_method', $order->payment_method) == 'cod' ? 'selected' : '' }}>Cash on
                                Delivery
                            </option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="customer_address" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_address') border-red-500 @enderror">{{ old('customer_address', $order->customer_address) }}</textarea>
                        @error('customer_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Order Items (Read-only) -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Order Items Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Order items cannot be modified after order creation. Contact administrator if changes are
                                    needed.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Instructions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $item->product_sku ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $item->special_instructions ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary (Read-only) -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($order->tax_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax:</span>
                            <span class="font-medium">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($order->shipping_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-medium">Rp {{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount:</span>
                            <span class="font-medium text-red-600">-Rp
                                {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-semibold border-t pt-2">
                        <span>Total:</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Current Status Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Status</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($order->order_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->order_status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->order_status == 'processing') bg-indigo-100 text-indigo-800
                                @elseif($order->order_status == 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->order_status == 'delivered') bg-green-100 text-green-800
                                @elseif($order->order_status == 'completed') bg-green-100 text-green-800
                                @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                @elseif($order->payment_status == 'refunded') bg-gray-100 text-gray-800
                                @elseif($order->payment_status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Date</label>
                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm text-gray-900">
                            {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm text-gray-900">
                            {{ $order->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.orders.show', $order) }}"
                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-1"></i> Update Order
                </button>
            </div>
        </form>
    </div>
@endsection
