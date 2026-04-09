<?php
// Para que serve: Adiciona campos CPF e is_admin à tabela users padrão do Laravel


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 14)->unique()->after('email');  // CPF do usuário
            $table->boolean('is_admin')->default(false)->after('password');  // É administrador?
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cpf', 'is_admin']);
        });
    }
};