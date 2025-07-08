<?php
// database/migrations/xxxx_xx_xx_create_cars_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->string('modelo');
            $table->string('caixa'); // Manual ou Automática
            $table->string('tracao'); // FWD, RWD, 4WD, etc.
            $table->integer('lugares');
            $table->string('combustivel'); // Gasolina, Diesel, Elétrico
            $table->string('status')->default('disponivel');
            $table->string('image_cover')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // opcional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
