<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Use o nome da classe como string para evitar erros de namespace
    public function carrinho()
    {
        return $this->hasMany('App\Models\Carrinho');
    }
}