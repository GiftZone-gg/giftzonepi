<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'link',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers para criar notificações ───

    public static function criarPedidoRealizado($userId, $orderNumber)
    {
        return self::create([
            'user_id' => $userId,
            'type'    => 'order_placed',
            'title'   => 'Pedido Confirmado!',
            'message' => "Seu pedido {$orderNumber} foi realizado com sucesso. Acesse seus pedidos para revelar suas chaves.",
            'icon'    => '🛒',
            'link'    => '/usuario/pedidos',
        ]);
    }

    public static function criarPedidoConcluido($userId, $orderNumber)
    {
        return self::create([
            'user_id' => $userId,
            'type'    => 'order_completed',
            'title'   => 'Pedido Concluído',
            'message' => "Seu pedido {$orderNumber} foi concluído! Suas chaves digitais estão disponíveis.",
            'icon'    => '✅',
            'link'    => '/usuario/pedidos',
        ]);
    }

    public static function criarPedidoCancelado($userId, $orderNumber)
    {
        return self::create([
            'user_id' => $userId,
            'type'    => 'order_cancelled',
            'title'   => 'Pedido Cancelado',
            'message' => "O pedido {$orderNumber} foi cancelado e o reembolso será processado.",
            'icon'    => '❌',
            'link'    => '/usuario/pedidos',
        ]);
    }

    public static function criarBoasVindas($userId)
    {
        return self::create([
            'user_id' => $userId,
            'type'    => 'welcome',
            'title'   => 'Bem-vindo(a) à GiftZone! 🎮',
            'message' => 'Sua conta foi criada com sucesso. Explore nosso catálogo e encontre os melhores jogos!',
            'icon'    => '🎉',
            'link'    => '/catalogo',
        ]);
    }
}