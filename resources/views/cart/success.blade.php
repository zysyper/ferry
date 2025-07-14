@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h1>
                <p class="text-gray-600">Terima kasih telah berbelanja dengan kami. Pesanan Anda sedang diproses.</p>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Detail Pesanan</h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Informasi Pesanan</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nomor Pesanan:</span>
                                    <span class="font-medium">{{ $order->order_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Pesanan:</span>
                                    <span class="font-medium">{{ $order->order_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Pembayaran:</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Informasi Pelanggan</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium ml-2">{{ $order->customer_name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Telepon:</span>
                                    <span class="font-medium ml-2">{{ $order->customer_phone }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium ml-2">{{ $order->customer_email }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Alamat:</span>
                                    <span class="font-medium ml-2">{{ $order->customer_address }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Metode Pembayaran</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <span class="font-medium">
                                @if ($order->payment_method == 'cash')
                                    Cash on Delivery (COD)
                                @elseif($order->payment_method == 'transfer')
                                    Transfer Bank
                                @elseif($order->payment_method == 'card')
                                    Kartu Kredit/Debit
                                @endif
                            </span>
                        </div>
                    </div>

                    @if ($order->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Catatan</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Item Pesanan</h2>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($order->orderItems as $item)
                        <div class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                        @if ($item->product->image_path)
                                            <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item->quantity }} x {{ $item->unit_weight }} kg
                                    </p>
                                    <p class="text-sm text-gray-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                        per item</p>
                                </div>

                                <!-- Total Weight -->
                                <div class="text-center">
                                    <div class="text-sm text-gray-600">Total Berat</div>
                                    <div class="font-medium">{{ number_format($item->total_weight, 2) }} kg</div>
                                </div>

                                <!-- Total Price -->
                                <div class="text-right">
                                    <div class="text-lg font-semibold text-gray-900">
                                        Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            Total Berat: {{ number_format($order->total_weight, 2) }} kg
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-medium text-blue-900 mb-3">Langkah Selanjutnya</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    @if ($order->payment_method == 'cash')
                        <li>• Pesanan Anda akan segera diproses</li>
                        <li>• Kami akan menghubungi Anda untuk konfirmasi pengiriman</li>
                        <li>• Pembayaran dilakukan saat barang diterima</li>
                    @elseif($order->payment_method == 'transfer')
                        <li>• Silakan lakukan transfer ke rekening yang akan diberikan</li>
                        <li>• Kirim bukti transfer via WhatsApp atau email</li>
                        <li>• Pesanan akan diproses setelah pembayaran dikonfirmasi</li>
                    @elseif($order->payment_method == 'card')
                        <li>• Pembayaran akan diproses dalam 1-2 jam</li>
                        <li>• Anda akan menerima notifikasi status pembayaran</li>
                        <li>• Pesanan akan diproses setelah pembayaran berhasil</li>
                    @endif
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium text-center">
                    Lanjut Belanja
                </a>
                {{-- <a href="{{ route('orders.show', $order->id) }}"
                    class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 font-medium text-center">
                    Lihat Detail Pesanan
                </a> --}}
                <a href=""
                    class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 font-medium text-center">
                    Lihat Detail Pesanan
                </a>
            </div>
        </div>
    </div>
@endsection
