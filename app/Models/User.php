<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is penjaga
     */
    public function isPenjaga(): bool
    {
        return $this->role === 'penjaga';
    }

    /**
     * Check if user is pemilik/admin
     */
    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

    /**
     * Get all orders created by this user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all incomes recorded by this user
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    /**
     * Get all expenses recorded by this user
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get all audit logs for this user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
