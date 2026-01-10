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

// Raw Turso Connectivity Test (Bypasses Laravel)
Route::get('/test-turso', function () {
    $url = "https://turso-db-create-warung-madura-zubedsa.aws-ap-northeast-1.turso.io/v2/pipeline";
    $token = "eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3Njc5ODk2MDIsImlkIjoiOTVlNWM3MGUtZTU4YS00MDFjLWI5Y2MtNWM3ZWMxNWZkZTUwIiwicmlkIjoiZjM1NTFhNWEtNjg2OS00MDc1LThhYTAtMjEyYjI5MDBhMDkzIn0.CoBmITRsgBq1jWm6WfFLf3AaEwokwPScM6cNbLZOgwZ7_GuuT7S7Q7r0D95e2oWxe6VrAPC3hsLRM-hsY51sAQ";

    $query = [
        "requests" => [
            ["type" => "execute", "stmt" => ["sql" => "SELECT name FROM sqlite_master WHERE type='table'"]],
            ["type" => "close"]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return "<h1>Raw Turso Test</h1><p>HTTP Code: $httpCode</p><pre>" . htmlspecialchars($response) . "</pre>";
});

// Create Tables Directly in Turso via HTTP
Route::get('/setup-turso', function () {
    $url = "https://turso-db-create-warung-madura-zubedsa.aws-ap-northeast-1.turso.io/v2/pipeline";
    $token = "eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3Njc5ODk2MDIsImlkIjoiOTVlNWM3MGUtZTU4YS00MDFjLWI5Y2MtNWM3ZWMxNWZkZTUwIiwicmlkIjoiZjM1NTFhNWEtNjg2OS00MDc1LThhYTAtMjEyYjI5MDBhMDkzIn0.CoBmITRsgBq1jWm6WfFLf3AaEwokwPScM6cNbLZOgwZ7_GuuT7S7Q7r0D95e2oWxe6VrAPC3hsLRM-hsY51sAQ";

    $statements = [
        "CREATE TABLE IF NOT EXISTS migrations (id INTEGER PRIMARY KEY AUTOINCREMENT, migration TEXT NOT NULL, batch INTEGER NOT NULL)",
        "CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT NOT NULL UNIQUE, phone TEXT DEFAULT '', role TEXT DEFAULT 'penjaga', email_verified_at TEXT DEFAULT '2000-01-01 00:00:00', password TEXT NOT NULL, remember_token TEXT DEFAULT '', created_at TEXT, updated_at TEXT)",
        "CREATE TABLE IF NOT EXISTS categories (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, created_at TEXT, updated_at TEXT)",
        "CREATE TABLE IF NOT EXISTS products (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, category_id INTEGER, stock_status TEXT DEFAULT 'cukup', supplier_name TEXT DEFAULT '', supplier_phone TEXT DEFAULT '', created_at TEXT, updated_at TEXT)",
        "CREATE TABLE IF NOT EXISTS sessions (id TEXT PRIMARY KEY, user_id INTEGER DEFAULT 0, ip_address TEXT DEFAULT '', user_agent TEXT DEFAULT '', payload TEXT NOT NULL, last_activity INTEGER NOT NULL)",
        "INSERT OR IGNORE INTO users (id, name, email, phone, role, password, created_at, updated_at) VALUES (1, 'Penjaga', 'penjaga@warung.com', '', 'penjaga', '\$2y\$12\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', datetime('now'), datetime('now'))",
        "INSERT OR IGNORE INTO users (id, name, email, phone, role, password, created_at, updated_at) VALUES (2, 'Pemilik', 'pemilik@warung.com', '', 'pemilik', '\$2y\$12\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', datetime('now'), datetime('now'))",
        "INSERT OR IGNORE INTO categories (id, name, created_at, updated_at) VALUES (1, 'Makanan', datetime('now'), datetime('now'))",
        "INSERT OR IGNORE INTO categories (id, name, created_at, updated_at) VALUES (2, 'Minuman', datetime('now'), datetime('now'))",
        "INSERT OR IGNORE INTO categories (id, name, created_at, updated_at) VALUES (3, 'Sembako', datetime('now'), datetime('now'))",
        "INSERT OR IGNORE INTO products (id, name, category_id, stock_status, created_at, updated_at) VALUES (1, 'Indomie Goreng', 1, 'banyak', datetime('now'), datetime('now'))",
        "INSERT OR IGNORE INTO products (id, name, category_id, stock_status, created_at, updated_at) VALUES (2, 'Aqua 600ml', 2, 'cukup', datetime('now'), datetime('now'))",
        "INSERT OR IGNORE INTO products (id, name, category_id, stock_status, created_at, updated_at) VALUES (3, 'Beras 5kg', 3, 'sedikit', datetime('now'), datetime('now'))"
    ];

    $requests = [];
    foreach ($statements as $sql) {
        $requests[] = ["type" => "execute", "stmt" => ["sql" => $sql]];
    }
    $requests[] = ["type" => "close"];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["requests" => $requests]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        return "<h1>TURSO DATABASE SETUP COMPLETE!</h1><p>All tables created and seeded.</p><a href='/'>Go to Login</a>";
    }
    return "<h1>Setup Failed</h1><p>HTTP Code: $httpCode</p><pre>" . htmlspecialchars($response) . "</pre>";
});

// Fix user passwords with correct bcrypt hash
Route::get('/fix-users', function () {
    $url = "https://turso-db-create-warung-madura-zubedsa.aws-ap-northeast-1.turso.io/v2/pipeline";
    $token = "eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3Njc5ODk2MDIsImlkIjoiOTVlNWM3MGUtZTU4YS00MDFjLWI5Y2MtNWM3ZWMxNWZkZTUwIiwicmlkIjoiZjM1NTFhNWEtNjg2OS00MDc1LThhYTAtMjEyYjI5MDBhMDkzIn0.CoBmITRsgBq1jWm6WfFLf3AaEwokwPScM6cNbLZOgwZ7_GuuT7S7Q7r0D95e2oWxe6VrAPC3hsLRM-hsY51sAQ";

    // Generate correct bcrypt hash for 'password'
    $hash = password_hash('password', PASSWORD_BCRYPT);

    $statements = [
        "DELETE FROM users",
        "INSERT INTO users (id, name, email, phone, role, password, created_at, updated_at) VALUES (1, 'Penjaga', 'penjaga@warung.com', '', 'penjaga', '$hash', datetime('now'), datetime('now'))",
        "INSERT INTO users (id, name, email, phone, role, password, created_at, updated_at) VALUES (2, 'Pemilik', 'pemilik@warung.com', '', 'pemilik', '$hash', datetime('now'), datetime('now'))"
    ];

    $requests = [];
    foreach ($statements as $sql) {
        $requests[] = ["type" => "execute", "stmt" => ["sql" => $sql]];
    }
    $requests[] = ["type" => "close"];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["requests" => $requests]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        return "<h1>USERS FIXED!</h1><p>Password: password</p><a href='/login'>Go to Login</a>";
    }
    return "<h1>Fix Failed</h1><p>HTTP Code: $httpCode</p><pre>" . htmlspecialchars($response) . "</pre>";
});

Route::get('/rescue', function () {
    try {
        // Clear all caches FIRST to reset everything
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');

        // Force fresh migration and seeding on Turso
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true,
            '--database' => 'libsql',
            '--seed' => true
        ]);

        return '<h1>WARUNG MADURA SYSTEM RESTORED!</h1><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre><br><a href="/">Go to Login</a>';
    } catch (\Throwable $e) {
        return '<h1>Restoration Failed!</h1><p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre>';
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
