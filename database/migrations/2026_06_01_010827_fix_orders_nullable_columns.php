<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite não suporta ALTER COLUMN, então recriamos as colunas
        // Mas podemos contornar removendo a constraint via rebuild

        // Solução simples: criar coluna nova, copiar dados, dropar antiga
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_id_new')->nullable()->after('final_amount');
            $table->string('transaction_id_new', 255)->nullable()->after('order_status');
        });

        // Copia dados existentes
        DB::table('orders')->update([
            'payment_method_id_new' => DB::raw('payment_method_id'),
            'transaction_id_new' => DB::raw('transaction_id'),
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method_id_new', 'transaction_id_new']);
        });
    }
};