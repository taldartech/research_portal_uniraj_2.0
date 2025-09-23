<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VivaExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_submission_id',
        'scholar_id',
        'supervisor_id',
        'external_examiner_id',
        'internal_examiner_id',
        'hod_id',
        'examination_type',
        'examination_date',
        'examination_time',
        'venue',
        'examination_notes',
        'result',
        'examiner_comments',
        'supervisor_comments',
        'additional_remarks',
        'recommended_for_degree',
        'recommendation_notes',
        'office_note_file',
        'office_note_generated',
        'office_note_generated_at',
        'office_note_signed_by',
        'office_note_signed_at',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'examination_date' => 'date',
        'examination_time' => 'datetime:H:i',
        'recommended_for_degree' => 'boolean',
        'office_note_generated' => 'boolean',
        'office_note_generated_at' => 'datetime',
        'office_note_signed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function externalExaminer()
    {
        return $this->belongsTo(User::class, 'external_examiner_id');
    }

    public function internalExaminer()
    {
        return $this->belongsTo(User::class, 'internal_examiner_id');
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function officeNoteSignedBy()
    {
        return $this->belongsTo(User::class, 'office_note_signed_by');
    }

    public function vivaReport()
    {
        return $this->hasOne(VivaReport::class);
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isSuccessful()
    {
        return $this->result === 'pass' || $this->result === 'conditional_pass';
    }

    public function canGenerateOfficeNote()
    {
        return $this->isCompleted() && $this->isSuccessful() && $this->recommended_for_degree;
    }

    public function getExaminationTypeText()
    {
        return $this->examination_type === 'online' ? 'Online' : 'Offline';
    }

    public function getResultText()
    {
        return match($this->result) {
            'pass' => 'Pass',
            'fail' => 'Fail',
            'conditional_pass' => 'Conditional Pass',
            'pending' => 'Pending',
            default => 'Unknown'
        };
    }
}
