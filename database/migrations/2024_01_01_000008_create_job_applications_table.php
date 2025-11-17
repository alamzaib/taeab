<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seeker_id')->constrained()->cascadeOnDelete();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['submitted', 'reviewing', 'shortlisted', 'rejected'])->default('submitted');
            $table->timestamps();

            $table->unique(['job_id', 'seeker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};

