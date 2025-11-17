<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('website');
            $table->string('banner_path')->nullable()->after('logo_path');
            $table->string('address')->nullable()->after('banner_path');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->decimal('latitude', 10, 6)->nullable()->after('country');
            $table->decimal('longitude', 10, 6)->nullable()->after('latitude');
            $table->string('organization_type')->nullable()->after('longitude');
            $table->text('about')->nullable()->after('organization_type');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'banner_path',
                'address',
                'city',
                'country',
                'latitude',
                'longitude',
                'organization_type',
                'about',
            ]);
        });
    }
};

