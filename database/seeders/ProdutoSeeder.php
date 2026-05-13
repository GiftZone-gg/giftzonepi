<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produtos')->insert([
            'nome'             => 'Death Stranding 2: On The Beach',
            'slug'             => 'death-stranding-2-on-the-beach',
            'descricao'        => 'Do lendário criador de jogos Hideo Kojima vem uma evolução emocionante desta experiência que desafia gêneros. Ao trazer este título para PC, a KOJIMA PRODUCTIONS trabalhou com a Nixxes Software para entregar a versão definitiva do lançamento aclamado pela crítica no console.',
            'desenvolvedor'    => 'Kojima Productions',
            'publisher'        => 'Sony Interactive Entertainment',
            'genero'           => 'Ação / Aventura',
            'imagem_principal' => 'deathstran2.webp',
            'galeria'          => json_encode([
                'death-stranding-2-1.jpg',
                'death-stranding-2-2.jpg',
                'death-stranding-2-3.jpg',
            ]),
            'plataformas'      => json_encode(['PlayStation 5', 'PlayStation 4', 'PC']),
            'edicoes'          => json_encode([
                ['nome' => 'Standard',  'preco' => 350.00],
                ['nome' => 'Deluxe',    'preco' => 449.90],
                ['nome' => 'Collector', 'preco' => 699.90],
            ]),
            'requisitos'       => json_encode([
                'minimo' => [
                    'SO'             => 'Windows 10 64-bit',
                    'Processador'    => 'Intel Core i7-8700',
                    'Memória'        => '16 GB RAM',
                    'Armazenamento'  => '100 GB',
                ],
                'recomendado' => [
                    'SO'             => 'Windows 11 64-bit',
                    'Processador'    => 'Intel Core i9-12900K',
                    'Memória'        => '32 GB RAM',
                    'Armazenamento'  => '100 GB SSD',
                ],
            ]),
            'ativo'            => true,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}