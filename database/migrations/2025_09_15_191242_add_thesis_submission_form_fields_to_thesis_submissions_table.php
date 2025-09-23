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
        Schema::table('thesis_submissions', function (Blueprint $table) {
            // Personal Details
            $table->string('father_husband_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('faculty')->nullable();
            
            // Academic Progress
            $table->date('mpat_passing_date')->nullable();
            $table->string('coursework_session')->nullable();
            $table->string('coursework_fee_receipt_no')->nullable();
            $table->date('coursework_fee_receipt_date')->nullable();
            $table->date('coursework_passing_date')->nullable();
            $table->date('registration_fee_date')->nullable();
            $table->date('extension_date')->nullable();
            $table->date('re_registration_date')->nullable();
            $table->date('pre_phd_presentation_date')->nullable();
            $table->string('pre_phd_presentation_certificate')->nullable();
            
            // Research Output
            $table->text('published_research_paper_details')->nullable();
            $table->string('published_research_paper_certificate')->nullable();
            $table->text('conference_presentation_1')->nullable();
            $table->string('conference_certificate_1')->nullable();
            $table->text('conference_presentation_2')->nullable();
            $table->string('conference_certificate_2')->nullable();
            
            // RAC/DRC Details
            $table->date('rac_constitution_date')->nullable();
            $table->date('drc_approval_date')->nullable();
            $table->json('rac_meeting_dates')->nullable();
            $table->json('drc_meeting_dates')->nullable();
            $table->text('rac_drc_undertaking')->nullable();
            
            // Additional Certificates
            $table->string('peer_reviewed_journal_certificate')->nullable();
            $table->string('research_papers_conference_certificate')->nullable();
            
            // Form completion status
            $table->boolean('form_completed')->default(false);
            $table->timestamp('form_submitted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'father_husband_name',
                'mother_name',
                'subject',
                'faculty',
                'mpat_passing_date',
                'coursework_session',
                'coursework_fee_receipt_no',
                'coursework_fee_receipt_date',
                'coursework_passing_date',
                'registration_fee_date',
                'extension_date',
                're_registration_date',
                'pre_phd_presentation_date',
                'pre_phd_presentation_certificate',
                'published_research_paper_details',
                'published_research_paper_certificate',
                'conference_presentation_1',
                'conference_certificate_1',
                'conference_presentation_2',
                'conference_certificate_2',
                'rac_constitution_date',
                'drc_approval_date',
                'rac_meeting_dates',
                'drc_meeting_dates',
                'rac_drc_undertaking',
                'peer_reviewed_journal_certificate',
                'research_papers_conference_certificate',
                'form_completed',
                'form_submitted_at',
            ]);
        });
    }
};