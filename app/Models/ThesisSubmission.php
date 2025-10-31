<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovalWorkflow;

class ThesisSubmission extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'scholar_id',
        'title',
        'abstract',
        'file_path',
        'status',
        'submission_date',
        'is_resubmission',
        'rejection_count',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'original_thesis_id',
        'supervisor_approver_id',
        'supervisor_approved_at',
        'supervisor_remarks',
        'rac_minutes_file',
        'rac_meeting_date',
        'hod_approver_id',
        'hod_approved_at',
        'hod_remarks',
        'drc_minutes_file',
        'da_approver_id',
        'da_approved_at',
        'da_remarks',
        'so_approver_id',
        'so_approved_at',
        'so_remarks',
        'ar_approver_id',
        'ar_approved_at',
        'ar_remarks',
        'dr_approver_id',
        'dr_approved_at',
        'dr_remarks',
        'hvc_approver_id',
        'hvc_approved_at',
        'hvc_remarks',
        // Personal Details
        'father_husband_name',
        'mother_name',
        'subject',
        'faculty',
        // Academic Progress
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
        // Research Output
        'published_research_paper_details',
        'published_research_paper_certificate',
        'conference_presentation_1',
        'conference_certificate_1',
        'conference_presentation_2',
        'conference_certificate_2',
        // RAC/DRC Details
        'rac_constitution_date',
        'drc_approval_date',
        'rac_meeting_dates',
        'drc_meeting_dates',
        'rac_drc_undertaking',
        // Additional Certificates
        'peer_reviewed_journal_certificate',
        'research_papers_conference_certificate',
        // Form completion status
        'form_completed',
        'form_submitted_at',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'is_resubmission' => 'boolean',
        'rejected_at' => 'datetime',
        'supervisor_approved_at' => 'datetime',
        'hod_approved_at' => 'datetime',
        'da_approved_at' => 'datetime',
        'so_approved_at' => 'datetime',
        'ar_approved_at' => 'datetime',
        'dr_approved_at' => 'datetime',
        'hvc_approved_at' => 'datetime',
        'mpat_passing_date' => 'date',
        'coursework_fee_receipt_date' => 'date',
        'coursework_passing_date' => 'date',
        'registration_fee_date' => 'date',
        'rac_meeting_date' => 'date',
        'extension_date' => 'date',
        're_registration_date' => 'date',
        'pre_phd_presentation_date' => 'date',
        'rac_constitution_date' => 'date',
        'drc_approval_date' => 'date',
        'rac_meeting_dates' => 'array',
        'drc_meeting_dates' => 'array',
        'form_completed' => 'boolean',
        'form_submitted_at' => 'datetime',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function thesisEvaluation()
    {
        return $this->hasOne(ThesisEvaluation::class);
    }

    public function vivaProcess()
    {
        return $this->hasOne(VivaProcess::class);
    }

    public function certificates()
    {
        return $this->hasMany(ThesisSubmissionCertificate::class);
    }

    public function finalCertificate()
    {
        return $this->hasOne(FinalCertificate::class);
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function originalThesis()
    {
        return $this->belongsTo(ThesisSubmission::class, 'original_thesis_id');
    }

    public function resubmissions()
    {
        return $this->hasMany(ThesisSubmission::class, 'original_thesis_id');
    }

    // Helper methods for rejection workflow
    public function isRejected()
    {
        return str_starts_with($this->status, 'rejected');
    }

    public function canResubmit()
    {
        return $this->isRejected() && $this->rejection_count < 3; // Max 3 resubmissions
    }

    public function getRejectionStage()
    {
        if (!$this->isRejected()) {
            return null;
        }

        return match($this->status) {
            'rejected_by_supervisor' => 'Supervisor',
            'rejected_by_hod' => 'HOD',
            'rejected_by_da' => 'Dean\'s Assistant',
            'rejected_by_so' => 'Section Officer',
            'rejected_by_ar' => 'Assistant Registrar',
            'rejected_by_dr' => 'Deputy Registrar',
            'rejected_by_hvc' => 'Head of Verification Committee',
            'rejected_by_expert' => 'Expert Evaluator',
            'rejected_by_viva' => 'Viva Committee',
            default => 'Unknown'
        };
    }
}
