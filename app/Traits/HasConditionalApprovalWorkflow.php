<?php

namespace App\Traits;

trait HasConditionalApprovalWorkflow
{
    /**
     * Get the current stage of the conditional approval workflow
     */
    public function getCurrentStageAttribute()
    {
        return match($this->status) {
            'pending_supervisor_approval' => 'Supervisor',
            'pending_hod_approval' => 'HOD',
            'pending_da_approval' => 'Dean\'s Assistant',
            'pending_so_approval' => 'Section Officer',
            'pending_ar_approval' => 'Assistant Registrar',
            'pending_dr_approval' => 'Deputy Registrar',
            'pending_hvc_approval' => 'Head of Verification Committee',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown',
        };
    }

    /**
     * Check if the item is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the item is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the item is pending approval
     */
    public function isPending()
    {
        return in_array($this->status, [
            'pending_supervisor_approval',
            'pending_hod_approval',
            'pending_da_approval',
            'pending_so_approval',
            'pending_ar_approval',
            'pending_dr_approval',
            'pending_hvc_approval'
        ]);
    }

    /**
     * Check if DA approved without negative remarks (direct to final approval)
     */
    public function isDAApprovedDirectly()
    {
        return $this->status === 'approved' &&
               $this->da_approved_at &&
               !$this->da_has_negative_remarks;
    }

    /**
     * Check if DA has negative remarks (triggers full approval chain)
     */
    public function hasDANegativeRemarks()
    {
        return $this->da_has_negative_remarks === true;
    }

    /**
     * Get the next approval stage based on conditional logic
     */
    public function getNextApprovalStage()
    {
        return match($this->status) {
            'pending_supervisor_approval' => 'pending_hod_approval',
            'pending_hod_approval' => 'pending_da_approval',
            'pending_da_approval' => $this->da_has_negative_remarks ? 'pending_so_approval' : 'approved',
            'pending_so_approval' => 'pending_ar_approval',
            'pending_ar_approval' => 'pending_dr_approval',
            'pending_dr_approval' => 'pending_hvc_approval',
            'pending_hvc_approval' => 'approved',
            default => null,
        };
    }

    /**
     * Get the previous approval stage
     */
    public function getPreviousApprovalStage()
    {
        return match($this->status) {
            'pending_hod_approval' => 'pending_supervisor_approval',
            'pending_da_approval' => 'pending_hod_approval',
            'pending_so_approval' => 'pending_da_approval',
            'pending_ar_approval' => 'pending_so_approval',
            'pending_dr_approval' => 'pending_ar_approval',
            'pending_hvc_approval' => 'pending_dr_approval',
            'approved' => $this->isDAApprovedDirectly() ? 'pending_da_approval' : 'pending_hvc_approval',
            'rejected' => $this->getLastApprovalStage(),
            default => null,
        };
    }

    /**
     * Get the last approval stage before current
     */
    private function getLastApprovalStage()
    {
        if ($this->hvc_approved_at) return 'pending_hvc_approval';
        if ($this->dr_approved_at) return 'pending_dr_approval';
        if ($this->ar_approved_at) return 'pending_ar_approval';
        if ($this->so_approved_at) return 'pending_so_approval';
        if ($this->da_approved_at) return 'pending_da_approval';
        if ($this->hod_approved_at) return 'pending_hod_approval';
        if ($this->supervisor_approved_at) return 'pending_supervisor_approval';
        return null;
    }

    /**
     * Get all approval relationships
     */
    public function supervisorApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'supervisor_approver_id');
    }

    public function hodApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'hod_approver_id');
    }

    public function daApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'da_approver_id');
    }

    public function soApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'so_approver_id');
    }

    public function arApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'ar_approver_id');
    }

    public function drApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'dr_approver_id');
    }

    public function hvcApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'hvc_approver_id');
    }

    /**
     * Get approval history as an array
     */
    public function getApprovalHistory()
    {
        $history = [];

        if ($this->supervisor_approved_at) {
            $history[] = [
                'stage' => 'Supervisor',
                'approver' => $this->supervisorApprover,
                'approved_at' => $this->supervisor_approved_at,
                'remarks' => $this->supervisor_remarks,
            ];
        }

        if ($this->hod_approved_at) {
            $history[] = [
                'stage' => 'HOD',
                'approver' => $this->hodApprover,
                'approved_at' => $this->hod_approved_at,
                'remarks' => $this->hod_remarks,
            ];
        }

        if ($this->da_approved_at) {
            $history[] = [
                'stage' => 'Dean\'s Assistant',
                'approver' => $this->daApprover,
                'approved_at' => $this->da_approved_at,
                'remarks' => $this->da_remarks,
                'has_negative_remarks' => $this->da_has_negative_remarks,
                'direct_approval' => !$this->da_has_negative_remarks,
            ];
        }

        if ($this->so_approved_at) {
            $history[] = [
                'stage' => 'Section Officer',
                'approver' => $this->soApprover,
                'approved_at' => $this->so_approved_at,
                'remarks' => $this->so_remarks,
            ];
        }

        if ($this->ar_approved_at) {
            $history[] = [
                'stage' => 'Assistant Registrar',
                'approver' => $this->arApprover,
                'approved_at' => $this->ar_approved_at,
                'remarks' => $this->ar_remarks,
            ];
        }

        if ($this->dr_approved_at) {
            $history[] = [
                'stage' => 'Deputy Registrar',
                'approver' => $this->drApprover,
                'approved_at' => $this->dr_approved_at,
                'remarks' => $this->dr_remarks,
            ];
        }

        if ($this->hvc_approved_at) {
            $history[] = [
                'stage' => 'Head of Verification Committee',
                'approver' => $this->hvcApprover,
                'approved_at' => $this->hvc_approved_at,
                'remarks' => $this->hvc_remarks,
            ];
        }

        return $history;
    }

    /**
     * Get workflow path description
     */
    public function getWorkflowPathDescription()
    {
        if ($this->isDAApprovedDirectly()) {
            return 'Direct approval path: Scholar → Supervisor → HOD → DA → Final Approval';
        } elseif ($this->hasDANegativeRemarks()) {
            return 'Full approval path: Scholar → Supervisor → HOD → DA → SO → AR → DR → HVC → Final Approval';
        } else {
            return 'Standard approval path: Scholar → Supervisor → HOD → DA → Final Approval';
        }
    }
}
