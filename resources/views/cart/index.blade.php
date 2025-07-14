@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Lanjut Belanja
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (empty($cartItems))
                <!-- Empty Cart -->
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19m-12 0a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h2>
                    <p class="text-gray-600 mb-6">Belum ada produk di keranjang Anda</p>
                    <a href="{{ route('products.index') }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <!-- Cart Items -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">{{ count($cartItems) }} Item dalam Keranjang
                            </h2>
                            <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium"
                                    onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                                    Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach ($cartItems as $item)
                            <div class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                                            @if ($item['product']->image_path)
                                                <img src="{{ asset('storage/' . $item['product']->image_path) }}"
                                                    alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item['product']->name }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $item['product']->description }}</p>
                                        <div class="flex items-center mt-2 space-x-4">
                                            <span class="text-sm text-gray-500">{{ $item['product']->weight }} kg</span>
                                            @if ($item['product']->is_halal)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Halal
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('cart.update', $item['product_id']) }}" method="POST"
                                            class="flex items-center space-x-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button type="button"
                                                    onclick="decreaseQuantity({{ $item['product_id'] }})"
                                                    class="px-3 py-1 text-gray-600 hover:text-gray-800">-</button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                                    min="1" max="{{ $item['product']->stock_quantity }}"
                                                    class="w-16 text-center border-0 focus:ring-0"
                                                    id="quantity-{{ $item['product_id'] }}">
                                                <button type="button"
                                                    onclick="increaseQuantity({{ $item['product_id'] }})"
                                                    class="px-3 py-1 text-gray-600 hover:text-gray-800">+</button>
                                            </div>
                                            <button type="submit"
                                                class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm">
                                                Update
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Price -->
                                    <div class="text-right">
                                        <div class="text-lg font-semibold text-gray-900">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Rp {{ number_format($item['product']->total_price, 0, ',', '.') }} / item
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="flex-shrink-0">
                                        <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1"
                                                onclick="return confirm('Hapus item ini dari keranjang?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Summary -->
                    <div class="bg-gray-50 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Total Berat: {{ number_format($totalWeight, 2) }} kg
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Button -->
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('cart.checkout') }}"
                        class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                        Lanjut ke Checkout
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function increaseQuantity(productId) {
            const input = document.getElementById(`quantity-${productId}`);
            const max = parseInt(input.getAttribute('max'));
            const current = parseInt(input.value);
            if (current < max) {
                input.value = current + 1;
            }
        }

        function decreaseQuantity(productId) {
            const input = document.getElementById(`quantity-${productId}`);
            const current = parseInt(input.value);
            if (current > 1) {
                input.value = current - 1;
            }
        }
    </script>
@endsection
