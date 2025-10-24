<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Auth\ScholarLoginController;
use App\Http\Controllers\HODController;
use App\Http\Controllers\DRCController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\DAController;
use App\Http\Controllers\SOController;
use App\Http\Controllers\ARController;
use App\Http\Controllers\DRController;
use App\Http\Controllers\HVCController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\UserTypeMiddleware;

Route::get('/', function () {
    return redirect()->route('scholar.login');

});

Route::get('/login', function () {
    return redirect()->route('scholar.login');
})->name('login');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Digital Signature Management
    Route::get('/profile/signatures', [ProfileController::class, 'showSignatures'])->name('profile.signatures');
    Route::post('/profile/signatures', [ProfileController::class, 'createSignature'])->name('profile.signatures.create');
    Route::put('/profile/signatures/{signature}', [ProfileController::class, 'updateSignature'])->name('profile.signatures.update');
    Route::delete('/profile/signatures/{signature}', [ProfileController::class, 'deleteSignature'])->name('profile.signatures.delete');
    Route::post('/profile/signatures/{signature}/activate', [ProfileController::class, 'setActiveSignature'])->name('profile.signatures.activate');
});

// Scholar Login Routes
Route::prefix('scholar')->name('scholar.')->middleware('guest')->group(function () {
    Route::get('/login', [ScholarLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ScholarLoginController::class, 'login']);
});

// Scholar Login & Dashboard
Route::prefix('scholar')->name('scholar.')->middleware(['auth'])->group(function () {
    Route::post('/logout', [ScholarLoginController::class, 'logout'])->name('logout');
    Route::middleware([UserTypeMiddleware::class])->group(function () {
        Route::get('/dashboard', function () {
            return view('scholar.dashboard');
        })->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\ScholarController::class, 'editProfile'])->name('profile.edit');
        Route::patch('/profile', [App\Http\Controllers\ScholarController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\ScholarController::class, 'updatePassword'])->name('password.update');
        Route::get('/supervisor-preference', [App\Http\Controllers\ScholarController::class, 'supervisorPreference'])->name('supervisor.preference');
        Route::post('/supervisor-preference', [App\Http\Controllers\ScholarController::class, 'storeSupervisorPreference'])->name('supervisor.preference.store');
        Route::get('/progress-report/submit', [App\Http\Controllers\ScholarController::class, 'submitProgressReport'])->name('progress_report.submit');
        Route::post('/progress-report', [App\Http\Controllers\ScholarController::class, 'storeProgressReport'])->name('progress_report.store');
        Route::get('/thesis/submit', [App\Http\Controllers\ScholarController::class, 'submitThesis'])->name('thesis.submit');
        Route::post('/thesis', [App\Http\Controllers\ScholarController::class, 'storeThesis'])->name('thesis.store');
        Route::get('/thesis/status', [App\Http\Controllers\ScholarController::class, 'viewThesisStatus'])->name('thesis.status');

        // New Thesis Submission Routes
        Route::get('/thesis/submission-form', [App\Http\Controllers\ScholarController::class, 'showThesisSubmissionForm'])->name('thesis.submission_form');
        Route::post('/thesis/submit-new', [App\Http\Controllers\ScholarController::class, 'storeThesis'])->name('thesis.submit_new');
        Route::get('/thesis/submissions/status', [App\Http\Controllers\ScholarController::class, 'thesisStatus'])->name('thesis.submissions.status');
        Route::post('/thesis/{thesis}/generate-certificate', [App\Http\Controllers\ScholarController::class, 'generateCertificate'])->name('thesis.generate_certificate');
        Route::get('/thesis/certificate/{certificate}', [App\Http\Controllers\ScholarController::class, 'showCertificate'])->name('thesis.certificate.show');
        Route::get('/thesis/certificate/{certificate}/download', [App\Http\Controllers\ScholarController::class, 'downloadCertificate'])->name('thesis.certificate.download');
        Route::get('/registration-letter/download', [App\Http\Controllers\ScholarController::class, 'downloadRegistrationLetter'])->name('registration_letter.download');
        Route::get('/supervisor-assignment/{assignment}/office-note/download', [App\Http\Controllers\ScholarController::class, 'downloadSupervisorOfficeNote'])->name('supervisor_assignment.office_note.download');
        Route::get('/thesis/eligibility', [App\Http\Controllers\ScholarController::class, 'thesisEligibility'])->name('thesis.eligibility');
        Route::get('/thesis/{thesis}/resubmit', [App\Http\Controllers\ScholarController::class, 'resubmitThesis'])->name('thesis.resubmit');
        Route::post('/thesis/{thesis}/resubmit', [App\Http\Controllers\ScholarController::class, 'storeResubmission'])->name('thesis.resubmit.store');
        Route::get('/thesis/{thesis}/certificate/download', [App\Http\Controllers\ScholarController::class, 'downloadSubmissionCertificate'])->name('thesis.submission_certificate.download');

        // Ph.D. Registration Form Routes
        Route::get('/registration/phd-form', [App\Http\Controllers\ScholarController::class, 'showPhdRegistrationForm'])->name('registration.phd_form');
        Route::patch('/registration/phd-form', [App\Http\Controllers\ScholarController::class, 'storePhdRegistrationForm'])->name('registration.phd_form.store');
        Route::get('/registration/supervisor-certificate', [App\Http\Controllers\ScholarController::class, 'showSupervisorCertificate'])->name('registration.supervisor_certificate');
        Route::patch('/registration/supervisor-certificate', [App\Http\Controllers\ScholarController::class, 'storeSupervisorCertificate'])->name('registration.supervisor_certificate.store');
        Route::get('/registration/hod-certificate', [App\Http\Controllers\ScholarController::class, 'showHodCertificate'])->name('registration.hod_certificate');
        Route::patch('/registration/hod-certificate', [App\Http\Controllers\ScholarController::class, 'storeHodCertificate'])->name('registration.hod_certificate.store');

        // Supervisor form editing routes
        Route::get('/scholar/{scholar}/form/edit', [App\Http\Controllers\ScholarController::class, 'supervisorEditScholarForm'])->name('supervisor.scholar.form_edit');
        Route::patch('/scholar/{scholar}/form/edit', [App\Http\Controllers\ScholarController::class, 'supervisorUpdateScholarForm'])->name('supervisor.scholar.form_update');

        // Registration Form Download
        Route::get('/registration-form/{registrationForm}/download', [App\Http\Controllers\RegistrationFormController::class, 'downloadRegistrationForm'])->name('registration_form.download');

        // Topic Change Response Routes
        Route::get('/synopsis/{synopsis}/topic-change-response', [App\Http\Controllers\ScholarController::class, 'showTopicChangeResponseForm'])->name('synopsis.topic-change-response');
        Route::post('/synopsis/{synopsis}/topic-change-response', [App\Http\Controllers\ScholarController::class, 'respondToTopicChange'])->name('synopsis.topic-change-response.store');

        // Late Submission Request
        Route::get('/late-submission/request', [App\Http\Controllers\LateSubmissionController::class, 'showRequestForm'])->name('late_submission.request');
        Route::post('/late-submission/request', [App\Http\Controllers\LateSubmissionController::class, 'submitRequest'])->name('late_submission.submit');
        Route::get('/late-submission/status', [App\Http\Controllers\LateSubmissionController::class, 'showStatus'])->name('late_submission.status');

        // Gantt Chart View

        // Registration Form Status
        Route::get('/registration-form/status', [App\Http\Controllers\RegistrationFormController::class, 'showScholarRegistrationFormStatus'])->name('registration_form.status');
    });
});

