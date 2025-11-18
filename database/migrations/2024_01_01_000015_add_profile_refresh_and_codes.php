<?php

use App\Models\Agent;
use App\Models\Company;
use App\Models\Seeker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->string('unique_code')->unique()->nullable()->after('id');
            $table->timestamp('profile_refreshed_at')->nullable()->after('updated_at');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->string('unique_code')->unique()->nullable()->after('id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('unique_code')->unique()->nullable()->after('id');
        });

        if (class_exists(Seeker::class)) {
            Seeker::withoutEvents(function () {
                Seeker::whereNull('unique_code')->chunkById(100, function ($seekers) {
                    foreach ($seekers as $seeker) {
                        $seeker->unique_code = Seeker::generateUniqueCode();
                        $seeker->profile_refreshed_at = $seeker->profile_refreshed_at ?? $seeker->updated_at ?? now();
                        $seeker->save();
                    }
                });
            });
        }

        if (class_exists(Agent::class)) {
            Agent::withoutEvents(function () {
                Agent::whereNull('unique_code')->chunkById(100, function ($agents) {
                    foreach ($agents as $agent) {
                        $agent->unique_code = Agent::generateUniqueCode();
                        $agent->save();
                    }
                });
            });
        }

        if (class_exists(Company::class)) {
            Company::withoutEvents(function () {
                Company::whereNull('unique_code')->chunkById(100, function ($companies) {
                    foreach ($companies as $company) {
                        $company->unique_code = Company::generateUniqueCode();
                        $company->save();
                    }
                });
            });
        }
    }

    public function down(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->dropColumn(['unique_code', 'profile_refreshed_at']);
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });
    }
};

