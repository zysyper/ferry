@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Breadcrumb -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Produk</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('products.index', ['type' => $product->type]) }}"
                                    class="ml-4 text-gray-500 hover:text-gray-700">{{ ucfirst($product->type) }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-gray-900 font-medium">{{ $product->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                    <!-- Product Images -->
                    <div class="space-y-4">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg">
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/600x600?text=No+Image' }}"
                                alt="{{ $product->name }}" class="w-full h-96 object-cover">
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <span
                                    class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                    {{ ucfirst($product->type) }}
                                </span>
                                @if ($product->is_halal)
                                    <span
                                        class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
                                        Halal
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                <span>Berat: {{ $product->weight }}kg</span>
                                <span>Status: {{ ucfirst($product->status) }}</span>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="space-y-2">
                            <div class="text-3xl font-bold text-gray-900">
                                Rp {{ number_format($product->total_price, 0, ',', '.') }}
                            </div>
                            <div class="text-lg text-gray-600">
                                Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}/kg
                            </div>
                        </div>

                        <!-- Stock Status -->
                        <div class="flex items-center space-x-2">
                            @if ($product->stock_quantity > 0)
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                                    <span class="text-green-600 font-medium">Tersedia ({{ $product->stock_quantity }}
                                        unit)</span>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
                                    <span class="text-red-600 font-medium">Stok Habis</span>
                                </div>
                            @endif

                            @if ($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                <span class="text-orange-600 text-sm">(Stok Terbatas)</span>
                            @endif
                        </div>

                        <!-- Description -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi Produk</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                        </div>

                        <!-- Storage Instructions -->
                        @if ($product->storage_instructions)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Petunjuk Penyimpanan</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $product->storage_instructions }}</p>
                            </div>
                        @endif

                        <!-- Add to Cart Button -->
                        <div class="space-y-4">
                            @if ($product->stock_quantity > 0)
                                <div class="flex items-center space-x-4">
                                    <label class="block text-sm font-medium text-gray-700">Jumlah:</label>
                                    <div class="flex items-center border border-gray-300 rounded-md">
                                        <button type="button" class="px-3 py-2 text-gray-600 hover:text-gray-800"
                                            onclick="decreaseQuantity()">-</button>
                                        <input type="number" id="quantity" value="1" min="1"
                                            max="{{ $product->stock_quantity }}"
                                            class="w-16 text-center border-0 focus:ring-0 focus:outline-none">
                                        <button type="button" class="px-3 py-2 text-gray-600 hover:text-gray-800"
                                            onclick="increaseQuantity()">+</button>
                                    </div>
                                    <span class="text-sm text-gray-500">Maks: {{ $product->stock_quantity }}</span>
                                </div>

                                <div class="flex space-x-4">
                                    <button onclick="addToCart({{ $product->id }})"
                                        class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition duration-200 font-medium">
                                        Tambah ke Keranjang
                                    </button>
                                    <button onclick="buyNow({{ $product->id }})"
                                        class="flex-1 bg-green-600 text-white py-3 px-6 rounded-md hover:bg-green-700 transition duration-200 font-medium">
                                        Beli Sekarang
                                    </button>
                                </div>
                            @else
                                <button disabled
                                    class="w-full bg-gray-300 text-gray-500 py-3 px-6 rounded-md cursor-not-allowed font-medium">
                                    Stok Habis
                                </button>
                            @endif
                        </div>

                        <!-- Additional Info -->
                        <div class="border-t pt-6">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Produk Segar</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span class="text-gray-700">Pengiriman Cepat</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700">Kualitas Terjamin</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    <span class="text-gray-700">Pembayaran Aman</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details Table -->
                <div class="border-t px-6 py-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Produk</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama Produk:</span>
                                <span class="font-medium">{{ $product->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipe:</span>
                                <span class="font-medium">{{ ucfirst($product->type) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Berat:</span>
                                <span class="font-medium">{{ $product->weight }}kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status Halal:</span>
                                <span class="font-medium">
                                    @if ($product->is_halal)
                                        <span class="text-green-600">Ya</span>
                                    @else
                                        <span class="text-gray-600">Tidak</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga Total:</span>
                                <span class="font-medium">Rp
                                    {{ number_format($product->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga per Kg:</span>
                                <span class="font-medium">Rp
                                    {{ number_format($product->price_per_kg, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Stok Tersedia:</span>
                                <span class="font-medium">{{ $product->stock_quantity }} unit</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">{{ ucfirst($product->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div
                                class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                                <div class="relative overflow-hidden">
                                    <img src="{{ $relatedProduct->image_path ? asset('storage/' . $relatedProduct->image_path) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                        alt="{{ $relatedProduct->name }}"
                                        class="w-full h-40 object-cover hover:scale-105 transition-transform duration-300">

                                    @if ($relatedProduct->is_halal)
                                        <div
                                            class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                            Halal
                                        </div>
                                    @endif

                                    @if ($relatedProduct->stock_quantity <= 5 && $relatedProduct->stock_quantity > 0)
                                        <div
                                            class="absolute top-2 right-2 bg-orange-500 text-white px-2 py-1 rounded-md text-xs">
                                            Stok Terbatas
                                        </div>
                                    @elseif($relatedProduct->stock_quantity <= 0)
                                        <div
                                            class="absolute top-2 right-2 bg-gray-500 text-white px-2 py-1 rounded-md text-xs">
                                            Habis
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <span
                                            class="text-xs text-blue-600 font-medium">{{ ucfirst($relatedProduct->type) }}</span>
                                        <span class="text-xs text-gray-500">{{ $relatedProduct->weight }}kg</span>
                                    </div>

                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}
                                    </h3>

                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-lg font-bold text-gray-900">Rp
                                                {{ number_format($relatedProduct->total_price, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-500">Rp
                                                {{ number_format($relatedProduct->price_per_kg, 0, ',', '.') }}/kg</span>
                                        </div>

                                        <a href="{{ route('products.show', $relatedProduct->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.max);

            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);

            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            // Implement add to cart functionality
            alert(`Produk ${productId} sebanyak ${quantity} unit telah ditambahkan ke keranjang.`);
        }

        function buyNow(productId) {
            const quantity = document.getElementById('quantity').value;
            // Implement buy now functionality
            alert(`Membeli produk ${productId} sebanyak ${quantity} unit.`);
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
