<?php
// Para que serve: Guarda cada compra finalizada


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();  // Quem comprou
            $table->string('order_number', 50)->unique();  // Número do pedido: "#GZ-2024001"
            $table->decimal('total_amount', 10, 2);  // Valor total
            $table->decimal('discount_amount', 10, 2)->default(0);  // Desconto aplicado
            $table->decimal('final_amount', 10, 2);  // Valor final pago
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();  // Cupom usado
            $table->foreignId('payment_method_id')->constrained();  // Cartão usado
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');  // Status pagamento
            $table->enum('order_status', ['processing', 'completed', 'cancelled'])->default('processing');  // Status pedido
            $table->string('transaction_id', 255)->nullable();  // ID da transação (gateway)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};