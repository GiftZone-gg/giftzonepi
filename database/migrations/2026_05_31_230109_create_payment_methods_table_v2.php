<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove a foreign key de orders primeiro
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
        });

        // Agora dropa a tabela antiga
        Schema::dropIfExists('payment_methods');

        // Cria a nova
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->string('brand');
            $table->string('type');
            $table->string('last_four', 4);
            $table->string('holder_name');
            $table->string('expiry', 7);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Torna payment_method_id nullable em orders (não é mais obrigatório)
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};