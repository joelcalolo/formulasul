<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('carro_principal_id')->constrained('cars')->onDelete('cascade');
            $table->foreignId('carro_secundario_id')->nullable()->constrained('cars')->onDelete('set null');
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');
            $table->string('local_entrega');
            $table->text('observacoes')->nullable();
            $table->enum('status', ['pendente', 'rejeitado', 'confirmado'])->default('pendente');
            $table->boolean('email_enviado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_requests');
    }
};
