<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VivaProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_submission_id',
        'hvc_assigned_expert_id',
        'hod_id',
        'supervisor_id',
        'viva_date',
        'viva_report_file',
        'status',
        'decision',
    ];

    protected $casts = [
        'viva_date' => 'date',
    ];

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    public function hvcAssignedExpert()
    {
        return $this->belongsTo(User::class, 'hvc_assigned_expert_id');
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
