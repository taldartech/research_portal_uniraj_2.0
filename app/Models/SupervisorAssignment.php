<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'supervisor_id',
        'assigned_date',
        'status',
        'justification',
        'remarks',
        // Approval fields
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        // Office Note fields
        'office_note_file',
        'office_note_generated',
        'office_note_generated_at',
        'office_note_signed_by',
        'office_note_signed_at',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'office_note_generated' => 'boolean',
        'office_note_generated_at' => 'datetime',
        'office_note_signed_at' => 'datetime',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
