<?php
// Para que serve: Tabela mais importante! Guarda todos os produtos da loja


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();  // ID do produto
            $table->string('title', 255);  // Nome: "God of War Ragnarök"
            $table->string('slug', 255)->unique();  // "god-of-war-ragnarok"
            $table->text('description')->nullable();  // Descrição do jogo
            $table->decimal('price', 10, 2);  // Preço: R$ 199,90
            $table->decimal('old_price', 10, 2)->nullable();  // Preço antigo (promoção)
            $table->enum('type', ['game', 'dlc', 'gift']);  // Tipo: jogo, DLC ou gift card
            $table->integer('stock')->default(0);  // Quantidade em estoque
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');  // Qual gênero
            $table->foreignId('platform_id')->constrained()->onDelete('cascade');  // Qual plataforma
            $table->string('os_requirement')->nullable();  // SO necessário: "Windows 10/11"
            $table->string('image_url', 500)->nullable();  // Caminho da imagem
            $table->boolean('is_active')->default(true);  // Produto ativo?
            $table->boolean('is_new')->default(false);  // É lançamento?
            $table->boolean('is_bestseller')->default(false);  // É mais vendido?
            $table->timestamps();
            
            // Índices para busca mais rápida
            $table->index(['genre_id', 'platform_id']);
            $table->index('is_new');
            $table->index('is_bestseller');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};