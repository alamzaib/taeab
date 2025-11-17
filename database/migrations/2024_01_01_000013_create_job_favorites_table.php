<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['seeker_id', 'job_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_favorites');
    }
};

