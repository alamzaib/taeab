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
        if (!Schema::hasTable('application_notifications')) {
            Schema::create('application_notifications', function (Blueprint $table) {
                $table->id();
                $table->enum('recipient_type', ['seeker', 'company'])->index();
                $table->foreignId('recipient_id')->index(); // seeker_id or company_id
                $table->enum('type', ['application', 'message', 'status_update'])->index();
                $table->string('title');
                $table->text('message');
                $table->foreignId('job_application_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('job_id')->nullable()->constrained()->cascadeOnDelete();
                $table->boolean('is_read')->default(false)->index();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['recipient_type', 'recipient_id', 'is_read']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_notifications');
    }
};
