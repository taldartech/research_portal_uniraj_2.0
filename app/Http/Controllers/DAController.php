<?php

namespace App\Http\Controllers;

use App\Models\SupervisorCapacityIncreaseRequest;
use App\Models\Scholar;
use App\Models\OfficeNote;
use App\Models\RACCommitteeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DAController extends Controller
{
    /**
     * Show DA dashboard
     */
    public function dashboard()
    {
        $pendingCapacityRequests = SupervisorCapacityIncreaseRequest::where('status', 'pending_da')->count();
        $pendingSynopses = \App\Models\Synopsis::where('status', 'pending_da_approval')->count();
        $pendingLateSubmissions = \App\Models\LateSubmissionRequest::where('status', 'pending_da')->count();
        $eligibleScholars = Scholar::where('registration_form_status', 'approved')
            ->whereDoesntHave('officeNote')
            ->count();

        return view('da.dashboard', compact(
            'pendingCapacityRequests',
            'pendingSynopses',
            'pendingLateSubmissions',
            'eligibleScholars'
        ));
    }

    /**
     * List pending capacity increase requests for DA approval
     */
    public function listPendingCapacityRequests()
    {
        $requests = SupervisorCapacityIncreaseRequest::where('status', 'pending_da')
            ->with(['supervisor.user', 'supervisor.department'])
            ->latest()
            ->get();

        return view('da.capacity_requests.pending', compact('requests'));
    }

    /**
     * List pending synopses for DA approval
     */
    public function listPendingSynopses()
    {
        $synopses = \App\Models\Synopsis::where('status', 'pending_da_approval')
            ->with(['scholar.user', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'supervisorApprover', 'hodApprover'])
            ->latest()
            ->get();

        return view('da.synopses.pending', compact('synopses'));
    }

    /**
     * Show the approval form for a specific request
     */
    public function showApprovalForm(SupervisorCapacityIncreaseRequest $request)
    {
        if ($request->status !== 'pending_da') {
            abort(403, 'This request is not pending DA approval.');
        }

        $request->load(['supervisor.user', 'supervisor.department']);

        return view('da.capacity_requests.approve', compact('request'));
    }

    /**
     * Process the approval/rejection
     */
    public function processApproval(Request $request, SupervisorCapacityIncreaseRequest $capacityRequest)
    {
        if ($capacityRequest->status !== 'pending_da') {
            abort(403, 'This request is not pending DA approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $capacityRequest->update([
                'status' => 'pending_so',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Request approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('so') . '.';
        } else {
            $capacityRequest->update([
                'status' => 'rejected',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Request rejected.';
        }

        return redirect()->route('da.capacity_requests.pending')->with('success', $message);
    }

    /**
     * Show the synopsis approval form for a specific synopsis
     */
    public function showSynopsisApprovalForm(\App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_da_approval') {
            abort(403, 'This synopsis is not pending DA approval.');
        }

        $synopsis->load([
            'scholar.user',
            'scholar.admission.department',
            'scholar.currentSupervisor.supervisor.user',
            'rac.supervisor.user',
            'supervisorApprover',
            'hodApprover'
        ]);

        // Get available roles for reassignment
        $availableRoles = \App\Helpers\WorkflowHelper::getAvailableReassignmentRoles($synopsis->status, $synopsis);

        return view('da.synopses.approve', compact('synopsis', 'availableRoles'));
    }

    /**
     * Process the synopsis approval/rejection
     */
    public function processSynopsisApproval(Request $request, \App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_da_approval') {
            abort(403, 'This synopsis is not pending DA approval.');
        }

        $request->validate([
            'drc_minutes_file' => $request->action === 'approve' ? 'required|url' : 'nullable|url',
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'reassigned_to_role' => 'nullable|string|in:supervisor,hod,da',
            'reassignment_reason' => 'nullable|string|max:1000',
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        if ($request->action === 'approve') {
            $synopsis->update([
                'da_remarks' => $request->remarks,
                'drc_minutes_file' => $request->drc_minutes_file,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'da_approve', Auth::user());
            $message = 'Synopsis approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('so') . '.';
        } else {
            $updateData = [
                'da_remarks' => $request->remarks,
                'reassignment_reason' => $request->reassignment_reason,
            ];

            // Only update drc_minutes_file if provided (optional for reject)
            if ($request->filled('drc_minutes_file')) {
                $updateData['drc_minutes_file'] = $request->drc_minutes_file;
            }

            $synopsis->update($updateData);

            // Sync workflow with reassignment
            $reassignedRole = $request->reassigned_to_role;
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'da_reject', Auth::user(), $reassignedRole);

            if ($reassignedRole) {
                $message = 'Synopsis rejected and reassigned to ' . \App\Helpers\WorkflowHelper::getRoleFullForm($reassignedRole) . ' for corrections.';
            } else {
                $message = 'Synopsis rejected.';
            }
        }

        return redirect()->route('da.synopses.pending')->with('success', $message);
    }

    /**
     * List pending coursework exemptions for DA approval
     */
    public function listPendingCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::where('status', 'pending_da_approval')
            ->with(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover'])
            ->latest()
            ->get();

        return view('da.coursework_exemptions.pending', compact('exemptions'));
    }

    /**
     * Show the coursework exemption approval form for a specific exemption
     */
    public function showCourseworkExemptionApprovalForm(\App\Models\CourseworkExemption $exemption)
    {
        if ($exemption->status !== 'pending_da_approval') {
            abort(403, 'This coursework exemption is not pending DA approval.');
        }

        $exemption->load(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover']);

        return view('da.coursework_exemptions.approve', compact('exemption'));
    }

    /**
     * Process the coursework exemption approval/rejection
     */
    public function processCourseworkExemptionApproval(Request $request, \App\Models\CourseworkExemption $exemption)
    {
        if ($exemption->status !== 'pending_da_approval') {
            abort(403, 'This coursework exemption is not pending DA approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $exemption->update([
                'status' => 'pending_so_approval',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Coursework exemption approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('so') . '.';
        } else {
            $exemption->update([
                'status' => 'rejected',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Coursework exemption rejected.';
        }

        return redirect()->route('da.coursework_exemptions.pending')->with('success', $message);
    }

    /**
     * List pending progress reports for DA approval
     */
    public function listPendingProgressReports()
    {
        $reports = \App\Models\ProgressReport::where('status', 'pending_da_approval')
            ->with(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover'])
            ->latest()
            ->get();

        return view('da.progress_reports.pending', compact('reports'));
    }

    /**
     * Show the progress report approval form for a specific report
     */
    public function showProgressReportApprovalForm(\App\Models\ProgressReport $report)
    {
        if ($report->status !== 'pending_da_approval') {
            abort(403, 'This progress report is not pending DA approval.');
        }

        $report->load(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover']);

        return view('da.progress_reports.approve', compact('report'));
    }

    /**
     * Process the progress report approval/rejection with conditional logic
     */
    public function processProgressReportApproval(Request $request, \App\Models\ProgressReport $report)
    {
        if ($report->status !== 'pending_da_approval') {
            abort(403, 'This progress report is not pending DA approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:500',
            'da_negative_remarks' => 'nullable|string|max:500',
        ]);

        if ($request->action === 'approve') {
            // Check if negative remarks are provided - if yes, forward to SO
            if (!empty($request->da_negative_remarks) && trim($request->da_negative_remarks) !== '') {
                $report->update([
                    'status' => 'pending_so_approval',
                    'da_approver_id' => Auth::id(),
                    'da_approved_at' => now(),
                    'da_remarks' => $request->remarks,
                    'da_negative_remarks' => $request->da_negative_remarks,
                ]);

                $message = 'Progress report forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('so') . ' for full approval chain.';
            } else {
                // Direct approval path (Submit button)
                $report->update([
                    'status' => 'approved',
                    'da_approver_id' => Auth::id(),
                    'da_approved_at' => now(),
                    'da_remarks' => $request->remarks,
                    'da_negative_remarks' => null,
                ]);

                $message = 'Progress report approved and submitted successfully.';
            }
        } else {
            $report->update([
                'status' => 'rejected',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
                'da_negative_remarks' => null,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $report->rejection_count + 1,
            ]);

            $message = 'Progress report rejected.';
        }

        return redirect()->route('da.progress_reports.pending')->with('success', $message);
    }

    /**
     * List pending thesis submissions for DA approval
     */
    public function listPendingThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::where('status', 'pending_da_approval')
            ->with(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover'])
            ->latest()
            ->get();

        return view('da.thesis.pending', compact('theses'));
    }

    /**
     * Show the thesis approval form for a specific thesis
     */
    public function showThesisApprovalForm(\App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'pending_da_approval') {
            abort(403, 'This thesis is not pending DA approval.');
        }

        $thesis->load(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover']);

        return view('da.thesis.approve', compact('thesis'));
    }

    /**
     * Process the thesis approval/rejection
     */
    public function processThesisApproval(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'pending_da_approval') {
            abort(403, 'This thesis is not pending DA approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $thesis->update([
                'status' => 'pending_so_approval',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            // Auto-generate submission certificate when DA approves
            $certificateService = new \App\Services\CertificateGenerationService();
            $certificateService->generateSubmissionCertificate($thesis);

            $message = 'Thesis approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('so') . '. Submission certificate generated.';
        } else {
            $thesis->update([
                'status' => 'rejected_by_da',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $thesis->rejection_count + 1,
            ]);

            $message = 'Thesis rejected by ' . \App\Helpers\WorkflowHelper::getRoleFullForm('da') . '.';
        }

        return redirect()->route('da.thesis.pending')->with('success', $message);
    }

    /**
     * List pending late submission requests for DA approval
     */
    public function listPendingLateSubmissions()
    {
        $requests = \App\Models\LateSubmissionRequest::where('status', 'pending_da_approval')
            ->with(['scholar.user', 'scholar.supervisor.user', 'supervisorApprover', 'hodApprover', 'deanApprover', 'rejectedBy'])
            ->latest()
            ->get();

        return view('da.late_submission.pending', compact('requests'));
    }

    /**
     * Process DA approval/rejection for late submission requests
     */
    public function processLateSubmissionApproval(Request $request, \App\Models\LateSubmissionRequest $lateSubmissionRequest)
    {
        if ($lateSubmissionRequest->status !== 'pending_da_approval') {
            abort(403, 'This request is not pending DA approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $lateSubmissionRequest->update([
                'status' => 'pending_so_approval',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Late submission request approved and forwarded to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('so') . '.';
        } else {
            $lateSubmissionRequest->update([
                'status' => 'rejected_by_da',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $lateSubmissionRequest->rejection_count + 1,
            ]);

            $message = 'Late submission request rejected.';
        }

        return redirect()->route('da.late_submission.pending')->with('success', $message);
    }

    /**
     * List scholars eligible for office note generation (HVC approved)
     */
    public function listEligibleScholars()
    {
        $scholars = Scholar::where('registration_form_status', 'approved')
            ->whereDoesntHave('officeNote')
            ->with(['user', 'supervisor.user'])
            ->latest()
            ->get();

        return view('da.office_notes.eligible_scholars', compact('scholars'));
    }

    /**
     * Show office note generation form for a specific scholar
     */
    public function showOfficeNoteForm(Scholar $scholar)
    {
        // Check if scholar is eligible (HVC approved)
        if ($scholar->registration_form_status !== 'approved') {
            abort(403, 'Scholar is not eligible for office note generation.');
        }

        // Check if office note already exists
        if ($scholar->officeNote) {
            return redirect()->route('da.office_notes.edit', $scholar->officeNote);
        }

        return view('da.office_notes.create', compact('scholar'));
    }

    /**
     * Generate office note for a scholar
     */
    public function generateOfficeNote(Request $request, Scholar $scholar)
    {
        $request->validate([
            'file_number' => 'required|string|max:255',
            'dated' => 'required|date',
            'supervisor_retirement_date' => 'nullable|date',
            'co_supervisor_retirement_date' => 'nullable|date',
            'drc_approval_date' => 'nullable|date',
            'registration_fee_receipt_number' => 'nullable|string|max:255',
            'registration_fee_date' => 'nullable|date',
            'commencement_date' => 'nullable|date',
            'enrollment_number' => 'nullable|string|max:255',
            'supervisor_registration_page_number' => 'nullable|string|max:255',
            'supervisor_seats_available' => 'nullable|integer|min:0',
            'candidates_under_guidance' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // Auto-populate scholar data
        $officeNoteData = [
            'scholar_id' => $scholar->id,
            'file_number' => $request->file_number,
            'dated' => $request->dated,
            'candidate_name' => $scholar->name,
            'research_subject' => $scholar->research_topic_title,
            'supervisor_name' => $scholar->supervisor_name,
            'supervisor_designation' => $scholar->supervisor_designation,
            'supervisor_address' => $scholar->supervisor_address,
            'supervisor_retirement_date' => $request->supervisor_retirement_date,
            'co_supervisor_name' => $scholar->co_supervisor_name,
            'co_supervisor_designation' => $scholar->co_supervisor_designation,
            'co_supervisor_address' => $scholar->co_supervisor_address,
            'co_supervisor_retirement_date' => $request->co_supervisor_retirement_date,
            'ug_university' => 'University of Rajasthan', // Default
            'ug_class' => 'B.A./B.Sc./B.Com',
            'ug_marks' => 'N/A',
            'ug_percentage' => 'N/A',
            'ug_division' => 'N/A',
            'pg_university' => $scholar->post_graduate_university,
            'pg_class' => $scholar->post_graduate_degree,
            'pg_marks' => $scholar->post_graduate_percentage,
            'pg_percentage' => $scholar->post_graduate_percentage,
            'pg_division' => 'First',
            'pat_year' => $scholar->net_slet_csir_gate_year,
            'pat_merit_number' => $scholar->net_slet_csir_gate_roll_number,
            'coursework_marks_obtained' => $scholar->coursework_marks_obtained,
            'coursework_merit_number' => $scholar->mpat_merit_number,
            'drc_approval_date' => $request->drc_approval_date,
            'registration_fee_receipt_number' => $request->registration_fee_receipt_number,
            'registration_fee_date' => $request->registration_fee_date,
            'commencement_date' => $request->commencement_date,
            'enrollment_number' => $request->enrollment_number ?? $scholar->enrollment_number,
            'supervisor_registration_page_number' => $request->supervisor_registration_page_number,
            'supervisor_seats_available' => $request->supervisor_seats_available,
            'candidates_under_guidance' => $request->candidates_under_guidance,
            'status' => 'generated',
            'notes' => $request->notes,
        ];

        $officeNote = OfficeNote::create($officeNoteData);

        return redirect()->route('da.office_notes.show', $officeNote)
            ->with('success', 'Office Note generated successfully!');
    }

    /**
     * Show generated office note
     */
    public function showOfficeNote(OfficeNote $officeNote)
    {
        $officeNote->load('scholar.user', 'scholar.supervisor.user');
        return view('da.office_notes.show', compact('officeNote'));
    }

    /**
     * Edit office note
     */
    public function editOfficeNote(OfficeNote $officeNote)
    {
        $officeNote->load('scholar.user', 'scholar.supervisor.user');
        return view('da.office_notes.edit', compact('officeNote'));
    }

    /**
     * Update office note
     */
    public function updateOfficeNote(Request $request, OfficeNote $officeNote)
    {
        $request->validate([
            'file_number' => 'required|string|max:255',
            'dated' => 'required|date',
            'supervisor_retirement_date' => 'nullable|date',
            'co_supervisor_retirement_date' => 'nullable|date',
            'drc_approval_date' => 'nullable|date',
            'registration_fee_receipt_number' => 'nullable|string|max:255',
            'registration_fee_date' => 'nullable|date',
            'commencement_date' => 'nullable|date',
            'enrollment_number' => 'nullable|string|max:255',
            'supervisor_registration_page_number' => 'nullable|string|max:255',
            'supervisor_seats_available' => 'nullable|integer|min:0',
            'candidates_under_guidance' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $officeNote->update($request->all());

        return redirect()->route('da.office_notes.show', $officeNote)
            ->with('success', 'Office Note updated successfully!');
    }

    /**
     * Download office note as PDF
     */
    public function downloadOfficeNote(OfficeNote $officeNote)
    {
        $officeNote->load('scholar.user', 'scholar.supervisor.user');

        // Generate PDF (you can use a PDF library like DomPDF or TCPDF)
        // For now, we'll return a view that can be printed as PDF
        return view('da.office_notes.pdf', compact('officeNote'));
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

        // Get all RAC committee submissions
        $racCommitteeSubmissions = \App\Models\RACCommitteeSubmission::with(['scholar.user', 'scholar.admission.department', 'supervisor.user', 'hod'])
            ->latest()
            ->get();

        return view('da.scholars.all_submissions', compact('scholars', 'synopses', 'progressReports', 'thesisSubmissions', 'racCommitteeSubmissions'));
    }

    /**
     * View scholar details
     */
    public function viewScholarDetails(Scholar $scholar)
    {
        $scholar->load(['user', 'admission.department', 'supervisorAssignments.supervisor.user', 'synopses']);

        return view('da.scholars.show', compact('scholar'));
    }
}
