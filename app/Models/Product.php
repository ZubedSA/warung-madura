<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'stock_status',
        'unit',
        'supplier_name',
        'supplier_phone',
    ];

    /**
     * Stock status options
     */
    public const STOCK_STATUSES = [
        'banyak' => ['label' => 'Banyak', 'color' => 'green', 'icon' => '🟢'],
        'cukup' => ['label' => 'Cukup', 'color' => 'blue', 'icon' => '🔵'],
        'sedikit' => ['label' => 'Sedikit', 'color' => 'orange', 'icon' => '🟠'],
        'kosong' => ['label' => 'Kosong', 'color' => 'red', 'icon' => '🔴'],
    ];

    /**
     * Get the category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get stock status info
     */
    public function getStatusInfoAttribute(): array
    {
        return self::STOCK_STATUSES[$this->stock_status] ?? self::STOCK_STATUSES['cukup'];
    }

    /**
     * Check if product is low on stock
     */
    public function isLowStock(): bool
    {
        return in_array($this->stock_status, ['sedikit', 'kosong']);
    }
}
