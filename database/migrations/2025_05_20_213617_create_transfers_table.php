<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('origem');
            $table->string('destino');
            $table->dateTime('data_hora');
            $table->enum('tipo', ['transfer', 'passeio']);
            $table->text('observacoes')->nullable();
            $table->enum('status', ['pendente', 'aceita', 'recusada'])->default('pendente');
            $table->boolean('email_enviado')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}