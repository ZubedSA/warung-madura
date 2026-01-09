<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products list for penjaga (stock management)
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->get();

        $stockSummary = [
            'banyak' => Product::where('stock_status', 'banyak')->count(),
            'cukup' => Product::where('stock_status', 'cukup')->count(),
            'sedikit' => Product::where('stock_status', 'sedikit')->count(),
            'kosong' => Product::where('stock_status', 'kosong')->count(),
        ];

        return view('penjaga.stock.index', compact('categories', 'stockSummary'));
    }

    /**
     * Display products by category
     */
    public function byCategory(Category $category)
    {
        $products = $category->products()->orderBy('name')->get();

        // Get products currently in draft order
        $draftOrder = Order::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->first();

        $draftProductIds = $draftOrder ? $draftOrder->items->pluck('product_id')->toArray() : [];

        return view('penjaga.stock.category', compact('category', 'products', 'draftProductIds'));
    }

    /**
     * Display products by stock status
     */
    public function byStatus($status)
    {
        $validStatuses = ['banyak', 'cukup', 'sedikit', 'kosong'];

        if (!in_array($status, $validStatuses)) {
            abort(404);
        }

        $products = Product::where('stock_status', $status)
            ->with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        // Get products currently in draft order
        $draftOrder = Order::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->first();

        $draftProductIds = $draftOrder ? $draftOrder->items->pluck('product_id')->toArray() : [];

        return view('penjaga.stock.status', compact('status', 'products', 'draftProductIds'));
    }

    /**
     * Update product stock status
     */
    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'stock_status' => ['required', 'in:banyak,cukup,sedikit,kosong'],
        ]);

        $oldStatus = $product->stock_status;
        $product->update(['stock_status' => $request->stock_status]);

        // Log activity
        AuditLog::log('update_stok', "Ubah stok {$product->name}: {$oldStatus} → {$request->stock_status}", [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'old_status' => $oldStatus,
            'new_status' => $request->stock_status,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status stok {$product->name} berhasil diubah",
                'new_status' => $request->stock_status
            ]);
        }

        return back()->with('success', "Status stok {$product->name} berhasil diubah menjadi {$request->stock_status}");
    }

    /**
     * Add product to current draft order
     */
    public function addToOrder(Request $request, Product $product)
    {
        $request->validate([
            'quantity_text' => ['required', 'string', 'max:100'],
        ], [
            'quantity_text.required' => 'Jumlah harus diisi',
        ]);

        // Get or create draft order for current user
        $order = Order::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'draft'],
            ['notes' => null]
        );

        // Check if product already in order
        $existingItem = $order->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update(['quantity_text' => $request->quantity_text]);
        } else {
            $order->items()->create([
                'product_id' => $product->id,
                'quantity_text' => $request->quantity_text,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} masuk order"
            ]);
        }

        return back()->with('success', "{$product->name} ditambahkan ke order");
    }

    /**
     * Store new product from Penjaga view
     */
    public function storeFromPenjaga(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
        ], [
            'name.required' => 'Nama barang harus diisi',
            'unit.required' => 'Satuan harus diisi',
        ]);

        // Default values for Penjaga input
        $validated['stock_status'] = 'cukup'; // Default status
        $validated['supplier_name'] = null;
        $validated['supplier_phone'] = null;

        $product = Product::create($validated);

        // Log activity
        AuditLog::log('tambah_barang', "Penjaga menambah barang baru: {$product->name} ({$product->unit})", [
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'name' => $product->name
        ]);

        return back()->with('success', "Barang {$product->name} berhasil ditambahkan");
    }

    // ==========================================
    // ADMIN METHODS
    // ==========================================

    /**
     * Display products list for admin (master data)
     */
    public function adminIndex()
    {
        $products = Product::with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(20);

        $categories = Category::orderBy('sort_order')->get();

        return view('admin.master.products.index', compact('products', 'categories'));
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.master.products.create', compact('categories'));
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'stock_status' => ['required', 'in:banyak,cukup,sedikit,kosong'],
            'unit' => ['required', 'string', 'max:50'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'supplier_phone' => ['nullable', 'string', 'max:20'],
        ], [
            'category_id.required' => 'Kategori harus dipilih',
            'name.required' => 'Nama barang harus diisi',
            'unit.required' => 'Satuan harus diisi',
        ]);

        Product::create($validated);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Show edit product form
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.master.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'stock_status' => ['required', 'in:banyak,cukup,sedikit,kosong'],
            'unit' => ['required', 'string', 'max:50'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'supplier_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $product->update($validated);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil diupdate');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }
}
