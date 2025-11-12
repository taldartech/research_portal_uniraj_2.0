<?php

namespace App\Http\Controllers;

use App\Models\SupervisorCapacityIncreaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DRController extends Controller
{
    /**
     * List pending capacity increase requests for DR approval
     */
    public function listPendingCapacityRequests()
    {
        $requests = SupervisorCapacityIncreaseRequest::where('status', 'pending_dr')
            ->with(['supervisor.user', 'supervisor.department', 'daApprover', 'soApprover', 'arApprover'])
            ->latest()
            ->get();

        return view('dr.capacity_requests.pending', compact('requests'));
    }

    /**
     * Show the approval form for a specific request
     */
    public function showApprovalForm(SupervisorCapacityIncreaseRequest $request)
    {
        if ($request->status !== 'pending_dr') {
            abort(403, 'This request is not pending DR approval.');
        }

        $request->load(['supervisor.user', 'supervisor.department', 'daApprover', 'soApprover', 'arApprover']);

        return view('dr.capacity_requests.approve', compact('request'));
    }

    /**
     * Process the approval/rejection
     */
    public function processApproval(Request $request, SupervisorCapacityIncreaseRequest $capacityRequest)
    {
        if ($capacityRequest->status !== 'pending_dr') {
            abort(403, 'This request is not pending DR approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $capacityRequest->update([
                'status' => 'pending_hvc',
                'dr_approver_id' => Auth::id(),
                'dr_approved_at' => now(),
                'dr_remarks' => $request->remarks,
            ]);

            $message = 'Request approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('hvc');
        } else {
            $capacityRequest->update([
                'status' => 'rejected',
                'dr_approver_id' => Auth::id(),
                'dr_approved_at' => now(),
                'dr_remarks' => $request->remarks,
            ]);

            $message = 'Request rejected.';
        }

        return redirect()->route('dr.capacity_requests.pending')->with('success', $message);
    }

    /**
     * List pending synopses for DR approval
     */
    public function listPendingSynopses()
    {
        $synopses = \App\Models\Synopsis::where('status', 'pending_dr_approval')
            ->with(['scholar.user', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'supervisorApprover', 'hodApprover', 'daApprover', 'soApprover', 'arApprover'])
            ->latest()
            ->get();

        return view('dr.synopses.pending', compact('synopses'));
    }

    /**
     * Show the synopsis approval form for a specific synopsis
     */
    public function showSynopsisApprovalForm(\App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_dr_approval') {
            abort(403, 'This synopsis is not pending DR approval.');
        }

        $synopsis->load([
            'scholar.user',
            'scholar.admission.department',
            'scholar.currentSupervisor.supervisor.user',
            'rac.supervisor.user',
            'supervisorApprover',
            'hodApprover',
            'daApprover',
            'soApprover',
            'arApprover'
        ]);

        // Get available roles for reassignment
        $availableRoles = \App\Helpers\WorkflowHelper::getAvailableReassignmentRoles($synopsis->status, $synopsis);

        return view('dr.synopses.approve', compact('synopsis', 'availableRoles'));
    }

    /**
     * Process the synopsis approval/rejection
     */
    public function processSynopsisApproval(Request $request, \App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_dr_approval') {
            abort(403, 'This synopsis is not pending DR approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'reassigned_to_role' => 'nullable|string|in:supervisor,hod,da,so,ar,dr',
            'reassignment_reason' => 'nullable|string|max:1000',
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        if ($request->action === 'approve') {
            $synopsis->update([
                'dr_remarks' => $request->remarks,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'dr_approve', Auth::user());
            $message = 'Synopsis approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('hvc');
        } else {
            $synopsis->update([
                'dr_remarks' => $request->remarks,
                'reassignment_reason' => $request->reassignment_reason,
            ]);

            // Sync workflow with reassignment
            $reassignedRole = $request->reassigned_to_role;
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'dr_reject', Auth::user(), $reassignedRole);

            if ($reassignedRole) {
                $message = 'Synopsis rejected and reassigned to ' . \App\Helpers\WorkflowHelper::getRoleFullForm($reassignedRole) . ' for corrections.';
            } else {
                $message = 'Synopsis rejected.';
            }
        }

        return redirect()->route('dr.synopses.pending')->with('success', $message);
    }

    /**
     * List pending progress reports
     */
    public function listPendingProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_dr_approval')
            ->latest()
            ->paginate(10);

        return view('dr.progress_reports.pending', compact('reports'));
    }

    /**
     * List all progress reports
     */
    public function listAllProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('dr.progress_reports.all', compact('reports'));
    }

    /**
     * Show progress report approval form
     */
    public function showProgressReportApprovalForm(\App\Models\ProgressReport $report)
    {
        if ($report->status !== 'pending_dr_approval') {
            abort(403, 'This progress report is not pending DR approval.');
        }

        $report->load(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover', 'daApprover', 'soApprover', 'arApprover']);

        return view('dr.progress_reports.approve', compact('report'));
    }

    /**
     * Process progress report approval/rejection
     */
    public function processProgressReportApproval(Request $request, \App\Models\ProgressReport $report)
    {
        if ($report->status !== 'pending_dr_approval') {
            abort(403, 'This progress report is not pending DR approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'reassigned_to_role' => 'nullable|string|in:supervisor,hod,da,so,ar,dr',
            'reassignment_reason' => 'nullable|string|max:1000',
        ]);

        if ($request->action === 'approve') {
            $report->update([
                'status' => 'pending_hvc_approval',
                'dr_approver_id' => Auth::id(),
                'dr_approved_at' => now(),
                'dr_remarks' => $request->remarks,
            ]);

            $message = 'Progress report approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('hvc') . '.';
        } else {
            if ($request->reassigned_to_role) {
                $report->update([
                    'status' => 'pending_' . $request->reassigned_to_role . '_approval',
                    'dr_approver_id' => Auth::id(),
                    'dr_approved_at' => now(),
                    'dr_remarks' => $request->remarks,
                    'reassigned_to_role' => $request->reassigned_to_role,
                    'reassignment_reason' => $request->reassignment_reason,
                ]);
                $message = 'Progress report rejected and reassigned to ' . \App\Helpers\WorkflowHelper::getRoleFullForm($request->reassigned_to_role) . ' for corrections.';
            } else {
                $report->update([
                    'status' => 'rejected',
                    'dr_approver_id' => Auth::id(),
                    'dr_approved_at' => now(),
                    'dr_remarks' => $request->remarks,
                    'rejected_by' => Auth::id(),
                    'rejected_at' => now(),
                    'rejection_reason' => $request->remarks,
                    'rejection_count' => $report->rejection_count + 1,
                ]);
                $message = 'Progress report rejected by ' . \App\Helpers\WorkflowHelper::getRoleFullForm('dr') . '.';
            }
        }

        return redirect()->route('dr.progress_reports.pending')->with('success', $message);
    }

    /**
     * List pending thesis submissions
     */
    public function listPendingThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'scholar.admission.department', 'thesisEvaluation'])
            ->where('status', 'pending_dr_approval')
            ->latest()
            ->paginate(10);

        return view('dr.thesis.pending', compact('theses'));
    }

    /**
     * List all thesis submissions
     */
    public function listAllThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'scholar.admission.department', 'thesisEvaluation'])
            ->latest()
            ->get();

        return view('dr.thesis.all', compact('theses'));
    }

    /**
     * View expert details for a thesis
     */
    public function viewExpertDetails(\App\Models\ThesisSubmission $thesis)
    {
        $thesis->load([
            'scholar.user',
            'scholar.currentSupervisor.supervisor.user',
            'supervisor.user',
            'scholar.admission.department',
            'thesisEvaluation.expert',
        ]);

        // Get supervisor-suggested experts
        $suggestedExperts = \App\Models\ExpertSuggestion::where('thesis_submission_id', $thesis->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get HVC-selected experts with priority
        $selectedExperts = $thesis->thesisEvaluation()
            ->with('expert')
            ->orderBy('priority_order', 'asc')
            ->get();

        return view('dr.thesis.expert_details', compact('thesis', 'suggestedExperts', 'selectedExperts'));
    }

    /**
     * Show thesis approval form
     */
    public function approveThesisForm(\App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'pending_dr_approval') {
            abort(403, 'This thesis is not pending DR approval.');
        }

        $thesis->load(['scholar.user', 'scholar.currentSupervisor.supervisor.user', 'supervisor.user', 'scholar.admission.department', 'supervisorApprover', 'hodApprover', 'daApprover', 'soApprover', 'arApprover']);

        return view('dr.thesis.approve', compact('thesis'));
    }

    /**
     * Process thesis approval/rejection
     */
    public function approveThesis(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'pending_dr_approval') {
            abort(403, 'This thesis is not pending DR approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $thesis->update([
                'status' => 'pending_hvc_approval',
                'dr_approver_id' => Auth::id(),
                'dr_approved_at' => now(),
                'dr_remarks' => $request->remarks,
            ]);

            $message = 'Thesis approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('hvc') . '.';
        } else {
            $thesis->update([
                'status' => 'rejected_by_dr',
                'dr_approver_id' => Auth::id(),
                'dr_approved_at' => now(),
                'dr_remarks' => $request->remarks,
            ]);

            $message = 'Thesis rejected.';
        }

        return redirect()->route('dr.thesis.pending')->with('success', $message);
    }

    /**
     * List pending coursework exemptions
     */
    public function listPendingCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_dr_approval')
            ->latest()
            ->paginate(10);

        return view('dr.coursework_exemptions.pending', compact('exemptions'));
    }

    /**
     * List all coursework exemptions
     */
    public function listAllCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('dr.coursework_exemptions.all', compact('exemptions'));
    }

    /**
     * List all scholar submissions (comprehensive view like HOD)
     */
    public function listAllScholarSubmissions()
    {
        // Get all scholars
        $scholars = \App\Models\Scholar::with(['user', 'admission.department'])->get();

        // Get all synopses
        $synopses = \App\Models\Synopsis::with(['scholar.user', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        // Get all progress reports
        $progressReports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        // Get all thesis submissions
        $thesisSubmissions = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        // Get all coursework exemptions
        $courseworkExemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('dr.scholars.all_submissions', compact('scholars', 'synopses', 'progressReports', 'thesisSubmissions', 'courseworkExemptions'));
    }

    /**
     * View scholar details
     */
    public function viewScholarDetails(\App\Models\Scholar $scholar)
    {
        $scholar->load(['user', 'admission.department', 'supervisorAssignments.supervisor.user', 'synopses']);

        return view('dr.scholars.show', compact('scholar'));
    }
}
