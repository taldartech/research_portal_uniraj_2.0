<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admission_id',
        'enrollment_number',
        'form_number',
        'date_of_confirmation',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'contact_number',
        'address',
        'academic_information',
        'research_area',
        'status',
        'enrollment_status',
        'enrolled_at',
        'registration_form_id',
        // Ph.D. Registration Form Fields
        'father_name',
        'mother_name',
        'nationality',
        'category',
        'occupation',
        'is_teacher',
        'teacher_employer',
        'appearing_other_exam',
        'other_exam_details',
        'research_topic_title',
        'research_scheme_outline',
        'research_bibliography',
        'supervisor_name',
        'supervisor_designation',
        'supervisor_department',
        'supervisor_college',
        'supervisor_address',
        'supervisor_letter_number',
        'supervisor_letter_date',
        'has_co_supervisor',
        'co_supervisor_name',
        'co_supervisor_designation',
        'co_supervisor_reasons',
        'co_supervisor_letter_number',
        'co_supervisor_letter_date',
        'post_graduate_degree',
        'post_graduate_university',
        'post_graduate_year',
        'post_graduate_percentage',
        // Multiple academic qualifications
        'academic_qualifications',
        'net_slet_csir_gate_exam',
        'net_slet_csir_gate_year',
        'net_slet_csir_gate_roll_number',
        'mpat_year',
        'mpat_roll_number',
        'mpat_merit_number',
        'mpat_subject',
        'coursework_exam_date',
        'coursework_marks_obtained',
        'coursework_max_marks',
        'phd_faculty',
        'phd_subject',
        'registration_form_status',
        'registration_form_submitted_at',
        'registration_documents',
        'supervisor_certificate_completed',
        'hod_certificate_completed',
        'supervisor_certificate_date',
        'hod_certificate_date',
        // Registration Letter fields
        'registration_letter_file',
        'registration_letter_generated',
        'registration_letter_generated_at',
        'registration_letter_signed_by',
        'registration_letter_signed_at',
        'coursework_completed',
        // Synopsis fields
        'synopsis_topic',
        'synopsis_file',
        'synopsis_submitted_at',
        'synopsis_status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'academic_information' => 'array',
        'date_of_confirmation' => 'date',
        'enrolled_at' => 'datetime',
        'is_teacher' => 'boolean',
        'appearing_other_exam' => 'boolean',
        'has_co_supervisor' => 'boolean',
        'supervisor_letter_date' => 'date',
        'co_supervisor_letter_date' => 'date',
        'post_graduate_percentage' => 'decimal:2',
        'academic_qualifications' => 'array',
        'registration_form_submitted_at' => 'datetime',
        'registration_documents' => 'array',
        'supervisor_certificate_completed' => 'boolean',
        'hod_certificate_completed' => 'boolean',
        'supervisor_certificate_date' => 'datetime',
        'hod_certificate_date' => 'datetime',
        'registration_letter_generated' => 'boolean',
        'registration_letter_generated_at' => 'datetime',
        'registration_letter_signed_at' => 'datetime',
        'coursework_completed' => 'boolean',
        'synopsis_submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function supervisorAssignments()
    {
        return $this->hasMany(SupervisorAssignment::class);
    }

    public function supervisor()
    {
        return $this->hasOneThrough(
            User::class,
            SupervisorAssignment::class,
            'scholar_id',
            'id',
            'id',
            'supervisor_id'
        )->where('supervisor_assignments.status', 'assigned');
    }

    public function currentSupervisor()
    {
        return $this->hasOne(SupervisorAssignment::class)->where('status', 'assigned')->latestOfMany();
    }

    public function racs()
    {
        return $this->hasMany(RAC::class);
    }

    public function synopses()
    {
        return $this->hasMany(Synopsis::class);
    }

    public function hasAssignedSupervisor()
    {
        return $this->supervisorAssignments()->where('status', 'assigned')->exists();
    }

    public function canEditProfile()
    {
        // Scholar can edit profile only if they don't have an assigned supervisor yet
        return !$this->hasAssignedSupervisor();
    }

    public function isProfileComplete()
    {
        // Check if all required profile fields are filled
        return !empty($this->first_name) &&
               !empty($this->last_name) &&
               !empty($this->date_of_birth) &&
               !empty($this->contact_number) &&
               !empty($this->address) &&
            //    !empty($this->research_area) &&
               !empty($this->father_name) &&
               !empty($this->mother_name) &&
               !empty($this->nationality) &&
               !empty($this->post_graduate_degree) &&
               !empty($this->post_graduate_university) &&
               !empty($this->post_graduate_year) &&
               !empty($this->post_graduate_percentage) &&
               !empty($this->phd_faculty) &&
               !empty($this->phd_subject);
    }

    public function vivaExaminations()
    {
        return $this->hasMany(\App\Models\VivaExamination::class);
    }

    public function progressReports()
    {
        return $this->hasMany(\App\Models\ProgressReport::class);
    }

    public function populateSupervisorInfo()
    {
        if ($this->hasAssignedSupervisor()) {
            $supervisor = $this->supervisor;
            if ($supervisor) {
                $updateData = [
                    'supervisor_name' => $supervisor->first_name . ' ' . $supervisor->last_name,
                    'supervisor_designation' => $supervisor->designation ?? 'Supervisor',
                    'supervisor_department' => $supervisor->department ?? 'Not specified',
                    'supervisor_college' => $supervisor->college ?? 'Not specified',
                    'supervisor_address' => $supervisor->address ?? 'Not specified',
                ];

                // Add research information if provided by supervisor
                if ($supervisor->research_topic_title) {
                    $updateData['research_topic_title'] = $supervisor->research_topic_title;
                }
                if ($supervisor->research_scheme_outline) {
                    $updateData['research_scheme_outline'] = $supervisor->research_scheme_outline;
                }
                if ($supervisor->research_bibliography) {
                    $updateData['research_bibliography'] = $supervisor->research_bibliography;
                }

                $this->update($updateData);
            }
        }
    }

    public function submittedSupervisorPreference()
    {
        return $this->hasOne(SupervisorAssignment::class)->latestOfMany();
    }

    public function courseworkExemptions()
    {
        return $this->hasMany(CourseworkExemption::class);
    }

    public function thesisSubmissions()
    {
        return $this->hasMany(ThesisSubmission::class);
    }

    // Ph.D. Registration Form Helper Methods
    public function isRegistrationFormCompleted()
    {
        return $this->registration_form_status === 'completed';
    }

    public function isRegistrationFormSubmitted()
    {
        return $this->registration_form_status === 'submitted';
    }

    public function canSubmitRegistrationForm()
    {
        return $this->registration_form_status === 'completed' &&
               $this->supervisor_certificate_completed &&
               $this->hod_certificate_completed;
    }

    public function getRegistrationFormProgress()
    {
        $requiredFields = [
            'father_name', 'mother_name', 'nationality', 'category', 'occupation',
            'phd_faculty', 'phd_subject', 'synopsis_topic', 'synopsis_file'
        ];

        $completedFields = 0;
        foreach ($requiredFields as $field) {
            if (!empty($this->$field)) {
                $completedFields++;
            }
        }

        return ($completedFields / count($requiredFields)) * 100;
    }

    public function canEditRegistrationForm()
    {
        // Scholar can only edit if form is not started yet
        return $this->registration_form_status === 'not_started';
    }

    public function isFormUnderReview()
    {
        return in_array($this->registration_form_status, ['submitted', 'under_review']);
    }

    public function isFormApproved()
    {
        return $this->registration_form_status === 'approved';
    }

    public function registrationForm()
    {
        return $this->hasOne(RegistrationForm::class);
    }

    public function officeNote()
    {
        return $this->hasOne(OfficeNote::class);
    }

    public function thesisSubmissionCertificates()
    {
        return $this->hasMany(ThesisSubmissionCertificate::class);
    }

    public function finalCertificates()
    {
        return $this->hasMany(FinalCertificate::class);
    }

    public function lateSubmissionRequests()
    {
        return $this->hasMany(LateSubmissionRequest::class);
    }

    public function approvedCourseworkExemption()
    {
        return $this->courseworkExemptions()->where('status', 'approved')->latest()->first();
    }

    public function hasApprovedCourseworkExemption()
    {
        return $this->courseworkExemptions()->where('status', 'approved')->exists();
    }

    public function hasExistingThesis()
    {
        return $this->thesisSubmissions()->exists();
    }

    public function getThesisEligibilityStatus()
    {
        if (!$this->date_of_confirmation) {
            return [
                'eligible' => false,
                'reason' => 'Date of Confirmation (DOC) not set',
                'can_submit' => false,
                'requires_special_approval' => false,
                'days_remaining' => null,
                'eligible_date' => null,
                'max_date' => null,
                'is_coursework_exempted' => false
            ];
        }

        $doc = $this->date_of_confirmation;
        $now = now();
        $isCourseworkExempted = $this->hasApprovedCourseworkExemption();

        // Calculate eligibility period
        if ($isCourseworkExempted) {
            // Coursework-exempted scholars: 2.5 years from DOC
            $eligibleDate = $doc->addYears(2)->addMonths(6);
            $maxDate = $doc->addYears(6);
        } else {
            // Regular scholars: 3 years from DOC
            $eligibleDate = $doc->addYears(3);
            $maxDate = $doc->addYears(6);
        }

        // Check if within 6-year limit
        if ($now->gt($maxDate)) {
            return [
                'eligible' => false,
                'reason' => 'Exceeded 6-year limit from Date of Confirmation',
                'can_submit' => false,
                'requires_special_approval' => true,
                'days_remaining' => 0,
                'eligible_date' => $eligibleDate,
                'max_date' => $maxDate
            ];
        }

        // Check if eligible period has passed
        if ($now->gte($eligibleDate)) {
            $daysRemaining = $maxDate->diffInDays($now);

            return [
                'eligible' => true,
                'reason' => $isCourseworkExempted ? 'Eligible (Coursework Exempted - 2.5 years from DOC)' : 'Eligible (3 years from DOC)',
                'can_submit' => true,
                'requires_special_approval' => false,
                'days_remaining' => $daysRemaining,
                'eligible_date' => $eligibleDate,
                'max_date' => $maxDate,
                'is_coursework_exempted' => $isCourseworkExempted
            ];
        } else {
            $daysUntilEligible = $eligibleDate->diffInDays($now);

            return [
                'eligible' => false,
                'reason' => $isCourseworkExempted ?
                    'Not yet eligible (Coursework Exempted - requires 2.5 years from DOC)' :
                    'Not yet eligible (requires 3 years from DOC)',
                'can_submit' => false,
                'requires_special_approval' => false,
                'days_remaining' => null,
                'days_until_eligible' => $daysUntilEligible,
                'eligible_date' => $eligibleDate,
                'max_date' => $maxDate,
                'is_coursework_exempted' => $isCourseworkExempted
            ];
        }
    }

    public function canSubmitThesis()
    {
        $eligibility = $this->getThesisEligibilityStatus();

        // Check if already has a thesis submission
        if ($this->hasExistingThesis()) {
            return [
                'can_submit' => false,
                'reason' => 'Already has a thesis submission',
                'eligibility' => $eligibility
            ];
        }

        return [
            'can_submit' => $eligibility['can_submit'],
            'reason' => $eligibility['reason'],
            'eligibility' => $eligibility
        ];
    }

    // Enrollment helper methods
    public function isEnrolled()
    {
        return $this->enrollment_status === 'enrolled';
    }

    public function isPendingEnrollment()
    {
        return $this->enrollment_status === 'pending';
    }

    public function hasRegistrationForm()
    {
        return $this->registrationForm !== null;
    }

    public function canBeEnrolled()
    {
        // Scholar can be enrolled after HVC approval of synopsis
        return $this->isSynopsisApproved();
    }

    public function enroll()
    {
        if ($this->canBeEnrolled()) {
            $this->update([
                'enrollment_status' => 'enrolled',
                'enrolled_at' => now()
            ]);
            return true;
        }
        return false;
    }

    // Late submission helper methods
    public function isOverdueForThesisSubmission()
    {
        $doc = $this->date_of_confirmation;
        if (!$doc) {
            return false;
        }

        $maxYears = $this->hasApprovedCourseworkExemption() ? 6 : 6; // Both regular and coursework-exempted have 6-year limit
        $maxDate = $doc->addYears($maxYears);

        return now()->gt($maxDate);
    }

    public function getDaysOverdue()
    {
        if (!$this->isOverdueForThesisSubmission()) {
            return 0;
        }

        $doc = $this->date_of_confirmation;
        $maxYears = $this->hasApprovedCourseworkExemption() ? 6 : 6;
        $maxDate = $doc->addYears($maxYears);

        return now()->diffInDays($maxDate);
    }

    public function getNextStep()
    {
        // Step 1: Synopsis Submission (now part of registration) - MOVED TO FIRST
        if (!$this->hasSubmittedSynopsis() && !$this->isSynopsisApproved()) {
            return [
                'step' => 1,
                'title' => 'Complete Registration with Synopsis',
                'description' => 'Complete your registration form including synopsis submission',
                'route' => 'scholar.registration.phd_form',
                'status' => 'pending',
                'icon' => 'synopsis'
            ];
        } elseif ($this->isSynopsisPending()) {
            return [
                'step' => 1,
                'title' => 'Synopsis Under Review',
                'description' => 'Your synopsis is being reviewed by the approval committee',
                'route' => 'scholar.registration.phd_form',
                'status' => 'in_progress',
                'icon' => 'synopsis'
            ];
        }

        // Step 2: Supervisor Assignment
        if (!$this->hasAssignedSupervisor()) {
            return [
                'step' => 2,
                'title' => 'Supervisor Assignment',
                'description' => 'Submit your supervisor preference and wait for approval',
                'route' => 'scholar.supervisor.preference',
                'status' => 'pending',
                'icon' => 'supervisor'
            ];
        }

        // Step 3: Profile Setup - MOVED TO THIRD
        if (!$this->isProfileComplete()) {
            return [
                'step' => 3,
                'title' => 'Profile Setup',
                'description' => 'Complete your personal information and upload required documents',
                'route' => 'scholar.profile.edit',
                'status' => 'pending',
                'icon' => 'profile'
            ];
        }

        // Step 4: Progress Reports
        $approvedReports = $this->progressReports()->where('status', 'approved')->count();
        if ($approvedReports < 2) {
            $isSubmissionAllowed = \App\Helpers\ProgressReportHelper::isSubmissionAllowed();
            $statusMessage = \App\Helpers\ProgressReportHelper::getSubmissionStatusMessage();

            return [
                'step' => 4,
                'title' => 'Progress Reports',
                'description' => $isSubmissionAllowed
                    ? "Submit periodic progress reports ({$approvedReports}/2 completed)"
                    : "Progress reports: {$statusMessage}",
                'route' => $isSubmissionAllowed ? 'scholar.progress_report.submit' : 'scholar.dashboard',
                'status' => $isSubmissionAllowed ? 'in_progress' : 'pending',
                'icon' => 'progress'
            ];
        }

        // Step 5: Coursework Completion
        if (!$this->coursework_completed) {
            return [
                'step' => 5,
                'title' => 'Coursework Completion',
                'description' => 'Complete required coursework and upload certificates',
                'route' => 'scholar.dashboard',
                'status' => 'pending',
                'icon' => 'coursework'
            ];
        }

        // Step 6: Thesis Submission
        if (!$this->thesisSubmissions()->where('status', 'approved')->exists()) {
            if (!$this->thesisSubmissions()->exists()) {
                return [
                    'step' => 6,
                    'title' => 'Thesis Submission',
                    'description' => 'Submit your final thesis for evaluation',
                    'route' => 'scholar.thesis.submit',
                    'status' => 'pending',
                    'icon' => 'thesis'
                ];
            } else {
                return [
                    'step' => 6,
                    'title' => 'Thesis Submission',
                    'description' => 'Wait for thesis approval',
                    'route' => 'scholar.thesis.submit',
                    'status' => 'in_progress',
                    'icon' => 'thesis'
                ];
            }
        }

        // Step 7: Viva Examination
        if (!$this->vivaExaminations()->where('status', 'completed')->exists()) {
            if (!$this->vivaExaminations()->exists()) {
                return [
                    'step' => 7,
                    'title' => 'Viva Examination',
                    'description' => 'Wait for viva scheduling',
                    'route' => 'scholar.thesis.status',
                    'status' => 'pending',
                    'icon' => 'viva'
                ];
            } else {
                return [
                    'step' => 7,
                    'title' => 'Viva Examination',
                    'description' => 'Attend scheduled viva examination',
                    'route' => 'scholar.thesis.status',
                    'status' => 'in_progress',
                    'icon' => 'viva'
                ];
            }
        }

        // Step 8: Final Documents
        if (!$this->registration_letter_generated) {
            return [
                'step' => 8,
                'title' => 'Final Documents',
                'description' => 'Download registration letter and certificates',
                'route' => 'scholar.thesis.submissions.status',
                'status' => 'pending',
                'icon' => 'documents'
            ];
        }

        // All steps completed
        return [
            'step' => 9,
            'title' => 'Congratulations!',
            'description' => 'You have completed all required steps in your Ph.D. journey',
            'route' => 'scholar.dashboard',
            'status' => 'completed',
            'icon' => 'celebration'
        ];
    }

    public function canRequestLateSubmission()
    {
        // Can request if overdue and no pending/approved late submission request exists
        if (!$this->isOverdueForThesisSubmission()) {
            return false;
        }

        $existingRequest = $this->lateSubmissionRequests()
            ->whereIn('status', ['pending_supervisor_approval', 'pending_hod_approval', 'pending_dean_approval', 'pending_da_approval', 'pending_so_approval', 'pending_ar_approval', 'pending_dr_approval', 'pending_hvc_approval', 'approved'])
            ->exists();

        return !$existingRequest;
    }

    public function hasApprovedLateSubmission()
    {
        return $this->lateSubmissionRequests()->where('status', 'approved')->exists();
    }

    // Synopsis related methods
    public function hasSubmittedSynopsis()
    {
        return !empty($this->synopsis_file) && !empty($this->synopsis_submitted_at);
    }

    public function getSynopsisStatus()
    {
        return $this->synopsis_status ?? 'not_submitted';
    }

    public function isSynopsisApproved()
    {
        return $this->synopsis_status === 'approved';
    }

    public function isSynopsisPending()
    {
        return in_array($this->synopsis_status, [
            'pending_supervisor_approval',
            'pending_hod_approval',
            'pending_da_approval',
            'pending_so_approval',
            'pending_ar_approval',
            'pending_dr_approval',
            'pending_hvc_approval'
        ]);
    }

    public function isSynopsisRejected()
    {
        return $this->synopsis_status === 'rejected';
    }
}
