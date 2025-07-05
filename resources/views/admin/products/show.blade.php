@extends('admin.layouts.app')

@section('title', 'Product Details - ' . $product->name)

@section('header', 'Product Details')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Products
                </a>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <i class="fas fa-edit mr-1"></i> Edit Product
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-1"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Product Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Image and Basic Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        @if ($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                class="w-full h-64 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                                <i class="fas fa-image text-6xl text-gray-400"></i>
                            </div>
                        @endif

                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

                        <div class="flex justify-center items-center space-x-2 mb-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $product->type }}
                            </span>
                            @if ($product->is_halal)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Halal
                                </span>
                            @endif
                        </div>

                        <div class="text-3xl font-bold text-green-600 mb-2">
                            Rp {{ number_format($product->total_price, 0, ',', '.') }}
                        </div>

                        <div class="text-sm text-gray-600">
                            Weight: {{ number_format($product->weight, 2) }} kg
                        </div>
                    </div>
                </div>

                <!-- Quick Stock Update -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stock Update</h3>
                    <form action="{{ route('admin.products.update-stock', $product) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="flex items-end space-x-2">
                            <div class="flex-1">
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock
                                    Quantity</label>
                                <input type="number" id="stock_quantity" name="stock_quantity"
                                    value="{{ $product->stock_quantity }}" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status and Statistics -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold mb-1">
                                @if ($product->status === 'active')
                                    <span class="text-green-600">Active</span>
                                @else
                                    <span class="text-red-600">Inactive</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">Status</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold mb-1">
                                @if ($product->stock_quantity <= 0)
                                    <span class="text-red-600">{{ $product->stock_quantity }}</span>
                                @elseif($product->stock_quantity <= 10)
                                    <span class="text-yellow-600">{{ $product->stock_quantity }}</span>
                                @else
                                    <span class="text-green-600">{{ $product->stock_quantity }}</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">
                                @if ($product->stock_quantity <= 0)
                                    Out of Stock
                                @elseif($product->stock_quantity <= 10)
                                    Low Stock
                                @else
                                    In Stock
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 mb-1">
                                {{ $product->orderItems->count() }}
                            </div>
                            <div class="text-sm text-gray-600">Total Orders</div>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Product Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Product Type</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->type }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Weight</label>
                                <p class="mt-1 text-sm text-gray-900">{{ number_format($product->weight, 2) }} kg</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <p class="mt-1 text-sm text-gray-900">Rp
                                    {{ number_format($product->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created At</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if ($product->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $product->description }}</p>
                            </div>
                        @endif

                        @if ($product->storage_instructions)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Storage Instructions</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                    {{ $product->storage_instructions }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Orders -->
                @if ($product->orderItems->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Orders</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($product->orderItems->take(5) as $orderItem)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $orderItem->order->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $orderItem->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($orderItem->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $orderItem->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($orderItem->order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($orderItem->order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($orderItem->order->status === 'processing') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($orderItem->order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($product->orderItems->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-900 text-sm">View all orders</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="ml-2">
                        <p class="font-bold">Success!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Auto hide notifications after 5 seconds
        setTimeout(function() {
            const notifications = document.querySelectorAll('.fixed.top-4.right-4');
            notifications.forEach(function(notification) {
                notification.style.opacity = '0';
                setTimeout(function() {
                    notification.remove();
                }, 300);
            });
        }, 5000);
    </script>
@endsection
