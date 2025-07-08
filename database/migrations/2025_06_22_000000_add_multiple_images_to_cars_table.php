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
        Schema::table('cars', function (Blueprint $table) {
          
            // Adicionar colunas para as outras 3 imagens
            $table->string('image_1')->nullable()->after('image_cover');
            $table->string('image_2')->nullable()->after('image_1');
            $table->string('image_3')->nullable()->after('image_2');
            
            // Adicionar coluna para controlar se é imagem de capa
            $table->boolean('has_gallery')->default(false)->after('image_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Reverter as mudanças
            $table->renameColumn('image_cover', 'image');
            $table->dropColumn(['image_1', 'image_2', 'image_3', 'has_gallery']);
        });
    }
}; 