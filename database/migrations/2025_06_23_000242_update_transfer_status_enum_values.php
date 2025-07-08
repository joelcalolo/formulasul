<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, change the column to a string temporarily
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('status')->default('pendente')->change();
        });

        // Update existing records to use the new status values
        DB::table('transfers')
            ->where('status', 'aceita')
            ->update(['status' => 'confirmado']);
            
        DB::table('transfers')
            ->where('status', 'recusada')
            ->update(['status' => 'rejeitado']);

        // Then change it back to an enum with the new values
        DB::statement("ALTER TABLE transfers MODIFY COLUMN status ENUM('pendente', 'confirmado', 'rejeitado') DEFAULT 'pendente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change the column to a string temporarily
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('status')->default('pendente')->change();
        });

        // Revert existing records back to old values
        DB::table('transfers')
            ->where('status', 'confirmado')
            ->update(['status' => 'aceita']);
            
        DB::table('transfers')
            ->where('status', 'rejeitado')
            ->update(['status' => 'recusada']);

        // Revert the enum column to old values
        DB::statement("ALTER TABLE transfers MODIFY COLUMN status ENUM('pendente', 'aceita', 'recusada') DEFAULT 'pendente'");
    }
};
