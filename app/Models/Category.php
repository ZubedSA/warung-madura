<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'sort_order',
    ];

    /**
     * Get all products in this category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get products count by status
     */
    public function getStockSummaryAttribute(): array
    {
        return [
            'banyak' => $this->products()->where('stock_status', 'banyak')->count(),
            'cukup' => $this->products()->where('stock_status', 'cukup')->count(),
            'sedikit' => $this->products()->where('stock_status', 'sedikit')->count(),
            'kosong' => $this->products()->where('stock_status', 'kosong')->count(),
        ];
    }
}
