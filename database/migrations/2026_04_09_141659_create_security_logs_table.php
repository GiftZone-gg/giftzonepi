<?php
// Para que serve: Registra ações importantes para auditoria (logins suspeitos, tentativas de invasão)


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();  // Usuário (se tiver)
            $table->string('action', 255);  // "login_failed", "password_changed"
            $table->string('ip_address', 45);  // IP de quem fez a ação
            $table->text('user_agent')->nullable();  // Navegador/Dispositivo
            $table->text('details')->nullable();  // Detalhes adicionais
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};