<?php
// Para que serve: Guarda cartões salvos do usuário (tokenizados por segurança)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_last_digits', 4);  // Últimos 4 dígitos: "1234"
            $table->string('card_brand', 50);  // Bandeira: "Visa", "Mastercard"
            $table->string('card_token', 255);  // Token do cartão (segurança)
            $table->boolean('is_default')->default(false);  // Cartão padrão?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};