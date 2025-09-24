<?php

namespace App\Http\Controllers;

use App\Models\CourseworkExemption;
use App\Models\RAC;
use App\Models\Scholar;
use App\Models\Supervisor;
use App\Models\SupervisorAssignment;
use App\Models\Synopsis;
use App\Models\ThesisEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SynopsisRejected;
use App\Notifications\SynopsisRevisionNeeded;
use App\Traits\HasAlertResponses;

class SupervisorController extends Controller
{
    use HasAlertResponses;
    public function listScholars()
    {
        $supervisor = $this->getSupervisor();

        // Only show scholars that have been approved by HOD (status = 'assigned')
        $scholars = $supervisor->assignedScholars()
            ->wherePivot('status', 'assigned')
            ->with('user')
            ->get();
        return view('supervisor.scholars.list', compact('scholars'));
    }

    public function viewScholarDetails(Scholar $scholar)
    {
        // Ensure the supervisor is assigned to this scholar
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        // Load related data
        $scholar->load([
            'user',
            'admission.department',
            'supervisorAssignments.supervisor.user',
            'synopses' => function($query) {
                $query->latest();
            },
            'progressReports' => function($query) {
                $query->latest();
            },
            'thesisSubmissions' => function($query) {
                $query->latest();
            },
            'vivaExaminations' => function($query) {
                $query->latest();
            }
        ]);

        return view('supervisor.scholars.show', compact('scholar'));
    }

