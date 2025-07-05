@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-3xl font-bold text-gray-900">Produk Kami</h1>
                <p class="mt-2 text-gray-600">Temukan produk terbaik dengan kualitas terjamin</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filter -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Produk</h3>

                        <form method="GET" action="{{ route('products.index') }}">
                            <!-- Search -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Nama produk..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Type Filter -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Produk</label>
                                <select name="type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Tipe</option>
                                    @foreach ($productTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Harga</label>
                                <div class="flex gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        placeholder="Min"
                                        class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        placeholder="Max"
                                        class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Halal Filter -->
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="halal" value="1"
                                        {{ request('halal') == '1' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Produk Halal</span>
                                </label>
                            </div>

                            <!-- In Stock Filter -->
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="in_stock" value="1"
                                        {{ request('in_stock') == '1' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Tersedia</span>
                                </label>
                            </div>

                            <!-- Sort Options -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                                <select name="sort"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z
                                    </option>
                                    <option value="total_price" {{ request('sort') == 'total_price' ? 'selected' : '' }}>
                                        Harga Terendah</option>
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                                        Terbaru</option>
                                    <option value="weight" {{ request('sort') == 'weight' ? 'selected' : '' }}>Berat
                                    </option>
                                    <option value="stock_quantity"
                                        {{ request('sort') == 'stock_quantity' ? 'selected' : '' }}>Stok</option>
                                </select>
                            </div>

                            <!-- Sort Order -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                                <select name="order"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Naik</option>
                                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Turun
                                    </option>
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                                Terapkan Filter
                            </button>

                            <a href="{{ route('products.index') }}"
                                class="block w-full text-center mt-2 text-gray-600 hover:text-gray-800">
                                Reset Filter
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="lg:w-3/4">
                    <!-- Results Info -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-gray-600">
                            Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari
                            {{ $products->total() }} produk
                        </p>
                    </div>

                    @if ($products->count() > 0)
                        <!-- Product Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            @foreach ($products as $product)
                                <div
                                    class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300x300?text=No+Image' }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                                        @if ($product->is_halal)
                                            <div
                                                class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                                Halal
                                            </div>
                                        @endif

                                        @if ($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                            <div
                                                class="absolute top-2 right-2 bg-orange-500 text-white px-2 py-1 rounded-md text-xs">
                                                Stok Terbatas
                                            </div>
                                        @elseif($product->stock_quantity <= 0)
                                            <div
                                                class="absolute top-2 right-2 bg-gray-500 text-white px-2 py-1 rounded-md text-xs">
                                                Habis
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <span
                                                class="text-xs text-blue-600 font-medium">{{ ucfirst($product->type) }}</span>
                                            <span class="text-xs text-gray-500">{{ $product->weight }}kg</span>
                                        </div>

                                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>

                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>

                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex flex-col">
                                                <span class="text-lg font-bold text-gray-900">Rp
                                                    {{ number_format($product->total_price, 0, ',', '.') }}</span>
                                                <span class="text-xs text-gray-500">Rp
                                                    {{ number_format($product->price_per_kg, 0, ',', '.') }}/kg</span>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                Stok: {{ $product->stock_quantity }}
                                            </div>
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 text-sm font-medium">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <!-- No Products Found -->
                        <div class="text-center py-12">
                            <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-2-2m0 0l-2 2m2-2v6m-8 0V9m0 0l2-2m-2 2l-2-2" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Produk tidak ditemukan</h3>
                            <p class="text-gray-600 mb-4">Coba ubah filter pencarian atau kata kunci Anda</p>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                                Lihat Semua Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
