<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'brand',
        'type',
        'last_four',
        'holder_name',
        'expiry',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Detecta a bandeira pelo número do cartão (primeiros dígitos)
     */
    public static function detectBrand(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);

        $patterns = [
            'visa'       => '/^4/',
            'mastercard' => '/^(5[1-5]|2[2-7])/',
            'amex'       => '/^3[47]/',
            'elo'        => '/^(636368|636297|504175|438935|451416|509048|509067|509049|509069|509050|509074|509068|509040|509045|509051|509046|509066|509047|509042|431274|438935|636297|636368|504175|451416|636369)/',
            'hipercard'  => '/^(606282|3841)/',
            'diners'     => '/^3(0[0-5]|[68])/',
            'discover'   => '/^6(?:011|5)/',
            'jcb'        => '/^(?:2131|1800|35\d{3})/',
        ];

        foreach ($patterns as $brand => $pattern) {
            if (preg_match($pattern, $number)) {
                return $brand;
            }
        }

        return 'generic';
    }

    /**
     * Retorna emoji/ícone da bandeira
     */
    public static function brandIcon(string $brand): string
    {
        return match ($brand) {
            'visa'       => '💳 Visa',
            'mastercard' => '💳 Mastercard',
            'amex'       => '💳 Amex',
            'elo'        => '💳 Elo',
            'hipercard'  => '💳 Hipercard',
            'diners'     => '💳 Diners',
            'discover'   => '💳 Discover',
            'jcb'        => '💳 JCB',
            default      => '💳 Cartão',
        };
    }
}