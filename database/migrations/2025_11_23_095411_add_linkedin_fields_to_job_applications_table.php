<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->boolean('applied_via_linkedin')->default(false)->after('status');
            $table->string('linkedin_profile_url')->nullable()->after('applied_via_linkedin');
            $table->text('linkedin_profile_data')->nullable()->after('linkedin_profile_url');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['applied_via_linkedin', 'linkedin_profile_url', 'linkedin_profile_data']);
        });
    }
};
