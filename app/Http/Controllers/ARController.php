<?php

namespace App\Http\Controllers;

use App\Models\SupervisorCapacityIncreaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ARController extends Controller
{
    /**
     * List pending capacity increase requests for AR approval
     */
    public function listPendingCapacityRequests()
    {
        $requests = SupervisorCapacityIncreaseRequest::where('status', 'pending_ar')
            ->with(['supervisor.user', 'supervisor.department', 'daApprover', 'soApprover'])
            ->latest()
            ->get();

        return view('ar.capacity_requests.pending', compact('requests'));
    }

    /**
     * Show the approval form for a specific request
     */
    public function showApprovalForm(SupervisorCapacityIncreaseRequest $request)
    {
        if ($request->status !== 'pending_ar') {
            abort(403, 'This request is not pending AR approval.');
        }

        $request->load(['supervisor.user', 'supervisor.department', 'daApprover', 'soApprover']);

        return view('ar.capacity_requests.approve', compact('request'));
    }

    /**
     * Process the approval/rejection
     */
    public function processApproval(Request $request, SupervisorCapacityIncreaseRequest $capacityRequest)
    {
        if ($capacityRequest->status !== 'pending_ar') {
            abort(403, 'This request is not pending AR approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $capacityRequest->update([
                'status' => 'pending_dr',
                'ar_approver_id' => Auth::id(),
                'ar_approved_at' => now(),
                'ar_remarks' => $request->remarks,
            ]);

            $message = 'Request approved and forwarded to Deputy Registrar.';
        } else {
            $capacityRequest->update([
                'status' => 'rejected',
                'ar_approver_id' => Auth::id(),
                'ar_approved_at' => now(),
                'ar_remarks' => $request->remarks,
            ]);

            $message = 'Request rejected.';
        }

        return redirect()->route('ar.capacity_requests.pending')->with('success', $message);
    }

    /**
     * List pending synopses for AR approval
     */
    public function listPendingSynopses()
    {
        $synopses = \App\Models\Synopsis::where('status', 'pending_ar_approval')
            ->with(['scholar.user', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'supervisorApprover', 'hodApprover', 'daApprover', 'soApprover'])
            ->latest()
            ->get();

        return view('ar.synopses.pending', compact('synopses'));
    }

    /**
     * Show the synopsis approval form for a specific synopsis
     */
    public function showSynopsisApprovalForm(\App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_ar_approval') {
            abort(403, 'This synopsis is not pending AR approval.');
        }

        $synopsis->load([
            'scholar.user',
            'scholar.admission.department',
            'scholar.currentSupervisor.supervisor.user',
            'rac.supervisor.user',
            'supervisorApprover',
            'hodApprover',
            'daApprover',
            'soApprover'
        ]);

        // Get available roles for reassignment
        $availableRoles = \App\Helpers\WorkflowHelper::getAvailableReassignmentRoles($synopsis->status, $synopsis);

        return view('ar.synopses.approve', compact('synopsis', 'availableRoles'));
    }

    /**
     * Process the synopsis approval/rejection
     */
    public function processSynopsisApproval(Request $request, \App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_ar_approval') {
            abort(403, 'This synopsis is not pending AR approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'reassigned_to_role' => 'nullable|string|in:supervisor,hod,da,so,ar',
            'reassignment_reason' => 'nullable|string|max:1000',
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        if ($request->action === 'approve') {
            $synopsis->update([
                'ar_remarks' => $request->remarks,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'ar_approve', Auth::user());
            $message = 'Synopsis approved and forwarded to Deputy Registrar.';
        } else {
            $synopsis->update([
                'ar_remarks' => $request->remarks,
                'reassignment_reason' => $request->reassignment_reason,
            ]);

            // Sync workflow with reassignment
            $reassignedRole = $request->reassigned_to_role;
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'ar_reject', Auth::user(), $reassignedRole);
            
            if ($reassignedRole) {
                $roleLabels = [
                    'supervisor' => 'Supervisor',
                    'hod' => 'HOD',
                    'da' => 'Dean\'s Assistant',
                    'so' => 'Section Officer',
                ];
                $message = 'Synopsis rejected and reassigned to ' . ($roleLabels[$reassignedRole] ?? $reassignedRole) . ' for corrections.';
            } else {
                $message = 'Synopsis rejected.';
            }
        }

        return redirect()->route('ar.synopses.pending')->with('success', $message);
    }

    /**
     * List pending progress reports
     */
    public function listPendingProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_ar_approval')
            ->latest()
            ->paginate(10);

        return view('ar.progress_reports.pending', compact('reports'));
    }

    /**
     * List all progress reports
     */
    public function listAllProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('ar.progress_reports.all', compact('reports'));
    }

    /**
     * List pending thesis submissions
     */
    public function listPendingThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_ar_approval')
            ->latest()
            ->paginate(10);

        return view('ar.thesis.pending', compact('theses'));
    }

    /**
     * List all thesis submissions
     */
    public function listAllThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('ar.thesis.all', compact('theses'));
    }

    /**
     * List pending coursework exemptions
     */
    public function listPendingCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_ar_approval')
            ->latest()
            ->paginate(10);

        return view('ar.coursework_exemptions.pending', compact('exemptions'));
    }

    /**
     * List all coursework exemptions
     */
    public function listAllCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('ar.coursework_exemptions.all', compact('exemptions'));
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

        return view('ar.scholars.all_submissions', compact('scholars', 'synopses', 'progressReports', 'thesisSubmissions', 'courseworkExemptions'));
    }

    /**
     * View scholar details
     */
    public function viewScholarDetails(\App\Models\Scholar $scholar)
    {
        $scholar->load(['user', 'admission.department', 'supervisorAssignments.supervisor.user', 'synopses']);

        return view('ar.scholars.show', compact('scholar'));
    }
}
