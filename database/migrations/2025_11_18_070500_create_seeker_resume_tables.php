<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seeker_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained()->cascadeOnDelete();
            $table->string('institution');
            $table->string('degree')->nullable();
            $table->string('field_of_study')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('seeker_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('role_title');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('achievements')->nullable();
            $table->timestamps();
        });

        Schema::create('seeker_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('seeker_hobbies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('seekers', function (Blueprint $table) {
            $table->text('resume_bio')->nullable()->after('about');
            $table->string('resume_portfolio_url')->nullable()->after('resume_bio');
        });
    }

    public function down(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->dropColumn(['resume_bio', 'resume_portfolio_url']);
        });

        Schema::dropIfExists('seeker_hobbies');
        Schema::dropIfExists('seeker_references');
        Schema::dropIfExists('seeker_experiences');
        Schema::dropIfExists('seeker_educations');
    }
};

