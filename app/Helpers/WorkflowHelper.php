<?php

namespace App\Helpers;

class WorkflowHelper
{
    /**
     * Get available roles for reassignment based on current status and approval history
     */
    public static function getAvailableReassignmentRoles(string $currentStatus, $model): array
    {
        $roles = [];
        $roleOrder = ['supervisor', 'hod', 'da', 'so', 'ar', 'dr', 'hvc'];

        // Get current role index
        $currentRoleIndex = self::getCurrentRoleIndex($currentStatus);
        if ($currentRoleIndex === null) {
            return [];
        }

        // Get all roles that have already approved
        $approvedRoles = [];

        if ($model->supervisor_approved_at) {
            $approvedRoles[] = ['value' => 'supervisor', 'label' => 'Supervisor'];
        }
        if ($model->hod_approved_at) {
            $approvedRoles[] = ['value' => 'hod', 'label' => 'HOD'];
        }
        if ($model->da_approved_at) {
            $approvedRoles[] = ['value' => 'da', 'label' => 'DA'];
        }
        if ($model->so_approved_at) {
            $approvedRoles[] = ['value' => 'so', 'label' => 'Section Officer'];
        }
        if ($model->ar_approved_at) {
            $approvedRoles[] = ['value' => 'ar', 'label' => 'Assistant Registrar'];
        }
        if ($model->dr_approved_at) {
            $approvedRoles[] = ['value' => 'dr', 'label' => 'Deputy Registrar'];
        }
        if ($model->hvc_approved_at) {
            $approvedRoles[] = ['value' => 'hvc', 'label' => 'HVC'];
        }

        return $approvedRoles;
    }

    /**
     * Get current role index in the workflow
     */
    private static function getCurrentRoleIndex(string $status): ?int
    {
        $statusMap = [
            'pending_supervisor_approval' => 0,
            'pending_hod_approval' => 1,
            'pending_da_approval' => 2,
            'pending_so_approval' => 3,
            'pending_ar_approval' => 4,
            'pending_dr_approval' => 5,
            'pending_hvc_approval' => 6,
        ];

        return $statusMap[$status] ?? null;
    }
}

