<?php
// Para que serve: Guarda cupons promocionais (ex: "BEMVINDO10" = 10% off)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();  // "FRETEGRATIS"
            $table->text('description')->nullable();  // "Cupom de boas-vindas"
            $table->enum('discount_type', ['percentage', 'fixed']);  // Porcentagem ou valor fixo
            $table->decimal('discount_value', 10, 2);  // 10 (10% ou R$10)
            $table->decimal('min_purchase', 10, 2)->default(0);  // Valor mínimo para usar
            $table->decimal('max_discount', 10, 2)->nullable();  // Desconto máximo (se for %)
            $table->integer('usage_limit')->nullable();  // Quantos usos no total
            $table->integer('used_count')->default(0);  // Quantas vezes já foi usado
            $table->timestamp('valid_from');  // Válido a partir de
            $table->timestamp('valid_until');  // Válido até
            $table->boolean('is_active')->default(true);  // Cupom ativo?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};