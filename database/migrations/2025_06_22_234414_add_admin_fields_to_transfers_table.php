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
            $table->unsignedBigInteger('admin_id')->nullable()->after('user_id');
            $table->timestamp('confirmed_at')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('confirmed_at');
            $table->text('admin_notes')->nullable()->after('rejected_at');
            $table->string('confirmation_method')->nullable()->after('admin_notes'); // email, sms, phone
            $table->boolean('notification_sent')->default(false)->after('confirmation_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['admin_id', 'confirmed_at', 'rejected_at', 'admin_notes', 'confirmation_method', 'notification_sent']);
        });
    }
};
