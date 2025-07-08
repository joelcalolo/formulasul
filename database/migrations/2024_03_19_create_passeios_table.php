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
        Schema::create('passeios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('subtitulo')->nullable();
            $table->string('imagem_principal');
            $table->string('localizacao');
            $table->string('duracao');
            $table->decimal('avaliacao', 2, 1)->default(0);
            $table->integer('total_avaliacoes')->default(0);
            $table->text('descricao');
            $table->decimal('preco', 10, 2);
            $table->string('tamanho_grupo');
            $table->string('idioma');
            $table->json('galeria')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passeios');
    }
}; 