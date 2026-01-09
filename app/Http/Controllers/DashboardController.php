<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Income;
use App\Models\Expense;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show dashboard based on user role
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isPemilik()) {
            return $this->adminDashboard();
        }

        return $this->penjagaDashboard();
    }

    /**
     * Penjaga dashboard
     */
    private function penjagaDashboard()
    {
        $today = today();

        $stats = [
            'stok_kosong' => Product::where('stock_status', 'kosong')->count(),
            'stok_sedikit' => Product::where('stock_status', 'sedikit')->count(),
            'order_draft' => Order::where('status', 'draft')->count(),
            'order_dikirim' => Order::where('status', 'dikirim')->count(),
        ];

        return view('penjaga.dashboard', compact('stats'));
    }

    /**
     * Admin/Pemilik dashboard
     */
    private function adminDashboard()
    {
        $today = today();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Stock stats
        $stockStats = [
            'kosong' => Product::where('stock_status', 'kosong')->count(),
            'sedikit' => Product::where('stock_status', 'sedikit')->count(),
            'cukup' => Product::where('stock_status', 'cukup')->count(),
            'banyak' => Product::where('stock_status', 'banyak')->count(),
        ];

        // Today's finance
        $todayIncome = Income::whereDate('date', $today)->sum('amount');
        $todayExpense = Expense::whereDate('date', $today)->sum('amount');

        // Monthly finance
        $monthlyIncome = Income::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyExpense = Expense::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyProfit = $monthlyIncome - $monthlyExpense;

        // Recent activities
        $recentLogs = AuditLog::with('user')
            ->latest('created_at')
            ->take(10)
            ->get();

        // Active orders
        $activeOrders = Order::with('user', 'items')
            ->whereIn('status', ['draft', 'dikirim'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stockStats',
            'todayIncome',
            'todayExpense',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyProfit',
            'recentLogs',
            'activeOrders'
        ));
    }
}
