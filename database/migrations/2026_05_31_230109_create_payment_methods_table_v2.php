<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dropa a tabela antiga se existir (era do template inicial, nunca usada de verdade)
        Schema::dropIfExists('payment_methods');

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label');           // "Visa final 1234"
            $table->string('brand');           // visa, mastercard, elo, amex, hipercard, diners, discover, jcb
            $table->string('type');            // credito ou debito
            $table->string('last_four', 4);    // últimos 4 dígitos
            $table->string('holder_name');     // nome no cartão
            $table->string('expiry', 7);       // MM/AAAA
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};