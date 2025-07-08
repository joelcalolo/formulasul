<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rental_requests', function (Blueprint $table) {
            $table->string('reject_reason')->nullable()->after('email_enviado');
        });
    }

    public function down()
    {
        Schema::table('rental_requests', function (Blueprint $table) {
            $table->dropColumn('reject_reason');
        });
    }
}; 