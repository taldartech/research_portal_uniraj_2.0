<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RACCommitteeSubmission extends Model
{
    use HasFactory;

    protected $table = 'rac_committee_submissions';

    protected $fillable = [
        'scholar_id',
        'supervisor_id',
        'member1_name',
        'member1_designation',
        'member1_department',
        'member2_name',
        'member2_designation',
        'member2_department',
        'status',
        'hod_id',
        'drc_date',
        'hod_remarks',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'drc_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function isPending()
    {
        return $this->status === 'pending_hod_approval';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
