<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasConditionalApprovalWorkflow;

class ProgressReport extends Model
{
    use HasFactory, HasConditionalApprovalWorkflow;

    protected $fillable = [
        'scholar_id',
        'supervisor_id',
        'hod_id',
        'report_file',
        'rac_minutes_file',
        'drc_minutes_file',
        'submission_date',
        'report_period',
        'feedback_da',
        'special_remark',
        'status',
        'cancellation_request',
        'supervisor_change_request',
        'supervisor_approver_id',
        'supervisor_approved_at',
        'supervisor_remarks',
        'rac_meeting_date',
        'hod_approver_id',
        'hod_approved_at',
        'hod_remarks',
        'da_approver_id',
        'da_approved_at',
        'da_remarks',
        'da_negative_remarks',
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
        'original_report_id',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'rac_meeting_date' => 'date',
        'supervisor_approved_at' => 'datetime',
        'hod_approved_at' => 'datetime',
        'da_approved_at' => 'datetime',
        'so_approved_at' => 'datetime',
        'ar_approved_at' => 'datetime',
        'dr_approved_at' => 'datetime',
        'hvc_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'special_remark' => 'boolean',
        'cancellation_request' => 'boolean',
        'supervisor_change_request' => 'boolean',
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

    public function supervisorApprover()
    {
        return $this->belongsTo(User::class, 'supervisor_approver_id');
    }

    public function hodApprover()
    {
        return $this->belongsTo(User::class, 'hod_approver_id');
    }

    public function daApprover()
    {
        return $this->belongsTo(User::class, 'da_approver_id');
    }

    public function soApprover()
    {
        return $this->belongsTo(User::class, 'so_approver_id');
    }

    public function arApprover()
    {
        return $this->belongsTo(User::class, 'ar_approver_id');
    }

    public function drApprover()
    {
        return $this->belongsTo(User::class, 'dr_approver_id');
    }

    public function hvcApprover()
    {
        return $this->belongsTo(User::class, 'hvc_approver_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function originalReport()
    {
        return $this->belongsTo(ProgressReport::class, 'original_report_id');
    }

    public function resubmissions()
    {
        return $this->hasMany(ProgressReport::class, 'original_report_id');
    }

    // Helper methods
    public function isRejected()
    {
        return !is_null($this->rejected_at);
    }

    public function canResubmit()
    {
        return $this->isRejected() && $this->rejection_count < 3;
    }

    public function getRejectionStage()
    {
        if (!$this->isRejected()) {
            return null;
        }

        $rejectedBy = $this->rejectedBy;
        if (!$rejectedBy) {
            return 'unknown';
        }

        return $rejectedBy->user_type;
    }

    public function hasNegativeRemarks()
    {
        return !empty($this->da_negative_remarks);
    }
}
