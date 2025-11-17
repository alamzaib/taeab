<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('resume_document_id')->nullable()->constrained('job_documents')->nullOnDelete()->after('seeker_id');
            $table->foreignId('cover_letter_document_id')->nullable()->constrained('job_documents')->nullOnDelete()->after('resume_document_id');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['resume_document_id']);
            $table->dropForeign(['cover_letter_document_id']);
            $table->dropColumn(['resume_document_id', 'cover_letter_document_id']);
        });
    }
};

