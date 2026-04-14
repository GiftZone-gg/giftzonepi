<?php
// Para que serve: Guarda cada produto dentro de um pedido (histórico)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Pedido
            $table->foreignId('product_id')->constrained();  // Produto comprado
            $table->integer('quantity');  // Quantidade
            $table->decimal('price', 10, 2);  // Preço na hora da compra
            $table->decimal('total', 10, 2);  // Preço x Quantidade
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};