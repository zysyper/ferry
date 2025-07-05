@extends('admin.layouts.app')

@section('title', 'Create New Order')

@section('header', 'Create New Order')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
        </div>

        <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
            @csrf

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror">
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_phone') border-red-500 @enderror">
                        @error('customer_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}"
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
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer</option>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery
                            </option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="customer_address" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_address') border-red-500 @enderror">{{ old('customer_address') }}</textarea>
                        @error('customer_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                    <button type="button" id="addItem"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-1"></i> Add Product
                    </button>
                </div>

                <div id="orderItems">
                    <!-- Items will be added here dynamically -->
                </div>

                @error('items')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" id="subtotalDisplay">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t pt-2">
                        <span>Total:</span>
                        <span id="totalDisplay">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.orders.index') }}"
                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-save mr-1"></i> Create Order
                </button>
            </div>
        </form>
    </div>

    <!-- Item Template (Hidden) -->
    <template id="itemTemplate">
        <div class="order-item border border-gray-200 rounded-lg p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <select name="items[INDEX][product_id]"
                        class="product-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->total_price }}"
                                data-stock="{{ $product->stock_quantity }}" data-weight="{{ $product->weight }}">
                                {{ $product->name }} - Rp {{ number_format($product->total_price, 0, ',', '.') }}
                                (Stock: {{ $product->stock_quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="items[INDEX][quantity]" min="1" value="1"
                        class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                    <input type="text" class="unit-price w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
                        readonly>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <input type="text" class="item-total w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
                        readonly>
                </div>

                <div class="md:col-span-2">
                    <button type="button"
                        class="remove-item w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="md:col-span-12">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions</label>
                    <input type="text" name="items[INDEX][special_instructions]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Optional special instructions for this item">
                </div>
            </div>
        </div>
    </template>

    <script>
        let itemIndex = 0;

        document.getElementById('addItem').addEventListener('click', function() {
            addOrderItem();
        });

        function addOrderItem() {
            const template = document.getElementById('itemTemplate');
            const container = document.getElementById('orderItems');

            const clone = template.content.cloneNode(true);

            // Replace INDEX with actual index
            clone.querySelectorAll('[name*="INDEX"]').forEach(function(input) {
                input.name = input.name.replace('INDEX', itemIndex);
            });

            container.appendChild(clone);

            // Add event listeners
            const newItem = container.lastElementChild;
            addItemEventListeners(newItem);

            itemIndex++;

            // Add first item automatically if none exist
            if (container.children.length === 1) {
                calculateTotals();
            }
        }

        function addItemEventListeners(item) {
            const productSelect = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');
            const unitPriceInput = item.querySelector('.unit-price');
            const itemTotalInput = item.querySelector('.item-total');
            const removeBtn = item.querySelector('.remove-item');

            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                unitPriceInput.value = 'Rp ' + numberFormat(price);
                calculateItemTotal(item);
            });

            quantityInput.addEventListener('input', function() {
                calculateItemTotal(item);
            });

            removeBtn.addEventListener('click', function() {
                item.remove();
                calculateTotals();
            });
        }

        function calculateItemTotal(item) {
            const productSelect = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');
            const itemTotalInput = item.querySelector('.item-total');

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price || 0);
            const quantity = parseInt(quantityInput.value || 0);
            const total = price * quantity;

            itemTotalInput.value = 'Rp ' + numberFormat(total);
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;

            document.querySelectorAll('.order-item').forEach(function(item) {
                const productSelect = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');

                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price || 0);
                const quantity = parseInt(quantityInput.value || 0);

                subtotal += price * quantity;
            });

            document.getElementById('subtotalDisplay').textContent = 'Rp ' + numberFormat(subtotal);
            document.getElementById('totalDisplay').textContent = 'Rp ' + numberFormat(subtotal);
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Add first item on page load
        document.addEventListener('DOMContentLoaded', function() {
            addOrderItem();
        });

        // Form validation
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            const items = document.querySelectorAll('.order-item');
            if (items.length === 0) {
                e.preventDefault();
                alert('Please add at least one product to the order.');
                return false;
            }

            // Validate stock availability
            let stockError = false;
            items.forEach(function(item) {
                const productSelect = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');

                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const availableStock = parseInt(selectedOption.dataset.stock || 0);
                const requestedQuantity = parseInt(quantityInput.value || 0);

                if (requestedQuantity > availableStock) {
                    stockError = true;
                    quantityInput.classList.add('border-red-500');
                } else {
                    quantityInput.classList.remove('border-red-500');
                }
            });

            if (stockError) {
                e.preventDefault();
                alert('Some products have insufficient stock. Please check the quantities.');
                return false;
            }
        });
    </script>
@endsection
