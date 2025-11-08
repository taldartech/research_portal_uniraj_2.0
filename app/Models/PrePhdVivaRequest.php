<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrePhdVivaRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'supervisor_id',
        'status',
        'request_remarks',
        'supportive_documents',
        'thesis_summary_file',
        'requested_date',
        'viva_date',
        'rac_approver_id',
        'rac_approved_at',
        'rac_remarks',
        'rac_minutes_file',
        'thesis_submission_deadline',
        'thesis_submitted',
        'thesis_submission_id',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'viva_date' => 'date',
        'rac_approved_at' => 'datetime',
        'thesis_submission_deadline' => 'date',
        'thesis_submitted' => 'boolean',
        'supportive_documents' => 'array', // Automatically cast JSON to array
    ];

    /**
     * Get supportive documents as array, ensuring it's always an array
     * This is a helper method to safely access supportive documents
     */
    public function getSupportiveDocumentsArray(): array
    {
        $docs = $this->supportive_documents;
        
        // If null or empty, return empty array
        if (empty($docs)) {
            return [];
        }
        
        // If already an array, return it
        if (is_array($docs)) {
            return $docs;
        }
        
        // If string, try to decode JSON
        if (is_string($docs)) {
            $decoded = json_decode($docs, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        // Fallback to empty array
        return [];
    }

    // Relationships
    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function racApprover()
    {
        return $this->belongsTo(User::class, 'rac_approver_id');
    }

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending_rac_approval';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isExpired()
    {
        return $this->status === 'expired';
    }

    public function isWithinSubmissionWindow()
    {
        if (!$this->isApproved() || !$this->viva_date || !$this->thesis_submission_deadline) {
            return false;
        }

        $now = now();
        // Can submit after viva date and before or on the deadline
        return $now->gte($this->viva_date->copy()->startOfDay()) && $now->lte($this->thesis_submission_deadline->copy()->endOfDay());
    }

    public function hasExpired()
    {
        if (!$this->isApproved() || !$this->thesis_submission_deadline) {
            return false;
        }

        return now()->gt($this->thesis_submission_deadline) && !$this->thesis_submitted;
    }

    public function canSubmitNewRequest()
    {
        // Can submit if no active request or if current request has expired
        $activeRequest = PrePhdVivaRequest::where('scholar_id', $this->scholar_id)
            ->whereIn('status', ['pending_rac_approval', 'approved'])
            ->where('id', '!=', $this->id)
            ->exists();

        return !$activeRequest || $this->hasExpired();
    }
}
