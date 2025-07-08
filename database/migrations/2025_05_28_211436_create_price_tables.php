<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceTables extends Migration
{
    public function up()
    {
        Schema::create('price_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            
            // Dentro da cidade
            $table->decimal('preco_dentro_com_motorista', 10, 2);
            $table->decimal('preco_dentro_sem_motorista', 10, 2);

            // Fora da cidade
            $table->decimal('preco_fora_com_motorista', 10, 2);
            $table->decimal('preco_fora_sem_motorista', 10, 2);

            // Extras
            $table->decimal('taxa_entrega_recolha', 10, 2)->nullable();
            $table->integer('plafond_km_dia')->default(100);
            $table->decimal('preco_km_extra', 10, 2)->nullable();
            $table->decimal('caucao', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('price_tables');
    }
}