// Staff Login Routes
Route::prefix('staff')->name('staff.')->middleware('guest')->group(function () {
    Route::get('/login', [StaffLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StaffLoginController::class, 'login']);
});

// Staff Login & Dashboard
Route::prefix('staff')->name('staff.')->middleware(['auth'])->group(function () {
    Route::post('/logout', [StaffLoginController::class, 'logout'])->name('logout');
    Route::middleware([UserTypeMiddleware::class])->group(function () {
        Route::get('/dashboard', function () {
            $canUploadRacMinutes = false; // Initialize here
            if (Auth::user()->user_type === 'supervisor') {
                $supervisor = Auth::user()->supervisor;
                if ($supervisor) {
                    // Check if there are any RACs for this supervisor that are pending minutes upload
                    $canUploadRacMinutes = \App\Models\RAC::where('supervisor_id', $supervisor->id)
                                                ->where('status', 'pending_minutes_upload') // New status for when minutes are expected
                                                ->exists();
                }
            }

            $canUploadDrcMinutes = false;
            if (Auth::user()->user_type === 'hod') {
                $hodDepartment = Auth::user()->departmentManaging;
                if ($hodDepartment && $hodDepartment->drc) {
                    // Check for supervisor assignments approved/rejected by HOD, awaiting DRC minutes
                    $pendingSupervisorAssignmentMinutes = \App\Models\SupervisorAssignment::whereIn('status', ['approved', 'rejected'])
                                                            ->whereHas('scholar.admission.department', function ($query) use ($hodDepartment) {
                                                                $query->where('id', $hodDepartment->id);
                                                            })
                                                            ->where('drc_minutes_uploaded', false)
                                                            ->exists();

                    // Check for synopses approved/rejected by HOD, awaiting DRC minutes
                    $pendingSynopsisMinutes = \App\Models\Synopsis::whereIn('status', ['approved', 'rejected'])
                                                    ->whereHas('rac.scholar.admission.department', function ($query) use ($hodDepartment) {
                                                        $query->where('id', $hodDepartment->id);
                                                    })
                                                    ->where('drc_minutes_uploaded', false)
                                                    ->exists();

                    if ($pendingSupervisorAssignmentMinutes || $pendingSynopsisMinutes) {
                        $canUploadDrcMinutes = true;
                    }
                }
            }

            return view('staff.dashboard', compact('canUploadRacMinutes', 'canUploadDrcMinutes'));
        })->name('dashboard');
        Route::get('/scholars', [App\Http\Controllers\SupervisorController::class, 'listScholars'])->name('scholars.list');
        Route::get('/scholars/all-submissions', [App\Http\Controllers\SupervisorController::class, 'listAllScholarSubmissions'])->name('scholars.all_submissions');
        Route::get('/scholars/{scholar}', [App\Http\Controllers\SupervisorController::class, 'viewScholarDetails'])->name('scholars.show');
        Route::get('/scholars/{scholar}/verify-data', [App\Http\Controllers\SupervisorController::class, 'verifyScholarDataForm'])->name('scholars.verify_data');
        Route::patch('/scholars/{scholar}/verify-data', [App\Http\Controllers\SupervisorController::class, 'verifyScholarData'])->name('scholars.verify_data.update');
        Route::get('/rac-minutes/upload', [App\Http\Controllers\SupervisorController::class, 'uploadRacMinutesForm'])->name('rac_minutes.upload');
        Route::post('/rac-minutes', [App\Http\Controllers\SupervisorController::class, 'storeRacMinutes'])->name('rac_minutes.store');
        Route::get('/synopsis/{synopsis}/approve', [App\Http\Controllers\SupervisorController::class, 'approveSynopsisForm'])->name('synopsis.approve');
        Route::patch('/synopsis/{synopsis}/approve', [App\Http\Controllers\SupervisorController::class, 'approveSynopsis'])->name('synopsis.approve.update');
        Route::get('/thesis-evaluation/experts', [App\Http\Controllers\SupervisorController::class, 'suggestExpertsForm'])->name('thesis_evaluation.experts');
        Route::post('/thesis-evaluation/experts', [App\Http\Controllers\SupervisorController::class, 'storeExpertsSuggestion'])->name('thesis_evaluation.experts.store');
        Route::get('/coursework-exemption/request/{scholar}', [App\Http\Controllers\SupervisorController::class, 'courseworkExemptionRequestForm'])->name('coursework_exemption.request');
        Route::get('/coursework-exemptions/pending', [App\Http\Controllers\SupervisorController::class, 'listPendingCourseworkExemptions'])->name('coursework_exemption.pending');
        Route::post('/coursework-exemption/request', [App\Http\Controllers\SupervisorController::class, 'storeCourseworkExemptionRequest'])->name('coursework_exemption.request.store');
        Route::get('/coursework-exemptions/{exemption}/approve', [App\Http\Controllers\SupervisorController::class, 'approveCourseworkExemptionForm'])->name('coursework_exemption.approve');
        Route::post('/coursework-exemptions/{exemption}/approve', [App\Http\Controllers\SupervisorController::class, 'approveCourseworkExemption'])->name('coursework_exemption.approve.store');
        Route::get('/synopses/pending', [App\Http\Controllers\SupervisorController::class, 'listPendingSynopses'])->name('synopsis.pending');
        Route::get('/progress-reports/pending', [App\Http\Controllers\SupervisorController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
        Route::get('/progress-reports/{report}', [App\Http\Controllers\SupervisorController::class, 'showProgressReport'])->name('progress_reports.show');
        Route::get('/progress-reports/{report}/approve', [App\Http\Controllers\SupervisorController::class, 'approveProgressReportForm'])->name('progress_reports.approve');
        Route::post('/progress-reports/{report}/approve', [App\Http\Controllers\SupervisorController::class, 'approveProgressReport'])->name('progress_reports.approve.store');
        Route::get('/thesis/pending', [App\Http\Controllers\SupervisorController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
        Route::get('/thesis/{thesis}/approve', [App\Http\Controllers\SupervisorController::class, 'approveThesisForm'])->name('thesis.approve');
        Route::post('/thesis/{thesis}/approve', [App\Http\Controllers\SupervisorController::class, 'approveThesis'])->name('thesis.approve.store');
        Route::get('/thesis/{thesis}/certificate/download', [App\Http\Controllers\SupervisorController::class, 'downloadSubmissionCertificate'])->name('supervisor.thesis.submission_certificate.download');

        // Viva Examination Routes
        Route::get('/viva/examinations', [App\Http\Controllers\SupervisorController::class, 'listVivaExaminations'])->name('viva.examinations');
        Route::get('/viva/{vivaExamination}/report', [App\Http\Controllers\SupervisorController::class, 'showVivaReportForm'])->name('viva.report.form');
        Route::post('/viva/{vivaExamination}/report', [App\Http\Controllers\SupervisorController::class, 'storeVivaReport'])->name('viva.report.store');
        Route::get('/viva/report/{vivaReport}/download', [App\Http\Controllers\SupervisorController::class, 'downloadVivaReport'])->name('viva.report.download');
        Route::get('/viva/{vivaExamination}/office-note/download', [App\Http\Controllers\SupervisorController::class, 'downloadOfficeNote'])->name('viva.office_note.download');


        // Late Submission Request Routes
        Route::get('/late-submission/pending', [App\Http\Controllers\LateSubmissionController::class, 'listPendingForSupervisor'])->name('late_submission.pending');
        Route::get('/late-submission/{lateSubmissionRequest}/approve', [App\Http\Controllers\LateSubmissionController::class, 'showApprovalForm'])->name('late_submission.approve');
        Route::post('/late-submission/{lateSubmissionRequest}/approve', [App\Http\Controllers\LateSubmissionController::class, 'processApproval'])->name('late_submission.process');
    });
});

// HOD Routes
Route::prefix('hod')->name('hod.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/admissions/upload-merit-list', [HODController::class, 'showUploadMeritListForm'])->name('admissions.upload_merit_list');
    Route::post('/admissions/upload-merit-list', [HODController::class, 'uploadMeritList'])->name('admissions.store_merit_list');
    Route::get('/admissions/download-template', [HODController::class, 'downloadMeritListTemplate'])->name('admissions.download_template');
    Route::get('/admissions/view-merit-lists', [HODController::class, 'viewMeritLists'])->name('admissions.view_merit_lists');
    Route::get('/scholars', [HODController::class, 'listScholars'])->name('scholars.list');
    Route::get('/scholars/submissions', [HODController::class, 'listScholarsWithSubmissions'])->name('scholars.submissions');
    Route::get('/scholars/all-submissions', [HODController::class, 'listAllScholarSubmissions'])->name('scholars.all_submissions');
    Route::get('/scholars/{scholar}', [HODController::class, 'viewScholarDetails'])->name('scholars.show');
    Route::get('/scholars/{scholar}/assign-supervisor', [HODController::class, 'assignSupervisorForm'])->name('scholars.assign_supervisor');
    Route::post('/scholars/{scholar}/assign-supervisor', [HODController::class, 'storeSupervisorAssignment'])->name('scholars.assign_supervisor.store');
    Route::get('/supervisors', [HODController::class, 'listSupervisors'])->name('supervisors.list');
    Route::get('/supervisor-assignments/pending', [HODController::class, 'listPendingSupervisorAssignments'])->name('supervisor_assignments.pending');
    Route::patch('/supervisor-assignments/{assignment}/approve', [HODController::class, 'approveSupervisorAssignment'])->name('supervisor_assignments.approve');
    Route::patch('/supervisor-assignments/{assignment}/reject', [HODController::class, 'rejectSupervisorAssignment'])->name('supervisor_assignments.reject');

    // Supervisor Preferences Routes
    Route::get('/supervisor-preferences/pending', [HODController::class, 'listPendingSupervisorPreferences'])->name('supervisor_preferences.pending');
    Route::get('/supervisor-preferences/{scholarId}/approve', [HODController::class, 'showSupervisorPreferencesApprovalForm'])->name('supervisor_preferences.approve');
    Route::post('/supervisor-preferences/{scholarId}/approve', [HODController::class, 'approveSupervisorPreferences'])->name('supervisor_preferences.approve.store');
    Route::get('/synopsis/pending', [HODController::class, 'listPendingSynopses'])->name('synopsis.pending');
    Route::get('/synopsis/{synopsis}/approve', [HODController::class, 'approveSynopsisForm'])->name('synopsis.approve');
    Route::post('/synopsis/{synopsis}/approve', [HODController::class, 'approveSynopsis'])->name('synopsis.approve.store');

    // Progress Reports Routes
    Route::get('/progress-reports/pending', [HODController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
    Route::get('/progress-reports/{progressReport}/approve', [HODController::class, 'approveProgressReportForm'])->name('progress_reports.approve');
    Route::post('/progress-reports/{progressReport}/approve', [HODController::class, 'approveProgressReport'])->name('progress_reports.approve.store');

    // Thesis Submissions Routes
    Route::get('/thesis/pending', [HODController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
    Route::get('/thesis/{thesis}/approve', [HODController::class, 'approveThesisForm'])->name('thesis.approve');
    Route::post('/thesis/{thesis}/approve', [HODController::class, 'approveThesis'])->name('thesis.approve.store');

    // Viva Examination Management Routes
    Route::get('/viva/examinations', [HODController::class, 'listVivaExaminations'])->name('viva.examinations');
    Route::get('/thesis/{thesis}/schedule-viva', [HODController::class, 'showScheduleVivaForm'])->name('thesis.schedule_viva');
    Route::post('/thesis/{thesis}/schedule-viva', [HODController::class, 'scheduleViva'])->name('thesis.schedule_viva.store');
    Route::get('/viva/{vivaExamination}/details', [HODController::class, 'showVivaDetails'])->name('viva.details');
    Route::post('/viva/{vivaExamination}/update-status', [HODController::class, 'updateVivaStatus'])->name('viva.update_status');
    Route::get('/viva/report/{vivaReport}/download', [HODController::class, 'downloadVivaReport'])->name('viva.report.download');
    Route::get('/viva/{vivaExamination}/office-note/download', [HODController::class, 'downloadOfficeNote'])->name('viva.office_note.download');

    // Late Submission Request Routes
    Route::get('/late-submission/pending', [App\Http\Controllers\LateSubmissionController::class, 'listPendingForHOD'])->name('late_submission.pending');
    Route::post('/late-submission/{lateSubmissionRequest}/approve', [App\Http\Controllers\LateSubmissionController::class, 'processHODApproval'])->name('late_submission.process');
});

// DRC Routes (managed by HOD)
Route::prefix('drc')->name('drc.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/supervisor-assignments/pending', [DRCController::class, 'listPendingSupervisorAssignments'])->name('supervisor_assignments.pending');
    Route::patch('/supervisor-assignments/{assignment}/approve', [DRCController::class, 'approveSupervisorAssignment'])->name('supervisor_assignments.approve');
    Route::get('/synopses/pending', [DRCController::class, 'listPendingSynopses'])->name('synopsis.pending');
    Route::patch('/synopses/{synopsis}/approve', [DRCController::class, 'approveSynopsis'])->name('synopsis.approve');
    Route::get('/minutes/upload', [DRCController::class, 'uploadDRCMinutesForm'])->name('minutes.upload');
    Route::post('/minutes', [DRCController::class, 'storeDRCMinutes'])->name('minutes.store');
    Route::get('/progress-reports/pending', [DRCController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
    Route::get('/progress-reports/{report}/approve', [DRCController::class, 'approveProgressReportForm'])->name('progress_reports.approve');
    Route::post('/progress-reports/{report}/approve', [DRCController::class, 'approveProgressReport'])->name('progress_reports.approve.store');
    Route::get('/thesis/pending', [DRCController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
    Route::get('/thesis/{thesis}/approve', [DRCController::class, 'approveThesisForm'])->name('thesis.approve');
    Route::post('/thesis/{thesis}/approve', [DRCController::class, 'approveThesis'])->name('thesis.approve.store');
});

// Dean Routes
Route::prefix('dean')->name('dean.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/coursework-exemptions/pending', [DeanController::class, 'listPendingCourseworkExemptions'])->name('coursework_exemptions.pending');
    Route::patch('/coursework-exemptions/{courseworkExemption}/approve', [DeanController::class, 'approveCourseworkExemption'])->name('coursework_exemptions.approve');
    Route::get('/scholars', [DeanController::class, 'listAllScholars'])->name('scholars.list');
    Route::get('/scholars/{scholar}', [DeanController::class, 'viewScholarDetails'])->name('scholars.show');
    Route::get('/supervisors', [DeanController::class, 'listSupervisors'])->name('supervisors.list');
    Route::get('/theses', [DeanController::class, 'listThesis'])->name('thesis.list');
    Route::get('/synopsis', [DeanController::class, 'listSynopsis'])->name('synopsis.list');

    // Late Submission Request Routes
    Route::get('/late-submission/pending', [DeanController::class, 'listPendingLateSubmissions'])->name('late_submission.pending');
    Route::post('/late-submission/{lateSubmissionRequest}/approve', [DeanController::class, 'processLateSubmissionApproval'])->name('late_submission.process');
});

// DA (Dean's Assistant) Routes
Route::prefix('da')->name('da.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/dashboard', [DAController::class, 'dashboard'])->name('dashboard');
    Route::get('/capacity-requests/pending', [DAController::class, 'listPendingCapacityRequests'])->name('capacity_requests.pending');
    Route::get('/capacity-requests/{request}/approve', [DAController::class, 'showApprovalForm'])->name('capacity_requests.approve');
    Route::post('/capacity-requests/{request}/approve', [DAController::class, 'processApproval'])->name('capacity_requests.process');

    Route::get('/synopses/pending', [DAController::class, 'listPendingSynopses'])->name('synopses.pending');
    Route::get('/synopses/all', [DAController::class, 'listAllSynopses'])->name('synopses.all');
    Route::get('/synopses/{synopsis}/approve', [DAController::class, 'showSynopsisApprovalForm'])->name('synopses.approve');
    Route::post('/synopses/{synopsis}/approve', [DAController::class, 'processSynopsisApproval'])->name('synopses.process');

    // All Scholar Submissions
    Route::get('/scholars/all-submissions', [DAController::class, 'listAllScholarSubmissions'])->name('scholars.all_submissions');

    // Registration Form Routes
    Route::get('/registration-forms/eligible-scholars', [App\Http\Controllers\RegistrationFormController::class, 'listEligibleScholars'])->name('registration_forms.eligible_scholars');
    Route::post('/registration-forms/generate/{scholar}', [App\Http\Controllers\RegistrationFormController::class, 'generateRegistrationForm'])->name('registration_forms.generate');
    Route::get('/registration-forms', [App\Http\Controllers\RegistrationFormController::class, 'listRegistrationForms'])->name('registration_forms.list');
    Route::get('/registration-forms/{registrationForm}/official-letter', [App\Http\Controllers\RegistrationFormController::class, 'showOfficialLetter'])->name('registration_forms.official_letter');

    // Expert Evaluation Routes
    Route::get('/thesis/evaluations-for-assignment', [App\Http\Controllers\ExpertEvaluationController::class, 'listThesisEvaluationsForAssignment'])->name('thesis.evaluations_for_assignment');
    Route::post('/thesis/{thesis}/assign-final-experts', [App\Http\Controllers\ExpertEvaluationController::class, 'assignFinalExperts'])->name('thesis.assign_final_experts');
    Route::post('/thesis/{thesis}/issue-evaluation-letters', [App\Http\Controllers\ExpertEvaluationController::class, 'issueEvaluationLetters'])->name('thesis.issue_evaluation_letters');

    Route::get('/coursework-exemptions/pending', [DAController::class, 'listPendingCourseworkExemptions'])->name('coursework_exemptions.pending');
    Route::get('/coursework-exemptions/{exemption}/approve', [DAController::class, 'showCourseworkExemptionApprovalForm'])->name('coursework_exemptions.approve');
    Route::post('/coursework-exemptions/{exemption}/approve', [DAController::class, 'processCourseworkExemptionApproval'])->name('coursework_exemptions.process');

    Route::get('/progress-reports/pending', [DAController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
    Route::get('/progress-reports/{report}/approve', [DAController::class, 'showProgressReportApprovalForm'])->name('progress_reports.approve');
    Route::post('/progress-reports/{report}/approve', [DAController::class, 'processProgressReportApproval'])->name('progress_reports.process');

    Route::get('/thesis/pending', [DAController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
    Route::get('/thesis/{thesis}/approve', [DAController::class, 'showThesisApprovalForm'])->name('thesis.approve');
    Route::post('/thesis/{thesis}/approve', [DAController::class, 'processThesisApproval'])->name('thesis.process');

    // Late Submission Request Routes
    Route::get('/late-submission/pending', [DAController::class, 'listPendingLateSubmissions'])->name('late_submission.pending');
    Route::post('/late-submission/{lateSubmissionRequest}/approve', [DAController::class, 'processLateSubmissionApproval'])->name('late_submission.process');

    // Office Note Routes
    Route::get('/office-notes/eligible-scholars', [DAController::class, 'listEligibleScholars'])->name('office_notes.eligible_scholars');
    Route::get('/office-notes/generate/{scholar}', [DAController::class, 'showOfficeNoteForm'])->name('office_notes.generate');
    Route::post('/office-notes/generate/{scholar}', [DAController::class, 'generateOfficeNote'])->name('office_notes.generate.store');
    Route::get('/office-notes/{officeNote}', [DAController::class, 'showOfficeNote'])->name('office_notes.show');
    Route::get('/office-notes/{officeNote}/edit', [DAController::class, 'editOfficeNote'])->name('office_notes.edit');
    Route::patch('/office-notes/{officeNote}', [DAController::class, 'updateOfficeNote'])->name('office_notes.update');
    Route::get('/office-notes/{officeNote}/download', [DAController::class, 'downloadOfficeNote'])->name('office_notes.download');

    // Final Certificate Routes
    Route::get('/final-certificates/eligible-thesis', [App\Http\Controllers\FinalCertificateController::class, 'listEligibleThesis'])->name('final_certificates.eligible_thesis');
    Route::get('/final-certificates/generate/{thesis}', [App\Http\Controllers\FinalCertificateController::class, 'showCertificateForm'])->name('final_certificates.generate');
    Route::post('/final-certificates/generate/{thesis}', [App\Http\Controllers\FinalCertificateController::class, 'generateCertificate'])->name('final_certificates.generate.store');
    Route::get('/final-certificates', [App\Http\Controllers\FinalCertificateController::class, 'listCertificates'])->name('final_certificates.list');
    Route::get('/final-certificates/{certificate}', [App\Http\Controllers\FinalCertificateController::class, 'showCertificate'])->name('final_certificates.show');
    Route::get('/final-certificates/{certificate}/download', [App\Http\Controllers\FinalCertificateController::class, 'downloadCertificate'])->name('final_certificates.download');
});

// SO (Section Officer) Routes
Route::prefix('so')->name('so.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/capacity-requests/pending', [SOController::class, 'listPendingCapacityRequests'])->name('capacity_requests.pending');
    Route::get('/capacity-requests/{request}/approve', [SOController::class, 'showApprovalForm'])->name('capacity_requests.approve');
    Route::post('/capacity-requests/{request}/approve', [SOController::class, 'processApproval'])->name('capacity_requests.process');

    // Synopses
    Route::get('/synopses/pending', [SOController::class, 'listPendingSynopses'])->name('synopses.pending');
    Route::get('/synopses/all', [SOController::class, 'listAllSynopses'])->name('synopses.all');
    Route::get('/synopses/{synopsis}/approve', [SOController::class, 'showSynopsisApprovalForm'])->name('synopses.approve');
    Route::post('/synopses/{synopsis}/process', [SOController::class, 'processSynopsisApproval'])->name('synopses.process');

    // Progress Reports
    Route::get('/progress-reports/pending', [SOController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
    Route::get('/progress-reports/all', [SOController::class, 'listAllProgressReports'])->name('progress_reports.all');

    // Thesis Submissions
    Route::get('/thesis/pending', [SOController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
    Route::get('/thesis/all', [SOController::class, 'listAllThesisSubmissions'])->name('thesis.all');

    // Coursework Exemptions
    Route::get('/coursework-exemptions/pending', [SOController::class, 'listPendingCourseworkExemptions'])->name('coursework_exemptions.pending');
    Route::get('/coursework-exemptions/all', [SOController::class, 'listAllCourseworkExemptions'])->name('coursework_exemptions.all');

    // All Scholar Submissions
    Route::get('/scholars/all-submissions', [SOController::class, 'listAllScholarSubmissions'])->name('scholars.all_submissions');
});

// AR (Assistant Registrar) Routes
Route::prefix('ar')->name('ar.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/capacity-requests/pending', [ARController::class, 'listPendingCapacityRequests'])->name('capacity_requests.pending');
    Route::get('/capacity-requests/{request}/approve', [ARController::class, 'showApprovalForm'])->name('capacity_requests.approve');
    Route::post('/capacity-requests/{request}/approve', [ARController::class, 'processApproval'])->name('capacity_requests.process');

    // Synopses
    Route::get('/synopses/pending', [ARController::class, 'listPendingSynopses'])->name('synopses.pending');
    Route::get('/synopses/all', [ARController::class, 'listAllSynopses'])->name('synopses.all');
    Route::get('/synopses/{synopsis}/approve', [ARController::class, 'showSynopsisApprovalForm'])->name('synopses.approve');
    Route::post('/synopses/{synopsis}/process', [ARController::class, 'processSynopsisApproval'])->name('synopses.process');

    // Progress Reports
    Route::get('/progress-reports/pending', [ARController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
    Route::get('/progress-reports/all', [ARController::class, 'listAllProgressReports'])->name('progress_reports.all');

    // Thesis Submissions
    Route::get('/thesis/pending', [ARController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
    Route::get('/thesis/all', [ARController::class, 'listAllThesisSubmissions'])->name('thesis.all');

    // Coursework Exemptions
    Route::get('/coursework-exemptions/pending', [ARController::class, 'listPendingCourseworkExemptions'])->name('coursework_exemptions.pending');
    Route::get('/coursework-exemptions/all', [ARController::class, 'listAllCourseworkExemptions'])->name('coursework_exemptions.all');

    // All Scholar Submissions
    Route::get('/scholars/all-submissions', [ARController::class, 'listAllScholarSubmissions'])->name('scholars.all_submissions');

    // Registration Forms
    Route::get('/registration-forms/pending', [App\Http\Controllers\RegistrationFormController::class, 'listPendingForAR'])->name('registration_forms.pending');
    Route::post('/registration-forms/{registrationForm}/sign', [App\Http\Controllers\RegistrationFormController::class, 'signByAR'])->name('registration_forms.sign');
});

// DR (Deputy Registrar) Routes
Route::prefix('dr')->name('dr.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/capacity-requests/pending', [DRController::class, 'listPendingCapacityRequests'])->name('capacity_requests.pending');
    Route::get('/capacity-requests/{request}/approve', [DRController::class, 'showApprovalForm'])->name('capacity_requests.approve');
    Route::post('/capacity-requests/{request}/approve', [DRController::class, 'processApproval'])->name('capacity_requests.process');

    // Synopses
    Route::get('/synopses/pending', [DRController::class, 'listPendingSynopses'])->name('synopses.pending');
    Route::get('/synopses/all', [DRController::class, 'listAllSynopses'])->name('synopses.all');
    Route::get('/synopses/{synopsis}/approve', [DRController::class, 'showSynopsisApprovalForm'])->name('synopses.approve');
    Route::post('/synopses/{synopsis}/process', [DRController::class, 'processSynopsisApproval'])->name('synopses.process');

    // Progress Reports
    Route::get('/progress-reports/pending', [DRController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
    Route::get('/progress-reports/all', [DRController::class, 'listAllProgressReports'])->name('progress_reports.all');

    // Thesis Submissions
    Route::get('/thesis/pending', [DRController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
    Route::get('/thesis/all', [DRController::class, 'listAllThesisSubmissions'])->name('thesis.all');

    // Coursework Exemptions
    Route::get('/coursework-exemptions/pending', [DRController::class, 'listPendingCourseworkExemptions'])->name('coursework_exemptions.pending');
    Route::get('/coursework-exemptions/all', [DRController::class, 'listAllCourseworkExemptions'])->name('coursework_exemptions.all');

    // All Scholar Submissions
    Route::get('/scholars/all-submissions', [DRController::class, 'listAllScholarSubmissions'])->name('scholars.all_submissions');

    // Registration Forms
    Route::get('/registration-forms/pending', [App\Http\Controllers\RegistrationFormController::class, 'listPendingForDR'])->name('registration_forms.pending');
    Route::post('/registration-forms/{registrationForm}/sign', [App\Http\Controllers\RegistrationFormController::class, 'signByDR'])->name('registration_forms.sign');
});

// HVC (Head of Verification Committee) Routes
Route::prefix('hvc')->name('hvc.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/capacity-requests/pending', [HVCController::class, 'listPendingCapacityRequests'])->name('capacity_requests.pending');
    Route::get('/capacity-requests/{request}/approve', [HVCController::class, 'showApprovalForm'])->name('capacity_requests.approve');
    Route::post('/capacity-requests/{request}/approve', [HVCController::class, 'processApproval'])->name('capacity_requests.process');

    // Synopsis Approval Routes
    Route::get('/synopses/pending', [HVCController::class, 'listPendingSynopses'])->name('synopses.pending');
    Route::get('/synopses/all', [HVCController::class, 'listAllSynopses'])->name('synopses.all');
    Route::get('/synopses/{synopsis}/approve', [HVCController::class, 'showSynopsisApprovalForm'])->name('synopses.approve');
    Route::post('/synopses/{synopsis}/process', [HVCController::class, 'processSynopsisApproval'])->name('synopses.process');

    // Progress Reports
    Route::get('/progress-reports/pending', [HVCController::class, 'listPendingProgressReports'])->name('progress_reports.pending');
    Route::get('/progress-reports/all', [HVCController::class, 'listAllProgressReports'])->name('progress_reports.all');

    // Thesis Submissions
    Route::get('/thesis/pending', [HVCController::class, 'listPendingThesisSubmissions'])->name('thesis.pending');
    Route::get('/thesis/all', [HVCController::class, 'listAllThesisSubmissions'])->name('thesis.all');

    // Coursework Exemptions
    Route::get('/coursework-exemptions/pending', [HVCController::class, 'listPendingCourseworkExemptions'])->name('coursework_exemptions.pending');
    Route::get('/coursework-exemptions/all', [HVCController::class, 'listAllCourseworkExemptions'])->name('coursework_exemptions.all');

    // All Scholar Submissions
    Route::get('/scholars/all-submissions', [HVCController::class, 'listAllScholarSubmissions'])->name('scholars.all_submissions');

    // Thesis Approval Routes
    Route::get('/thesis/pending-approval', [HVCController::class, 'listPendingThesisApprovals'])->name('thesis.pending_approval');
    Route::get('/thesis/{thesis}/approve', [HVCController::class, 'approveThesisForm'])->name('thesis.approve');
    Route::post('/thesis/{thesis}/approve', [HVCController::class, 'approveThesis'])->name('thesis.approve.store');

    // Thesis Evaluation Routes
    Route::get('/thesis/approved', [App\Http\Controllers\ExpertEvaluationController::class, 'listApprovedThesisSubmissions'])->name('thesis.approved');
    Route::get('/thesis/{thesis}/select-experts', [App\Http\Controllers\ExpertEvaluationController::class, 'selectExpertsForm'])->name('thesis.select_experts');
    Route::post('/thesis/{thesis}/select-experts', [App\Http\Controllers\ExpertEvaluationController::class, 'selectExperts'])->name('thesis.select_experts.store');
    Route::get('/thesis/evaluations', [HVCController::class, 'listThesisEvaluations'])->name('thesis.evaluations');
    Route::get('/thesis/evaluations/{evaluation}/review', [HVCController::class, 'reviewEvaluationForm'])->name('thesis.evaluation.review');

    // Viva Management Routes
    Route::get('/viva/completed-evaluations', [App\Http\Controllers\ExpertEvaluationController::class, 'listCompletedEvaluations'])->name('viva.completed_evaluations');
    Route::post('/viva/{thesis}/schedule', [App\Http\Controllers\ExpertEvaluationController::class, 'scheduleViva'])->name('viva.schedule.store');
    Route::post('/thesis/evaluations/{evaluation}/review', [HVCController::class, 'processEvaluationReview'])->name('thesis.evaluation.review.store');

    // Viva Process Routes
    Route::get('/viva/candidates', [HVCController::class, 'listVivaCandidates'])->name('viva.candidates');
    Route::get('/viva/{thesis}/schedule', [HVCController::class, 'scheduleVivaForm'])->name('viva.schedule');
    Route::post('/viva/{thesis}/schedule', [HVCController::class, 'scheduleViva'])->name('viva.schedule.store');
    Route::get('/viva/scheduled', [HVCController::class, 'listScheduledVivas'])->name('viva.scheduled');
    Route::get('/viva/{viva}/review-report', [HVCController::class, 'reviewVivaReportForm'])->name('viva.review_report');
    Route::post('/viva/{viva}/review-report', [HVCController::class, 'processVivaReview'])->name('viva.review_report.store');
});

// Expert Routes
Route::prefix('expert')->name('expert.')->middleware(['auth', UserTypeMiddleware::class])->group(function () {
    Route::get('/evaluations', [App\Http\Controllers\ExpertEvaluationController::class, 'listMyEvaluations'])->name('evaluations.list');
    Route::post('/evaluations/{evaluation}/submit', [App\Http\Controllers\ExpertEvaluationController::class, 'submitEvaluation'])->name('evaluations.submit');
});

require __DIR__.'/auth.php';

// Custom login routes
Route::middleware('guest')->group(function () {
    // Scholar Login Routes
    Route::get('/scholar/login', [App\Http\Controllers\Auth\ScholarLoginController::class, 'showLoginForm'])->name('scholar.login');
    Route::post('/scholar/login', [App\Http\Controllers\Auth\ScholarLoginController::class, 'login']);

    // Staff Login Routes
    Route::get('/staff/login', [App\Http\Controllers\Auth\StaffLoginController::class, 'showLoginForm'])->name('staff.login');
    Route::post('/staff/login', [App\Http\Controllers\Auth\StaffLoginController::class, 'login']);
});

// Logout Routes (accessible to authenticated users)
Route::post('/scholar/logout', [App\Http\Controllers\Auth\ScholarLoginController::class, 'logout'])->name('scholar.logout');
Route::post('/staff/logout', [App\Http\Controllers\Auth\StaffLoginController::class, 'logout'])->name('staff.logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/progress-report-config', function() {
        return view('admin.progress_report_config');
    })->name('progress_report_config');
});

// Debug route (remove in production)
Route::get('/debug-login', function () {
    return view('debug-login');
})->name('debug.login');

// Debug route to check current user
Route::get('/debug-user', function () {
    $user = Auth::user();
    if ($user) {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'role_id' => $user->role_id,
            'supervisor_id' => $user->supervisor ? $user->supervisor->id : null,
        ]);
    }
    return response()->json(['error' => 'No user authenticated']);
})->middleware('auth');

// Workflow Dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/workflow/dashboard', [\App\Http\Controllers\WorkflowDashboardController::class, 'index'])->name('workflow.dashboard');
    Route::get('/workflow/scholar/{scholar}/status', [\App\Http\Controllers\WorkflowDashboardController::class, 'getScholarWorkflowStatus'])->name('workflow.scholar.status');
    Route::get('/workflow/pending-items', [\App\Http\Controllers\WorkflowDashboardController::class, 'getPendingItems'])->name('workflow.pending.items');
});
