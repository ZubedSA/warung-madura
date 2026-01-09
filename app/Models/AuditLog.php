<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'data',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Action types
     */
    public const ACTIONS = [
        'update_stok' => ['label' => 'Update Stok', 'icon' => '📦', 'color' => 'blue'],
        'buat_order' => ['label' => 'Buat Order', 'icon' => '🛒', 'color' => 'purple'],
        'kirim_order' => ['label' => 'Kirim Order', 'icon' => '📤', 'color' => 'green'],
        'terima_barang' => ['label' => 'Terima Barang', 'icon' => '📥', 'color' => 'teal'],
        'input_pemasukan' => ['label' => 'Input Pemasukan', 'icon' => '💰', 'color' => 'green'],
        'input_pengeluaran' => ['label' => 'Input Pengeluaran', 'icon' => '💸', 'color' => 'red'],
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action info
     */
    public function getActionInfoAttribute(): array
    {
        return self::ACTIONS[$this->action] ?? [
            'label' => $this->action,
            'icon' => '📋',
            'color' => 'gray',
        ];
    }

    /**
     * Log an activity
     */
    public static function log(string $action, string $description, array $data = []): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'data' => $data,
            'created_at' => now(),
        ]);
    }
}
