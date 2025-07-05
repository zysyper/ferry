<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter berdasarkan tipe
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter halal
        if ($request->filled('is_halal')) {
            if ($request->is_halal == '1') {
                $query->halal();
            } else {
                $query->where('is_halal', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'low_stock' => Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
            'halal_products' => Product::halal()->count(),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'weight' => 'required|numeric|min:0.01',
            'total_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_halal' => 'boolean',
            'status' => 'required|in:active,inactive',
            'storage_instructions' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name',
            'description',
            'type',
            'weight',
            'total_price',
            'stock_quantity',
            'is_halal',
            'status',
            'storage_instructions'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'weight' => 'required|numeric|min:0.01',
            'total_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_halal' => 'boolean',
            'status' => 'required|in:active,inactive',
            'storage_instructions' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name',
            'description',
            'type',
            'weight',
            'total_price',
            'stock_quantity',
            'is_halal',
            'status',
            'storage_instructions'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return back()->with('error', 'Cannot delete product with existing orders.');
        }

        // Delete image
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update(['stock_quantity' => $request->stock_quantity]);

        return back()->with('success', 'Stock updated successfully!');
    }
}
