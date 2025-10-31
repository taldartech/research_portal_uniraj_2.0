<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Scholar;
use App\Models\Supervisor;
use App\Models\SupervisorAssignment;
use App\Models\RAC;
use App\Models\Synopsis;
use App\Models\ThesisSubmission;
use App\Models\ThesisSubmissionCertificate;
use App\Models\User;
use App\Traits\HasAlertResponses;

class ScholarController extends Controller
{
    use HasAlertResponses;
    public function editProfile()
    {
        $scholar = Auth::user()->scholar;

        // Check if scholar can edit profile
        if (!$scholar->canEditProfile()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You cannot edit your profile after supervisor approval. Please contact your supervisor for any changes.');
        }

        return view('scholar.profile.edit', compact('scholar'));
    }

    public function updateProfile(Request $request)
    {
        $scholar = Auth::user()->scholar;

        // Check if scholar can edit profile
        if (!$scholar->canEditProfile()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You cannot edit your profile after supervisor approval. Please contact your supervisor for any changes.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'academic_information' => 'nullable|json',
        ]);

        $scholar->update($request->all());

        return $this->successResponse('Profile updated successfully.', 'scholar.profile.edit');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::where('id', Auth::id())->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('scholar.profile.edit')->with('status', 'password-updated');
    }



    // Ph.D. Registration Form Methods
    public function showPhdRegistrationForm()
    {
        $scholar = Auth::user()->scholar;
        return view('scholar.registration.phd_form', compact('scholar'));
    }

    public function storePhdRegistrationForm(Request $request)
    {
        $scholar = Auth::user()->scholar;

        $request->validate([
            // Basic Profile Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'research_area' => 'required|string|max:255',

            // Family Information
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'category' => 'required|in:SC,ST,OBC,MBC,EWS,P.H.,General',
            'occupation' => 'required|string|max:255',
            'is_teacher' => 'nullable|boolean',
            'teacher_employer' => 'nullable|string|max:255',
            'appearing_other_exam' => 'nullable|boolean',
            'other_exam_details' => 'nullable|string',

            // Academic Qualifications (arrays)
            'post_graduate_degrees' => 'required|array|min:1',
            'post_graduate_degrees.*' => 'required|string|max:255',
            'post_graduate_universities' => 'required|array|min:1',
            'post_graduate_universities.*' => 'required|string|max:255',
            'post_graduate_years' => 'required|array|min:1',
            'post_graduate_years.*' => 'required|string|max:255',
            'post_graduate_percentages' => 'required|array|min:1',
            'post_graduate_percentages.*' => 'required|numeric|min:0|max:100',
            'net_slet_csir_gate_exam' => 'nullable|in:NET,SLET,CSIR,GATE',
            'net_slet_csir_gate_year' => 'nullable|string|max:255',
            'net_slet_csir_gate_roll_number' => 'nullable|string|max:255',
            'mpat_year' => 'nullable|string|max:255',
            'mpat_roll_number' => 'nullable|string|max:255',
            'mpat_merit_number' => 'nullable|string|max:255',
            'mpat_subject' => 'nullable|string|max:255',
            'coursework_exam_date' => 'nullable|string|max:255',
            'coursework_marks_obtained' => 'nullable|string|max:255',
            'coursework_max_marks' => 'nullable|string|max:255',

            // Document Upload (arrays) - optional if documents already exist
            'document_types' => 'nullable|array',
            'document_types.*' => 'nullable|string|in:degree_certificate,marksheet,net_certificate,slet_certificate,csir_certificate,gate_certificate,mpat_certificate,noc_letter,other',
            'registration_documents' => 'nullable|array',
            'registration_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',

            // Synopsis
            'synopsis_topic' => 'nullable|string|max:255',
            'synopsis_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Custom validation: if document_types array has non-empty values, ensure corresponding files exist
        if ($request->has('document_types') && $request->hasFile('registration_documents')) {
            $documentTypes = $request->input('document_types', []);
            $files = $request->file('registration_documents', []);

            // Filter out empty document types
            $nonEmptyTypes = array_filter($documentTypes, function($type) {
                return !empty($type);
            });

            // If there are non-empty document types, ensure we have corresponding files
            if (!empty($nonEmptyTypes) && count($files) !== count($nonEmptyTypes)) {
                return redirect()->back()
                    ->withErrors(['registration_documents' => 'Number of uploaded files must match the number of selected document types.'])
                    ->withInput();
            }
        }

        // Handle file uploads
        $uploadedDocuments = [];
        if ($request->hasFile('registration_documents') && $request->has('document_types')) {
            $documentTypes = array_filter($request->input('document_types', []), function($type) {
                return !empty($type);
            });
            $files = $request->file('registration_documents');

            foreach ($files as $index => $file) {
                if ($file && isset($documentTypes[$index])) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('registration_documents/' . $scholar->id, $filename, 'public');
                    $uploadedDocuments[] = [
                        'type' => $documentTypes[$index],
                        'filename' => $filename,
                        'path' => $path,
                        'uploaded_at' => now()->toISOString(),
                    ];
                }
            }
        }

        // Handle synopsis file upload
        $synopsisFilePath = null;
        if ($request->hasFile('synopsis_file')) {
            $synopsisFile = $request->file('synopsis_file');
            $filename = 'synopsis_' . time() . '_' . $synopsisFile->getClientOriginalName();
            $synopsisFilePath = $synopsisFile->storeAs('synopses/' . $scholar->id, $filename, 'public');
        }

        // Update scholar with form data
        $formData = $request->except(['registration_documents', 'synopsis_file', 'action', 'post_graduate_degrees', 'post_graduate_universities', 'post_graduate_years', 'post_graduate_percentages', 'document_types']);

        // Handle academic qualifications arrays - store all qualifications
        if ($request->has('post_graduate_degrees') && !empty($request->post_graduate_degrees)) {
            $qualifications = [];
            $degrees = $request->post_graduate_degrees;
            $universities = $request->post_graduate_universities ?? [];
            $years = $request->post_graduate_years ?? [];
            $percentages = $request->post_graduate_percentages ?? [];

            // Combine all qualification data
            for ($i = 0; $i < count($degrees); $i++) {
                $qualifications[] = [
                    'degree' => $degrees[$i] ?? '',
                    'university' => $universities[$i] ?? '',
                    'year' => $years[$i] ?? '',
                    'percentage' => $percentages[$i] ?? '',
                ];
            }

            $formData['academic_qualifications'] = $qualifications;

            // Keep the first qualification in the old single fields for backward compatibility
            $formData['post_graduate_degree'] = $degrees[0] ?? '';
            $formData['post_graduate_university'] = $universities[0] ?? '';
            $formData['post_graduate_year'] = $years[0] ?? '';
            $formData['post_graduate_percentage'] = $percentages[0] ?? '';
        }

        // Handle checkbox fields properly
        $formData['is_teacher'] = $request->has('is_teacher') ? (bool) $request->input('is_teacher') : false;
        $formData['appearing_other_exam'] = $request->has('appearing_other_exam') ? (bool) $request->input('appearing_other_exam') : false;
        $formData['has_co_supervisor'] = $request->has('has_co_supervisor') ? (bool) $request->input('has_co_supervisor') : false;

        // Only merge new documents if any were uploaded
        if (!empty($uploadedDocuments)) {
            $formData['registration_documents'] = array_merge($scholar->registration_documents ?? [], $uploadedDocuments);
        }

        // Handle synopsis data
        if ($synopsisFilePath) {
            $formData['synopsis_file'] = $synopsisFilePath;
            $formData['synopsis_submitted_at'] = now();
            $formData['synopsis_status'] = 'pending_supervisor_approval';

            // Create Synopsis model record for workflow tracking
            $synopsis = \App\Models\Synopsis::create([
                'scholar_id' => $scholar->id,
                'rac_id' => null, // RAC will be created after supervisor approval
                'proposed_topic' => $request->synopsis_topic,
                'synopsis_file' => $synopsisFilePath,
                'submission_date' => now(),
                'status' => 'pending_supervisor_approval',
            ]);
        }

        // Update registration form status
        if ($request->action === 'submit') {
            $formData['registration_form_status'] = 'submitted';
            $formData['registration_form_submitted_at'] = now();
        } else {
            // Only allow editing if form is not started yet
            if ($scholar->registration_form_status === 'not_started') {
                $formData['registration_form_status'] = 'in_progress';
            }
        }

        $scholar->update($formData);

        $message = $request->action === 'submit'
            ? 'Ph.D. registration form submitted successfully!'
            : 'Ph.D. registration form saved successfully!';

        return redirect()->route('scholar.registration.phd_form')->with('success', $message);
    }

    // Supervisor methods for editing scholar forms
    public function supervisorEditScholarForm($scholarId)
    {
        $scholar = Scholar::findOrFail($scholarId);

        // Check if current user is the supervisor of this scholar
        if (!Auth::user()->scholar || !$scholar->hasAssignedSupervisor() ||
            $scholar->supervisor->id !== Auth::user()->id) {
            abort(403, 'You are not authorized to edit this scholar\'s form.');
        }

        return view('supervisor.scholar.form_edit', compact('scholar'));
    }

    public function supervisorUpdateScholarForm(Request $request, $scholarId)
    {
        $scholar = Scholar::findOrFail($scholarId);

        // Check if current user is the supervisor of this scholar
        if (!Auth::user()->scholar || !$scholar->hasAssignedSupervisor() ||
            $scholar->supervisor->id !== Auth::user()->id) {
            abort(403, 'You are not authorized to edit this scholar\'s form.');
        }

        $request->validate([
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'category' => 'required|in:SC,ST,OBC,MBC,EWS,P.H.,General',
            'occupation' => 'required|string|max:255',
            'is_teacher' => 'nullable|boolean',
            'teacher_employer' => 'nullable|string|max:255',
            'phd_faculty' => 'required|string|max:255',
            'phd_subject' => 'required|string|max:255',
            'post_graduate_degree' => 'nullable|string|max:255',
            'post_graduate_university' => 'nullable|string|max:255',
            'post_graduate_year' => 'nullable|string|max:255',
            'post_graduate_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Handle checkbox fields properly
        $formData = $request->except(['action']);
        $formData['is_teacher'] = $request->has('is_teacher') ? (bool) $request->input('is_teacher') : false;

        // Update form status based on action
        if ($request->action === 'approve') {
            $formData['registration_form_status'] = 'approved';
        } elseif ($request->action === 'review') {
            $formData['registration_form_status'] = 'under_review';
        }

        $scholar->update($formData);

        // Sync workflow if supervisor is approving
        if ($request->action === 'approve') {
            $workflowSyncService = app(\App\Services\WorkflowSyncService::class);
            $registrationForm = $scholar->registrationForm;
            if ($registrationForm) {
                $workflowSyncService->syncRegistrationWorkflow($registrationForm, 'supervisor_approve', Auth::user());
            }
        }

        $message = match($request->action) {
            'approve' => 'Scholar form approved successfully!',
            'review' => 'Scholar form marked for review!',
            default => 'Scholar form updated successfully!'
        };

        return redirect()->route('supervisor.scholar.form_edit', $scholarId)->with('success', $message);
    }

    public function showSupervisorCertificate()
    {
        $scholar = Auth::user()->scholar;
        return view('scholar.registration.supervisor_certificate', compact('scholar'));
    }

    public function storeSupervisorCertificate(Request $request)
    {
        $request->validate([
            'current_research_candidates' => 'required|integer|min:0',
            'candidate_position' => 'required|string|max:255',
            'relationship_confirmation' => 'required|accepted',
            'retirement_date' => 'required|date|after:today',
            'recognition_letter_number' => 'required|string|max:255',
            'recognition_letter_date' => 'required|date',
            'supervisor_signature' => 'required|string',
        ]);

        $scholar = Auth::user()->scholar;
        $scholar->update([
            'supervisor_certificate_completed' => true,
            'supervisor_certificate_date' => now(),
        ]);

        return redirect()->route('scholar.registration.supervisor_certificate')
            ->with('success', 'Supervisor certificate completed successfully!');
    }

    public function showHodCertificate()
    {
        $scholar = Auth::user()->scholar;
        return view('scholar.registration.hod_certificate', compact('scholar'));
    }

    public function storeHodCertificate(Request $request)
    {
        $request->validate([
            'candidate_relation' => 'required|string|max:255',
            'drc_date' => 'required|date',
            'eligibility_criteria' => 'required|in:net_slet_csir_gate,mpat,percentage,coursework',
            'supervisor_retirement_date' => 'required|date|after:today',
            'hod_date' => 'required|date',
            'hod_signature' => 'required|string',
        ]);

        $scholar = Auth::user()->scholar;
        $scholar->update([
            'hod_certificate_completed' => true,
            'hod_certificate_date' => now(),
        ]);

        return redirect()->route('scholar.registration.hod_certificate')
            ->with('success', 'HOD certificate completed successfully!');
    }


    public function supervisorPreference()
    {
        $scholar = Auth::user()->scholar;

        if ($scholar->hasAssignedSupervisor()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You already have an assigned supervisor.');
        }

        $submittedPreferences = $scholar->supervisorPreferences()
            ->where('status', 'pending')
            ->with('supervisor.user')
            ->orderBy('preference_order')
            ->get();

        $supervisors = Supervisor::with('user')->get();
        return view('scholar.supervisor.preference', compact('supervisors', 'submittedPreferences'));
    }


    public function storeSupervisorPreference(Request $request)
    {
        $scholar = Auth::user()->scholar;
        if ($scholar->hasAssignedSupervisor()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You already have an assigned supervisor.');
        }

        $request->validate([
            'supervisor_1_id' => 'required|exists:supervisors,id',
            'justification_1' => 'nullable|string',
            'supervisor_2_id' => 'nullable|exists:supervisors,id',
            'justification_2' => 'nullable|string',
            'supervisor_3_id' => 'nullable|exists:supervisors,id',
            'justification_3' => 'nullable|string',
        ]);

        // Check if scholar already has pending preferences
        $existingPreferences = $scholar->supervisorPreferences()
            ->where('status', 'pending')
            ->count();

        if ($existingPreferences > 0) {
            return redirect()->route('scholar.supervisor.preference')
                ->with('error', 'You already have pending supervisor preferences. Please wait for HOD approval.');
        }

        // Validate that all selected supervisors are different
        $selectedSupervisors = array_filter([
            $request->supervisor_1_id,
            $request->supervisor_2_id,
            $request->supervisor_3_id
        ]);

        if (count($selectedSupervisors) !== count(array_unique($selectedSupervisors))) {
            return redirect()->back()->withErrors([
                'supervisor_selection' => 'You cannot select the same supervisor for multiple preferences.'
            ]);
        }

        // Create supervisor preferences
        $preferences = [];

        // First preference (required)
        $preferences[] = [
            'scholar_id' => $scholar->id,
            'supervisor_id' => $request->supervisor_1_id,
            'preference_order' => 1,
            'remarks' => $request->remarks,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Second preference (optional)
        if ($request->supervisor_2_id) {
            $preferences[] = [
                'scholar_id' => $scholar->id,
                'supervisor_id' => $request->supervisor_2_id,
                'preference_order' => 2,
                'justification' => $request->justification_2,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Third preference (optional)
        if ($request->supervisor_3_id) {
            $preferences[] = [
                'scholar_id' => $scholar->id,
                'supervisor_id' => $request->supervisor_3_id,
                'preference_order' => 3,
                'justification' => $request->justification_3,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all preferences
        \App\Models\SupervisorPreference::insert($preferences);

        return redirect()->route('scholar.supervisor.preference')->with('success', 'Supervisor preferences submitted successfully!');
    }


    public function submitProgressReport()
    {
        $scholar = Auth::user()->scholar;
        if (! $scholar->hasAssignedSupervisor()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You must have an assigned supervisor to submit a progress report.');
        }

        // Check if progress report submission is allowed in current month
        if (!\App\Helpers\ProgressReportHelper::isSubmissionAllowed()) {
            $message = \App\Helpers\ProgressReportHelper::getSubmissionStatusMessage();
            return redirect()->route('scholar.dashboard')->with('error', $message);
        }

        $allowedMonths = \App\Helpers\ProgressReportHelper::getAllowedMonthNames();
        $currentMonth = date('F'); // Get current month name (e.g., 'April', 'October')

        // Check if a report already exists for the current month
        $existingReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
            ->where('report_period', $currentMonth)
            ->where('status', '!=', 'rejected')
            ->first();

        return view('scholar.progress_report.submit', compact('allowedMonths', 'currentMonth', 'existingReport'));
    }

    public function storeProgressReport(Request $request)
    {
        $scholar = Auth::user()->scholar;
        if (! $scholar->hasAssignedSupervisor()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You must have an assigned supervisor to submit a progress report.');
        }

        // Check if progress report submission is allowed in current month
        if (!\App\Helpers\ProgressReportHelper::isSubmissionAllowed()) {
            $message = \App\Helpers\ProgressReportHelper::getSubmissionStatusMessage();
            return redirect()->route('scholar.dashboard')->with('error', $message);
        }

        $allowedMonths = \App\Helpers\ProgressReportHelper::getAllowedMonthNames();
        $allowedMonthValues = array_values($allowedMonths);

        $request->validate([
            'report_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'report_period' => 'required|string|in:' . implode(',', $allowedMonthValues),
            'special_remark' => 'boolean',
            'cancellation_request' => 'boolean',
            'supervisor_change_request' => 'boolean',
        ]);

        // Check if a non-rejected progress report already exists for this month
        $existingReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
            ->where('report_period', $request->report_period)
            ->where('status', '!=', 'rejected')
            ->first();

        if ($existingReport) {
            return redirect()->back()->withErrors([
                'report_period' => 'A progress report for ' . $request->report_period . ' has already been submitted. Only one report per month is allowed.'
            ])->withInput();
        }

        // Check if this is a resubmission of a rejected report
        $rejectedReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
            ->where('report_period', $request->report_period)
            ->where('status', 'rejected')
            ->first();

        $path = $request->file('report_file')->store('progress_reports', 'public');

        $supervisor = $scholar->currentSupervisor;
        $hod = $scholar->admission->department->hod;

        $progressReportData = [
            'scholar_id' => $scholar->id,
            'supervisor_id' => $supervisor->supervisor_id,
            'hod_id' => $hod->id,
            'report_file' => $path,
            'submission_date' => now(),
            'report_period' => $request->report_period,
            'special_remark' => $request->boolean('special_remark'),
            'cancellation_request' => $request->boolean('cancellation_request'),
            'supervisor_change_request' => $request->boolean('supervisor_change_request'),
            'status' => 'pending_supervisor_approval',
        ];

        // If this is a resubmission, link it to the original rejected report
        if ($rejectedReport) {
            $progressReportData['original_report_id'] = $rejectedReport->id;
            $progressReportData['rejection_count'] = ($rejectedReport->rejection_count ?? 0) + 1;

            // Update the original report to mark it as superseded
            $rejectedReport->update(['status' => 'superseded']);
        }

        \App\Models\ProgressReport::create($progressReportData);

        $message = $rejectedReport
            ? 'Progress report resubmitted successfully for supervisor approval.'
            : 'Progress report submitted for supervisor approval.';

        return redirect()->route('scholar.progress_report.submit')->with('success', $message);
    }


    public function submitThesis()
    {
        $scholar = Auth::user()->scholar;
        if (! $scholar->hasAssignedSupervisor()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You must have an assigned supervisor to submit a thesis.');
        }

        // Check thesis eligibility
        $eligibilityCheck = $scholar->canSubmitThesis();
        if (!$eligibilityCheck['can_submit']) {
            return redirect()->route('scholar.dashboard')->with('error', $eligibilityCheck['reason']);
        }

        return view('scholar.thesis.submit', compact('eligibilityCheck'));
    }

    public function storeThesis(Request $request)
    {
        $scholar = Auth::user()->scholar;
        if (! $scholar->hasAssignedSupervisor()) {
            return redirect()->route('scholar.dashboard')->with('error', 'You must have an assigned supervisor to submit a thesis.');
        }

        // Check thesis eligibility
        $eligibilityCheck = $scholar->canSubmitThesis();
        if (!$eligibilityCheck['can_submit']) {
            return redirect()->route('scholar.dashboard')->with('error', $eligibilityCheck['reason']);
        }

        $request->validate([
            // Personal Details
            'father_husband_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',

            // Academic Progress
            'mpat_passing_date' => 'nullable|date',
            'coursework_session' => 'nullable|string|max:255',
            'coursework_fee_receipt_no' => 'nullable|string|max:255',
            'coursework_fee_receipt_date' => 'nullable|date',
            'coursework_passing_date' => 'nullable|date',
            'registration_fee_date' => 'nullable|date',
            'extension_date' => 'nullable|date',
            're_registration_date' => 'nullable|date',
            'pre_phd_presentation_date' => 'required|date',
            'pre_phd_presentation_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // Research Output
            'published_research_paper_details' => 'required|string|max:2000',
            'published_research_paper_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'conference_presentation_1' => 'required|string|max:1000',
            'conference_certificate_1' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'conference_presentation_2' => 'required|string|max:1000',
            'conference_certificate_2' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // RAC/DRC Details
            'rac_constitution_date' => 'nullable|date',
            'drc_approval_date' => 'nullable|date',
            'rac_drc_undertaking' => 'nullable|string|max:1000',

            // Thesis Details
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|max:2000',
            'thesis_file' => 'required|file|mimes:pdf|max:10240',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx|max:2048',

            // Declaration
            'declaration' => 'required|accepted',
        ]);

        $thesisPath = $request->file('thesis_file')->store('theses', 'public');

        $supportingDocs = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $supportingDocs[] = $file->store('thesis_supporting_docs', 'public');
            }
        }

        // Handle certificate uploads
        $prePhdCertificate = null;
        if ($request->hasFile('pre_phd_presentation_certificate')) {
            $prePhdCertificate = $request->file('pre_phd_presentation_certificate')->store('thesis_certificates', 'public');
        }

        $researchPaperCertificate = null;
        if ($request->hasFile('published_research_paper_certificate')) {
            $researchPaperCertificate = $request->file('published_research_paper_certificate')->store('thesis_certificates', 'public');
        }

        $conferenceCertificate1 = null;
        if ($request->hasFile('conference_certificate_1')) {
            $conferenceCertificate1 = $request->file('conference_certificate_1')->store('thesis_certificates', 'public');
        }

        $conferenceCertificate2 = null;
        if ($request->hasFile('conference_certificate_2')) {
            $conferenceCertificate2 = $request->file('conference_certificate_2')->store('thesis_certificates', 'public');
        }

        $supervisor = $scholar->currentSupervisor;
        $hod = $scholar->admission->department->hod;

        \App\Models\ThesisSubmission::create([
            'scholar_id' => $scholar->id,
            'supervisor_id' => $supervisor->id,
            'hod_id' => $hod->id,

            // Personal Details
            'father_husband_name' => $request->father_husband_name,
            'mother_name' => $request->mother_name,
            'subject' => $request->subject,
            'faculty' => $request->faculty,

            // Academic Progress
            'mpat_passing_date' => $request->mpat_passing_date,
            'coursework_session' => $request->coursework_session,
            'coursework_fee_receipt_no' => $request->coursework_fee_receipt_no,
            'coursework_fee_receipt_date' => $request->coursework_fee_receipt_date,
            'coursework_passing_date' => $request->coursework_passing_date,
            'registration_fee_date' => $request->registration_fee_date,
            'extension_date' => $request->extension_date,
            're_registration_date' => $request->re_registration_date,
            'pre_phd_presentation_date' => $request->pre_phd_presentation_date,
            'pre_phd_presentation_certificate' => $prePhdCertificate,

            // Research Output
            'published_research_paper_details' => $request->published_research_paper_details,
            'published_research_paper_certificate' => $researchPaperCertificate,
            'conference_presentation_1' => $request->conference_presentation_1,
            'conference_certificate_1' => $conferenceCertificate1,
            'conference_presentation_2' => $request->conference_presentation_2,
            'conference_certificate_2' => $conferenceCertificate2,

            // RAC/DRC Details
            'rac_constitution_date' => $request->rac_constitution_date,
            'drc_approval_date' => $request->drc_approval_date,
            'rac_drc_undertaking' => $request->rac_drc_undertaking,

            // Thesis Details
            'title' => $request->title,
            'abstract' => $request->abstract,
            'file_path' => $thesisPath,
            'supporting_documents' => json_encode($supportingDocs),
            'submission_date' => now(),
            'status' => 'pending_supervisor_approval',
            'form_completed' => true,
            'form_submitted_at' => now(),
        ]);

        return redirect()->route('scholar.thesis.submit')->with('success', 'Thesis submitted for supervisor approval.');
    }

    public function viewThesisStatus()
    {
        $scholar = Auth::user()->scholar;
        $thesisSubmissions = \App\Models\ThesisSubmission::where('scholar_id', $scholar->id)
            ->with(['rejectedBy', 'originalThesis'])
            ->latest()
            ->get();

        return view('scholar.thesis.status', compact('scholar', 'thesisSubmissions'));
    }

    public function resubmitThesis(\App\Models\ThesisSubmission $thesis)
    {
        $scholar = Auth::user()->scholar;

        if ($thesis->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$thesis->canResubmit()) {
            abort(403, 'This thesis cannot be resubmitted.');
        }

        return view('scholar.thesis.resubmit', compact('thesis'));
    }

    public function storeResubmission(Request $request, \App\Models\ThesisSubmission $originalThesis)
    {
        $scholar = Auth::user()->scholar;

        if ($originalThesis->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$originalThesis->canResubmit()) {
            abort(403, 'This thesis cannot be resubmitted.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|max:2000',
            'thesis_file' => 'required|file|mimes:pdf|max:10240',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $thesisPath = $request->file('thesis_file')->store('theses', 'public');

        $supportingDocs = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $supportingDocs[] = $file->store('thesis_supporting_docs', 'public');
            }
        }

        $supervisor = $scholar->currentSupervisor;
        $hod = $scholar->admission->department->hod;

        \App\Models\ThesisSubmission::create([
            'scholar_id' => $scholar->id,
            'supervisor_id' => $supervisor->id,
            'hod_id' => $hod->id,
            'title' => $request->title,
            'abstract' => $request->abstract,
            'file_path' => $thesisPath,
            'supporting_documents' => json_encode($supportingDocs),
            'submission_date' => now(),
            'status' => 'resubmitted',
            'is_resubmission' => true,
            'rejection_count' => $originalThesis->rejection_count,
            'original_thesis_id' => $originalThesis->id,
        ]);

        return redirect()->route('scholar.thesis.status')->with('success', 'Thesis resubmitted successfully.');
    }

    public function thesisEligibility()
    {
        $scholar = Auth::user()->scholar;
        $eligibilityCheck = $scholar->canSubmitThesis();

        return view('scholar.thesis.eligibility', compact('eligibilityCheck'));
    }

    public function downloadSubmissionCertificate(\App\Models\ThesisSubmission $thesis)
    {
        $scholar = Auth::user()->scholar;

        // Check if the scholar owns this thesis
        if ($thesis->scholar_id !== $scholar->id) {
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

    /**
     * Show thesis submission form
     */
    public function showThesisSubmissionForm()
    {
        $scholar = Auth::user()->scholar;

        // Check if scholar can submit thesis
        if (!$scholar->canSubmitThesis()) {
            return redirect()->route('scholar.thesis.eligibility')
                ->with('error', 'You are not eligible to submit thesis yet.');
        }

        return view('scholar.thesis.submit', compact('scholar'));
    }


    /**
     * Show thesis status and certificates
     */
    public function thesisStatus()
    {
        $scholar = Auth::user()->scholar;
        $thesisSubmissions = $scholar->thesisSubmissions()->with('certificates')->latest()->get();

        return view('scholar.thesis.status', compact('scholar', 'thesisSubmissions'));
    }

    /**
     * Generate certificate after supervisor approval
     */
    public function generateCertificate(Request $request, ThesisSubmission $thesis)
    {
        $scholar = Auth::user()->scholar;

        // Check if scholar owns this thesis
        if ($thesis->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if thesis is approved by supervisor
        if ($thesis->status !== 'supervisor_approved') {
            return redirect()->back()->with('error', 'Thesis must be approved by supervisor first.');
        }

        $request->validate([
            'certificate_type' => 'required|in:pre_phd_presentation,research_papers,peer_reviewed_journal',
            'certificate_data' => 'required|array',
        ]);

        // Create certificate
        $certificate = ThesisSubmissionCertificate::create([
            'scholar_id' => $scholar->id,
            'thesis_submission_id' => $thesis->id,
            'certificate_type' => $request->certificate_type,
            'certificate_data' => $request->certificate_data,
            'status' => 'generated',
            'generated_by' => Auth::id(),
            'generated_at' => now(),
        ]);

        return redirect()->route('scholar.thesis.certificate.show', $certificate)
            ->with('success', 'Certificate generated successfully.');
    }

    /**
     * Show certificate
     */
    public function showCertificate(ThesisSubmissionCertificate $certificate)
    {
        $scholar = Auth::user()->scholar;

        // Check if scholar owns this certificate
        if ($certificate->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('scholar.thesis.certificate.show', compact('certificate'));
    }

    /**
     * Download certificate as PDF
     */
    public function downloadCertificate(ThesisSubmissionCertificate $certificate)
    {
        $scholar = Auth::user()->scholar;

        // Check if scholar owns this certificate
        if ($certificate->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if certificate is generated
        if (!$certificate->isGenerated() || !$certificate->generated_file_path) {
            return redirect()->back()->with('error', 'Certificate not yet generated.');
        }

        $filePath = storage_path('app/public/' . $certificate->generated_file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Certificate file not found.');
        }

        return response()->download($filePath, $certificate->certificate_type_name . '_' . $certificate->id . '.pdf');
    }

    /**
     * Download registration letter
     */
    public function downloadRegistrationLetter()
    {
        $scholar = Auth::user()->scholar;

        if (!$scholar->registration_letter_generated || !$scholar->registration_letter_file) {
            return redirect()->back()->with('error', 'Registration letter not yet generated.');
        }

        $registrationLetterService = new \App\Services\RegistrationLetterGenerationService();
        $filePath = $registrationLetterService->downloadRegistrationLetter($scholar);

        if (!$filePath) {
            return redirect()->back()->with('error', 'Registration letter file not found.');
        }

        return response()->download($filePath, 'registration_letter_' . $scholar->id . '.pdf');
    }

    /**
     * Download supervisor assignment office note
     */
    public function downloadSupervisorOfficeNote(\App\Models\SupervisorAssignment $assignment)
    {
        $scholar = Auth::user()->scholar;

        // Check if scholar owns this assignment
        if ($scholar && $assignment->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$assignment->office_note_generated || !$assignment->office_note_file) {
            return $this->errorResponse('Office note not yet generated.');
        }

        $filePath = storage_path('app/public/' . $assignment->office_note_file);

        if (!file_exists($filePath)) {
            return $this->errorResponse('Office note file not found.');
        }
        return response()->download($filePath, 'supervisor_selection_office_note_' . $assignment->id . '.pdf');
    }

    /**
     * Show topic change response form
     */
    public function showTopicChangeResponseForm(\App\Models\Synopsis $synopsis)
    {
        $scholar = Auth::user()->scholar;

        // Check if this synopsis belongs to the scholar
        if ($synopsis->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if there's a pending topic change proposal
        if (!$synopsis->canRespondToTopicChange()) {
            abort(403, 'No pending topic change proposal found.');
        }

        // Load relationships
        $synopsis->load(['topicChangeProposedBy']);

        return view('scholar.synopsis.topic-change-response', compact('synopsis'));
    }

    /**
     * Respond to topic change proposal
     */
    public function respondToTopicChange(Request $request, \App\Models\Synopsis $synopsis)
    {
        $scholar = Auth::user()->scholar;

        // Check if this synopsis belongs to the scholar
        if ($synopsis->scholar_id !== $scholar->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if there's a pending topic change proposal
        if (!$synopsis->canRespondToTopicChange()) {
            abort(403, 'No pending topic change proposal found.');
        }

        $request->validate([
            'response' => 'required|in:accept,reject',
            'remarks' => 'required|string|max:1000',
        ]);

        if ($request->response === 'accept') {
            // Accept the topic change
            $synopsis->update([
                'proposed_topic' => $synopsis->proposed_topic_change,
                'topic_change_status' => 'accepted_by_scholar',
                'topic_change_responded_at' => now(),
                'scholar_response_remarks' => $request->remarks,
            ]);

            $message = 'Topic change accepted. Your synopsis has been updated with the new topic.';
        } else {
            // Reject the topic change
            $synopsis->update([
                'topic_change_status' => 'rejected_by_scholar',
                'topic_change_responded_at' => now(),
                'scholar_response_remarks' => $request->remarks,
            ]);

            $message = 'Topic change rejected. Your original topic remains unchanged.';
        }

        // Notify supervisor about the response
        $synopsis->topicChangeProposedBy->notify(new \App\Notifications\TopicChangeResponseNotification($synopsis, $request->response));

        return redirect()->route('scholar.dashboard')->with('success', $message);
    }
}
