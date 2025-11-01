<?php

namespace App\Http\Controllers;

use App\Models\SupervisorCapacityIncreaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HVCController extends Controller
{
    /**
     * List pending capacity increase requests for HVC approval
     */
    public function listPendingCapacityRequests()
    {
        $requests = SupervisorCapacityIncreaseRequest::where('status', 'pending_hvc')
            ->with(['supervisor.user', 'supervisor.department', 'daApprover', 'soApprover', 'arApprover', 'drApprover'])
            ->latest()
            ->get();

        return view('hvc.capacity_requests.pending', compact('requests'));
    }

    /**
     * Show the approval form for a specific request
     */
    public function showApprovalForm(SupervisorCapacityIncreaseRequest $request)
    {
        if ($request->status !== 'pending_hvc') {
            abort(403, 'This request is not pending HVC approval.');
        }

        $request->load(['supervisor.user', 'supervisor.department', 'daApprover', 'soApprover', 'arApprover', 'drApprover']);

        return view('hvc.capacity_requests.approve', compact('request'));
    }

    /**
     * Process the approval/rejection
     */
    public function processApproval(Request $request, SupervisorCapacityIncreaseRequest $capacityRequest)
    {
        if ($capacityRequest->status !== 'pending_hvc') {
            abort(403, 'This request is not pending HVC approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $capacityRequest->update([
                'status' => 'approved',
                'hvc_approver_id' => Auth::id(),
                'hvc_approved_at' => now(),
                'hvc_remarks' => $request->remarks,
            ]);

            $message = 'Request approved! Supervisor capacity has been increased.';
        } else {
            $capacityRequest->update([
                'status' => 'rejected',
                'hvc_approver_id' => Auth::id(),
                'hvc_approved_at' => now(),
                'hvc_remarks' => $request->remarks,
            ]);

            $message = 'Request rejected.';
        }

        return redirect()->route('hvc.capacity_requests.pending')->with('success', $message);
    }

    /**
     * List thesis submissions pending HVC approval
     */
    public function listPendingThesisApprovals()
    {
        $theses = \App\Models\ThesisSubmission::where('status', 'pending_hvc_approval')
            ->with(['scholar.user', 'supervisor.user', 'hodApprover', 'daApprover', 'soApprover', 'arApprover', 'drApprover'])
            ->latest()
            ->get();

        return view('hvc.thesis.pending_approval', compact('theses'));
    }

    /**
     * Show thesis approval form
     */
    public function approveThesisForm(\App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'pending_hvc_approval') {
            abort(403, 'This thesis is not pending HVC approval.');
        }

        $thesis->load(['scholar.user', 'supervisor.user', 'hodApprover', 'daApprover', 'soApprover', 'arApprover', 'drApprover']);

        return view('hvc.thesis.approve', compact('thesis'));
    }

    /**
     * Process thesis approval/rejection
     */
    public function approveThesis(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'pending_hvc_approval') {
            abort(403, 'This thesis is not pending HVC approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $thesis->update([
                'status' => 'approved',
                'hvc_approver_id' => Auth::id(),
                'hvc_approved_at' => now(),
                'hvc_remarks' => $request->remarks,
            ]);

            $message = 'Thesis approved by HVC!';
        } else {
            $thesis->update([
                'status' => 'rejected',
                'hvc_approver_id' => Auth::id(),
                'hvc_approved_at' => now(),
                'hvc_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
            ]);

            $message = 'Thesis rejected by HVC.';
        }

        return redirect()->route('hvc.thesis.pending_approval')->with('success', $message);
    }

    /**
     * List approved thesis submissions ready for evaluation
     */
    public function listApprovedThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::where('status', 'approved')
            ->whereDoesntHave('thesisEvaluation')
            ->with(['scholar.user', 'supervisor.user'])
            ->latest()
            ->get();

        return view('hvc.thesis.approved', compact('theses'));
    }

    /**
     * Show form to assign expert for thesis evaluation
     */
    public function assignExpertForm(\App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'approved') {
            abort(403, 'This thesis is not approved for evaluation.');
        }

        if ($thesis->thesisEvaluation) {
            abort(403, 'Expert has already been assigned for this thesis.');
        }

        // Get available experts (users with expert role)
        $experts = \App\Models\User::where('user_type', 'expert')->get();

        return view('hvc.thesis.assign_expert', compact('thesis', 'experts'));
    }

    /**
     * Assign expert for thesis evaluation
     */
    public function assignExpert(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'approved') {
            abort(403, 'This thesis is not approved for evaluation.');
        }

        if ($thesis->thesisEvaluation) {
            abort(403, 'Expert has already been assigned for this thesis.');
        }

        $request->validate([
            'expert_id' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
        ]);

        \App\Models\ThesisEvaluation::create([
            'thesis_submission_id' => $thesis->id,
            'expert_id' => $request->expert_id,
            'supervisor_id' => $thesis->supervisor_id,
            'assigned_date' => now(),
            'due_date' => $request->due_date,
            'status' => 'assigned',
            'hvc_selected_expert_id' => Auth::id(),
        ]);

        return redirect()->route('hvc.thesis.approved')->with('success', 'Expert assigned successfully for thesis evaluation.');
    }

    /**
     * List thesis evaluations in progress
     */
    public function listThesisEvaluations()
    {
        $evaluations = \App\Models\ThesisEvaluation::with(['thesisSubmission.scholar.user', 'expert', 'supervisor.user'])
            ->whereIn('status', ['assigned', 'in_progress', 'submitted'])
            ->latest()
            ->get();

        return view('hvc.thesis.evaluations', compact('evaluations'));
    }

    /**
     * Review expert evaluation report
     */
    public function reviewEvaluationForm(\App\Models\ThesisEvaluation $evaluation)
    {
        if ($evaluation->status !== 'submitted') {
            abort(403, 'This evaluation is not ready for review.');
        }

        return view('hvc.thesis.review_evaluation', compact('evaluation'));
    }

    /**
     * Process evaluation review and decision
     */
    public function processEvaluationReview(Request $request, \App\Models\ThesisEvaluation $evaluation)
    {
        if ($evaluation->status !== 'submitted') {
            abort(403, 'This evaluation is not ready for review.');
        }

        $request->validate([
            'decision' => 'required|in:approved_for_viva,revision_required,rejected',
            'remarks' => 'required|string|max:500',
        ]);

        $evaluation->update([
            'status' => 'reviewed',
            'decision' => $request->decision,
        ]);

        // Update thesis status based on decision
        if ($request->decision === 'approved_for_viva') {
            $evaluation->thesisSubmission->update(['status' => 'approved_for_viva']);
        } else {
            $evaluation->thesisSubmission->update(['status' => 'evaluation_completed']);
        }

        return redirect()->route('hvc.thesis.evaluations')->with('success', 'Evaluation reviewed successfully.');
    }

    /**
     * List thesis approved for viva
     */
    public function listVivaCandidates()
    {
        $candidates = \App\Models\ThesisSubmission::where('status', 'approved_for_viva')
            ->whereDoesntHave('vivaProcess')
            ->with(['scholar.user', 'supervisor.user', 'thesisEvaluation.expert'])
            ->latest()
            ->get();

        return view('hvc.viva.candidates', compact('candidates'));
    }

    /**
     * Schedule viva for thesis
     */
    public function scheduleVivaForm(\App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'approved_for_viva') {
            abort(403, 'This thesis is not approved for viva.');
        }

        if ($thesis->vivaProcess) {
            abort(403, 'Viva has already been scheduled for this thesis.');
        }

        // Get available experts for viva
        $experts = \App\Models\User::where('user_type', 'expert')->get();

        return view('hvc.viva.schedule', compact('thesis', 'experts'));
    }

    /**
     * Schedule viva process
     */
    public function scheduleViva(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        if ($thesis->status !== 'approved_for_viva') {
            abort(403, 'This thesis is not approved for viva.');
        }

        if ($thesis->vivaProcess) {
            abort(403, 'Viva has already been scheduled for this thesis.');
        }

        $request->validate([
            'expert_id' => 'required|exists:users,id',
            'viva_date' => 'required|date|after:today',
        ]);

        \App\Models\VivaProcess::create([
            'thesis_submission_id' => $thesis->id,
            'hvc_assigned_expert_id' => $request->expert_id,
            'hod_id' => $thesis->hod_id,
            'supervisor_id' => $thesis->supervisor_id,
            'viva_date' => $request->viva_date,
            'status' => 'scheduled',
        ]);

        return redirect()->route('hvc.viva.candidates')->with('success', 'Viva scheduled successfully.');
    }

    /**
     * List scheduled viva processes
     */
    public function listScheduledVivas()
    {
        $scheduledVivas = \App\Models\VivaProcess::with(['thesisSubmission.scholar.user', 'hvcAssignedExpert', 'supervisor.user'])
            ->whereIn('status', ['scheduled', 'completed'])
            ->latest()
            ->get();

        return view('hvc.viva.scheduled', compact('scheduledVivas'));
    }

    /**
     * Review viva report
     */
    public function reviewVivaReportForm(\App\Models\VivaProcess $viva)
    {
        if ($viva->status !== 'completed') {
            abort(403, 'This viva is not completed yet.');
        }

        return view('hvc.viva.review_report', compact('viva'));
    }

    /**
     * Process viva report review
     */
    public function processVivaReview(Request $request, \App\Models\VivaProcess $viva)
    {
        if ($viva->status !== 'completed') {
            abort(403, 'This viva is not completed yet.');
        }

        $request->validate([
            'decision' => 'required|in:approved,revision_required,rejected',
            'remarks' => 'required|string|max:500',
        ]);

        $viva->update([
            'decision' => $request->decision,
        ]);

        // Update thesis status based on viva decision
        if ($request->decision === 'approved') {
            $viva->thesisSubmission->update(['status' => 'final_approved']);

            // Auto-generate registration letter when viva is approved
            $registrationLetterService = new \App\Services\RegistrationLetterGenerationService();
            $registrationLetterService->generateRegistrationLetter($viva->thesisSubmission->scholar);
        } else {
            $viva->thesisSubmission->update(['status' => 'viva_completed']);
        }

        return redirect()->route('hvc.viva.scheduled')->with('success', 'Viva report reviewed successfully.');
    }

    /**
     * List pending synopses for HVC approval
     */
    public function listPendingSynopses()
    {
        $synopses = \App\Models\Synopsis::where('status', 'pending_hvc_approval')
            ->with(['scholar.user', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user', 'supervisorApprover', 'hodApprover', 'daApprover', 'soApprover', 'arApprover', 'drApprover'])
            ->latest()
            ->get();

        return view('hvc.synopses.pending', compact('synopses'));
    }

    /**
     * Show the synopsis approval form for a specific synopsis
     */
    public function showSynopsisApprovalForm(\App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_hvc_approval') {
            abort(403, 'This synopsis is not pending HVC approval.');
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
            'arApprover',
            'drApprover'
        ]);

        // Get available roles for reassignment
        $availableRoles = \App\Helpers\WorkflowHelper::getAvailableReassignmentRoles($synopsis->status, $synopsis);

        return view('hvc.synopses.approve', compact('synopsis', 'availableRoles'));
    }

    /**
     * Process the synopsis approval/rejection
     */
    public function processSynopsisApproval(Request $request, \App\Models\Synopsis $synopsis)
    {
        if ($synopsis->status !== 'pending_hvc_approval') {
            abort(403, 'This synopsis is not pending HVC approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'reassigned_to_role' => 'nullable|string|in:supervisor,hod,da,so,ar,dr,hvc',
            'reassignment_reason' => 'nullable|string|max:1000',
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        if ($request->action === 'approve') {
            $synopsis->update([
                'hvc_remarks' => $request->remarks,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'hvc_approve', Auth::user());
            $message = 'Synopsis approved by HVC!';
        } else {
            $synopsis->update([
                'hvc_remarks' => $request->remarks,
                'reassignment_reason' => $request->reassignment_reason,
            ]);

            // Sync workflow with reassignment
            $reassignedRole = $request->reassigned_to_role;
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'hvc_reject', Auth::user(), $reassignedRole);

            if ($reassignedRole) {
                $roleLabels = [
                    'supervisor' => 'Supervisor',
                    'hod' => 'HOD',
                    'da' => 'DA',
                    'so' => 'Section Officer',
                    'ar' => 'Assistant Registrar',
                    'dr' => 'Deputy Registrar',
                ];
                $message = 'Synopsis rejected and reassigned to ' . ($roleLabels[$reassignedRole] ?? $reassignedRole) . ' for corrections.';
            } else {
                $message = 'Synopsis rejected by HVC.';
            }
        }

        return redirect()->route('hvc.synopses.pending')->with('success', $message);
    }

    /**
     * List pending progress reports
     */
    public function listPendingProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_hvc_approval')
            ->latest()
            ->paginate(10);

        return view('hvc.progress_reports.pending', compact('reports'));
    }

    /**
     * List all progress reports
     */
    public function listAllProgressReports()
    {
        $reports = \App\Models\ProgressReport::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('hvc.progress_reports.all', compact('reports'));
    }

    /**
     * List pending thesis submissions
     */
    public function listPendingThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_hvc_approval')
            ->latest()
            ->paginate(10);

        return view('hvc.thesis.pending', compact('theses'));
    }

    /**
     * List all thesis submissions
     */
    public function listAllThesisSubmissions()
    {
        $theses = \App\Models\ThesisSubmission::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('hvc.thesis.all', compact('theses'));
    }

    /**
     * List pending coursework exemptions
     */
    public function listPendingCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->where('status', 'pending_hvc_approval')
            ->latest()
            ->paginate(10);

        return view('hvc.coursework_exemptions.pending', compact('exemptions'));
    }

    /**
     * List all coursework exemptions
     */
    public function listAllCourseworkExemptions()
    {
        $exemptions = \App\Models\CourseworkExemption::with(['scholar.user', 'supervisor.user', 'scholar.admission.department'])
            ->latest()
            ->get();

        return view('hvc.coursework_exemptions.all', compact('exemptions'));
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

        return view('hvc.scholars.all_submissions', compact('scholars', 'synopses', 'progressReports', 'thesisSubmissions', 'courseworkExemptions'));
    }

    /**
     * View scholar details
     */
    public function viewScholarDetails(\App\Models\Scholar $scholar)
    {
        $scholar->load(['user', 'admission.department', 'supervisorAssignments.supervisor.user', 'synopses']);

        return view('hvc.scholars.show', compact('scholar'));
    }
}
