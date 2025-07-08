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
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('flight_number')->nullable()->after('destino');
            $table->time('flight_time')->nullable()->after('flight_number');
            $table->date('flight_date')->nullable()->after('flight_time');
            $table->string('airline')->nullable()->after('flight_date');
            $table->text('special_requests')->nullable()->after('airline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['flight_number', 'flight_time', 'flight_date', 'airline', 'special_requests']);
        });
    }
};
