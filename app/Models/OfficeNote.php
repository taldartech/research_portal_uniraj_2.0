<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeNote extends Model
{
    protected $fillable = [
        'scholar_id',
        'file_number',
        'dated',
        'candidate_name',
        'research_subject',
        'supervisor_name',
        'supervisor_designation',
        'supervisor_address',
        'supervisor_retirement_date',
        'co_supervisor_name',
        'co_supervisor_designation',
        'co_supervisor_address',
        'co_supervisor_retirement_date',
        'ug_university',
        'ug_class',
        'ug_marks',
        'ug_percentage',
        'ug_division',
        'pg_university',
        'pg_class',
        'pg_marks',
        'pg_percentage',
        'pg_division',
        'pat_year',
        'pat_merit_number',
        'coursework_marks_obtained',
        'coursework_merit_number',
        'drc_approval_date',
        'registration_fee_receipt_number',
        'registration_fee_date',
        'commencement_date',
        'enrollment_number',
        'supervisor_registration_page_number',
        'supervisor_seats_available',
        'candidates_under_guidance',
        'status',
        'notes',
    ];

    protected $casts = [
        'dated' => 'date',
        'supervisor_retirement_date' => 'date',
        'co_supervisor_retirement_date' => 'date',
        'drc_approval_date' => 'date',
        'registration_fee_date' => 'date',
        'commencement_date' => 'date',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isGenerated()
    {
        return $this->status === 'generated';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }
}
