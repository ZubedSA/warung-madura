<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user who recorded this income
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format amount as Rupiah
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
