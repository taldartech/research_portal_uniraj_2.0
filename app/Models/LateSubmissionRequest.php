<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateSubmissionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'thesis_submission_id',
        'justification',
        'supporting_documents',
        'original_due_date',
        'requested_extension_date',
        'status',
        'supervisor_approver_id',
        'supervisor_approved_at',
        'supervisor_remarks',
        'hod_approver_id',
        'hod_approved_at',
        'hod_remarks',
        'dean_approver_id',
        'dean_approved_at',
        'dean_remarks',
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
        'original_request_id',
    ];

    protected $casts = [
        'original_due_date' => 'date',
        'requested_extension_date' => 'date',
        'supervisor_approved_at' => 'datetime',
        'hod_approved_at' => 'datetime',
        'dean_approved_at' => 'datetime',
        'da_approved_at' => 'datetime',
        'so_approved_at' => 'datetime',
        'ar_approved_at' => 'datetime',
        'dr_approved_at' => 'datetime',
        'hvc_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'supporting_documents' => 'array',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    public function supervisorApprover()
    {
        return $this->belongsTo(User::class, 'supervisor_approver_id');
    }

    public function hodApprover()
    {
        return $this->belongsTo(User::class, 'hod_approver_id');
    }

    public function deanApprover()
    {
        return $this->belongsTo(User::class, 'dean_approver_id');
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

    public function originalRequest()
    {
        return $this->belongsTo(LateSubmissionRequest::class, 'original_request_id');
    }

    public function resubmissions()
    {
        return $this->hasMany(LateSubmissionRequest::class, 'original_request_id');
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

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return str_starts_with($this->status, 'pending_');
    }

    public function getCurrentApprovalStage()
    {
        if ($this->isApproved()) {
            return 'approved';
        }

        if ($this->isRejected()) {
            return 'rejected';
        }

        return str_replace('pending_', '', $this->status);
    }

    public function getDaysOverdue()
    {
        return now()->diffInDays($this->original_due_date, false);
    }

    public function getRequestedExtensionDays()
    {
        return $this->original_due_date->diffInDays($this->requested_extension_date);
    }
}
