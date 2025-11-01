<?php

namespace App\Services;

use App\Models\Scholar;
use App\Models\Synopsis;
use App\Models\RegistrationForm;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WorkflowStatusUpdate;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkflowSyncService
{
    /**
     * Sync synopsis workflow across all roles
     */
    public function syncSynopsisWorkflow(Synopsis $synopsis, string $action, User $actor, ?string $reassignedToRole = null)
    {
        $scholar = $synopsis->scholar;

        // Update synopsis status based on action
        $statusUpdate = $this->getSynopsisStatusUpdate($action, $actor, $reassignedToRole);

        if ($statusUpdate) {
            $synopsis->update($statusUpdate);

            // Log the action
            Log::info("Synopsis workflow updated", [
                'synopsis_id' => $synopsis->id,
                'scholar_id' => $scholar->id,
                'action' => $action,
                'actor' => $actor->user_type,
                'new_status' => $synopsis->status
            ]);

            // Notify relevant users
            $this->notifySynopsisStatusUpdate($synopsis, $action, $actor);

            // Update scholar's synopsis status if needed
            $this->updateScholarSynopsisStatus($scholar, $synopsis);

            // Auto-generate registration form when synopsis is approved by HVC
            if ($action === 'hvc_approve' && $synopsis->status === 'approved') {
                $this->autoGenerateRegistrationForm($scholar);
            }
        }
    }

    /**
     * Sync registration form workflow across all roles
     */
    public function syncRegistrationWorkflow(RegistrationForm $registrationForm, string $action, User $actor)
    {
        $scholar = $registrationForm->scholar;

        // Update registration form status based on action
        $statusUpdate = $this->getRegistrationStatusUpdate($action, $actor);

        if ($statusUpdate) {
            $registrationForm->update($statusUpdate);

            // Log the action
            Log::info("Registration workflow updated", [
                'registration_form_id' => $registrationForm->id,
                'scholar_id' => $scholar->id,
                'action' => $action,
                'actor' => $actor->user_type,
                'new_status' => $registrationForm->status
            ]);

            // Notify relevant users
            $this->notifyRegistrationStatusUpdate($registrationForm, $action, $actor);

            // Auto-enroll scholar if registration is completed
            if ($registrationForm->status === 'completed') {
                $this->enrollScholar($scholar);
            }
        }
    }

    /**
     * Get synopsis status update based on action and actor
     */
    private function getSynopsisStatusUpdate(string $action, User $actor, ?string $reassignedToRole = null): ?array
    {
        $now = now();

        return match($action) {
            'supervisor_approve' => [
                'status' => 'pending_hod_approval',
                'supervisor_approver_id' => $actor->id,
                'supervisor_approved_at' => $now,
            ],
            'supervisor_reject' => [
                'status' => 'rejected_by_supervisor',
                'supervisor_approver_id' => $actor->id,
                'supervisor_approved_at' => $now,
                'rejected_by' => $actor->id,
                'rejected_at' => $now,
            ],
            'hod_approve' => [
                'status' => 'pending_da_approval',
                'hod_approver_id' => $actor->id,
                'hod_approved_at' => $now,
            ],
            'hod_reject' => $this->handleRejection('hod', $actor, $now, $reassignedToRole),
            'da_approve' => [
                'status' => 'pending_so_approval',
                'da_approver_id' => $actor->id,
                'da_approved_at' => $now,
            ],
            'da_reject' => $this->handleRejection('da', $actor, $now, $reassignedToRole),
            'so_approve' => [
                'status' => 'pending_ar_approval',
                'so_approver_id' => $actor->id,
                'so_approved_at' => $now,
            ],
            'so_reject' => $this->handleRejection('so', $actor, $now, $reassignedToRole),
            'ar_approve' => [
                'status' => 'pending_dr_approval',
                'ar_approver_id' => $actor->id,
                'ar_approved_at' => $now,
            ],
            'ar_reject' => $this->handleRejection('ar', $actor, $now, $reassignedToRole),
            'dr_approve' => [
                'status' => 'pending_hvc_approval',
                'dr_approver_id' => $actor->id,
                'dr_approved_at' => $now,
            ],
            'dr_reject' => $this->handleRejection('dr', $actor, $now, $reassignedToRole),
            'hvc_approve' => [
                'status' => 'approved',
                'hvc_approver_id' => $actor->id,
                'hvc_approved_at' => $now,
            ],
            'hvc_reject' => $this->handleRejection('hvc', $actor, $now, $reassignedToRole),
            default => null
        };
    }

    /**
     * Handle rejection with optional reassignment
     */
    private function handleRejection(string $role, User $actor, $now, ?string $reassignedToRole = null): array
    {
        // If reassignment is specified, assign back to that role instead of rejecting
        if ($reassignedToRole) {
            $statusMap = [
                'supervisor' => 'pending_supervisor_approval',
                'hod' => 'pending_hod_approval',
                'da' => 'pending_da_approval',
                'so' => 'pending_so_approval',
                'ar' => 'pending_ar_approval',
                'dr' => 'pending_dr_approval',
                'hvc' => 'pending_hvc_approval',
            ];

            $approverFieldMap = [
                'supervisor' => 'supervisor_approver_id',
                'hod' => 'hod_approver_id',
                'da' => 'da_approver_id',
                'so' => 'so_approver_id',
                'ar' => 'ar_approver_id',
                'dr' => 'dr_approver_id',
                'hvc' => 'hvc_approver_id',
            ];

            $approvalFieldMap = [
                'supervisor' => 'supervisor_approved_at',
                'hod' => 'hod_approved_at',
                'da' => 'da_approved_at',
                'so' => 'so_approved_at',
                'ar' => 'ar_approved_at',
                'dr' => 'dr_approved_at',
                'hvc' => 'hvc_approved_at',
            ];

            // Get the current role's approver field
            $currentApproverField = $approverFieldMap[$role] ?? null;
            $currentApprovalField = $approvalFieldMap[$role] ?? null;

            $update = [
                'status' => $statusMap[$reassignedToRole] ?? 'rejected_by_' . $role,
                'reassigned_to_role' => $reassignedToRole,
            ];

            // Set current role's approver and approval time
            if ($currentApproverField) {
                $update[$currentApproverField] = $actor->id;
            }
            if ($currentApprovalField) {
                $update[$currentApprovalField] = $now;
            }

            return $update;
        }

        // Standard rejection
        return [
            'status' => 'rejected_by_' . $role,
            $this->getApproverField($role) => $actor->id,
            $this->getApprovalField($role) => $now,
            'rejected_by' => $actor->id,
            'rejected_at' => $now,
        ];
    }

    /**
     * Get approver field name for a role
     */
    private function getApproverField(string $role): string
    {
        return match($role) {
            'supervisor' => 'supervisor_approver_id',
            'hod' => 'hod_approver_id',
            'da' => 'da_approver_id',
            'so' => 'so_approver_id',
            'ar' => 'ar_approver_id',
            'dr' => 'dr_approver_id',
            'hvc' => 'hvc_approver_id',
            default => 'rejected_by',
        };
    }

    /**
     * Get approval field name for a role
     */
    private function getApprovalField(string $role): string
    {
        return match($role) {
            'supervisor' => 'supervisor_approved_at',
            'hod' => 'hod_approved_at',
            'da' => 'da_approved_at',
            'so' => 'so_approved_at',
            'ar' => 'ar_approved_at',
            'dr' => 'dr_approved_at',
            'hvc' => 'hvc_approved_at',
            default => 'rejected_at',
        };
    }

    /**
     * Get registration form status update based on action and actor
     */
    private function getRegistrationStatusUpdate(string $action, User $actor): ?array
    {
        $now = now();

        return match($action) {
            'supervisor_approve' => [
                'status' => 'supervisor_approved',
                'supervisor_approved_at' => $now,
                'supervisor_approved_by' => $actor->id,
            ],
            'supervisor_reject' => [
                'status' => 'supervisor_rejected',
                'supervisor_rejected_at' => $now,
                'supervisor_rejected_by' => $actor->id,
            ],
            'da_generate' => [
                'status' => 'generated',
                'generated_at' => $now,
                'generated_by_da_id' => $actor->id,
            ],
            'dr_sign' => [
                'status' => 'completed',
                'signed_by_dr_id' => $actor->id,
                'signed_by_dr_at' => $now,
                'completed_at' => $now,
            ],
            'ar_sign' => [
                'status' => 'signed_by_ar',
                'signed_by_ar_id' => $actor->id,
                'signed_by_ar_at' => $now,
            ],
            'complete' => [
                'status' => 'completed',
                'completed_at' => $now,
            ],
            default => null
        };
    }

    /**
     * Update scholar's synopsis status
     */
    private function updateScholarSynopsisStatus(Scholar $scholar, Synopsis $synopsis)
    {
        if ($synopsis->status === 'approved') {
            $scholar->update([
                'synopsis_status' => 'approved',
                'synopsis_submitted_at' => $synopsis->submission_date,
            ]);
        } elseif (str_starts_with($synopsis->status, 'rejected')) {
            $scholar->update([
                'synopsis_status' => 'rejected',
            ]);
        }
    }

    /**
     * Enroll scholar when registration is completed
     */
    private function enrollScholar(Scholar $scholar)
    {
        $scholar->update([
            'enrollment_status' => 'enrolled',
            'enrolled_at' => now(),
            'status' => 'active',
        ]);

        Log::info("Scholar enrolled", [
            'scholar_id' => $scholar->id,
            'enrollment_date' => now()
        ]);
    }

    /**
     * Notify users about synopsis status updates
     */
    private function notifySynopsisStatusUpdate(Synopsis $synopsis, string $action, User $actor)
    {
        $scholar = $synopsis->scholar;
        $nextApprover = $this->getNextSynopsisApprover($synopsis->status);

        // Notify scholar
        if ($scholar->user) {
            Notification::send($scholar->user, new WorkflowStatusUpdate(
                'synopsis',
                $action,
                $synopsis->status,
                $actor
            ));
        }

        // Notify next approver
        if ($nextApprover) {
            Notification::send($nextApprover, new WorkflowStatusUpdate(
                'synopsis',
                'pending_approval',
                $synopsis->status,
                $actor
            ));
        }
    }

    /**
     * Notify users about registration status updates
     */
    private function notifyRegistrationStatusUpdate(RegistrationForm $registrationForm, string $action, User $actor)
    {
        $scholar = $registrationForm->scholar;
        $nextApprover = $this->getNextRegistrationApprover($registrationForm->status);

        // Notify scholar
        if ($scholar->user) {
            Notification::send($scholar->user, new WorkflowStatusUpdate(
                'registration',
                $action,
                $registrationForm->status,
                $actor
            ));
        }

        // Notify next approver
        if ($nextApprover) {
            Notification::send($nextApprover, new WorkflowStatusUpdate(
                'registration',
                'pending_approval',
                $registrationForm->status,
                $actor
            ));
        }
    }

    /**
     * Get next synopsis approver based on current status
     */
    private function getNextSynopsisApprover(string $status): ?User
    {
        return match($status) {
            'pending_hod_approval' => User::where('user_type', 'hod')->first(),
            'pending_da_approval' => User::where('user_type', 'da')->first(),
            'pending_so_approval' => User::where('user_type', 'so')->first(),
            'pending_ar_approval' => User::where('user_type', 'ar')->first(),
            'pending_dr_approval' => User::where('user_type', 'dr')->first(),
            'pending_hvc_approval' => User::where('user_type', 'hvc')->first(),
            default => null
        };
    }

    /**
     * Get next registration approver based on current status
     */
    private function getNextRegistrationApprover(string $status): ?User
    {
        return match($status) {
            'supervisor_approved' => User::where('user_type', 'da')->first(),
            'generated' => User::where('user_type', 'dr')->first(),
            'signed_by_dr' => User::where('user_type', 'ar')->first(),
            default => null
        };
    }

    /**
     * Get workflow status for a scholar across all processes
     */
    public function getScholarWorkflowStatus(Scholar $scholar): array
    {
        $latestSynopsis = $scholar->synopses()->latest()->first();
        $registrationForm = $scholar->registrationForm;

        return [
            'scholar_id' => $scholar->id,
            'scholar_name' => $scholar->name . ' ' . $scholar->last_name,
            'synopsis' => [
                'status' => $latestSynopsis ? $latestSynopsis->status : 'not_submitted',
                'submission_date' => $latestSynopsis ? $latestSynopsis->submission_date : null,
                'current_stage' => $this->getSynopsisCurrentStage($latestSynopsis),
                'progress_percentage' => $this->getSynopsisProgressPercentage($latestSynopsis),
            ],
            'registration' => [
                'status' => $registrationForm ? $registrationForm->status : 'not_started',
                'submission_date' => $scholar->registration_form_submitted_at,
                'current_stage' => $this->getRegistrationCurrentStage($registrationForm),
                'progress_percentage' => $this->getRegistrationProgressPercentage($registrationForm),
            ],
            'overall_status' => $this->getOverallStatus($latestSynopsis, $registrationForm),
        ];
    }

    /**
     * Get current stage of synopsis workflow
     */
    private function getSynopsisCurrentStage(?Synopsis $synopsis): string
    {
        if (!$synopsis) {
            return 'Not Submitted';
        }

        return match($synopsis->status) {
            'pending_supervisor_approval' => 'Pending Supervisor Approval',
            'pending_hod_approval' => 'Pending HOD Approval',
            'pending_da_approval' => 'Pending Dean Assistant Approval',
            'pending_so_approval' => 'Pending Section Officer Approval',
            'pending_ar_approval' => 'Pending Assistant Registrar Approval',
            'pending_dr_approval' => 'Pending Deputy Registrar Approval',
            'pending_hvc_approval' => 'Pending HVC Approval',
            'approved' => 'Approved',
            default => 'Rejected'
        };
    }

    /**
     * Get current stage of registration workflow
     */
    private function getRegistrationCurrentStage(?RegistrationForm $registrationForm): string
    {
        if (!$registrationForm) {
            return 'Not Started';
        }

        return match($registrationForm->status) {
            'supervisor_approved' => 'Supervisor Approved',
            'generated' => 'Generated by DA',
            'signed_by_dr' => 'Signed by DR',
            'signed_by_ar' => 'Signed by AR',
            'completed' => 'Completed',
            default => 'In Progress'
        };
    }

    /**
     * Get synopsis progress percentage
     */
    private function getSynopsisProgressPercentage(?Synopsis $synopsis): int
    {
        if (!$synopsis) {
            return 0;
        }

        $stages = [
            'pending_supervisor_approval' => 14,
            'pending_hod_approval' => 28,
            'pending_da_approval' => 42,
            'pending_so_approval' => 56,
            'pending_ar_approval' => 70,
            'pending_dr_approval' => 84,
            'pending_hvc_approval' => 98,
            'approved' => 100,
        ];

        return $stages[$synopsis->status] ?? 0;
    }

    /**
     * Get registration progress percentage
     */
    private function getRegistrationProgressPercentage(?RegistrationForm $registrationForm): int
    {
        if (!$registrationForm) {
            return 0;
        }

        $stages = [
            'supervisor_approved' => 25,
            'generated' => 50,
            'signed_by_dr' => 75,
            'signed_by_ar' => 90,
            'completed' => 100,
        ];

        return $stages[$registrationForm->status] ?? 0;
    }

    /**
     * Get overall status
     */
    private function getOverallStatus(?Synopsis $synopsis, ?RegistrationForm $registrationForm): string
    {
        if ($registrationForm && $registrationForm->status === 'completed') {
            return 'Enrolled';
        }

        if ($synopsis && $synopsis->status === 'approved') {
            return 'Synopsis Approved - Registration Pending';
        }

        if ($synopsis && str_starts_with($synopsis->status, 'rejected')) {
            return 'Synopsis Rejected';
        }

        if ($synopsis) {
            return 'Synopsis Under Review';
        }

        return 'Registration Form Pending';
    }

    /**
     * Auto-generate registration form when synopsis is approved by HVC
     */
    private function autoGenerateRegistrationForm(Scholar $scholar)
    {
        // Check if registration form already exists
        if ($scholar->registrationForm) {
            Log::info("Registration form already exists for scholar", ['scholar_id' => $scholar->id]);
            return;
        }

        // Check if scholar has approved synopsis
        $approvedSynopsis = $scholar->synopses()->where('status', 'approved')->first();
        if (!$approvedSynopsis) {
            Log::warning("No approved synopsis found for scholar", ['scholar_id' => $scholar->id]);
            return;
        }

        try {
            // Generate unique dispatch number
            $dispatchNumber = 'REG-' . date('Y') . '-' . str_pad($scholar->id, 6, '0', STR_PAD_LEFT);

            // Create registration form record
            $registrationForm = RegistrationForm::create([
                'scholar_id' => $scholar->id,
                'dispatch_number' => $dispatchNumber,
                'form_file_path' => '', // Will be updated after PDF generation
                'generated_by_da_id' => null, // Auto-generated by system
                'status' => 'generated', // Make it available for DR signing
                'generated_at' => now(),
            ]);

            // Generate PDF form using official letter blade template
            $pdf = Pdf::loadView('registration.official_letter', [
                'scholar' => $scholar,
                'registrationForm' => $registrationForm,
                'synopsis' => $approvedSynopsis
            ]);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'Times New Roman',
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false
            ]);

            $formPath = 'registration_forms/' . $dispatchNumber . '.pdf';
            \Illuminate\Support\Facades\Storage::disk('public')->put($formPath, $pdf->output());

            // Update form file path
            $registrationForm->update(['form_file_path' => $formPath]);

            // Update scholar's registration form relationship
            $scholar->update(['registration_form_id' => $registrationForm->id]);

            // Sync registration workflow
            $this->syncRegistrationWorkflow($registrationForm, 'da_generate', $scholar->user);

            Log::info("Registration form auto-generated for scholar", [
                'scholar_id' => $scholar->id,
                'registration_form_id' => $registrationForm->id,
                'dispatch_number' => $dispatchNumber
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to auto-generate registration form for scholar", [
                'scholar_id' => $scholar->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate form content for auto-generated registration form
     */
    private function generateFormContent(Scholar $scholar, RegistrationForm $registrationForm, Synopsis $approvedSynopsis): string
    {
        $content = "REGISTRATION FORM\n";
        $content .= "Dispatch Number: " . $registrationForm->dispatch_number . "\n";
        $content .= "Generated Date: " . $registrationForm->generated_at->format('Y-m-d H:i:s') . "\n\n";
        $content .= "SCHOLAR INFORMATION\n";
        $content .= "Name: " . $scholar->name . " " . $scholar->last_name . "\n";
        $content .= "Enrollment Number: " . $scholar->enrollment_number . "\n";
        $content .= "Department: " . $scholar->admission->department->name . "\n";
        $content .= "Research Area: " . $scholar->research_area . "\n\n";
        $content .= "SUPERVISOR INFORMATION\n";
        if ($scholar->currentSupervisor) {
            $content .= "Supervisor: " . $scholar->currentSupervisor->supervisor->user->name . "\n";
        }
        $content .= "\nSYNOPSIS APPROVAL\n";
        $content .= "Synopsis Title: " . $approvedSynopsis->proposed_topic . "\n";
        $content .= "Approval Date: " . $approvedSynopsis->hvc_approved_at->format('Y-m-d H:i:s') . "\n";
        $content .= "\nThis form confirms the official enrollment of the scholar in the Research Portal.\n";
        $content .= "Auto-generated by system after HVC synopsis approval\n";
        $content .= "Generation Date: " . now()->format('Y-m-d H:i:s') . "\n";

        return $content;
    }

    /**
     * Generate a properly formatted PDF from registration form data
     */
    private function generateSimplePDF(string $content): string
    {
        // Create a properly formatted PDF structure
        $pdfContent = '%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj

2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj

3 0 obj
<<
/Type /Page
/Parent 2 0 R
/MediaBox [0 0 612 792]
/Contents 4 0 R
/Resources <<
/Font <<
/F1 <<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica
/F2 <<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica-Bold
>>
>>
>>
>>
endobj

4 0 obj
<<
/Length 2000
>>
stream
BT
/F2 16 Tf
72 750 Td
(REGISTRATION FORM) Tj
0 -30 Td
/F1 10 Tf
' . str_replace(['(', ')', '\\', "\r", "\n"], ['\\(', '\\)', '\\\\', '\\r', '\\n'], $content) . '
ET
endstream
endobj

xref
0 5
0000000000 65535 f
0000000009 00000 n
0000000058 00000 n
0000000115 00000 n
0000000206 00000 n
trailer
<<
/Size 5
/Root 1 0 R
>>
startxref
2400
%%EOF';

        return $pdfContent;
    }
}
