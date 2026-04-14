<?php
// Para que serve: Guarda onde o jogo roda (PC, PlayStation, Xbox, Nintendo Switch)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();  // "PC", "PlayStation 5"
            $table->string('slug', 100)->unique();  // "pc", "playstation-5"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};