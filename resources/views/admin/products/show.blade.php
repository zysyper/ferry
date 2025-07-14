@extends('admin.layouts.app')

@section('title', 'Product Detail - ' . $product->name)

@section('header', 'Product Detail')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h2>
                <div class="space-x-2">
                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-800 text-sm rounded-md hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>

            <!-- Product Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Image -->
                <div class="border rounded-lg overflow-hidden">
                    @if ($product->image_path)
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                            class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No Image Available</span>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600">Product Type</h4>
                        <p class="text-lg text-gray-900">{{ ucfirst($product->type) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600">Weight</h4>
                        <p class="text-lg text-gray-900">{{ $product->weight }} kg</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600">Price</h4>
                        <p class="text-lg text-gray-900">Rp {{ number_format($product->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600">Stock Quantity</h4>
                        <p class="text-lg text-gray-900">{{ $product->stock_quantity }} unit</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600">Status</h4>
                        <p class="text-lg text-gray-900">
                            @if ($product->status === 'active')
                                <span class="text-green-600 font-medium">Active</span>
                            @else
                                <span class="text-red-600 font-medium">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600">Halal Status</h4>
                        <p class="text-lg text-gray-900">
                            {{ $product->is_halal ? 'Halal Certified' : 'Not Certified' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-8">
                <h4 class="text-sm font-semibold text-gray-600 mb-2">Description</h4>
                <p class="text-gray-700 leading-relaxed">{{ $product->description ?: '-' }}</p>
            </div>

            <!-- Storage Instructions -->
            <div class="mt-6">
                <h4 class="text-sm font-semibold text-gray-600 mb-2">Storage Instructions</h4>
                <p class="text-gray-700 leading-relaxed">{{ $product->storage_instructions ?: '-' }}</p>
            </div>

            <!-- Metadata -->
            <div class="mt-6 border-t pt-4 text-sm text-gray-500">
                <p><strong>Created at:</strong> {{ $product->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Last updated:</strong> {{ $product->updated_at->format('d M Y, H:i') }}</p>
                <p><strong>Total Orders:</strong> {{ $product->orderItems->count() }}</p>
            </div>
        </div>
    </div>
@endsection
