<?php

namespace App\Http\Controllers;

use App\Models\ThesisSubmission;
use App\Models\ThesisEvaluation;
use App\Models\VivaProcess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpertEvaluationController extends Controller
{
    /**
     * List approved thesis submissions for expert selection (HVC)
     */
    public function listApprovedThesisSubmissions()
    {
        // Check if user is HVC
        if (Auth::user()->user_type !== 'hvc') {
            abort(403, 'Only Head of Verification Committee can manage expert selection.');
        }

        $approvedTheses = ThesisSubmission::where('status', 'approved')
            ->whereDoesntHave('thesisEvaluation')
            ->with(['scholar.user', 'supervisor.user', 'hod'])
            ->get();

        return view('hvc.thesis.approved', compact('approvedTheses'));
    }

    /**
     * Show form to select experts for thesis evaluation
     */
    public function selectExpertsForm(ThesisSubmission $thesis)
    {
        // Check if user is HVC
        if (Auth::user()->user_type !== 'hvc') {
            abort(403, 'Only Head of Verification Committee can select experts.');
        }

        if ($thesis->status !== 'approved') {
            abort(403, 'This thesis is not approved for evaluation.');
        }

        if ($thesis->thesisEvaluation) {
            abort(403, 'Experts have already been selected for this thesis.');
        }

        // Load relationships
        $thesis->load(['scholar.user', 'scholar.currentSupervisor.supervisor.user', 'supervisor.user', 'scholar.admission.department']);

        // Get supervisor-suggested experts
        $suggestedExperts = \App\Models\ExpertSuggestion::where('thesis_submission_id', $thesis->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hvc.thesis.select_experts', compact('thesis', 'suggestedExperts'));
    }

    /**
     * Store expert selection (HVC selects 4 experts and prioritizes them)
     */
    public function selectExperts(Request $request, ThesisSubmission $thesis)
    {
        // Check if user is HVC
        if (Auth::user()->user_type !== 'hvc') {
            abort(403, 'Only Head of Verification Committee can select experts.');
        }

        if ($thesis->status !== 'approved') {
            abort(403, 'This thesis is not approved for evaluation.');
        }

        $request->validate([
            'experts' => 'required|array|size:4',
            'experts.*.expert_suggestion_id' => 'required|exists:expert_suggestions,id',
            'experts.*.priority' => 'required|integer|min:1|max:4',
        ]);

        // Verify all expert suggestions belong to this thesis
        $expertSuggestionIds = array_column($request->experts, 'expert_suggestion_id');
        $suggestedExperts = \App\Models\ExpertSuggestion::whereIn('id', $expertSuggestionIds)
            ->where('thesis_submission_id', $thesis->id)
            ->get();

        if ($suggestedExperts->count() !== 4) {
            return redirect()->back()
                ->withErrors(['experts' => 'Invalid expert selection. All experts must be from the supervisor suggestions for this thesis.'])
                ->withInput();
        }

        // Update thesis status
        $thesis->update(['status' => 'pending_expert_assignment']);

        // Get the Supervisor role for experts (experts use Supervisor role based on seeder)
        $expertRole = \App\Models\Role::where('name', 'Supervisor')->first();
        if (!$expertRole) {
            return redirect()->back()
                ->withErrors(['experts' => 'Expert role not found. Please ensure roles are seeded.'])
                ->withInput();
        }

        // Get supervisor_id - check both direct supervisor_id and currentSupervisor relationship
        $supervisorId = $thesis->supervisor_id;
        if (!$supervisorId && $thesis->scholar->currentSupervisor) {
            $supervisorId = $thesis->scholar->currentSupervisor->supervisor_id;
        }

        // Ensure supervisor_id exists (required for thesis_evaluations table)
        if (!$supervisorId) {
            return redirect()->back()
                ->withErrors(['experts' => 'Supervisor information is missing for this thesis. Please ensure the thesis has an assigned supervisor.'])
                ->withInput();
        }

        // Create evaluation records for selected experts
        foreach ($request->experts as $expertData) {
            $expertSuggestion = \App\Models\ExpertSuggestion::findOrFail($expertData['expert_suggestion_id']);

            // Find or create user account for the expert
            $expertUser = User::firstOrCreate(
                ['email' => $expertSuggestion->email],
                [
                    'name' => $expertSuggestion->name,
                    'user_type' => 'expert',
                    'role_id' => $expertRole->id,
                    'password' => bcrypt(str()->random(16)), // Random password, expert can reset if needed
                ]
            );

            // Update user details if they exist but info is different
            if ($expertUser->name !== $expertSuggestion->name) {
                $expertUser->update(['name' => $expertSuggestion->name]);
            }

            // Ensure role_id is set even if user already existed
            if (!$expertUser->role_id) {
                $expertUser->update(['role_id' => $expertRole->id]);
            }

            // Ensure priority is an integer
            $priority = (int) $expertData['priority'];

            ThesisEvaluation::create([
                'thesis_submission_id' => $thesis->id,
                'expert_id' => $expertUser->id,
                'supervisor_id' => $supervisorId,
                'assigned_date' => now(),
                'status' => 'assigned',
                'hvc_selected_expert_id' => Auth::id(),
                'priority_order' => $priority,
            ]);
        }

        // Reload thesis with evaluations
        $thesis->refresh();
        $thesis->load('thesisEvaluation.expert');

        // Send notification to all DR users
        $drUsers = \App\Models\User::where('user_type', 'dr')->get();
        foreach ($drUsers as $drUser) {
            $drUser->notify(new \App\Notifications\ExpertSelectionNotification($thesis, 'dr'));
        }

        // Send notification to all DA users
        $daUsers = \App\Models\User::where('user_type', 'da')->get();
        foreach ($daUsers as $daUser) {
            $daUser->notify(new \App\Notifications\ExpertSelectionNotification($thesis, 'da'));
        }

        return redirect()->route('hvc.thesis.approved')->with('success', '4 experts selected and prioritized successfully. Notification sent to ' . \App\Helpers\WorkflowHelper::getRoleFullForm('dr') . ' and ' . \App\Helpers\WorkflowHelper::getRoleFullForm('da') . '.');
    }

    /**
     * List thesis evaluations for DA to assign final experts
     */
    public function listThesisEvaluationsForAssignment()
    {
        // Check if user is DA
        if (Auth::user()->user_type !== 'da') {
            abort(403, 'Only ' . \App\Helpers\WorkflowHelper::getRoleFullForm('da') . ' can assign final experts.');
        }

        $thesisEvaluations = ThesisEvaluation::where('status', 'assigned')
            ->whereNull('da_assigned_expert_id')
            ->with(['thesisSubmission.scholar.user', 'expert', 'supervisor.user'])
            ->get()
            ->groupBy('thesis_submission_id');

        return view('da.thesis.evaluations_for_assignment', compact('thesisEvaluations'));
    }

    /**
     * Assign final 2 experts (DA assigns based on HVC priority)
     */
    public function assignFinalExperts(Request $request, ThesisSubmission $thesis)
    {
        // Check if user is DA
        if (Auth::user()->user_type !== 'da') {
            abort(403, 'Only ' . \App\Helpers\WorkflowHelper::getRoleFullForm('da') . ' can assign final experts.');
        }

        if ($thesis->status !== 'pending_expert_assignment') {
            abort(403, 'This thesis is not ready for expert assignment.');
        }

        $request->validate([
            'selected_experts' => 'required|array|size:2',
            'selected_experts.*' => 'required|exists:thesis_evaluations,id',
            'due_date' => 'required|date|after:today',
        ]);

        // Get the selected evaluations
        $selectedEvaluations = ThesisEvaluation::whereIn('id', $request->selected_experts)
            ->where('thesis_submission_id', $thesis->id)
            ->get();

        if ($selectedEvaluations->count() !== 2) {
            return redirect()->back()->with('error', 'Please select exactly 2 experts.');
        }

        // Update selected evaluations
        foreach ($selectedEvaluations as $evaluation) {
            $evaluation->update([
                'da_assigned_expert_id' => Auth::id(),
                'due_date' => $request->due_date,
                'status' => 'assigned',
            ]);
        }

        // Update thesis status
        $thesis->update(['status' => 'pending_evaluation_letters']);

        return redirect()->route('da.thesis.evaluations_for_assignment')->with('success', 'Final 2 experts assigned successfully.');
    }

    /**
     * Issue evaluation request letters (DA → SO → AR → DR)
     */
    public function issueEvaluationLetters(Request $request, ThesisSubmission $thesis)
    {
        // Check if user is DA, SO, AR, or DR
        $allowedTypes = ['da', 'so', 'ar', 'dr'];
        if (!in_array(Auth::user()->user_type, $allowedTypes)) {
            abort(403, 'Only DA, SO, AR, or DR can issue evaluation letters.');
        }

        if ($thesis->status !== 'pending_evaluation_letters') {
            abort(403, 'This thesis is not ready for evaluation letters.');
        }

        $request->validate([
            'letter_content' => 'required|string|max:2000',
        ]);

        // Generate evaluation request letter
        $letterContent = $this->generateEvaluationLetter($thesis, $request->letter_content);
        $letterPath = 'evaluation_letters/' . $thesis->id . '_' . Auth::user()->user_type . '_' . time() . '.pdf';
        Storage::disk('public')->put($letterPath, $letterContent);

        // Update thesis status based on current user type
        $nextStatus = match(Auth::user()->user_type) {
            'da' => 'pending_so_approval',
            'so' => 'pending_ar_approval',
            'ar' => 'pending_dr_approval',
            'dr' => 'pending_expert_evaluation',
        };

        $thesis->update(['status' => $nextStatus]);

        return redirect()->back()->with('success', 'Evaluation request letter issued successfully.');
    }

    /**
     * List thesis evaluations for experts
     */
    public function listMyEvaluations()
    {
        // Check if user is expert
        if (Auth::user()->user_type !== 'expert') {
            abort(403, 'Only experts can view their evaluations.');
        }

        $evaluations = ThesisEvaluation::where('expert_id', Auth::id())
            ->where('status', 'assigned')
            ->with(['thesisSubmission.scholar.user', 'supervisor.user'])
            ->get();

        return view('expert.evaluations.list', compact('evaluations'));
    }

    /**
     * Submit evaluation report
     */
    public function submitEvaluation(Request $request, ThesisEvaluation $evaluation)
    {
        // Check if user is the assigned expert
        if (Auth::user()->id !== $evaluation->expert_id) {
            abort(403, 'Unauthorized access to evaluation.');
        }

        if ($evaluation->status !== 'assigned') {
            abort(403, 'This evaluation is not active.');
        }

        $request->validate([
            'evaluation_report' => 'required|string|max:5000',
            'decision' => 'required|in:approved,approved_with_minor_revisions,approved_with_major_revisions,rejected',
            'remarks' => 'nullable|string|max:1000',
            'report_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $reportFilePath = null;
        if ($request->hasFile('report_file')) {
            $reportFilePath = $request->file('report_file')->store('evaluation_reports', 'public');
        }

        $evaluation->update([
            'evaluation_report' => $request->evaluation_report,
            'decision' => $request->decision,
            'remarks' => $request->remarks,
            'report_file' => $reportFilePath,
            'submission_date' => now(),
            'status' => 'submitted',
        ]);

        return redirect()->route('expert.evaluations.list')->with('success', 'Evaluation report submitted successfully.');
    }

    /**
     * List completed evaluations for HVC to schedule viva
     */
    public function listCompletedEvaluations()
    {
        // Check if user is HVC
        if (Auth::user()->user_type !== 'hvc') {
            abort(403, 'Only Head of Verification Committee can schedule viva.');
        }

        $completedEvaluations = ThesisEvaluation::where('status', 'submitted')
            ->with(['thesisSubmission.scholar.user', 'expert'])
            ->get()
            ->groupBy('thesis_submission_id');

        return view('hvc.viva.completed_evaluations', compact('completedEvaluations'));
    }

    /**
     * Schedule viva for thesis
     */
    public function scheduleViva(Request $request, ThesisSubmission $thesis)
    {
        // Check if user is HVC
        if (Auth::user()->user_type !== 'hvc') {
            abort(403, 'Only Head of Verification Committee can schedule viva.');
        }

        if ($thesis->status !== 'pending_viva_scheduling') {
            abort(403, 'This thesis is not ready for viva scheduling.');
        }

        $request->validate([
            'expert_id' => 'required|exists:users,id',
            'viva_date' => 'required|date|after:today',
            'viva_time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
        ]);

        // Get supervisor_id - check both direct supervisor_id and currentSupervisor relationship
        $supervisorId = $thesis->supervisor_id;
        if (!$supervisorId && $thesis->scholar->currentSupervisor) {
            $supervisorId = $thesis->scholar->currentSupervisor->supervisor_id;
        }

        VivaProcess::create([
            'thesis_submission_id' => $thesis->id,
            'hvc_assigned_expert_id' => $request->expert_id,
            'hod_id' => $thesis->hod_id,
            'supervisor_id' => $supervisorId,
            'viva_date' => $request->viva_date,
            'viva_time' => $request->viva_time,
            'venue' => $request->venue,
            'status' => 'scheduled',
        ]);

        $thesis->update(['status' => 'viva_scheduled']);

        return redirect()->route('hvc.viva.completed_evaluations')->with('success', 'Viva scheduled successfully.');
    }

    /**
     * Generate evaluation request letter
     */
    private function generateEvaluationLetter(ThesisSubmission $thesis, string $content)
    {
        $letter = "EVALUATION REQUEST LETTER\n\n";
        $letter .= "To: Expert Evaluator\n";
        $letter .= "From: " . Auth::user()->name . " (" . ucfirst(Auth::user()->user_type) . ")\n";
        $letter .= "Date: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $letter .= "Subject: Thesis Evaluation Request\n\n";
        $letter .= "Dear Expert,\n\n";
        $letter .= "You have been assigned to evaluate the following thesis:\n\n";
        $letter .= "Scholar: " . $thesis->scholar->name. "\n";
        $letter .= "Title: " . $thesis->title . "\n";
        $letter .= "Supervisor: " . $thesis->supervisor->user->name . "\n";
        $letter .= "Department: " . $thesis->scholar->admission->department->name . "\n\n";
        $letter .= "Additional Instructions:\n";
        $letter .= $content . "\n\n";
        $letter .= "Please submit your evaluation report within the specified timeframe.\n\n";
        $letter .= "Thank you for your cooperation.\n\n";
        $letter .= "Best regards,\n";
        $letter .= Auth::user()->name . "\n";
        $letter .= ucfirst(Auth::user()->user_type) . "\n";

        return $letter;
    }
}
