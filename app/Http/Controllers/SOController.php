<?php

namespace App\Http\Controllers;

use App\Models\SupervisorCapacityIncreaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SOController extends Controller
{
    /**
     * List pending capacity increase requests for SO approval
     */
    public function listPendingCapacityRequests()
    {
        $requests = SupervisorCapacityIncreaseRequest::where('status', 'pending_so')
            ->with(['supervisor.user', 'supervisor.department', 'daApprover'])
            ->latest()
            ->get();

        return view('so.capacity_requests.pending', compact('requests'));
    }

    /**
     * Show the approval form for a specific request
     */
    public function showApprovalForm(SupervisorCapacityIncreaseRequest $request)
    {
        if ($request->status !== 'pending_so') {
            abort(403, 'This request is not pending SO approval.');
        }

        $request->load(['supervisor.user', 'supervisor.department', 'daApprover']);

        return view('so.capacity_requests.approve', compact('request'));
    }

    /**
     * Process the approval/rejection
     */
    public function processApproval(Request $request, SupervisorCapacityIncreaseRequest $capacityRequest)
    {
        if ($capacityRequest->status !== 'pending_so') {
            abort(403, 'This request is not pending SO approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $capacityRequest->update([
                'status' => 'pending_ar',
                'so_approver_id' => Auth::id(),
                'so_approved_at' => now(),
                'so_remarks' => $request->remarks,
            ]);

            $message = 'Request approved and forwarded to Assistant Registrar.';
        } else {
            $capacityRequest->update([
                'status' => 'rejected',
                'so_approver_id' => Auth::id(),
                'so_approved_at' => now(),
                'so_remarks' => $request->remarks,
            ]);

            $message = 'Request rejected.';
        }

        return redirect()->route('so.capacity_requests.pending')->with('success', $message);
    }

    /**
     * List pending synopses for SO approval
     */
    public function listPendingSynopses()
    {
        $synopses = \App\Models\Synopsis::where('status', 'pending_so_approval')
            ->with(['scholar.user', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'supervisorApprover', 'hodApprover', 'daApprover'])
            ->latest()
            ->get();

        return view('so.synopses.pending', compact('synopses'));
    }

    /**
     * Show the synopsis approval form for a specific synopsis
     */
    public function showSynopsisApprovalForm(\App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_so_approval') {
            abort(403, 'This synopsis is not pending SO approval.');
        }

        $synopsis->load([
            'scholar.user',
            'scholar.admission.department',
            'scholar.currentSupervisor.supervisor.user',
            'rac.supervisor.user',
            'supervisorApprover',
            'hodApprover',
            'daApprover'
        ]);

        return view('so.synopses.approve', compact('synopsis'));
    }

    /**
     * Process the synopsis approval/rejection
     */
    public function processSynopsisApproval(Request $request, \App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_so_approval') {
            abort(403, 'This synopsis is not pending SO approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        if ($request->action === 'approve') {
            $synopsis->update([
                'so_remarks' => $request->remarks,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'so_approve', Auth::user());
            $message = 'Synopsis approved and forwarded to Assistant Registrar.';
        } else {
            $synopsis->update([
                'so_remarks' => $request->remarks,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'so_reject', Auth::user());
            $message = 'Synopsis rejected.';
        }

        return redirect()->route('so.synopses.pending')->with('success', $message);
    }

    /**
     * List pending progress reports
     */
    public function listPendingProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_so_approval')
            ->latest()
            ->paginate(10);

        return view('so.progress_reports.pending', compact('reports'));
    }

    /**
     * List all progress reports
     */
    public function listAllProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('so.progress_reports.all', compact('reports'));
    }

    /**
     * List pending thesis submissions
     */
    public function listPendingThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_so_approval')
            ->latest()
            ->paginate(10);

        return view('so.thesis.pending', compact('theses'));
    }

    /**
     * List all thesis submissions
     */
    public function listAllThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('so.thesis.all', compact('theses'));
    }

    /**
     * List pending coursework exemptions
     */
    public function listPendingCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_so_approval')
            ->latest()
            ->paginate(10);

        return view('so.coursework_exemptions.pending', compact('exemptions'));
    }

    /**
     * List all coursework exemptions
     */
    public function listAllCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('so.coursework_exemptions.all', compact('exemptions'));
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

        return view('so.scholars.all_submissions', compact('scholars', 'synopses', 'progressReports', 'thesisSubmissions', 'courseworkExemptions'));
    }
}
