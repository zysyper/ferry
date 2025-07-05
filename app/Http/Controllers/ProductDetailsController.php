<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active(); // Using the active scope from your model

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Type filter (changed from category to type)
        if ($request->has('type') && !empty($request->type)) {
            $query->byType($request->type); // Using the byType scope
        }

        // Price range filter (using total_price instead of price)
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('total_price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('total_price', '<=', $request->max_price);
        }

        // Halal filter
        if ($request->has('halal') && $request->halal == '1') {
            $query->halal(); // Using the halal scope
        }

        // In stock filter
        if ($request->has('in_stock') && $request->in_stock == '1') {
            $query->inStock(); // Using the inStock scope
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        $allowedSorts = ['name', 'total_price', 'created_at', 'weight', 'stock_quantity'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12);

        // Get unique product types for filter dropdown
        $productTypes = Product::select('type')
            ->distinct()
            ->whereNotNull('type')
            ->pluck('type');

        return view('products.index', compact('products', 'productTypes'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Get related products by same type
        $relatedProducts = Product::byType($product->type)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
