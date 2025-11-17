<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('phone');
            $table->string('profile_cover_path')->nullable()->after('profile_photo_path');
            $table->text('about')->nullable()->after('profile_cover_path');
            $table->text('skills')->nullable()->after('about');
            $table->string('current_salary')->nullable()->after('skills');
            $table->string('target_salary')->nullable()->after('current_salary');
            $table->string('current_company')->nullable()->after('target_salary');
            $table->string('residence_country')->nullable()->after('current_company');
            $table->string('nationality')->nullable()->after('residence_country');
            $table->date('date_of_birth')->nullable()->after('nationality');
            $table->string('address')->nullable()->after('date_of_birth');
            $table->string('linkedin_url')->nullable()->after('address');
            $table->string('whatsapp_number')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo_path',
                'profile_cover_path',
                'about',
                'skills',
                'current_salary',
                'target_salary',
                'current_company',
                'residence_country',
                'nationality',
                'date_of_birth',
                'address',
                'linkedin_url',
                'whatsapp_number',
            ]);
        });
    }
};

