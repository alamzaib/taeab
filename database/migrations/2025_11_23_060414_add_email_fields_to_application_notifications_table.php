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
        Schema::table('application_notifications', function (Blueprint $table) {
            $table->boolean('email_sent')->default(false)->index()->after('read_at');
            $table->timestamp('email_sent_at')->nullable()->after('email_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_notifications', function (Blueprint $table) {
            $table->dropColumn(['email_sent', 'email_sent_at']);
        });
    }
};
