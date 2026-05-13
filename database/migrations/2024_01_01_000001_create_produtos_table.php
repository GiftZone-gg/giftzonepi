<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->text('descricao');
            $table->string('desenvolvedor')->nullable();
            $table->string('publisher')->nullable();
            $table->string('genero')->nullable();
            $table->string('imagem_principal');
            $table->json('galeria')->nullable(); // array de paths de imagens
            $table->json('plataformas'); // ex: ["PS4","PS5","PC"]
            $table->json('edicoes'); // ex: [{"nome":"Standard","preco":350},{"nome":"Deluxe","preco":499}]
            $table->json('requisitos')->nullable(); // {"minimo":{"so":"...","armazenamento":"..."},"recomendado":{...}}
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
