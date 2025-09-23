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
        Schema::create('office_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->string('file_number')->nullable();
            $table->date('dated')->nullable();
            $table->string('candidate_name')->nullable();
            $table->string('research_subject')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_designation')->nullable();
            $table->string('supervisor_address')->nullable();
            $table->date('supervisor_retirement_date')->nullable();
            $table->string('co_supervisor_name')->nullable();
            $table->string('co_supervisor_designation')->nullable();
            $table->string('co_supervisor_address')->nullable();
            $table->date('co_supervisor_retirement_date')->nullable();
            $table->string('ug_university')->nullable();
            $table->string('ug_class')->nullable();
            $table->string('ug_marks')->nullable();
            $table->string('ug_percentage')->nullable();
            $table->string('ug_division')->nullable();
            $table->string('pg_university')->nullable();
            $table->string('pg_class')->nullable();
            $table->string('pg_marks')->nullable();
            $table->string('pg_percentage')->nullable();
            $table->string('pg_division')->nullable();
            $table->string('pat_year')->nullable();
            $table->string('pat_merit_number')->nullable();
            $table->string('coursework_marks_obtained')->nullable();
            $table->string('coursework_merit_number')->nullable();
            $table->date('drc_approval_date')->nullable();
            $table->string('registration_fee_receipt_number')->nullable();
            $table->date('registration_fee_date')->nullable();
            $table->date('commencement_date')->nullable();
            $table->string('enrollment_number')->nullable();
            $table->string('supervisor_registration_page_number')->nullable();
            $table->integer('supervisor_seats_available')->nullable();
            $table->integer('candidates_under_guidance')->nullable();
            $table->string('status')->default('draft'); // draft, generated, approved
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_notes');
    }
};
