<?php

namespace App\Helpers;

class WorkflowHelper
{
    /**
     * Get full form of role abbreviation
     */
    public static function getRoleFullForm(string $role): string
    {
        $roleMap = [
            'supervisor' => 'Supervisor',
            'hod' => 'Head of Department',
            'da' => 'Dealing Assistant',
            'so' => 'Section Officer',
            'ar' => 'Assistant Registrar',
            'dr' => 'Deputy Registrar',
            'hvc' => 'Honorable Vice Chancellor',
            'dean' => 'Dean',
            'scholar' => 'Scholar',
            'admin' => 'Administrator',
        ];

        return $roleMap[strtolower($role)] ?? ucfirst($role);
    }

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
            $approvedRoles[] = ['value' => 'supervisor', 'label' => self::getRoleFullForm('supervisor')];
        }
        if ($model->hod_approved_at) {
            $approvedRoles[] = ['value' => 'hod', 'label' => self::getRoleFullForm('hod')];
        }
        if ($model->da_approved_at) {
            $approvedRoles[] = ['value' => 'da', 'label' => self::getRoleFullForm('da')];
        }
        if ($model->so_approved_at) {
            $approvedRoles[] = ['value' => 'so', 'label' => self::getRoleFullForm('so')];
        }
        if ($model->ar_approved_at) {
            $approvedRoles[] = ['value' => 'ar', 'label' => self::getRoleFullForm('ar')];
        }
        if ($model->dr_approved_at) {
            $approvedRoles[] = ['value' => 'dr', 'label' => self::getRoleFullForm('dr')];
        }
        if ($model->hvc_approved_at) {
            $approvedRoles[] = ['value' => 'hvc', 'label' => self::getRoleFullForm('hvc')];
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

