<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'amount',
        'category',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Category options
     */
    public const CATEGORIES = [
        'belanja_barang' => ['label' => 'Belanja Barang', 'icon' => '🛒'],
        'listrik' => ['label' => 'Listrik', 'icon' => '⚡'],
        'air' => ['label' => 'Air', 'icon' => '💧'],
        'lainnya' => ['label' => 'Lainnya', 'icon' => '📋'],
    ];

    /**
     * Get the user who recorded this expense
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get category info
     */
    public function getCategoryInfoAttribute(): array
    {
        return self::CATEGORIES[$this->category] ?? self::CATEGORIES['lainnya'];
    }

    /**
     * Format amount as Rupiah
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
