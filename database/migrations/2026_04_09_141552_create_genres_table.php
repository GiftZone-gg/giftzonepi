<?php
// Para que serve: Guarda as categorias dos jogos (Ação, RPG, Esporte, etc.)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();  // Nome: "Ação", "RPG"
            $table->string('slug', 100)->unique();  // Versão amigável: "acao", "rpg"
            $table->timestamps();  // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};