<?php
// Para que serve: Palavras-chave para filtrar produtos (ex: "Multiplayer", "Indie", "Lançamento")


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();  // "Multiplayer"
            $table->string('slug', 100)->unique();  // "multiplayer"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};