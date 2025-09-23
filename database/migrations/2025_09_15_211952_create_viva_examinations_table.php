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
        Schema::create('viva_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->foreignId('external_examiner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('internal_examiner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('hod_id')->constrained('users')->onDelete('cascade');

            // Viva examination details
            $table->string('examination_type')->default('offline'); // offline, online
            $table->date('examination_date');
            $table->time('examination_time');
            $table->string('venue')->nullable();
            $table->text('examination_notes')->nullable();

            // Examination results
            $table->enum('result', ['pass', 'fail', 'conditional_pass', 'pending'])->default('pending');
            $table->text('examiner_comments')->nullable();
            $table->text('supervisor_comments')->nullable();
            $table->text('additional_remarks')->nullable();

            // Recommendation for degree award
            $table->boolean('recommended_for_degree')->default(false);
            $table->text('recommendation_notes')->nullable();

            // Office note generation
            $table->string('office_note_file')->nullable();
            $table->boolean('office_note_generated')->default(false);
            $table->timestamp('office_note_generated_at')->nullable();
            $table->foreignId('office_note_signed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('office_note_signed_at')->nullable();

            // Status tracking
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viva_examinations');
    }
};
