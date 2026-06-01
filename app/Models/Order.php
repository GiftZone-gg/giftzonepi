<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'discount_amount',
        'final_amount',
        'payment_status',
        'order_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Pode cancelar se:
     * - Não está cancelado
     * - Nenhuma chave foi revelada
     */
    public function canCancel(): bool
    {
        if ($this->order_status === 'cancelled') {
            return false;
        }
        return !$this->items()->where('key_revealed', true)->exists();
    }
}