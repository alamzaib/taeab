<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->string('linkedin_id')->nullable()->unique()->after('email');
            $table->string('linkedin_avatar')->nullable()->after('linkedin_id');
        });
    }

    public function down(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->dropColumn(['linkedin_id', 'linkedin_avatar']);
        });
    }
};

