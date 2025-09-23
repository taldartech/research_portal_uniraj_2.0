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

            $message = 'Request approved and forwarded to Hon\'ble Vice Chancellor';
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
            ->with(['scholar.user', 'rac.supervisor.user', 'supervisorApprover', 'hodApprover', 'daApprover', 'soApprover', 'arApprover'])
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

        $synopsis->load(['scholar.user', 'rac.supervisor.user', 'supervisorApprover', 'hodApprover', 'daApprover', 'soApprover', 'arApprover']);

        return view('dr.synopses.approve', compact('synopsis'));
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
        ]);

        if ($request->action === 'approve') {
            $synopsis->update([
                'status' => 'pending_hvc_approval',
                'dr_approver_id' => Auth::id(),
                'dr_approved_at' => now(),
                'dr_remarks' => $request->remarks,
            ]);

            $message = 'Synopsis approved and forwarded to Hon\'ble Vice Chancellor';
        } else {
            $synopsis->update([
                'status' => 'rejected',
                'dr_approver_id' => Auth::id(),
                'dr_approved_at' => now(),
                'dr_remarks' => $request->remarks,
            ]);

            $message = 'Synopsis rejected.';
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
     * List pending thesis submissions
     */
    public function listPendingThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
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
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('dr.thesis.all', compact('theses'));
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
        $synopses = \App\Models\Synopsis::with(['scholar.user', 'rac.supervisor.user', 'scholar.admission.department'])
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
}
