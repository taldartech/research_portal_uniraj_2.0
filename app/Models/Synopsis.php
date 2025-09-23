<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovalWorkflow;

class Synopsis extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'scholar_id',
        'rac_id',
        'proposed_topic',
        'synopsis_file',
        'submission_date',
        'status',
        'supervisor_approver_id',
        'supervisor_approved_at',
        'supervisor_remarks',
        'rac_minutes_file',
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
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'rejection_count',
        'original_synopsis_id',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'supervisor_approved_at' => 'datetime',
        'hod_approved_at' => 'datetime',
        'da_approved_at' => 'datetime',
        'so_approved_at' => 'datetime',
        'ar_approved_at' => 'datetime',
        'dr_approved_at' => 'datetime',
        'hvc_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function rac()
    {
        return $this->belongsTo(RAC::class);
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function originalSynopsis()
    {
        return $this->belongsTo(Synopsis::class, 'original_synopsis_id');
    }

    public function resubmissions()
    {
        return $this->hasMany(Synopsis::class, 'original_synopsis_id');
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
            default => 'Unknown'
        };
    }
}
