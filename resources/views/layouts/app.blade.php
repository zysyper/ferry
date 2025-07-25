<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Toko Online') - {{ config('app.name', 'E-Commerce') }}</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-store mr-2"></i>
                        TokoKu
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <form action="{{ route('products.index') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari produk..."
                                class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:bg-white focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-search text-blue-600 hover:text-blue-800"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-th-large mr-1"></i>
                        Produk
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-th-large mr-1"></i>
                        Tombol Admin sementara
                    </a>

                    <!-- Cart with Dynamic Count -->
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @php
                            $cartItems = Session::get('cart', []);
                            $totalItems = array_sum(array_column($cartItems, 'quantity'));
                        @endphp
                        @if ($totalItems > 0)
                            <span id="cart-count"
                                class="cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $totalItems }}
                            </span>
                        @else
                            <span id="cart-count"
                                class="cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
                                0
                            </span>
                        @endif
                    </a>

                    <!-- User Account -->
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            <i class="fas fa-user-plus mr-1"></i> Daftar
                        </a>
                    @else
                        <div class="relative dropdown">
                            <button type="button" onclick="toggleDropdown()" id="dropdownButton"
                                class="text-gray-700 hover:text-blue-600 flex items-center focus:outline-none">
                                <i class="fas fa-user-circle text-xl mr-1"></i>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="dropdownMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil Saya
                                </a>
                                <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-shopping-bag mr-2"></i>Pesanan Saya
                                </a>
                                <div class="border-t my-1"></div>
                                <a href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-blue-600" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-4">
                <div class="mb-4">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari produk..."
                                class="w-full px-4 py-2 pl-10 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:bg-white focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="space-y-2">
                    <a href="{{ route('products.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-th-large mr-2"></i>Produk
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                        @if ($totalItems > 0)
                            <span
                                class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">{{ $totalItems }}</span>
                        @endif
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    @else
                        <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                            <i class="fas fa-user mr-2"></i>Akun Saya
                        </a>
                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
    </nav>



    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">TokoKu</h3>
                    <p class="text-gray-400 mb-4">
                        Toko online terpercaya dengan koleksi produk berkualitas dan pelayanan terbaik.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Link Cepat</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white">Kontak</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                        <li><a href="#" class="hover:text-white">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Layanan Pelanggan</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white">Cara Berbelanja</a></li>
                        <li><a href="#" class="hover:text-white">Pengiriman</a></li>
                        <li><a href="#" class="hover:text-white">Pengembalian</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                    <div class="space-y-2 text-gray-400">
                        <p><i class="fas fa-phone mr-2"></i>+62 123 456 789</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@tokoku.com</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Jakarta, Indonesia</p>
                        <p><i class="fas fa-clock mr-2"></i>Sen-Jum: 09:00-18:00</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} TokoKu. Semua hak dilindungi.
                </p>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <img src="https://via.placeholder.com/40x25?text=VISA" alt="Visa" class="h-6">
                    <img src="https://via.placeholder.com/40x25?text=MC" alt="Mastercard" class="h-6">
                    <img src="https://via.placeholder.com/40x25?text=OVO" alt="OVO" class="h-6">
                    <img src="https://via.placeholder.com/40x25?text=DANA" alt="DANA" class="h-6">
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = document.getElementById('dropdownButton');
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Function to update cart count (for AJAX requests)
        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cart-count');
            if (count > 0) {
                cartCountElement.textContent = count;
                cartCountElement.classList.remove('hidden');
                // Add bounce animation
                cartCountElement.classList.add('cart-badge');
                setTimeout(() => {
                    cartCountElement.classList.remove('cart-badge');
                }, 500);
            } else {
                cartCountElement.classList.add('hidden');
            }
        }

        // Example AJAX function for adding to cart (optional)
        function addToCart(productId, quantity) {
            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount(data.cart_count);
                        // Show success notification
                        showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                    } else {
                        showNotification(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat menambahkan produk', 'error');
                });
        }

        // Simple notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>

    @stack('scripts')
</body>

</html>
