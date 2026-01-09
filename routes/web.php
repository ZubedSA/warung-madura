<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

// Debug Route
Route::get('/debug-db', function () {
    try {
        $connection = config('database.default');
        $tables = \Illuminate\Support\Facades\DB::select("SELECT name FROM sqlite_master WHERE type='table'");

        return [
            'status' => 'success',
            'tables' => array_column($tables, 'name'),
            'connection' => $connection,
            'driver' => config("database.connections.$connection.driver"),
            'url_masked' => substr(config("database.connections.$connection.url"), 0, 20) . '...',
        ];
    } catch (\Throwable $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'class' => get_class($e),
        ];
    }
});

// Force Migration Route (Temporary for Vercel Setup)
Route::get('/force-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return 'Migration successful: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        return 'Migration failed: ' . $e->getMessage();
    }
});

// Force Seed Route (Temporary for Vercel Setup)
Route::get('/force-seed', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return 'Seeding successful: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        return 'Seeding failed: ' . $e->getMessage();
    }
});


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // PENJAGA ROUTES
    // ==========================================
    Route::middleware('role:penjaga')->prefix('penjaga')->name('penjaga.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('penjaga.stok.index');
        });
        // Stock Management
        Route::get('/stok', [ProductController::class, 'index'])->name('stok.index');
        Route::get('/stok/status/{status}', [ProductController::class, 'byStatus'])->name('stok.status');
        Route::get('/stok/kategori/{category}', [ProductController::class, 'byCategory'])->name('stok.kategori');
        Route::post('/stok/{product}/update-status', [ProductController::class, 'updateStatus'])->name('stok.update-status');
        Route::post('/stok/{product}/add-to-order', [ProductController::class, 'addToOrder'])->name('stok.add-to-order');
        Route::post('/stok/tambah-barang', [ProductController::class, 'storeFromPenjaga'])->name('stok.store');

        // Order Management
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');
        Route::get('/order/draft', [OrderController::class, 'draft'])->name('order.draft');
        Route::post('/order/create', [OrderController::class, 'create'])->name('order.create');
        Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
        Route::post('/order/{order}/add-item', [OrderController::class, 'addItem'])->name('order.add-item');
        Route::put('/order/{order}/item/{item}', [OrderController::class, 'updateItem'])->name('order.update-item');
        Route::delete('/order/{order}/item/{item}', [OrderController::class, 'removeItem'])->name('order.remove-item');
        Route::post('/order/{order}/send', [OrderController::class, 'send'])->name('order.send');
        Route::get('/order/{order}/whatsapp', [OrderController::class, 'whatsapp'])->name('order.whatsapp');
        Route::post('/order/{order}/arrived', [OrderController::class, 'markArrived'])->name('order.arrived');
        Route::post('/order/{order}/complete', [OrderController::class, 'complete'])->name('order.complete');

        // Income
        Route::get('/pemasukan', [IncomeController::class, 'index'])->name('pemasukan.index');
        Route::get('/pemasukan/tambah', [IncomeController::class, 'create'])->name('pemasukan.create');
        Route::post('/pemasukan', [IncomeController::class, 'store'])->name('pemasukan.store');

        // Expense
        Route::get('/pengeluaran', [ExpenseController::class, 'index'])->name('pengeluaran.index');
        Route::get('/pengeluaran/tambah', [ExpenseController::class, 'create'])->name('pengeluaran.create');
        Route::post('/pengeluaran', [ExpenseController::class, 'store'])->name('pengeluaran.store');

        // Finance History
        Route::get('/keuangan', [ReportController::class, 'penjagaHistory'])->name('keuangan.index');
    });

    // ==========================================
    // ADMIN/PEMILIK ROUTES
    // ==========================================
    Route::middleware('role:pemilik')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.laporan.index');
        });
        // Dashboard already handled above

        // Reports
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/harian', [ReportController::class, 'daily'])->name('laporan.harian');
        Route::get('/laporan/bulanan', [ReportController::class, 'monthly'])->name('laporan.bulanan');

        // Order Monitoring (Read Only)
        Route::get('/order', [OrderController::class, 'adminIndex'])->name('order.index');
        Route::get('/order/{order}', [OrderController::class, 'adminShow'])->name('order.show');

        // Audit Logs
        Route::get('/log', [AuditLogController::class, 'index'])->name('log.index');

        // Master Data
        Route::get('/master', function () {
            return view('admin.master.index');
        })->name('master.index');

        // Categories
        Route::resource('/master/kategori', CategoryController::class)->names('kategori');

        // Products
        Route::get('/master/barang', [ProductController::class, 'adminIndex'])->name('barang.index');
        Route::get('/master/barang/tambah', [ProductController::class, 'create'])->name('barang.create');
        Route::post('/master/barang', [ProductController::class, 'store'])->name('barang.store');
        Route::get('/master/barang/{product}/edit', [ProductController::class, 'edit'])->name('barang.edit');
        Route::put('/master/barang/{product}', [ProductController::class, 'update'])->name('barang.update');
        Route::delete('/master/barang/{product}', [ProductController::class, 'destroy'])->name('barang.destroy');

        // Users
        Route::resource('/master/user', UserController::class)->names('user');
    });
});
