<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $type = $request->get('type');
        $status = $request->get('status');
        $isHalal = $request->get('is_halal');

        // Build query with filters
        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($type) {
            $query->byType($type);
        }

        if ($status) {
            if ($status === 'active') {
                $query->active();
            } else {
                $query->where('status', $status);
            }
        }

        if ($isHalal !== null) {
            if ($isHalal === '1') {
                $query->halal();
            } else {
                $query->where('is_halal', false);
            }
        }

        // Get paginated products
        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        // Append query parameters to pagination links
        $products->appends($request->query());

        // Calculate statistics
        $statistics = $this->getDashboardStatistics();

        return view('admin.dashboard', array_merge([
            'products' => $products,
            'filters' => [
                'search' => $search,
                'type' => $type,
                'status' => $status,
                'is_halal' => $isHalal,
            ]
        ], $statistics));
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStatistics()
    {
        return [
            'totalProducts' => Product::count(),
            'activeProducts' => Product::active()->count(),
            'totalStock' => Product::sum('stock_quantity'),
            'lowStockProducts' => Product::where('stock_quantity', '<=', 5)->count(),
            'totalValue' => Product::active()->sum(DB::raw('total_price * stock_quantity')),
            'halalProducts' => Product::halal()->count(),
            'inStockProducts' => Product::inStock()->count(),
            'outOfStockProducts' => Product::where('stock_quantity', 0)->count(),
        ];
    }

    /**
     * Get product statistics for charts/graphs.
     */
    public function getProductStatistics()
    {
        $productsByType = Product::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        $stockByType = Product::select('type', DB::raw('sum(stock_quantity) as total_stock'))
            ->groupBy('type')
            ->get();

        $salesByMonth = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('YEAR(orders.created_at) as year'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->where('orders.created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json([
            'productsByType' => $productsByType,
            'stockByType' => $stockByType,
            'salesByMonth' => $salesByMonth,
        ]);
    }

    /**
     * Get low stock alerts.
     */
    public function getLowStockAlerts()
    {
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->where('status', 'active')
            ->select('id', 'name', 'stock_quantity', 'type')
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return response()->json([
            'alerts' => $lowStockProducts,
            'count' => $lowStockProducts->count()
        ]);
    }

    /**
     * Update product stock quickly.
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update([
            'stock_quantity' => $request->stock_quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui',
            'product' => $product->fresh()
        ]);
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product)
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';

        $product->update([
            'status' => $newStatus
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status produk berhasil diubah',
            'status' => $newStatus
        ]);
    }

    /**
     * Export products data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        $products = Product::with(['orderItems' => function ($query) {
            $query->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id');
        }])->get();

        if ($format === 'csv') {
            return $this->exportToCsv($products);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($products);
        }

        return redirect()->back()->with('error', 'Format export tidak valid');
    }

    /**
     * Export to CSV.
     */
    private function exportToCsv($products)
    {
        $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'ID',
                'Nama',
                'Deskripsi',
                'Tipe',
                'Berat (kg)',
                'Harga Total',
                'Harga per KG',
                'Stok',
                'Halal',
                'Status',
                'Total Terjual',
                'Dibuat'
            ]);

            // Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->description,
                    $product->type,
                    $product->weight,
                    $product->total_price,
                    $product->price_per_kg,
                    $product->stock_quantity,
                    $product->is_halal ? 'Ya' : 'Tidak',
                    $product->status,
                    $product->orderItems->sum('total_sold') ?? 0,
                    $product->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel (would need additional package like Laravel Excel).
     */
    private function exportToExcel($products)
    {
        // This would require Laravel Excel package
        // For now, return CSV format
        return $this->exportToCsv($products);
    }
}
