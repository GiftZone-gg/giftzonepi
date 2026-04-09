<?php
// Para que serve: Liga produtos com tags (um produto pode ter várias tags)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');  // Qual produto
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');  // Qual tag
            $table->unique(['product_id', 'tag_id']);  // Não pode repetir a mesma tag no mesmo produto
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tag');
    }
};