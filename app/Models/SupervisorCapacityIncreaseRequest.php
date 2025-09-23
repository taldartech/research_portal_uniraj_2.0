<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupervisorCapacityIncreaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'current_capacity',
        'requested_capacity',
        'justification',
        'status',
        'da_approver_id',
        'da_approved_at',
        'da_remarks',
        'so_approver_id',
        'so_approved_at',
        'so_remarks',
        'ar_approver_id',
        'ar_approved_at',
        'ar_remarks',
        'dr_approver_id',
        'dr_approved_at',
        'dr_remarks',
        'hvc_approver_id',
        'hvc_approved_at',
        'hvc_remarks',
    ];

    protected $casts = [
        'da_approved_at' => 'datetime',
        'so_approved_at' => 'datetime',
        'ar_approved_at' => 'datetime',
        'dr_approved_at' => 'datetime',
        'hvc_approved_at' => 'datetime',
    ];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function daApprover()
    {
        return $this->belongsTo(User::class, 'da_approver_id');
    }

    public function soApprover()
    {
        return $this->belongsTo(User::class, 'so_approver_id');
    }

    public function arApprover()
    {
        return $this->belongsTo(User::class, 'ar_approver_id');
    }

    public function drApprover()
    {
        return $this->belongsTo(User::class, 'dr_approver_id');
    }

    public function hvcApprover()
    {
        return $this->belongsTo(User::class, 'hvc_approver_id');
    }

    public function getCurrentStageAttribute()
    {
        return match($this->status) {
            'pending_da' => 'Dean\'s Assistant',
            'pending_so' => 'Section Officer',
            'pending_ar' => 'Assistant Registrar',
            'pending_dr' => 'Deputy Registrar',
            'pending_hvc' => 'Head of Verification Committee',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown',
        };
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPending()
    {
        return in_array($this->status, ['pending_da', 'pending_so', 'pending_ar', 'pending_dr', 'pending_hvc']);
    }
}
