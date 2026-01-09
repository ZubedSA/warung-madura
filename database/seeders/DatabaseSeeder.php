<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        User::create([
            'name' => 'Pemilik Warung',
            'email' => 'pemilik@warung.com',
            'phone' => '081234567890',
            'role' => 'pemilik',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Penjaga Warung',
            'email' => 'penjaga@warung.com',
            'phone' => '081234567891',
            'role' => 'penjaga',
            'password' => Hash::make('password'),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Minuman', 'icon' => '🥤', 'sort_order' => 1],
            ['name' => 'Makanan Ringan', 'icon' => '🍪', 'sort_order' => 2],
            ['name' => 'Sembako', 'icon' => '🍚', 'sort_order' => 3],
            ['name' => 'Rokok', 'icon' => '🚬', 'sort_order' => 4],
            ['name' => 'Bumbu Dapur', 'icon' => '🧄', 'sort_order' => 5],
            ['name' => 'Sabun & Deterjen', 'icon' => '🧴', 'sort_order' => 6],
            ['name' => 'Obat-obatan', 'icon' => '💊', 'sort_order' => 7],
            ['name' => 'Lainnya', 'icon' => '📦', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Sample Products
        $products = [
            // Minuman
            ['category_id' => 1, 'name' => 'Aqua 600ml', 'stock_status' => 'banyak', 'unit' => 'dus'],
            ['category_id' => 1, 'name' => 'Teh Pucuk', 'stock_status' => 'cukup', 'unit' => 'dus'],
            ['category_id' => 1, 'name' => 'Coca Cola', 'stock_status' => 'sedikit', 'unit' => 'dus'],
            ['category_id' => 1, 'name' => 'Kopi Sachet ABC', 'stock_status' => 'cukup', 'unit' => 'renceng'],
            ['category_id' => 1, 'name' => 'Susu Ultra 250ml', 'stock_status' => 'banyak', 'unit' => 'dus'],

            // Makanan Ringan
            ['category_id' => 2, 'name' => 'Chitato', 'stock_status' => 'cukup', 'unit' => 'dus'],
            ['category_id' => 2, 'name' => 'Oreo', 'stock_status' => 'sedikit', 'unit' => 'dus'],
            ['category_id' => 2, 'name' => 'Mie Instan', 'stock_status' => 'banyak', 'unit' => 'dus'],
            ['category_id' => 2, 'name' => 'Roti Tawar', 'stock_status' => 'cukup', 'unit' => 'pcs'],

            // Sembako
            ['category_id' => 3, 'name' => 'Beras 5kg', 'stock_status' => 'banyak', 'unit' => 'karung'],
            ['category_id' => 3, 'name' => 'Gula Pasir 1kg', 'stock_status' => 'cukup', 'unit' => 'pcs'],
            ['category_id' => 3, 'name' => 'Minyak Goreng 1L', 'stock_status' => 'sedikit', 'unit' => 'pcs'],
            ['category_id' => 3, 'name' => 'Telur Ayam', 'stock_status' => 'kosong', 'unit' => 'peti'],

            // Rokok
            ['category_id' => 4, 'name' => 'Gudang Garam', 'stock_status' => 'banyak', 'unit' => 'slop'],
            ['category_id' => 4, 'name' => 'Sampoerna', 'stock_status' => 'cukup', 'unit' => 'slop'],
            ['category_id' => 4, 'name' => 'Djarum', 'stock_status' => 'sedikit', 'unit' => 'slop'],

            // Bumbu Dapur
            ['category_id' => 5, 'name' => 'Garam', 'stock_status' => 'cukup', 'unit' => 'pcs'],
            ['category_id' => 5, 'name' => 'Kecap Bango', 'stock_status' => 'banyak', 'unit' => 'pcs'],
            ['category_id' => 5, 'name' => 'Saos ABC', 'stock_status' => 'sedikit', 'unit' => 'pcs'],

            // Sabun & Deterjen
            ['category_id' => 6, 'name' => 'Rinso', 'stock_status' => 'cukup', 'unit' => 'dus'],
            ['category_id' => 6, 'name' => 'Sabun Lifebuoy', 'stock_status' => 'banyak', 'unit' => 'lusin'],
            ['category_id' => 6, 'name' => 'Shampo Sachet', 'stock_status' => 'sedikit', 'unit' => 'renceng'],

            // Obat-obatan
            ['category_id' => 7, 'name' => 'Paracetamol', 'stock_status' => 'cukup', 'unit' => 'strip'],
            ['category_id' => 7, 'name' => 'Antangin', 'stock_status' => 'banyak', 'unit' => 'dus'],
            ['category_id' => 7, 'name' => 'Tolak Angin', 'stock_status' => 'kosong', 'unit' => 'dus'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('==================');
        $this->command->info('Pemilik/Admin:');
        $this->command->info('  Email: pemilik@warung.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Penjaga:');
        $this->command->info('  Email: penjaga@warung.com');
        $this->command->info('  Password: password');
    }
}
