<?php

namespace App\Http\Controllers;

use App\Models\CourseworkExemption;
use App\Models\RAC;
use App\Models\RACCommitteeSubmission;
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
        
        // Check which scholars can have progress reports submitted by supervisor
        $scholarsWithSubmissionInfo = $scholars->map(function($scholar) {
            $canSubmitInfo = $this->canSubmitProgressReportForScholar($scholar);
            return [
                'scholar' => $scholar,
                'can_submit' => $canSubmitInfo['can_submit'],
                'report_period' => $canSubmitInfo['report_period'],
            ];
        });
        
        return view('supervisor.scholars.list', compact('scholars', 'scholarsWithSubmissionInfo'));
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

        // Check if supervisor can submit progress report for this scholar
        $canSubmitInfo = $this->canSubmitProgressReportForScholar($scholar);

        return view('supervisor.scholars.show', compact('scholar', 'canSubmitInfo'));
    }

    public function reviewScholarForm(Scholar $scholar)
    {
        // Ensure the supervisor is assigned to this scholar
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        // Load synopsis if it exists
        $synopsis = $scholar->synopsis;

        return view('supervisor.scholars.review', compact('scholar', 'synopsis'));
    }

    public function reviewScholar(Request $request, Scholar $scholar)
    {
        // Ensure the supervisor is assigned to this scholar
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|in:approve_synopsis,reject_synopsis',
            'research_topic' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
            'rac_minutes_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'rac_meeting_date' => 'required|date',
            'fee_receipt_verified' => $scholar->fee_receipt_file ? 'required|accepted' : 'nullable',
        ]);

        if ($request->action === 'verify_data') {
            // Update scholar data
            $scholar->update($request->only(['date_of_confirmation', 'enrollment_number', 'research_area']));
            return $this->successResponse('Scholar data verified successfully.', 'staff.scholars.list');
        }

        // Handle synopsis approval/rejection
        $synopsis = $scholar->synopses->first();
        if (!$synopsis) {
            return redirect()->back()->withErrors(['synopsis' => 'No synopsis found for this scholar.']);
        }
        if ($synopsis->status !== 'pending_supervisor_approval') {
            return redirect()->back()->withErrors(['synopsis' => 'This synopsis is not pending supervisor approval.']);
        }

        // Upload RAC minutes file (required for approve/reject synopsis)
        $racMinutesPath = null;
        if ($request->hasFile('rac_minutes_file')) {
            $racMinutesPath = $request->file('rac_minutes_file')->store('rac_minutes', 'public');
        }

        // For approve/reject actions, RAC minutes and date are required
        if (in_array($request->action, ['approve_synopsis', 'reject_synopsis'])) {
            if (!$racMinutesPath || !$request->rac_meeting_date) {
                return redirect()->back()->withErrors([
                    'rac_minutes_file' => 'RAC minutes file is required for synopsis approval/rejection.',
                    'rac_meeting_date' => 'RAC meeting date is required for synopsis approval/rejection.'
                ]);
            }
        }

        $updateData = [
            'supervisor_approved_at' => now(),
            'supervisor_remarks' => $request->remarks,
        ];

        // Always include RAC minutes data for approve/reject actions
        if (in_array($request->action, ['approve_synopsis', 'reject_synopsis'])) {
            $updateData['rac_minutes_file'] = $racMinutesPath;
            $updateData['rac_meeting_date'] = $request->rac_meeting_date;
        }

        if ($request->action === 'approve_synopsis') {
            $updateData['status'] = 'pending_hod_approval';
            $updateData['supervisor_approver_id'] = Auth::id();
            $updateData['supervisor_approved_at'] = now();
            $synopsis->update($updateData);

            // Update research topic if provided
            if ($request->research_topic) {
                $scholar->update(['research_topic' => $request->research_topic]);
            }

            return $this->successResponse('Synopsis approved successfully.', 'staff.scholars.list');
        } elseif ($request->action === 'reject_synopsis') {
            $updateData['status'] = 'supervisor_rejected';
            $updateData['rejected_by'] = Auth::id();
            $updateData['rejected_at'] = now();
            $updateData['rejection_reason'] = $request->remarks;
            $synopsis->update($updateData);

            return $this->successResponse('Synopsis rejected successfully.', 'staff.scholars.list');
        }

        return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
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
            'rac_minutes_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'rac_meeting_date' => 'required|date',
        ]);

        // Upload RAC minutes file
        $racMinutesPath = $request->file('rac_minutes_file')->store('rac_minutes', 'public');

        $updateData = [
            'supervisor_approved_at' => now(),
            'supervisor_remarks' => $request->supervisor_remarks,
            'minutes_file' => $racMinutesPath,
            'rac_meeting_date' => $request->rac_meeting_date,
        ];

        if ($request->action === 'approve') {
            $updateData['status'] = 'pending_dean_approval';
            $message = 'Coursework exemption approved and forwarded to Dean.';
        } else {
            $updateData['status'] = 'rejected_by_supervisor';
            $updateData['rejected_at'] = now();
            $message = 'Coursework exemption rejected.';
        }

        $exemption->update($updateData);

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
            'rac_minutes_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'rac_meeting_date' => 'required|date',
        ]);

        \Illuminate\Support\Facades\Log::info('Action: ' . $request->action);
        \Illuminate\Support\Facades\Log::info('Remarks: ' . $request->remarks);

        // Upload RAC minutes file
        $racMinutesPath = $request->file('rac_minutes_file')->store('rac_minutes', 'public');

        $updateData = [
            'supervisor_approver_id' => Auth::id(),
            'supervisor_approved_at' => now(),
            'supervisor_remarks' => $request->remarks,
            'rac_minutes_file' => $racMinutesPath,
            'rac_meeting_date' => $request->rac_meeting_date,
        ];

        if ($request->action === 'approve') {
            $updateData['status'] = 'pending_hod_approval';
            $message = 'Progress report approved and forwarded to HOD.';
            \Illuminate\Support\Facades\Log::info('Progress report approved successfully');
        } else {
            $updateData['status'] = 'rejected';
            $updateData['rejected_by'] = Auth::id();
            $updateData['rejected_at'] = now();
            $updateData['rejection_reason'] = $request->remarks;
            $message = 'Progress report rejected.';
            \Illuminate\Support\Facades\Log::info('Progress report rejected');
        }

        $progressReport->update($updateData);

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
            'rac_minutes_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'rac_meeting_date' => 'required|date',
        ]);

        // Upload RAC minutes file
        $racMinutesPath = $request->file('rac_minutes_file')->store('rac_minutes', 'public');

        $updateData = [
            'supervisor_approver_id' => Auth::id(),
            'supervisor_approved_at' => now(),
            'supervisor_remarks' => $request->remarks,
            'rac_minutes_file' => $racMinutesPath,
            'rac_meeting_date' => $request->rac_meeting_date,
        ];

        if ($request->action === 'approve') {
            $updateData['status'] = 'pending_hod_approval';
            $message = 'Thesis approved and forwarded to HOD.';
        } else {
            $updateData['status'] = 'rejected_by_supervisor';
            $updateData['rejected_by'] = Auth::id();
            $updateData['rejected_at'] = now();
            $updateData['rejection_reason'] = $request->remarks;
            $updateData['rejection_count'] = $thesis->rejection_count + 1;
            $message = 'Thesis rejected by supervisor.';
        }

        $thesis->update($updateData);

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

    /**
     * Show form to submit/update RAC committee members for a scholar
     */
    public function submitRACCommitteeForm(Scholar $scholar)
    {
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        // Get existing submission if any
        $existingSubmission = RACCommitteeSubmission::where('scholar_id', $scholar->id)
            ->where('supervisor_id', Auth::user()->supervisor->id)
            ->with('supervisor.user', 'supervisor.department')
            ->latest()
            ->first();

        return view('supervisor.rac_committee.submit', compact('scholar', 'existingSubmission'));
    }

    /**
     * Store or update RAC committee members submission
     */
    public function storeRACCommitteeSubmission(Request $request, Scholar $scholar)
    {
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'member1_name' => 'required|string|max:255',
            'member1_designation' => 'required|string|max:255',
            'member1_department' => 'required|string|max:255',
            'member2_name' => 'required|string|max:255',
            'member2_designation' => 'required|string|max:255',
            'member2_department' => 'required|string|max:255',
        ]);

        // Check if there's a pending submission
        $existingSubmission = RACCommitteeSubmission::where('scholar_id', $scholar->id)
            ->where('supervisor_id', Auth::user()->supervisor->id)
            ->where('status', 'pending_hod_approval')
            ->first();

        if ($existingSubmission) {
            // Update existing pending submission
            $existingSubmission->update([
                'member1_name' => $request->member1_name,
                'member1_designation' => $request->member1_designation,
                'member1_department' => $request->member1_department,
                'member2_name' => $request->member2_name,
                'member2_designation' => $request->member2_designation,
                'member2_department' => $request->member2_department,
            ]);

            return redirect()->route('staff.scholars.show', $scholar)
                ->with('success', 'RAC committee members updated successfully. Waiting for HOD approval.');
        }

        // Create new submission
        RACCommitteeSubmission::create([
            'scholar_id' => $scholar->id,
            'supervisor_id' => Auth::user()->supervisor->id,
            'member1_name' => $request->member1_name,
            'member1_designation' => $request->member1_designation,
            'member1_department' => $request->member1_department,
            'member2_name' => $request->member2_name,
            'member2_designation' => $request->member2_designation,
            'member2_department' => $request->member2_department,
            'status' => 'pending_hod_approval',
        ]);

        return redirect()->route('staff.scholars.show', $scholar)
            ->with('success', 'RAC committee members submitted successfully. Waiting for HOD approval.');
    }

    /**
     * Check if supervisor can submit progress report for scholar
     * Only allowed for current month and next month, and only if scholar hasn't submitted
     */
    public function canSubmitProgressReportForScholar(Scholar $scholar): array
    {
        $currentMonth = (int) date('n');
        $currentMonthName = date('F');
        $allowedMonths = \App\Helpers\ProgressReportHelper::getAllowedMonths();
        
        $nextMonth = $currentMonth + 1;
        if ($nextMonth > 12) {
            $nextMonth = 1;
        }
        
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $nextMonthName = $monthNames[$nextMonth];
        
        $canSubmit = false;
        $reportPeriod = null;
        
        // Check current month
        if (in_array($currentMonth, $allowedMonths)) {
            $existingReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
                ->where('report_period', $currentMonthName)
                ->where('status', '!=', 'rejected')
                ->first();
            
            if (!$existingReport) {
                $canSubmit = true;
                $reportPeriod = $currentMonthName;
            }
        }
        
        // Check next month if current month not allowed or already submitted
        if (!$canSubmit && in_array($nextMonth, $allowedMonths)) {
            $existingReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
                ->where('report_period', $nextMonthName)
                ->where('status', '!=', 'rejected')
                ->first();
            
            if (!$existingReport) {
                $canSubmit = true;
                $reportPeriod = $nextMonthName;
            }
        }
        
        return [
            'can_submit' => $canSubmit,
            'report_period' => $reportPeriod,
            'current_month_allowed' => in_array($currentMonth, $allowedMonths),
            'next_month_allowed' => in_array($nextMonth, $allowedMonths),
        ];
    }

    /**
     * Show form to submit progress report for a scholar
     */
    public function submitProgressReportForScholarForm(Scholar $scholar)
    {
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        $canSubmitInfo = $this->canSubmitProgressReportForScholar($scholar);
        
        if (!$canSubmitInfo['can_submit']) {
            return redirect()->route('staff.scholars.show', $scholar)
                ->with('error', 'You can only submit progress reports for the current month or next month, and only if the scholar has not already submitted.');
        }

        return view('supervisor.progress_report.submit', [
            'scholar' => $scholar,
            'reportPeriod' => $canSubmitInfo['report_period'],
        ]);
    }

    /**
     * Store progress report submitted by supervisor for scholar
     */
    public function storeProgressReportForScholar(Request $request, Scholar $scholar)
    {
        if (! $this->isAssignedSupervisor($scholar)) {
            abort(403, 'Unauthorized action.');
        }

        $canSubmitInfo = $this->canSubmitProgressReportForScholar($scholar);
        
        if (!$canSubmitInfo['can_submit']) {
            return redirect()->route('staff.scholars.show', $scholar)
                ->with('error', 'You can only submit progress reports for the current month or next month, and only if the scholar has not already submitted.');
        }

        $allowedMonths = \App\Helpers\ProgressReportHelper::getAllowedMonthNames();
        $allowedMonthValues = array_values($allowedMonths);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'rac_minutes_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'rac_meeting_date' => 'required|date',
            'report_period' => 'required|string|in:' . implode(',', $allowedMonthValues),
        ]);

        // Double-check that scholar hasn't submitted for this period
        $existingReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
            ->where('report_period', $request->report_period)
            ->where('status', '!=', 'rejected')
            ->first();

        if ($existingReport) {
            return redirect()->back()->withErrors([
                'report_period' => 'A progress report for ' . $request->report_period . ' has already been submitted by the scholar.'
            ])->withInput();
        }

        // Upload RAC minutes file
        $racMinutesPath = $request->file('rac_minutes_file')->store('rac_minutes', 'public');

        $supervisor = Auth::user()->supervisor;
        $hod = $scholar->admission->department->hod;

        $progressReportData = [
            'scholar_id' => $scholar->id,
            'supervisor_id' => $supervisor->id,
            'hod_id' => $hod->id,
            'report_file' => '', // Empty since supervisor doesn't upload report file
            'submission_date' => now(),
            'report_period' => $request->report_period,
            'supervisor_remarks' => $request->remarks,
            'rac_minutes_file' => $racMinutesPath,
            'rac_meeting_date' => $request->rac_meeting_date,
            'supervisor_approver_id' => Auth::id(),
            'supervisor_approved_at' => now(),
        ];

        // Handle approve/reject action
        if ($request->action === 'approve') {
            $progressReportData['status'] = 'pending_hod_approval';
            $message = 'Progress report submitted and approved. Forwarded to HOD for approval.';
        } else {
            $progressReportData['status'] = 'rejected';
            $progressReportData['rejected_by'] = Auth::id();
            $progressReportData['rejected_at'] = now();
            $progressReportData['rejection_reason'] = $request->remarks;
            $message = 'Progress report submitted but marked as rejected.';
        }

        \App\Models\ProgressReport::create($progressReportData);

        return redirect()->route('staff.scholars.show', $scholar)
            ->with('success', $message);
    }
}
