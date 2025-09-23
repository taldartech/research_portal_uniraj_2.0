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
        Schema::create('thesis_submission_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholar_id');
            $table->unsignedBigInteger('thesis_submission_id');
            $table->string('certificate_type'); // pre_phd_presentation, research_papers, peer_reviewed_journal
            $table->string('status')->default('pending'); // pending, approved, generated
            $table->json('certificate_data')->nullable(); // Store certificate-specific data
            $table->string('generated_file_path')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable(); // User who generated the certificate
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');
            $table->foreign('thesis_submission_id')->references('id')->on('thesis_submissions')->onDelete('cascade');
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_submission_certificates');
    }
};
