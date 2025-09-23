<?php

namespace App\Http\Controllers;

use App\Models\CourseworkExemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CourseworkExemptionApproved;
use App\Notifications\CourseworkExemptionRejected;
use App\Models\ThesisSubmission;
use App\Models\Synopsis;

class DeanController extends Controller
{
    public function listPendingCourseworkExemptions()
    {
        $dean = Auth::user()->departmentDean;
        $pendingExemptions = CourseworkExemption::where('status', 'pending_dean_approval')
                                                ->whereHas('scholar.admission.department', function ($query) use ($dean) {
                                                    $query->where('dean_id', $dean->id);
                                                })
                                                ->with(['scholar.user', 'supervisor.user', 'rac', 'drc'])
                                                ->get();
        return view('dean.coursework_exemptions.pending', compact('pendingExemptions'));
    }

    public function approveCourseworkExemption(Request $request, CourseworkExemption $courseworkExemption)
    {
        $dean = Auth::user()->departmentDean;
        if (! $dean || $courseworkExemption->scholar->admission->department->dean_id !== $dean->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($courseworkExemption->status !== 'pending_dean_approval') {
            abort(403, 'This coursework exemption is not pending Dean approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $courseworkExemption->update([
                'status' => 'pending_da_approval',
                'dean_approver_id' => Auth::id(),
                'dean_approved_at' => now(),
                'dean_remarks' => $request->remarks,
            ]);

            $message = 'Coursework exemption approved and forwarded to Dean\'s Assistant.';
        } else {
            $courseworkExemption->update([
                'status' => 'rejected_by_dean',
                'dean_approver_id' => Auth::id(),
                'dean_approved_at' => now(),
                'dean_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $courseworkExemption->rejection_count + 1,
            ]);

            $message = 'Coursework exemption rejected.';
        }

        return redirect()->route('dean.coursework_exemptions.pending')->with('success', $message);
    }

    public function listAllScholars()
    {
        $scholars = \App\Models\Scholar::with(['user', 'admission.department'])
                                        ->get();

        return view('dean.scholars.list', compact('scholars'));
    }

    public function viewScholarDetails(\App\Models\Scholar $scholar)
    {
        // Ensure the Dean has access to this scholar (no departmental restriction for Dean)
        $scholar->load(['user', 'admission.department', 'supervisorAssignments.supervisor.user', 'racs.supervisor.user', 'synopses']);

        return view('dean.scholars.show', compact('scholar'));
    }

    public function listSupervisors()
    {
        $supervisors = \App\Models\Supervisor::withCount(['assignedScholars as assigned_scholars_count' => function ($query) {
                                            $query->where('supervisor_assignments.status', 'assigned');
                                        }])
                                        ->with('user', 'department')
                                        ->get();

        return view('dean.supervisors.list', compact('supervisors'));
    }

    public function listThesis()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'scholar.admission.department'])
                                            ->get();

        return view('dean.thesis.list', compact('theses'));
    }

    public function listSynopsis()
    {
        $synopses = \App\Models\Synopsis::with(['scholar.user', 'scholar.admission.department', 'rac.supervisor.user'])
                                        ->get();

        return view('dean.synopsis.list', compact('synopses'));
    }

    /**
     * List pending late submission requests for Dean approval
     */
    public function listPendingLateSubmissions()
    {
        $requests = \App\Models\LateSubmissionRequest::where('status', 'pending_dean_approval')
            ->with(['scholar.user', 'scholar.supervisor.user', 'supervisorApprover', 'hodApprover', 'rejectedBy'])
            ->latest()
            ->get();

        return view('dean.late_submission.pending', compact('requests'));
    }

    /**
     * Process Dean approval/rejection for late submission requests
     */
    public function processLateSubmissionApproval(Request $request, \App\Models\LateSubmissionRequest $lateSubmissionRequest)
    {
        if ($lateSubmissionRequest->status !== 'pending_dean_approval') {
            abort(403, 'This request is not pending Dean approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $lateSubmissionRequest->update([
                'status' => 'pending_da_approval',
                'dean_approver_id' => Auth::id(),
                'dean_approved_at' => now(),
                'dean_remarks' => $request->remarks,
            ]);

            $message = 'Late submission request approved and forwarded to Dean\'s Assistant.';
        } else {
            $lateSubmissionRequest->update([
                'status' => 'rejected_by_dean',
                'dean_approver_id' => Auth::id(),
                'dean_approved_at' => now(),
                'dean_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $lateSubmissionRequest->rejection_count + 1,
            ]);

            $message = 'Late submission request rejected.';
        }

        return redirect()->route('dean.late_submission.pending')->with('success', $message);
    }
}
