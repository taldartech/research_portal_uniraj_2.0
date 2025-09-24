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
        Schema::create('final_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thesis_submission_id');
            $table->unsignedBigInteger('scholar_id');
            $table->string('certificate_number')->unique();
            $table->date('issue_date');
            $table->string('degree_title');
            $table->string('specialization');
            $table->date('viva_date');
            $table->string('viva_venue');
            $table->json('examiner_names'); // Array of examiner names
            $table->json('examiner_designations'); // Array of examiner designations
            $table->json('examiner_institutions'); // Array of examiner institutions
            $table->text('recommendation_notes')->nullable();
            $table->string('certificate_file')->nullable();
            $table->enum('status', ['generated', 'completed'])->default('generated');
            $table->unsignedBigInteger('generated_by');
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->foreign('thesis_submission_id')->references('id')->on('thesis_submissions')->onDelete('cascade');
            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_certificates');
    }
};
