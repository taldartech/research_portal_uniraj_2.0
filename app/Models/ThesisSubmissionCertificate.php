<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisSubmissionCertificate extends Model
{
    protected $fillable = [
        'scholar_id',
        'thesis_submission_id',
        'certificate_type',
        'status',
        'certificate_data',
        'generated_file_path',
        'generated_at',
        'generated_by',
        'remarks',
    ];

    protected $casts = [
        'certificate_data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isGenerated()
    {
        return $this->status === 'generated';
    }

    public function getCertificateTypeNameAttribute()
    {
        return match($this->certificate_type) {
            'pre_phd_presentation' => 'Pre-Ph.D. Presentation Certificate',
            'research_papers' => 'Research Papers Presentation Certificate',
            'peer_reviewed_journal' => 'Peer Reviewed Journal Certificate',
            default => 'Unknown Certificate'
        };
    }
}
