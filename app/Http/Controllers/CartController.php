<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $total = 0;
        $totalWeight = 0;

        foreach ($cartItems as &$item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $item['quantity'] * $product->total_price;
                $item['total_weight'] = $item['quantity'] * $product->weight;
                $total += $item['subtotal'];
                $totalWeight += $item['total_weight'];
            }
        }

        return view('cart.index', compact('cartItems', 'total', 'totalWeight'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek stock
        if (!$product->hasEnoughStock($request->quantity)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock tidak mencukupi!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Stock tidak mencukupi!');
        }

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $request->quantity,
            ];
        }

        Session::put('cart', $cart);

        // Hitung total items untuk response
        $totalItems = $this->getTotalCartItems();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $totalItems,
                'cart_items' => $cart
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($product && $product->hasEnoughStock($request->quantity)) {
                $cart[$productId]['quantity'] = $request->quantity;
                Session::put('cart', $cart);

                $totalItems = $this->getTotalCartItems();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Keranjang berhasil diperbarui!',
                        'cart_count' => $totalItems
                    ]);
                }

                return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock tidak mencukupi!'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Stock tidak mencukupi!');
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di keranjang!'
            ], 404);
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang!');
    }

    public function remove($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);

            $totalItems = $this->getTotalCartItems();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus dari keranjang!',
                    'cart_count' => $totalItems
                ]);
            }

            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di keranjang!'
            ], 404);
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang!');
    }

    public function clear()
    {
        Session::forget('cart');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan!',
                'cart_count' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function getCartCount()
    {
        $totalItems = $this->getTotalCartItems();
        return response()->json(['cart_count' => $totalItems]);
    }

    private function getTotalCartItems()
    {
        $cartItems = Session::get('cart', []);
        return array_sum(array_column($cartItems, 'quantity'));
    }

    public function checkout()
    {
        $cartItems = Session::get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $total = 0;
        $totalWeight = 0;

        foreach ($cartItems as &$item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $item['quantity'] * $product->total_price;
                $item['total_weight'] = $item['quantity'] * $product->weight;
                $total += $item['subtotal'];
                $totalWeight += $item['total_weight'];
            }
        }

        return view('cart.checkout', compact('cartItems', 'total', 'totalWeight'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'customer_address' => 'required|string',
            'payment_method' => 'required|in:cash,transfer,card',
            'notes' => 'nullable|string',
        ]);

        $cartItems = Session::get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();

        try {
            // Hitung total
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $subtotal += $item['quantity'] * $product->total_price;
                }
            }

            // Buat order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'order_date' => now(),
                'notes' => $request->notes,
            ]);

            // Buat order items dan kurangi stock
            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    // Cek stock lagi
                    if (!$product->hasEnoughStock($item['quantity'])) {
                        throw new \Exception("Stock produk {$product->name} tidak mencukupi!");
                    }

                    // Buat order item
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->setFromProduct($product, $item['quantity']);
                    $orderItem->save();

                    // Kurangi stock
                    $product->decreaseStock($item['quantity']);
                }
            }

            DB::commit();

            // Kosongkan keranjang
            Session::forget('cart');

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function orderSuccess($orderId)
    {
        // Ambil order berdasarkan orderId, dan relasi dengan orderItems dan product
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        // Periksa apakah email pengguna yang sedang login cocok dengan email order
        if (Auth::user()->email !== $order->customer_email) {
            // Jika tidak cocok, arahkan ke halaman yang sesuai, misalnya halaman home atau 403 error
            return redirect('/')->with('error', 'You are not authorized to view this order.');
        }

        // Jika email cocok, tampilkan halaman sukses
        return view('cart.success', compact('order'));
    }


    public function buyNow(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Simpan data ke session (atau cart khusus)
        session()->put('buy_now', [
            'product_id' => $product->id,
            'quantity' => $quantity
        ]);

        return redirect()->route('buy.now.checkout'); // arahkan ke halaman checkout
    }

    public function buyNowCheckout()
    {
        $data = session('buy_now');
        if (!$data) {
            return redirect()->route('products.index');
        }

        $product = Product::find($data['product_id']);
        $quantity = $data['quantity'];

        return view('checkout.index', compact('product', 'quantity'));
    }

    public function buy(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'customer_address' => 'required|string',
            'payment_method' => 'required|in:cash,transfer,card',
            'notes' => 'nullable|string',
        ]);

        $buyNow = Session::get('buy_now');

        if (empty($buyNow)) {
            return redirect()->route('products.index')->with('error', 'Tidak ada produk untuk dibeli.');
        }

        $product = Product::find($buyNow['product_id']);
        $quantity = $buyNow['quantity'] ?? 1;

        if (!$product || $quantity <= 0) {
            return redirect()->route('products.index')->with('error', 'Produk tidak valid atau jumlah tidak sesuai.');
        }

        // Validasi stok
        if (!$product->hasEnoughStock($quantity)) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        DB::beginTransaction();

        try {
            $subtotal = $product->total_price * $quantity;

            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'order_date' => now(),
                'notes' => $request->notes,
            ]);

            // Buat order item
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->setFromProduct($product, $quantity);
            $orderItem->save();

            // Kurangi stok
            $product->decreaseStock($quantity);

            DB::commit();

            // Kosongkan session buy_now
            Session::forget('buy_now');

            return redirect()->route('order.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
