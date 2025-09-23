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
        Schema::create('viva_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viva_examination_id')->constrained()->onDelete('cascade');
            $table->foreignId('thesis_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');

            // Viva report details
            $table->string('research_topic');
            $table->string('external_examiner_name');
            $table->date('viva_date');
            $table->time('viva_time');
            $table->string('venue');
            $table->text('faculty_present')->nullable(); // List of faculty members present

            // Viva outcome
            $table->boolean('viva_successful')->default(false);
            $table->text('viva_outcome_notes')->nullable();
            $table->text('additional_remarks')->nullable();

            // Signatures
            $table->string('hod_signature')->nullable();
            $table->string('supervisor_signature')->nullable();
            $table->string('external_examiner_signature')->nullable();

            // Report file
            $table->string('report_file')->nullable();
            $table->boolean('report_completed')->default(false);
            $table->timestamp('report_submitted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viva_reports');
    }
};
