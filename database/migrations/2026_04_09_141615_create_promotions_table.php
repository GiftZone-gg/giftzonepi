<?php
// Para que serve: Guarda campanhas promocionais (Black Friday, Natal, etc.)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);  // "Black Friday 2024"
            $table->integer('discount_percent');  // 20 (20% de desconto)
            $table->timestamp('start_date');  // Quando começa
            $table->timestamp('end_date');  // Quando termina
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};