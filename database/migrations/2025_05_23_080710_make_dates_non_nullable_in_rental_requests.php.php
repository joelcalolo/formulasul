<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDatesNonNullableInRentalRequests extends Migration
{
    public function up()
    {
        // Set default values for existing null records
        \App\Models\RentalRequest::whereNull('data_inicio')->update(['data_inicio' => now()]);
        \App\Models\RentalRequest::whereNull('data_fim')->update(['data_fim' => now()->addDays(3)]);

        Schema::table('rental_requests', function (Blueprint $table) {
            $table->dateTime('data_inicio')->nullable(false)->change();
            $table->dateTime('data_fim')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('rental_requests', function (Blueprint $table) {
            $table->dateTime('data_inicio')->nullable()->change();
            $table->dateTime('data_fim')->nullable()->change();
        });
    }
}