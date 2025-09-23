<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalCertificate extends Model
{
    protected $fillable = [
        'thesis_submission_id',
        'scholar_id',
        'certificate_number',
        'issue_date',
        'degree_title',
        'specialization',
        'viva_date',
        'viva_venue',
        'examiner_names',
        'examiner_designations',
        'examiner_institutions',
        'recommendation_notes',
        'certificate_file',
        'status',
        'generated_by',
        'generated_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'viva_date' => 'date',
        'examiner_names' => 'array',
        'examiner_designations' => 'array',
        'examiner_institutions' => 'array',
        'generated_at' => 'datetime',
    ];

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function isGenerated()
    {
        return $this->status === 'generated';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getExaminerDetailsAttribute()
    {
        $names = $this->examiner_names ?? [];
        $designations = $this->examiner_designations ?? [];
        $institutions = $this->examiner_institutions ?? [];

        $examiners = [];
        for ($i = 0; $i < count($names); $i++) {
            $examiners[] = [
                'name' => $names[$i] ?? '',
                'designation' => $designations[$i] ?? '',
                'institution' => $institutions[$i] ?? '',
            ];
        }

        return $examiners;
    }
}
