@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Kembali ke Keranjang
                </a>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('buy.now.checkout') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Pelanggan</h2>

                        <div class="space-y-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap
                                    *</label>
                                <input type="text" id="customer_name" name="customer_name"
                                    value="{{ old('customer_name') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                    Telepon *</label>
                                <input type="tel" id="customer_phone" name="customer_phone"
                                    value="{{ old('customer_phone') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                    *</label>
                                <input type="email" id="customer_email" name="customer_email"
                                    value="{{ old('customer_email') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>

                            <div>
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat
                                    Lengkap *</label>
                                <textarea id="customer_address" name="customer_address" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>{{ old('customer_address') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Metode Pembayaran</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="radio" id="cash" name="payment_method" value="cash"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                        {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                    <label for="cash" class="ml-3 block text-sm font-medium text-gray-700">Cash on
                                        Delivery (COD)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="transfer" name="payment_method" value="transfer"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                        {{ old('payment_method') == 'transfer' ? 'checked' : '' }}>
                                    <label for="transfer" class="ml-3 block text-sm font-medium text-gray-700">Transfer
                                        Bank</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="card" name="payment_method" value="card"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                        {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                                    <label for="card" class="ml-3 block text-sm font-medium text-gray-700">Kartu
                                        Kredit/Debit</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                Tambahan</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Instruksi khusus untuk pesanan Anda...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan Pesanan</h2>

                        @php $isBuyNow = session()->has('buy_now'); @endphp

                        <div class="space-y-4">
                            @if ($isBuyNow)
                                @php
                                    $buyNow = session('buy_now');
                                    $product = \App\Models\Product::find($buyNow['product_id']);
                                    $quantity = $buyNow['quantity'];
                                    $subtotal = $product->total_price * $quantity;
                                    $totalQty = $quantity;
                                    $totalWeight = $product->weight * $quantity;
                                    $total = $subtotal;
                                @endphp
                                <div class="flex items-center space-x-4 py-3 border-b border-gray-200">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden">
                                            @if ($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                                    alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $quantity }} x {{ $product->weight }} kg
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php $totalQty = array_sum(array_column($cartItems, 'quantity')); @endphp
                                @foreach ($cartItems as $item)
                                    <div class="flex items-center space-x-4 py-3 border-b border-gray-200">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden">
                                                @if ($item['product']->image_path)
                                                    <img src="{{ asset('storage/' . $item['product']->image_path) }}"
                                                        alt="{{ $item['product']->name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $item['product']->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600">{{ $item['quantity'] }} x
                                                {{ $item['product']->weight }} kg</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mt-6 space-y-2">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Total Item</span>
                                <span>{{ $totalQty }} item</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Total Berat</span>
                                <span>{{ number_format($totalWeight, 2) }} kg</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-2 border-t border-gray-200">
                                <div class="flex justify-between text-lg font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" type="checkbox" required
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="font-medium text-gray-700">
                                        Saya setuju dengan <a href="#"
                                            class="text-blue-600 hover:text-blue-800">syarat dan ketentuan</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                                Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
