<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'notes',
        'sent_at',
        'completed_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Status options
     */
    public const STATUSES = [
        'draft' => ['label' => 'Draft', 'color' => 'gray', 'icon' => '📝'],
        'dikirim' => ['label' => 'Dikirim', 'color' => 'blue', 'icon' => '📤'],
        'selesai' => ['label' => 'Selesai', 'color' => 'green', 'icon' => '✅'],
    ];

    /**
     * Boot method to generate order number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . str_pad(
                    self::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    /**
     * Get the user who created this order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get status info
     */
    public function getStatusInfoAttribute(): array
    {
        return self::STATUSES[$this->status] ?? self::STATUSES['draft'];
    }

    /**
     * Check if order is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if order is sent
     */
    public function isSent(): bool
    {
        return $this->status === 'dikirim';
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'selesai';
    }

    /**
     * Generate WhatsApp message
     */
    public function generateWhatsAppMessage(): string
    {
        $message = "🛒 *ORDER BARANG*\n";
        $message .= "No: {$this->order_number}\n";
        $message .= "Tanggal: " . $this->created_at->format('d/m/Y H:i') . "\n\n";
        $message .= "📦 *Daftar Barang:*\n";

        foreach ($this->items as $index => $item) {
            $message .= ($index + 1) . ". {$item->product->name} - {$item->quantity_text}\n";
        }

        if ($this->notes) {
            $message .= "\n📝 *Catatan:* {$this->notes}";
        }

        $message .= "\n\nTerima kasih 🙏";

        return $message;
    }
}
