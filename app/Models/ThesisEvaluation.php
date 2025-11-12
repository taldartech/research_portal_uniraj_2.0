<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_submission_id',
        'expert_id',
        'supervisor_id',
        'report_file',
        'assigned_date',
        'due_date',
        'submission_date',
        'status',
        'hvc_selected_expert_id',
        'da_assigned_expert_id',
        'priority_order',
        'decision',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'submission_date' => 'date',
    ];

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function hvcSelectedExpert()
    {
        return $this->belongsTo(User::class, 'hvc_selected_expert_id');
    }

    public function daAssignedExpert()
    {
        return $this->belongsTo(User::class, 'da_assigned_expert_id');
    }
}