    public function verifyScholarDataForm(Scholar $scholar)
    {
        // Ensure the supervisor is assigned to this scholar
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }
        return view('supervisor.scholars.verify_data', compact('scholar'));
    }

    public function verifyScholarData(Request $request, Scholar $scholar)
    {
        // Ensure the supervisor is assigned to this scholar
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'date_of_confirmation' => 'nullable|date',
            'enrollment_number' => 'nullable|string|max:255',
            'research_area' => 'nullable|string|max:255',
            // Add other fields that a supervisor can verify
        ]);

        $scholar->update($request->all());

        return $this->successResponse('Scholar data verified successfully.', 'staff.scholars.list');
    }

    public function uploadRacMinutesForm()
    {
        $supervisor = Auth::user()->supervisor;
        $racs = RAC::where('supervisor_id', $supervisor->id)->get();
        return view('supervisor.rac_minutes.upload', compact('racs'));
    }

    public function storeRacMinutes(Request $request)
    {
        $request->validate([
            'rac_id' => 'required|exists:racs,id',
            'minutes_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'meeting_date' => 'required|date',
        ]);

        $rac = RAC::findOrFail($request->rac_id);

        if ($rac->supervisor_id !== Auth::user()->supervisor->id) {
            abort(403, 'Unauthorized action.');
        }

        $path = $request->file('minutes_file')->store('rac_minutes', 'public');

        $rac->update([
            'minutes_file' => $path,
            'meeting_date' => $request->meeting_date,
            'status' => 'minutes_uploaded',
        ]);

        return $this->successResponse('RAC minutes uploaded successfully.');
    }

    public function approveSynopsisForm(Synopsis $synopsis)
    {
        if (! $this->isAssignedSupervisor($synopsis->scholar)) {
            abort(403, 'Unauthorized action.');
        }

        // Load additional relationships for registration details
        $synopsis->load([
            'scholar.user',
            'scholar.admission.department',
            'scholar.currentSupervisor.supervisor.user',
            'rac.supervisor.user'
        ]);

        return view('supervisor.synopsis.approve', compact('synopsis'));
    }

    public function approveSynopsis(Request $request, Synopsis $synopsis)
    {
        if (! $this->isAssignedSupervisor($synopsis->scholar)) {
            abort(403, 'Unauthorized action.');
        }

        if ($synopsis->status !== 'pending_supervisor_approval') {
            abort(403, 'This synopsis is not pending supervisor approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'rac_minutes_file' => 'required_if:action,approve|file|mimes:pdf|max:2048',
            'research_topic' => 'nullable|string|max:1000',
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        if ($request->action === 'approve') {
            // Upload RAC minutes file
            $racMinutesPath = $request->file('rac_minutes_file')->store('rac_minutes', 'public');

            $synopsis->update([
                'supervisor_remarks' => $request->remarks,
                'rac_minutes_file' => $racMinutesPath,
            ]);

            // Update scholar's research topic if provided
            if ($request->filled('research_topic')) {
                $synopsis->scholar->update([
                    'research_topic_title' => $request->research_topic,
                ]);
            }

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'supervisor_approve', Auth::user());
            $message = 'Synopsis approved and forwarded to HOD with RAC minutes.';
        } else {
            $synopsis->update([
                'supervisor_remarks' => $request->remarks,
            ]);

            // Update scholar's research topic if provided
            if ($request->filled('research_topic')) {
                $synopsis->scholar->update([
                    'research_topic_title' => $request->research_topic,
                ]);
            }

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'supervisor_reject', Auth::user());

            $message = 'Synopsis rejected.';
        }

        return redirect()->route('staff.synopsis.pending')->with('success', $message);
    }

    public function suggestExpertsForm()
    {
        return view('supervisor.thesis_evaluation.suggest_experts');
    }

    public function storeExpertsSuggestion(Request $request)
    {
        $request->validate([
            'thesis_submission_id' => 'required|exists:thesis_submissions,id',
            'expert_suggestions' => 'required|array|min:8',
            'expert_suggestions.*.name' => 'required|string|max:255',
            'expert_suggestions.*.affiliation' => 'required|string|max:255',
            'expert_suggestions.*.email' => 'required|email',
        ]);

        // Logic to store expert suggestions. This would typically involve creating records in a related table.
        // For now, simulate success.

        return redirect()->back()->with('success', 'Expert suggestions submitted successfully.');
    }

    public function listPendingSynopses()
    {
        $supervisor = $this->getSupervisor();
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

        return view('supervisor.synopsis.pending', compact('pendingSynopses'));
    }

    public function listSubmittedCourseworkExemptions()
    {
        $supervisor = $this->getSupervisor();
        $submittedExemptions = CourseworkExemption::where('supervisor_id', $supervisor->id)
                                                ->whereIn('status', ['pending_dean_approval', 'pending_da_approval', 'pending_so_approval', 'pending_ar_approval', 'pending_dr_approval', 'pending_hvc_approval', 'approved', 'rejected_by_dean', 'rejected_by_da', 'rejected_by_so', 'rejected_by_ar', 'rejected_by_dr', 'rejected_by_hvc'])
                                                ->with(['scholar.user', 'rac'])
                                                ->get();

        return view('supervisor.coursework_exemption.submitted', compact('submittedExemptions'));
    }

    public function listPendingCourseworkExemptions()
    {
        $supervisor = Auth::user()->supervisor;
        $pendingExemptions = CourseworkExemption::where('supervisor_id', $supervisor->id)
                                                ->where('status', 'pending_supervisor_approval')
                                                ->with(['scholar.user', 'rac'])
                                                ->latest()
                                                ->get();

        return view('supervisor.coursework_exemption.pending', compact('pendingExemptions'));
    }

    public function approveCourseworkExemptionForm(\App\Models\CourseworkExemption $exemption)
    {
        $supervisor = Auth::user()->supervisor;

        // Ensure the supervisor is assigned to this exemption
        if ($exemption->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($exemption->status !== 'pending_supervisor_approval') {
            abort(403, 'This exemption is not pending your approval.');
        }

        return view('supervisor.coursework_exemption.approve', compact('exemption'));
    }

    public function approveCourseworkExemption(Request $request, \App\Models\CourseworkExemption $exemption)
    {
        $supervisor = Auth::user()->supervisor;

        // Ensure the supervisor is assigned to this exemption
        if ($exemption->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($exemption->status !== 'pending_supervisor_approval') {
            abort(403, 'This exemption is not pending your approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'supervisor_remarks' => 'nullable|string|max:1000',
        ]);

        if ($request->action === 'approve') {
            $exemption->update([
                'status' => 'pending_dean_approval',
                'supervisor_approved_at' => now(),
                'supervisor_remarks' => $request->supervisor_remarks,
            ]);

            $message = 'Coursework exemption approved and forwarded to Dean.';
        } else {
            $exemption->update([
                'status' => 'rejected_by_supervisor',
                'supervisor_remarks' => $request->supervisor_remarks,
                'rejected_at' => now(),
            ]);

            $message = 'Coursework exemption rejected.';
        }

        return redirect()->route('staff.coursework_exemption.pending')->with('success', $message);
    }

    public function courseworkExemptionRequestForm(Scholar $scholar)
    {
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }
        return view('supervisor.coursework_exemption.request', compact('scholar'));
    }

    public function storeCourseworkExemptionRequest(Request $request)
    {
        $request->validate([
            'scholar_id' => 'required|exists:scholars,id',
            'rac_id' => 'required|exists:racs,id',
            'reason' => 'required|string',
            'minutes_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $scholar = Scholar::findOrFail($request->scholar_id);
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        $rac = RAC::findOrFail($request->rac_id);
        // Ensure RAC belongs to this scholar and supervisor
        if ($rac->scholar_id !== $scholar->id || $rac->supervisor_id !== Auth::user()->supervisor->id) {
            abort(403, 'Unauthorized action.');
        }

        $drc = $rac->department->drcs()->where('hod_id', $scholar->admission->department->hod_id)->first();
        if (! $drc) {
            abort(500, 'DRC not found for this department.');
        }

        $path = $request->file('minutes_file')->store('coursework_exemption_minutes', 'public');

        CourseworkExemption::create([
            'scholar_id' => $scholar->id,
            'supervisor_id' => Auth::user()->supervisor->id,
            'rac_id' => $rac->id,
            'drc_id' => $drc->id,
            'reason' => $request->reason,
            'minutes_file' => $path,
            'request_date' => now(),
            'status' => 'pending_dean_approval',
        ]);

        return redirect()->back()->with('success', 'Coursework exemption request submitted.');
    }


    public function listPendingProgressReports()
    {
        $supervisor = Auth::user()->supervisor;
        $pendingReports = \App\Models\ProgressReport::where('supervisor_id', $supervisor->id)
                                                    ->where('status', 'pending_supervisor_approval')
                                                    ->with(['scholar.user'])
                                                    ->get();

        return view('supervisor.progress_reports.pending', compact('pendingReports'));
    }

    public function showProgressReport($reportId)
    {
        \Illuminate\Support\Facades\Log::info('=== SHOW PROGRESS REPORT DEBUG ===');
        \Illuminate\Support\Facades\Log::info('Report ID Parameter: ' . $reportId);

        // Manually fetch the progress report
        $progressReport = \App\Models\ProgressReport::find($reportId);

        if (!$progressReport) {
            \Illuminate\Support\Facades\Log::error('Progress report not found with ID: ' . $reportId);
            abort(404, 'Progress report not found.');
        }

        \Illuminate\Support\Facades\Log::info('Report ID: ' . $progressReport->id);
        \Illuminate\Support\Facades\Log::info('Report Supervisor ID: ' . $progressReport->supervisor_id);
        \Illuminate\Support\Facades\Log::info('Report Status: ' . $progressReport->status);

        $authUser = Auth::user();
        \Illuminate\Support\Facades\Log::info('Auth User: ' . ($authUser ? $authUser->name : 'NULL'));

        if ($authUser && $authUser->supervisor) {
            \Illuminate\Support\Facades\Log::info('Auth User Supervisor ID: ' . $authUser->supervisor->id);
        } else {
            \Illuminate\Support\Facades\Log::error('Auth user has no supervisor relationship!');
        }

        if ($progressReport->supervisor_id !== Auth::user()->supervisor->id) {
            \Illuminate\Support\Facades\Log::error('SUPERVISOR ID MISMATCH: Report=' . $progressReport->supervisor_id . ', User=' . Auth::user()->supervisor->id);
            abort(403, 'Unauthorized action.');
        }

        \Illuminate\Support\Facades\Log::info('All checks passed - loading show view');
        $progressReport->load(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover']);

        return view('supervisor.progress_reports.show', compact('progressReport'));
    }

    public function approveProgressReportForm($reportId)
    {
        \Illuminate\Support\Facades\Log::info('=== APPROVE PROGRESS REPORT FORM DEBUG ===');
        \Illuminate\Support\Facades\Log::info('Report ID Parameter: ' . $reportId);

        // Manually fetch the progress report
        $progressReport = \App\Models\ProgressReport::find($reportId);

        if (!$progressReport) {
            \Illuminate\Support\Facades\Log::error('Progress report not found with ID: ' . $reportId);
            abort(404, 'Progress report not found.');
        }

        \Illuminate\Support\Facades\Log::info('Report ID: ' . $progressReport->id);
        \Illuminate\Support\Facades\Log::info('Report Supervisor ID: ' . $progressReport->supervisor_id);
        \Illuminate\Support\Facades\Log::info('Report Status: ' . $progressReport->status);

        $authUser = Auth::user();
        \Illuminate\Support\Facades\Log::info('Auth User: ' . ($authUser ? $authUser->name : 'NULL'));

        if ($authUser && $authUser->supervisor) {
            \Illuminate\Support\Facades\Log::info('Auth User Supervisor ID: ' . $authUser->supervisor->id);
        } else {
            \Illuminate\Support\Facades\Log::error('Auth user has no supervisor relationship!');
        }

        if ($progressReport->supervisor_id !== Auth::user()->supervisor->id) {
            \Illuminate\Support\Facades\Log::error('SUPERVISOR ID MISMATCH: Report=' . $progressReport->supervisor_id . ', User=' . Auth::user()->supervisor->id);
            abort(403, 'Unauthorized action.');
        }

        if ($progressReport->status !== 'pending_supervisor_approval') {
            \Illuminate\Support\Facades\Log::error('STATUS MISMATCH: Report status is ' . $progressReport->status . ', expected pending_supervisor_approval');
            abort(403, 'This progress report is not pending supervisor approval.');
        }

        \Illuminate\Support\Facades\Log::info('All checks passed - loading view');
        $progressReport->load(['scholar.user', 'supervisor.user', 'supervisorApprover', 'hodApprover']);

        return view('supervisor.progress_reports.approve', compact('progressReport'));
    }

    public function approveProgressReport(Request $request, $reportId)
    {
        \Illuminate\Support\Facades\Log::info('=== APPROVE PROGRESS REPORT POST DEBUG ===');
        \Illuminate\Support\Facades\Log::info('Report ID Parameter: ' . $reportId);

        // Manually fetch the progress report
        $progressReport = \App\Models\ProgressReport::find($reportId);

        if (!$progressReport) {
            \Illuminate\Support\Facades\Log::error('Progress report not found with ID: ' . $reportId);
            abort(404, 'Progress report not found.');
        }

        \Illuminate\Support\Facades\Log::info('Report ID: ' . $progressReport->id);
        \Illuminate\Support\Facades\Log::info('Report Supervisor ID: ' . $progressReport->supervisor_id);
        \Illuminate\Support\Facades\Log::info('Report Status: ' . $progressReport->status);

        $authUser = Auth::user();
        \Illuminate\Support\Facades\Log::info('Auth User: ' . ($authUser ? $authUser->name : 'NULL'));

        if ($authUser && $authUser->supervisor) {
            \Illuminate\Support\Facades\Log::info('Auth User Supervisor ID: ' . $authUser->supervisor->id);
        } else {
            \Illuminate\Support\Facades\Log::error('Auth user has no supervisor relationship!');
        }

        if ($progressReport->supervisor_id !== Auth::user()->supervisor->id) {
            \Illuminate\Support\Facades\Log::error('SUPERVISOR ID MISMATCH: Report=' . $progressReport->supervisor_id . ', User=' . Auth::user()->supervisor->id);
            abort(403, 'Unauthorized action.');
        }

        if ($progressReport->status !== 'pending_supervisor_approval') {
            \Illuminate\Support\Facades\Log::error('STATUS MISMATCH: Report status is ' . $progressReport->status . ', expected pending_supervisor_approval');
            abort(403, 'This progress report is not pending supervisor approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        \Illuminate\Support\Facades\Log::info('Action: ' . $request->action);
        \Illuminate\Support\Facades\Log::info('Remarks: ' . $request->remarks);

        if ($request->action === 'approve') {
            $progressReport->update([
                'status' => 'pending_hod_approval',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Progress report approved and forwarded to HOD.';
            \Illuminate\Support\Facades\Log::info('Progress report approved successfully');
        } else {
            $progressReport->update([
                'status' => 'rejected',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Progress report rejected.';
            \Illuminate\Support\Facades\Log::info('Progress report rejected');
        }

        return redirect()->route('staff.progress_reports.pending')->with('success', $message);
    }

    public function listPendingThesisSubmissions()
    {
        $supervisor = Auth::user()->supervisor;
        $pendingTheses = \App\Models\ThesisSubmission::where('supervisor_id', $supervisor->id)
                                                    ->where('status', 'pending_supervisor_approval')
                                                    ->with(['scholar.user'])
                                                    ->get();

        return view('supervisor.thesis.pending', compact('pendingTheses'));
    }

    public function approveThesisForm(\App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->supervisor_id !== Auth::user()->supervisor->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($thesis->status !== 'pending_supervisor_approval') {
            abort(403, 'This thesis is not pending supervisor approval.');
        }

        return view('supervisor.thesis.approve', compact('thesis'));
    }

    public function approveThesis(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->supervisor_id !== Auth::user()->supervisor->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($thesis->status !== 'pending_supervisor_approval') {
            abort(403, 'This thesis is not pending supervisor approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $thesis->update([
                'status' => 'pending_hod_approval',
                'supervisor_approver_id' => Auth::id(),
                'supervisor_approved_at' => now(),
                'supervisor_remarks' => $request->remarks,
            ]);

            $message = 'Thesis approved and forwarded to HOD.';
        } else {
            $thesis->update([
                'status' => 'rejected_by_supervisor',
                'supervisor_approver_id' => Auth::id(),
                'supervisor_approved_at' => now(),
                'supervisor_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $thesis->rejection_count + 1,
            ]);

            $message = 'Thesis rejected by supervisor.';
        }

        return redirect()->route('staff.thesis.pending')->with('success', $message);
    }

    private function getSupervisor()
    {
        $supervisor = Auth::user()->supervisor;
        if (!$supervisor) {
            abort(403, 'You are not assigned as a supervisor.');
        }
        return $supervisor;
    }

    private function isAssignedSupervisor(Scholar $scholar)
    {
        $supervisor = Auth::user()->supervisor;
        if (! $supervisor) {
            return false;
        }
        return SupervisorAssignment::where('scholar_id', $scholar->id)
            ->where('supervisor_id', $supervisor->id)
            ->where('status', 'assigned') // Assuming a status for assigned supervisors
            ->exists();
    }

    public function downloadSubmissionCertificate(\App\Models\ThesisSubmission $thesis)
    {
        $supervisor = Auth::user()->supervisor;

        // Check if the supervisor is assigned to this thesis
        if ($thesis->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized access to certificate.');
        }

        // Check if certificate exists
        if (!$thesis->submission_certificate_file) {
            return redirect()->back()->with('error', 'Certificate not yet generated. Please wait for DA approval.');
        }

        $certificateService = new \App\Services\CertificateGenerationService();
        $filePath = $certificateService->downloadCertificate($thesis);

        if (!$filePath) {
            return redirect()->back()->with('error', 'Certificate file not found.');
        }

        return response()->download($filePath, 'submission_certificate_' . $thesis->id . '.pdf');
    }

    public function listVivaExaminations()
    {
        $supervisor = Auth::user()->supervisor;
        $vivaExaminations = \App\Models\VivaExamination::where('supervisor_id', $supervisor->id)
            ->with(['scholar.user', 'thesisSubmission', 'externalExaminer', 'internalExaminer'])
            ->latest()
            ->get();

        return view('supervisor.viva.examinations', compact('vivaExaminations'));
    }

    /**
     * Show comprehensive list of all scholar submissions
     */
    public function listAllScholarSubmissions()
    {
        $supervisor = $this->getSupervisor();

        // Get all assigned scholars
        $scholars = $supervisor->assignedScholars()
            ->wherePivot('status', 'assigned')
            ->with(['user'])
            ->get();

        // Get all synopses for these scholars
        $synopses = \App\Models\Synopsis::where(function ($query) use ($supervisor) {
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
        ->latest()
        ->get();

        // Get all coursework exemptions for these scholars
        $courseworkExemptions = \App\Models\CourseworkExemption::where('supervisor_id', $supervisor->id)
            ->with(['scholar.user', 'rac'])
            ->latest()
            ->get();

        // Get all progress reports for these scholars
        $progressReports = \App\Models\ProgressReport::where('supervisor_id', $supervisor->id)
            ->with(['scholar.user'])
            ->latest()
            ->get();

        // Get all thesis submissions for these scholars
        $thesisSubmissions = \App\Models\ThesisSubmission::where('supervisor_id', $supervisor->id)
            ->with(['scholar.user'])
            ->latest()
            ->get();

        return view('supervisor.scholars.all_submissions', compact(
            'scholars',
            'synopses',
            'courseworkExemptions',
            'progressReports',
            'thesisSubmissions'
        ));
    }

    public function showVivaReportForm(\App\Models\VivaExamination $vivaExamination)
    {
        $supervisor = Auth::user()->supervisor;

        // Check if the supervisor is assigned to this viva examination
        if ($vivaExamination->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized access to viva examination.');
        }

        // Check if viva examination is completed
        if (!$vivaExamination->isCompleted()) {
            return redirect()->back()->with('error', 'Viva examination must be completed before submitting report.');
        }

        // Check if report already exists
        $vivaReport = $vivaExamination->vivaReport;
        if ($vivaReport && $vivaReport->isCompleted()) {
            return redirect()->back()->with('info', 'Viva report already submitted.');
        }

        return view('supervisor.viva.report_form', compact('vivaExamination', 'vivaReport'));
    }

    public function storeVivaReport(Request $request, \App\Models\VivaExamination $vivaExamination)
    {
        $supervisor = Auth::user()->supervisor;

        // Check if the supervisor is assigned to this viva examination
        if ($vivaExamination->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized access to viva examination.');
        }

        // Check if viva examination is completed
        if (!$vivaExamination->isCompleted()) {
            return redirect()->back()->with('error', 'Viva examination must be completed before submitting report.');
        }

        $request->validate([
            'research_topic' => 'required|string|max:500',
            'external_examiner_name' => 'required|string|max:255',
            'viva_date' => 'required|date',
            'viva_time' => 'required',
            'venue' => 'required|string|max:255',
            'faculty_present' => 'nullable|string|max:1000',
            'viva_successful' => 'required|boolean',
            'viva_outcome_notes' => 'required|string|max:1000',
            'additional_remarks' => 'nullable|string|max:1000',
        ]);

        // Create or update viva report
        $vivaReport = $vivaExamination->vivaReport ?? new \App\Models\VivaReport();
        $vivaReport->fill([
            'viva_examination_id' => $vivaExamination->id,
            'thesis_submission_id' => $vivaExamination->thesis_submission_id,
            'scholar_id' => $vivaExamination->scholar_id,
            'supervisor_id' => $vivaExamination->supervisor_id,
            'research_topic' => $request->research_topic,
            'external_examiner_name' => $request->external_examiner_name,
            'viva_date' => $request->viva_date,
            'viva_time' => $request->viva_time,
            'venue' => $request->venue,
            'faculty_present' => $request->faculty_present,
            'viva_successful' => $request->boolean('viva_successful'),
            'viva_outcome_notes' => $request->viva_outcome_notes,
            'additional_remarks' => $request->additional_remarks,
        ]);
        $vivaReport->save();

        // Generate PDF report
        $reportService = new \App\Services\VivaReportGenerationService();
        $reportService->generateVivaReport($vivaReport);

        // If viva was successful, update viva examination for degree recommendation
        if ($request->boolean('viva_successful')) {
            $vivaExamination->update([
                'result' => 'pass',
                'recommended_for_degree' => true,
                'recommendation_notes' => $request->viva_outcome_notes,
            ]);

            // Generate office note for degree award recommendation
            $officeNoteService = new \App\Services\OfficeNoteGenerationService();
            $officeNoteService->generateOfficeNote($vivaExamination);
        } else {
            $vivaExamination->update([
                'result' => 'fail',
                'recommended_for_degree' => false,
            ]);
        }

        return redirect()->route('staff.viva.examinations')->with('success', 'Viva report submitted successfully.');
    }

    public function downloadVivaReport(\App\Models\VivaReport $vivaReport)
    {
        $supervisor = Auth::user()->supervisor;

        // Check if the supervisor is assigned to this viva report
        if ($vivaReport->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized access to viva report.');
        }

        // Check if report exists
        if (!$vivaReport->report_file) {
            return redirect()->back()->with('error', 'Viva report not yet generated.');
        }

        $reportService = new \App\Services\VivaReportGenerationService();
        $filePath = $reportService->downloadVivaReport($vivaReport);

        if (!$filePath) {
            return redirect()->back()->with('error', 'Viva report file not found.');
        }

        return response()->download($filePath, 'viva_report_' . $vivaReport->id . '.pdf');
    }

    public function downloadOfficeNote(\App\Models\VivaExamination $vivaExamination)
    {
        $supervisor = Auth::user()->supervisor;

        // Check if the supervisor is assigned to this viva examination
        if ($vivaExamination->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized access to office note.');
        }

        // Check if office note exists
        if (!$vivaExamination->office_note_file) {
            return redirect()->back()->with('error', 'Office note not yet generated.');
        }

        $officeNoteService = new \App\Services\OfficeNoteGenerationService();
        $filePath = $officeNoteService->downloadOfficeNote($vivaExamination);

        if (!$filePath) {
            return redirect()->back()->with('error', 'Office note file not found.');
        }

        return response()->download($filePath, 'office_note_' . $vivaExamination->id . '.pdf');
    }
}
