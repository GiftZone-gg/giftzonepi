<?php
// Para que serve: Guarda cada produto dentro do carrinho


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');  // Carrinho
            $table->foreignId('product_id')->constrained();  // Produto adicionado
            $table->integer('quantity');  // Quantidade
            $table->decimal('price_at_time', 10, 2);  // Preço no momento que adicionou
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};