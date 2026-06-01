<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'desenvolvedor',
        'publisher',
        'genero',
        'imagem_principal',
        'galeria',
        'plataformas',
        'edicoes',
        'requisitos',
        'trailer_url',
        'ativo',
    ];

    protected $casts = [
        'galeria'     => 'array',
        'plataformas' => 'array',
        'edicoes'     => 'array',
        'requisitos'  => 'array',
        'ativo'       => 'boolean',
    ];

    public function getPrecoMinimoAttribute(): float
    {
        if (empty($this->edicoes)) return 0;
        return collect($this->edicoes)->min('preco');
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
}