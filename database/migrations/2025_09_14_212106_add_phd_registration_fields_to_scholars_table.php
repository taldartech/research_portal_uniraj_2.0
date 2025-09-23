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
        Schema::table('scholars', function (Blueprint $table) {
            // Personal Information
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('category', ['SC', 'ST', 'OBC', 'MBC', 'EWS', 'P.H.', 'General'])->nullable();
            $table->string('occupation')->nullable();
            $table->boolean('is_teacher')->default(false);
            $table->string('teacher_employer')->nullable();

            // Examination Information
            $table->boolean('appearing_other_exam')->default(false);
            $table->string('other_exam_details')->nullable();

            // Research Information
            $table->text('research_topic_title')->nullable();
            $table->text('research_scheme_outline')->nullable();
            $table->text('research_bibliography')->nullable();

            // Supervisor Information
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_designation')->nullable();
            $table->string('supervisor_department')->nullable();
            $table->string('supervisor_college')->nullable();
            $table->text('supervisor_address')->nullable();
            $table->string('supervisor_letter_number')->nullable();
            $table->date('supervisor_letter_date')->nullable();

            // Co-supervisor Information
            $table->boolean('has_co_supervisor')->default(false);
            $table->string('co_supervisor_name')->nullable();
            $table->string('co_supervisor_designation')->nullable();
            $table->text('co_supervisor_reasons')->nullable();
            $table->string('co_supervisor_letter_number')->nullable();
            $table->date('co_supervisor_letter_date')->nullable();

            // Academic Qualifications
            $table->string('post_graduate_degree')->nullable();
            $table->string('post_graduate_university')->nullable();
            $table->string('post_graduate_year')->nullable();
            $table->decimal('post_graduate_percentage', 5, 2)->nullable();
            $table->string('net_slet_csir_gate_exam')->nullable();
            $table->string('net_slet_csir_gate_year')->nullable();
            $table->string('net_slet_csir_gate_roll_number')->nullable();
            $table->string('mpat_year')->nullable();
            $table->string('mpat_roll_number')->nullable();
            $table->string('mpat_merit_number')->nullable();
            $table->string('mpat_subject')->nullable();
            $table->string('coursework_exam_date')->nullable();
            $table->string('coursework_marks_obtained')->nullable();
            $table->string('coursework_max_marks')->nullable();

            // Faculty and Subject Information
            $table->string('phd_faculty')->nullable();
            $table->string('phd_subject')->nullable();

            // Registration Form Status
            $table->enum('registration_form_status', ['not_started', 'in_progress', 'completed', 'submitted'])->default('not_started');
            $table->timestamp('registration_form_submitted_at')->nullable();

            // Document Uploads
            $table->json('registration_documents')->nullable();

            // Supervisor and HOD Certificates
            $table->boolean('supervisor_certificate_completed')->default(false);
            $table->boolean('hod_certificate_completed')->default(false);
            $table->timestamp('supervisor_certificate_date')->nullable();
            $table->timestamp('hod_certificate_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropColumn([
                'father_name', 'mother_name', 'nationality', 'category', 'occupation',
                'is_teacher', 'teacher_employer', 'appearing_other_exam', 'other_exam_details',
                'research_topic_title', 'research_scheme_outline', 'research_bibliography',
                'supervisor_name', 'supervisor_designation', 'supervisor_department',
                'supervisor_college', 'supervisor_address', 'supervisor_letter_number',
                'supervisor_letter_date', 'has_co_supervisor', 'co_supervisor_name',
                'co_supervisor_designation', 'co_supervisor_reasons', 'co_supervisor_letter_number',
                'co_supervisor_letter_date', 'post_graduate_degree', 'post_graduate_university',
                'post_graduate_year', 'post_graduate_percentage', 'net_slet_csir_gate_exam',
                'net_slet_csir_gate_year', 'net_slet_csir_gate_roll_number', 'mpat_year',
                'mpat_roll_number', 'mpat_merit_number', 'mpat_subject', 'coursework_exam_date',
                'coursework_marks_obtained', 'coursework_max_marks', 'phd_faculty',
                'phd_subject', 'registration_form_status', 'registration_form_submitted_at',
                'registration_documents', 'supervisor_certificate_completed', 'hod_certificate_completed',
                'supervisor_certificate_date', 'hod_certificate_date'
            ]);
        });
    }
};
