<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VivaReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'viva_examination_id',
        'thesis_submission_id',
        'scholar_id',
        'supervisor_id',
        'research_topic',
        'external_examiner_name',
        'viva_date',
        'viva_time',
        'venue',
        'faculty_present',
        'viva_successful',
        'viva_outcome_notes',
        'additional_remarks',
        'hod_signature',
        'supervisor_signature',
        'external_examiner_signature',
        'report_file',
        'report_completed',
        'report_submitted_at',
    ];

    protected $casts = [
        'viva_date' => 'date',
        'viva_time' => 'datetime:H:i',
        'viva_successful' => 'boolean',
        'report_completed' => 'boolean',
        'report_submitted_at' => 'datetime',
    ];

    public function vivaExamination()
    {
        return $this->belongsTo(VivaExamination::class);
    }

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

    // Helper methods
    public function isCompleted()
    {
        return $this->report_completed;
    }

    public function hasAllSignatures()
    {
        return !empty($this->hod_signature) &&
               !empty($this->supervisor_signature) &&
               !empty($this->external_examiner_signature);
    }
}
