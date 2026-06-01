<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'digital_key',
        'key_revealed',
    ];

    protected $casts = [
        'key_revealed' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'product_id');
    }

    /**
     * Gera uma chave digital no formato XXXXX-XXXXX-XXXXX
     */
    public static function gerarChaveDigital(): string
    {
        $segments = [];
        for ($i = 0; $i < 3; $i++) {
            $segments[] = strtoupper(Str::random(5));
        }
        return implode('-', $segments);
    }
}