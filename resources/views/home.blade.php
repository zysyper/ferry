@extends('layouts.app')

@section('content')

    <body class="bg-gray-50">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-orange-500 via-red-500 to-red-600 text-white overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-30"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                            Ayam Segar
                            <span class="block text-yellow-300">Langsung ke Rumah</span>
                        </h1>
                        <p class="text-xl mb-8 text-orange-100">
                            Dapatkan ayam segar berkualitas premium dengan harga terbaik. Pengiriman cepat dan
                            terpercaya ke
                            seluruh Indonesia.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button
                                class="bg-white text-red-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors transform hover:scale-105 shadow-lg">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Belanja Sekarang
                            </button>
                            <button
                                class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition-colors shadow-lg">
                                <i class="fas fa-play mr-2"></i>
                                Lihat Video
                            </button>
                        </div>
                    </div>
                    <div class="relative">
                        <div
                            class="bg-white bg-opacity-20 backdrop-blur-sm rounded-3xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="bg-white rounded-2xl p-6 text-gray-800">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold text-gray-800">Promo Hari Ini</h3>
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">50%
                                        OFF</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-drumstick-bite text-3xl text-orange-500 mr-4"></i>
                                    <div>
                                        <p class="font-semibold text-gray-800">Ayam Kampung</p>
                                        <p class="text-sm text-gray-600">1 kg - Segar & Organik</p>
                                        <p class="text-lg font-bold text-green-600">Rp 45.000</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih AyamFresh?</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">Kami berkomitmen memberikan produk ayam terbaik
                        dengan layanan yang memuaskan</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow transform hover:-translate-y-2 duration-300">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-leaf text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">100% Segar & Organik</h3>
                        <p class="text-gray-600">Ayam dipelihara secara organik tanpa bahan kimia berbahaya. Dipotong
                            fresh
                            setiap hari untuk menjaga kualitas.</p>
                    </div>
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow transform hover:-translate-y-2 duration-300">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-shipping-fast text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Pengiriman Cepat</h3>
                        <p class="text-gray-600">Pengiriman same day untuk area Jakarta dan sekitarnya. Sistem cold
                            chain
                            untuk menjaga kesegaran.</p>
                    </div>
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow transform hover:-translate-y-2 duration-300">
                        <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-tags text-2xl text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Harga Terjangkau</h3>
                        <p class="text-gray-600">Harga langsung dari peternak, tanpa markup berlebihan. Dapatkan promo
                            menarik setiap hari.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Produk Pilihan</h2>
                    <p class="text-xl text-gray-600">Berbagai jenis ayam segar dengan kualitas terbaik</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Product Cards -->
                    @foreach ($products as $product)
                        <div
                            class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2 duration-300 border border-gray-100">
                            <!-- Product Image -->
                            <div class="relative bg-gray-100 h-48 flex items-center justify-center">
                                <img src="{{ $product->image_path ? Storage::url($product->image_path) : asset('images/placeholder-meat.jpg') }}"
                                    alt="{{ $product->name }}" class="h-full w-full object-cover" loading="lazy">



                                <!-- Halal Badge -->
                                @if ($product->is_halal)
                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow-md flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i>Halal
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-6">
                                <!-- Product Type & Weight -->
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                        {{ ucfirst($product->type) }}
                                    </span>
                                    <span class="text-gray-500 text-sm font-medium">{{ $product->weight }}kg</span>
                                </div>

                                <!-- Product Name -->
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $product->description }}</p>

                                <!-- Stock Information -->
                                <div class="mb-4">
                                    @if ($product->stock_quantity > 0)
                                        <div class="flex items-center text-green-600 text-sm">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            <span>Stok: {{ $product->stock_quantity }}kg tersedia</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-red-600 text-sm">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            <span>Stok habis</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Storage Instructions -->
                                @if ($product->storage_instructions)
                                    <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-yellow-600 mt-0.5 mr-2 text-sm"></i>
                                            <p class="text-yellow-800 text-xs">
                                                <strong>Penyimpanan:</strong> {{ $product->storage_instructions }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Price & Action -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-green-600">
                                            Rp {{ number_format($product->total_price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-gray-500 text-sm">/kg</span>
                                    </div>
                                </div>

                                <!-- Additional Actions -->
                                <div class="mt-4 flex gap-2">
                                    <button onclick="viewProduct({{ $product->id }})"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg transition-colors duration-200 text-sm flex items-center justify-center">
                                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- View All Products Button -->
                <div class="text-center mt-12">
                    <button
                        class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-lg transition-colors duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Lihat Semua Produk
                    </button>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Kata Pelanggan</h2>
                    <p class="text-xl text-gray-600">Testimoni dari pelanggan yang puas dengan layanan kami</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                S
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Sari Dewi</h4>
                                <p class="text-gray-600 text-sm">Jakarta Selatan</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700">"Ayamnya sangat segar dan berkualitas. Pengiriman juga cepat, sampai
                            dalam
                            kondisi yang sangat baik. Recommended!"</p>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                B
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Budi Santoso</h4>
                                <p class="text-gray-600 text-sm">Depok</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700">"Harga terjangkau dengan kualitas premium. Ayam kampungnya benar-benar
                            organik dan rasanya enak sekali."</p>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                L
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Lisa Permata</h4>
                                <p class="text-gray-600 text-sm">Tangerang</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700">"Pelayanan customer service sangat ramah dan responsif. Akan terus
                            berlangganan di AyamFresh!"</p>
                    </div>
                </div>
            </div>
        </section>


        <script>
            // Simple animations and interactions
            document.addEventListener('DOMContentLoaded', function() {
                // Smooth scrolling for navigation links
                const links = document.querySelectorAll('a[href^="#"]');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    });
                });

                // Add to cart functionality (simple example)
                const addToCartButtons = document.querySelectorAll('button');
                addToCartButtons.forEach(button => {
                    if (button.innerHTML.includes('fa-plus')) {
                        button.addEventListener('click', function() {
                            // Simple animation
                            this.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                this.style.transform = 'scale(1)';
                            }, 150);

                            // You can add actual cart functionality here
                            console.log('Product added to cart');
                        });
                    }
                });
            });

            function addToCart(productId) {
                // Implementasi add to cart
                console.log('Adding product to cart:', productId);

                // Example AJAX call
                fetch(`/cart/add/${productId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success notification
                            showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                        } else {
                            showNotification('Gagal menambahkan produk ke keranjang!', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan!', 'error');
                    });
            }

            function viewProduct(productId) {
                // Redirect to product detail page
                window.location.href = `/produk/${productId}`;
            }

            function addToWishlist(productId) {
                // Implementasi add to wishlist
                console.log('Adding product to wishlist:', productId);

                fetch(`/wishlist/add/${productId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Produk berhasil ditambahkan ke wishlist!', 'success');
                        } else {
                            showNotification('Gagal menambahkan produk ke wishlist!', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan!', 'error');
                    });
            }

            function showNotification(message, type) {
                // Simple notification system
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        </script>
    </body>
@endsection
