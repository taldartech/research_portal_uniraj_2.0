<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\Synopsis;
use App\Models\RegistrationForm;
use App\Services\WorkflowSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowDashboardController extends Controller
{
    protected $workflowSyncService;

    public function __construct(WorkflowSyncService $workflowSyncService)
    {
        $this->workflowSyncService = $workflowSyncService;
    }

    /**
     * Show unified workflow dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $data = [];

        switch ($user->user_type) {
            case 'scholar':
                $data = $this->getScholarDashboard($user);
                break;
            case 'supervisor':
                $data = $this->getSupervisorDashboard($user);
                break;
            case 'hod':
                $data = $this->getHODDashboard($user);
                break;
            case 'da':
                $data = $this->getDADashboard($user);
                break;
            case 'so':
                $data = $this->getSODashboard($user);
                break;
            case 'ar':
                $data = $this->getARDashboard($user);
                break;
            case 'dr':
                $data = $this->getDRDashboard($user);
                break;
            case 'hvc':
                $data = $this->getHVCDashboard($user);
                break;
            case 'admin':
                $data = $this->getAdminDashboard($user);
                break;
            default:
                $data = $this->getDefaultDashboard($user);
        }

        return view('workflow.dashboard', $data);
    }

    /**
     * Get scholar dashboard data
     */
    private function getScholarDashboard($user)
    {
        $scholar = $user->scholar;
        if (!$scholar) {
            return ['error' => 'Scholar profile not found'];
        }

        $workflowStatus = $this->workflowSyncService->getScholarWorkflowStatus($scholar);

        return [
            'user' => $user,
            'scholar' => $scholar,
            'workflow_status' => $workflowStatus,
            'pending_actions' => $this->getScholarPendingActions($scholar),
            'recent_updates' => $this->getRecentUpdates($scholar),
        ];
    }

    /**
     * Get supervisor dashboard data
     */
    private function getSupervisorDashboard($user)
    {
        $scholars = Scholar::whereHas('supervisorAssignments', function ($query) use ($user) {
            $query->where('supervisor_id', $user->id)
                  ->where('status', 'assigned');
        })->with(['user', 'synopses', 'registrationForm'])->get();

        $workflowStatuses = $scholars->map(function ($scholar) {
            return $this->workflowSyncService->getScholarWorkflowStatus($scholar);
        });

        return [
            'user' => $user,
            'scholars' => $scholars,
            'workflow_statuses' => $workflowStatuses,
            'pending_approvals' => $this->getSupervisorPendingApprovals($user),
            'statistics' => $this->getSupervisorStatistics($scholars),
        ];
    }

    /**
     * Get HOD dashboard data
     */
    private function getHODDashboard($user)
    {
        $department = $user->departmentManaging;
        if (!$department) {
            return ['error' => 'No department assigned'];
        }

        $scholars = Scholar::whereHas('admission', function ($query) use ($department) {
            $query->where('department_id', $department->id);
        })->with(['user', 'synopses', 'registrationForm'])->get();

        $pendingSynopses = Synopsis::where('status', 'pending_hod_approval')
            ->whereHas('scholar.admission', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })->with(['scholar.user'])->get();

        return [
            'user' => $user,
            'department' => $department,
            'scholars' => $scholars,
            'pending_synopses' => $pendingSynopses,
            'statistics' => $this->getHODStatistics($scholars),
        ];
    }

    /**
     * Get DA dashboard data
     */
    private function getDADashboard($user)
    {
        $pendingSynopses = Synopsis::where('status', 'pending_da_approval')
            ->with(['scholar.user', 'rac.supervisor.user'])->get();

        $approvedSynopses = Synopsis::where('status', 'approved')
            ->whereDoesntHave('scholar.registrationForm')
            ->with(['scholar.user'])->get();

        return [
            'user' => $user,
            'pending_synopses' => $pendingSynopses,
            'approved_synopses' => $approvedSynopses,
            'statistics' => $this->getDAStatistics(),
        ];
    }

    /**
     * Get SO dashboard data
     */
    private function getSODashboard($user)
    {
        $pendingSynopses = Synopsis::where('status', 'pending_so_approval')
            ->with(['scholar.user', 'rac.supervisor.user'])->get();

        return [
            'user' => $user,
            'pending_synopses' => $pendingSynopses,
            'statistics' => $this->getSOStatistics(),
        ];
    }

    /**
     * Get AR dashboard data
     */
    private function getARDashboard($user)
    {
        $pendingSynopses = Synopsis::where('status', 'pending_ar_approval')
            ->with(['scholar.user', 'rac.supervisor.user'])->get();

        $pendingRegistrations = RegistrationForm::where('status', 'signed_by_dr')
            ->with(['scholar.user'])->get();

        return [
            'user' => $user,
            'pending_synopses' => $pendingSynopses,
            'pending_registrations' => $pendingRegistrations,
            'statistics' => $this->getARStatistics(),
        ];
    }

    /**
     * Get DR dashboard data
     */
    private function getDRDashboard($user)
    {
        $pendingSynopses = Synopsis::where('status', 'pending_dr_approval')
            ->with(['scholar.user', 'rac.supervisor.user'])->get();

        $pendingRegistrations = RegistrationForm::where('status', 'generated')
            ->with(['scholar.user'])->get();

        return [
            'user' => $user,
            'pending_synopses' => $pendingSynopses,
            'pending_registrations' => $pendingRegistrations,
            'statistics' => $this->getDRStatistics(),
        ];
    }

    /**
     * Get HVC dashboard data
     */
    private function getHVCDashboard($user)
    {
        $pendingSynopses = Synopsis::where('status', 'pending_hvc_approval')
            ->with(['scholar.user', 'rac.supervisor.user'])->get();

        return [
            'user' => $user,
            'pending_synopses' => $pendingSynopses,
            'statistics' => $this->getHVCStatistics(),
        ];
    }

    /**
     * Get admin dashboard data
     */
    private function getAdminDashboard($user)
    {
        $allScholars = Scholar::with(['user', 'synopses', 'registrationForm'])->get();
        $workflowStatuses = $allScholars->map(function ($scholar) {
            return $this->workflowSyncService->getScholarWorkflowStatus($scholar);
        });

        return [
            'user' => $user,
            'scholars' => $allScholars,
            'workflow_statuses' => $workflowStatuses,
            'statistics' => $this->getAdminStatistics($allScholars),
        ];
    }

    /**
     * Get default dashboard data
     */
    private function getDefaultDashboard($user)
    {
        return [
            'user' => $user,
            'message' => 'Welcome to the Research Portal',
        ];
    }

    /**
     * Get scholar pending actions
     */
    private function getScholarPendingActions($scholar)
    {
        $actions = [];

        if ($scholar->registration_form_status === 'not_started') {
            $actions[] = [
                'type' => 'registration_form',
                'message' => 'Complete your PhD registration form',
                'url' => route('scholar.registration.phd_form'),
                'priority' => 'high'
            ];
        }

        if ($scholar->synopsis_status === 'rejected') {
            $actions[] = [
                'type' => 'synopsis_resubmit',
                'message' => 'Resubmit your synopsis',
                'url' => route('scholar.synopsis.create'),
                'priority' => 'high'
            ];
        }

        return $actions;
    }

    /**
     * Get supervisor pending approvals
     */
    private function getSupervisorPendingApprovals($user)
    {
        $supervisor = \App\Models\Supervisor::where('user_id', $user->id)->first();
        if (!$supervisor) {
            return collect();
        }

        $pendingSynopses = Synopsis::where('status', 'pending_supervisor_approval')
            ->where(function ($query) use ($supervisor) {
                // Look for synopses through RAC (traditional workflow)
                $query->whereHas('rac', function ($racQuery) use ($supervisor) {
                    $racQuery->where('supervisor_id', $supervisor->id);
                })
                // OR look for synopses through current supervisor assignment (new workflow)
                ->orWhereHas('scholar.currentSupervisor', function ($assignmentQuery) use ($supervisor) {
                    $assignmentQuery->where('supervisor_id', $supervisor->id)
                                  ->where('status', 'assigned');
                });
            })
            ->with(['scholar.user', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user'])
            ->get();

        return $pendingSynopses;
    }

    /**
     * Get recent updates for scholar
     */
    private function getRecentUpdates($scholar)
    {
        // This would typically come from a notifications or activity log table
        return [
            [
                'type' => 'synopsis',
                'message' => 'Synopsis submitted for approval',
                'date' => $scholar->synopsis_submitted_at,
            ],
            [
                'type' => 'registration',
                'message' => 'Registration form submitted',
                'date' => $scholar->registration_form_submitted_at,
            ],
        ];
    }

    /**
     * Get supervisor statistics
     */
    private function getSupervisorStatistics($scholars)
    {
        $scholarIds = $scholars->pluck('id');

        return [
            'total_scholars' => $scholars->count(),
            'synopsis_approved' => Synopsis::whereIn('scholar_id', $scholarIds)
                ->where('status', 'approved')
                ->count(),
            'synopsis_pending' => Synopsis::whereIn('scholar_id', $scholarIds)
                ->where('status', 'pending_supervisor_approval')
                ->count(),
            'registration_completed' => $scholars->where('enrollment_status', 'enrolled')->count(),
        ];
    }

    /**
     * Get HOD statistics
     */
    private function getHODStatistics($scholars)
    {
        return [
            'total_scholars' => $scholars->count(),
            'pending_synopses' => Synopsis::where('status', 'pending_hod_approval')
                ->whereHas('scholar.admission', function ($query) use ($scholars) {
                    $query->whereIn('scholar_id', $scholars->pluck('id'));
                })->count(),
            'approved_synopses' => $scholars->where('synopsis_status', 'approved')->count(),
        ];
    }

    /**
     * Get DA statistics
     */
    private function getDAStatistics()
    {
        return [
            'pending_synopses' => Synopsis::where('status', 'pending_da_approval')->count(),
            'approved_synopses' => Synopsis::where('status', 'approved')->count(),
            'generated_forms' => RegistrationForm::where('status', 'generated')->count(),
        ];
    }

    /**
     * Get SO statistics
     */
    private function getSOStatistics()
    {
        return [
            'pending_synopses' => Synopsis::where('status', 'pending_so_approval')->count(),
            'approved_synopses' => Synopsis::where('status', 'approved')->count(),
        ];
    }

    /**
     * Get AR statistics
     */
    private function getARStatistics()
    {
        return [
            'pending_synopses' => Synopsis::where('status', 'pending_ar_approval')->count(),
            'pending_registrations' => RegistrationForm::where('status', 'signed_by_dr')->count(),
            'completed_registrations' => RegistrationForm::where('status', 'completed')->count(),
        ];
    }

    /**
     * Get DR statistics
     */
    private function getDRStatistics()
    {
        return [
            'pending_synopses' => Synopsis::where('status', 'pending_dr_approval')->count(),
            'pending_registrations' => RegistrationForm::where('status', 'generated')->count(),
            'signed_registrations' => RegistrationForm::where('status', 'signed_by_dr')->count(),
        ];
    }

    /**
     * Get HVC statistics
     */
    private function getHVCStatistics()
    {
        return [
            'pending_synopses' => Synopsis::where('status', 'pending_hvc_approval')->count(),
            'approved_synopses' => Synopsis::where('status', 'approved')->count(),
        ];
    }

    /**
     * Get admin statistics
     */
    private function getAdminStatistics($scholars)
    {
        return [
            'total_scholars' => $scholars->count(),
            'enrolled_scholars' => $scholars->where('enrollment_status', 'enrolled')->count(),
            'pending_synopses' => Synopsis::whereIn('status', [
                'pending_supervisor_approval',
                'pending_hod_approval',
                'pending_da_approval',
                'pending_so_approval',
                'pending_ar_approval',
                'pending_dr_approval',
                'pending_hvc_approval'
            ])->count(),
            'approved_synopses' => Synopsis::where('status', 'approved')->count(),
            'completed_registrations' => RegistrationForm::where('status', 'completed')->count(),
        ];
    }

    /**
     * Get workflow status for specific scholar
     */
    public function getScholarWorkflowStatus($scholarId)
    {
        $scholar = Scholar::findOrFail($scholarId);
        $workflowStatus = $this->workflowSyncService->getScholarWorkflowStatus($scholar);

        return response()->json($workflowStatus);
    }

    /**
     * Get all pending items for current user
     */
    public function getPendingItems()
    {
        $user = Auth::user();
        $pendingItems = [];

        switch ($user->user_type) {
            case 'supervisor':
                $pendingItems = $this->getSupervisorPendingApprovals($user);
                break;
            case 'hod':
                $pendingItems = Synopsis::where('status', 'pending_hod_approval')
                    ->whereHas('scholar.admission', function ($query) use ($user) {
                        $query->where('department_id', $user->departmentManaging->id);
                    })->with(['scholar.user'])->get();
                break;
            case 'da':
                $pendingItems = Synopsis::where('status', 'pending_da_approval')
                    ->with(['scholar.user'])->get();
                break;
            case 'so':
                $pendingItems = Synopsis::where('status', 'pending_so_approval')
                    ->with(['scholar.user'])->get();
                break;
            case 'ar':
                $pendingItems = Synopsis::where('status', 'pending_ar_approval')
                    ->with(['scholar.user'])->get();
                break;
            case 'dr':
                $pendingItems = Synopsis::where('status', 'pending_dr_approval')
                    ->with(['scholar.user'])->get();
                break;
            case 'hvc':
                $pendingItems = Synopsis::where('status', 'pending_hvc_approval')
                    ->with(['scholar.user'])->get();
                break;
        }

        return response()->json($pendingItems);
    }
}
