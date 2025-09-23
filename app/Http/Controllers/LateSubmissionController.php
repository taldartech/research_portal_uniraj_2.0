<?php

namespace App\Http\Controllers;

use App\Models\LateSubmissionRequest;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LateSubmissionController extends Controller
{
    /**
     * Show the late submission request form for scholars
     */
    public function showRequestForm()
    {
        $scholar = Auth::user()->scholar;

        if (!$scholar) {
            abort(404, 'Scholar profile not found.');
        }

        if (!$scholar->canRequestLateSubmission()) {
            return redirect()->route('scholar.dashboard')
                ->with('error', 'You are not eligible to request late submission at this time.');
        }

        return view('scholar.late_submission.request', compact('scholar'));
    }

    /**
     * Submit a late submission request
     */
    public function submitRequest(Request $request)
    {
        $scholar = Auth::user()->scholar;

        if (!$scholar || !$scholar->canRequestLateSubmission()) {
            abort(403, 'You are not eligible to request late submission.');
        }

        $request->validate([
            'justification' => 'required|string|max:2000',
            'requested_extension_date' => 'required|date|after:today',
            'supporting_documents' => 'nullable|array|max:5',
            'supporting_documents.*' => 'file|mimes:pdf,doc,docx|max:10240', // 10MB max per file
        ]);

        // Calculate original due date
        $doc = $scholar->date_of_confirmation;
        $maxYears = $scholar->hasApprovedCourseworkExemption() ? 6 : 6;
        $originalDueDate = $doc->addYears($maxYears);

        // Handle file uploads
        $supportingDocuments = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $path = $file->store('late_submission_documents', 'public');
                $supportingDocuments[] = $path;
            }
        }

        // Create the late submission request
        $lateSubmissionRequest = LateSubmissionRequest::create([
            'scholar_id' => $scholar->id,
            'justification' => $request->justification,
            'supporting_documents' => $supportingDocuments,
            'original_due_date' => $originalDueDate,
            'requested_extension_date' => $request->requested_extension_date,
            'status' => 'pending_supervisor_approval',
        ]);

        return redirect()->route('scholar.late_submission.status')
            ->with('success', 'Late submission request submitted successfully. It will be reviewed by your supervisor.');
    }

    /**
     * Show the status of late submission requests for scholars
     */
    public function showStatus()
    {
        $scholar = Auth::user()->scholar;

        if (!$scholar) {
            abort(404, 'Scholar profile not found.');
        }

        $lateSubmissionRequests = $scholar->lateSubmissionRequests()
            ->with(['supervisorApprover', 'hodApprover', 'deanApprover', 'daApprover', 'soApprover', 'arApprover', 'drApprover', 'hvcApprover', 'rejectedBy'])
            ->latest()
            ->get();

        return view('scholar.late_submission.status', compact('scholar', 'lateSubmissionRequests'));
    }

    /**
     * List pending late submission requests for supervisors
     */
    public function listPendingForSupervisor()
    {
        $supervisor = Auth::user()->supervisor;

        if (!$supervisor) {
            abort(404, 'Supervisor profile not found.');
        }

        $requests = LateSubmissionRequest::where('status', 'pending_supervisor_approval')
            ->whereHas('scholar', function ($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id);
            })
            ->with(['scholar.user', 'rejectedBy'])
            ->latest()
            ->get();

        return view('supervisor.late_submission.pending', compact('requests'));
    }

    /**
     * Show the approval form for a specific late submission request
     */
    public function showApprovalForm(LateSubmissionRequest $lateSubmissionRequest)
    {
        if ($lateSubmissionRequest->status !== 'pending_supervisor_approval') {
            abort(403, 'This request is not pending supervisor approval.');
        }

        // Check if the current user is the supervisor of the scholar
        if (Auth::user()->supervisor->id !== $lateSubmissionRequest->scholar->supervisor_id) {
            abort(403, 'You are not authorized to approve this request.');
        }

        $lateSubmissionRequest->load(['scholar.user', 'rejectedBy']);

        return view('supervisor.late_submission.approve', compact('lateSubmissionRequest'));
    }

    /**
     * Process the late submission request approval/rejection
     */
    public function processApproval(Request $request, LateSubmissionRequest $lateSubmissionRequest)
    {
        if ($lateSubmissionRequest->status !== 'pending_supervisor_approval') {
            abort(403, 'This request is not pending supervisor approval.');
        }

        // Check if the current user is the supervisor of the scholar
        if (Auth::user()->supervisor->id !== $lateSubmissionRequest->scholar->supervisor_id) {
            abort(403, 'You are not authorized to approve this request.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $lateSubmissionRequest->update([
                'status' => 'pending_hod_approval',
                'supervisor_approver_id' => Auth::id(),
                'supervisor_approved_at' => now(),
                'supervisor_remarks' => $request->remarks,
            ]);

            $message = 'Late submission request approved and forwarded to HOD.';
        } else {
            $lateSubmissionRequest->update([
                'status' => 'rejected_by_supervisor',
                'supervisor_approver_id' => Auth::id(),
                'supervisor_approved_at' => now(),
                'supervisor_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $lateSubmissionRequest->rejection_count + 1,
            ]);

            $message = 'Late submission request rejected.';
        }

        return redirect()->route('supervisor.late_submission.pending')->with('success', $message);
    }

    /**
     * List pending late submission requests for HOD
     */
    public function listPendingForHOD()
    {
        $hod = Auth::user();

        if ($hod->user_type !== 'hod') {
            abort(403, 'Access denied.');
        }

        $requests = LateSubmissionRequest::where('status', 'pending_hod_approval')
            ->whereHas('scholar.supervisor', function ($query) use ($hod) {
                $query->where('department_id', $hod->hod->department_id);
            })
            ->with(['scholar.user', 'scholar.supervisor.user', 'supervisorApprover', 'rejectedBy'])
            ->latest()
            ->get();

        return view('hod.late_submission.pending', compact('requests'));
    }

    /**
     * Process HOD approval/rejection
     */
    public function processHODApproval(Request $request, LateSubmissionRequest $lateSubmissionRequest)
    {
        if ($lateSubmissionRequest->status !== 'pending_hod_approval') {
            abort(403, 'This request is not pending HOD approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $lateSubmissionRequest->update([
                'status' => 'pending_dean_approval',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
            ]);

            $message = 'Late submission request approved and forwarded to Dean.';
        } else {
            $lateSubmissionRequest->update([
                'status' => 'rejected_by_hod',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $lateSubmissionRequest->rejection_count + 1,
            ]);

            $message = 'Late submission request rejected.';
        }

        return redirect()->route('hod.late_submission.pending')->with('success', $message);
    }

    /**
     * Download supporting documents
     */
    public function downloadDocument(LateSubmissionRequest $lateSubmissionRequest, $index)
    {
        $documents = $lateSubmissionRequest->supporting_documents ?? [];

        if (!isset($documents[$index])) {
            abort(404, 'Document not found.');
        }

        $filePath = $documents[$index];

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        $fileName = basename($filePath);

        return response()->download(storage_path('app/public/' . $filePath), $fileName);
    }
}
