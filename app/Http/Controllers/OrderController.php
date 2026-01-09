<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display orders list for penjaga
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('penjaga.order.index', compact('orders'));
    }

    /**
     * Display current draft order
     */
    public function draft()
    {
        $order = Order::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->with('items.product')
            ->first();

        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return view('penjaga.order.draft', compact('order', 'products'));
    }

    /**
     * Create new draft order
     */
    public function create()
    {
        // Check if draft already exists
        $existingDraft = Order::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->first();

        if ($existingDraft) {
            return redirect()->route('penjaga.order.draft')
                ->with('info', 'Anda sudah memiliki draft order');
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'draft',
        ]);

        AuditLog::log('buat_order', "Membuat draft order baru: {$order->order_number}", [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        return redirect()->route('penjaga.order.draft')
            ->with('success', 'Draft order berhasil dibuat');
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        $order->load('items.product');

        return view('penjaga.order.show', compact('order'));
    }

    /**
     * Add item to order
     */
    public function addItem(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if (!$order->isDraft()) {
            return back()->with('error', 'Tidak bisa mengedit order yang sudah dikirim');
        }

        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity_text' => ['required', 'string', 'max:100'],
        ]);

        // Check if product already in order
        $existingItem = $order->items()->where('product_id', $request->product_id)->first();

        if ($existingItem) {
            $existingItem->update(['quantity_text' => $request->quantity_text]);
        } else {
            $order->items()->create([
                'product_id' => $request->product_id,
                'quantity_text' => $request->quantity_text,
            ]);
        }

        $product = Product::find($request->product_id);

        return back()->with('success', "{$product->name} ditambahkan ke order");
    }

    /**
     * Update order item
     */
    public function updateItem(Request $request, Order $order, OrderItem $item)
    {
        $this->authorizeOrder($order);

        if (!$order->isDraft()) {
            return back()->with('error', 'Tidak bisa mengedit order yang sudah dikirim');
        }

        $request->validate([
            'quantity_text' => ['required', 'string', 'max:100'],
        ]);

        $item->update(['quantity_text' => $request->quantity_text]);

        return back()->with('success', 'Jumlah berhasil diupdate');
    }

    /**
     * Remove item from order
     */
    public function removeItem(Order $order, OrderItem $item)
    {
        $this->authorizeOrder($order);

        if (!$order->isDraft()) {
            return back()->with('error', 'Tidak bisa mengedit order yang sudah dikirim');
        }

        $productName = $item->product->name;
        $item->delete();

        return back()->with('success', "{$productName} dihapus dari order");
    }

    /**
     * Send order (change status to dikirim)
     */
    public function send(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if (!$order->isDraft()) {
            return back()->with('error', 'Order sudah dikirim');
        }

        if ($order->items->isEmpty()) {
            return back()->with('error', 'Order tidak boleh kosong');
        }

        $order->update([
            'status' => 'dikirim',
            'sent_at' => now(),
            'notes' => $request->notes,
        ]);

        AuditLog::log('kirim_order', "Mengirim order: {$order->order_number}", [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'items_count' => $order->items->count(),
        ]);

        return redirect()->route('penjaga.order.whatsapp', $order);
    }

    /**
     * Show WhatsApp page with generated message
     */
    public function whatsapp(Order $order)
    {
        $this->authorizeOrder($order);

        $order->load('items.product');
        $message = $order->generateWhatsAppMessage();
        $encodedMessage = urlencode($message);

        return view('penjaga.order.whatsapp', compact('order', 'message', 'encodedMessage'));
    }

    /**
     * Show goods arrival form
     */
    public function markArrived(Order $order)
    {
        $this->authorizeOrder($order);

        if (!$order->isSent()) {
            return back()->with('error', 'Order belum dikirim');
        }

        $order->load('items.product');

        return view('penjaga.order.arrived', compact('order'));
    }

    /**
     * Complete order and update stock
     */
    public function complete(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if (!$order->isSent()) {
            return back()->with('error', 'Order belum dikirim');
        }

        // Update stock for each item
        foreach ($order->items as $item) {
            $newStatus = $request->input("stock_status.{$item->id}");

            if ($newStatus && in_array($newStatus, ['banyak', 'cukup', 'sedikit', 'kosong'])) {
                $item->product->update(['stock_status' => $newStatus]);
                $item->update(['stock_after_arrival' => $newStatus]);
            }
        }

        $order->update([
            'status' => 'selesai',
            'completed_at' => now(),
        ]);

        AuditLog::log('terima_barang', "Menerima barang order: {$order->order_number}", [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        return redirect()->route('penjaga.order.index')
            ->with('success', 'Order selesai! Stok telah diupdate.');
    }

    // ==========================================
    // ADMIN METHODS (Read Only)
    // ==========================================

    /**
     * Display orders list for admin
     */
    public function adminIndex()
    {
        $orders = Order::with('user', 'items.product')
            ->latest()
            ->paginate(15);

        $stats = [
            'draft' => Order::where('status', 'draft')->count(),
            'dikirim' => Order::where('status', 'dikirim')->count(),
            'selesai' => Order::where('status', 'selesai')->count(),
        ];

        return view('admin.order.index', compact('orders', 'stats'));
    }

    /**
     * Show order detail for admin
     */
    public function adminShow(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.order.show', compact('order'));
    }

    /**
     * Authorization helper
     */
    private function authorizeOrder(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isPemilik()) {
            abort(403, 'Anda tidak memiliki akses ke order ini');
        }
    }
}
