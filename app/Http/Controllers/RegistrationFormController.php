<?php

namespace App\Http\Controllers;

use App\Models\RegistrationForm;
use App\Models\Scholar;
use App\Models\Synopsis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationFormController extends Controller
{
    /**
     * Show scholar's registration form status
     */
    public function showScholarRegistrationFormStatus()
    {
        $scholar = Auth::user()->scholar;

        if (!$scholar) {
            abort(403, 'Only scholars can access this page.');
        }

        $registrationForm = $scholar->registrationForm;

        return view('scholar.registration_form.status', compact('scholar', 'registrationForm'));
    }

    /**
     * Generate registration form after HVC approval of synopsis
     */
    public function generateRegistrationForm(Scholar $scholar)
    {
        // Check if user is DA
        if (Auth::user()->user_type !== 'da') {
            abort(403, 'Only Dean\'s Assistant can generate registration forms.');
        }

        // Check if scholar has approved synopsis
        $approvedSynopsis = $scholar->synopses()->where('status', 'approved')->first();
        if (!$approvedSynopsis) {
            return redirect()->back()->with('error', 'Scholar must have an approved synopsis to generate registration form.');
        }

        // Check if registration form already exists
        if ($scholar->registrationForm) {
            return redirect()->back()->with('error', 'Registration form already exists for this scholar.');
        }

        // Generate unique dispatch number
        $dispatchNumber = 'REG-' . date('Y') . '-' . str_pad($scholar->id, 6, '0', STR_PAD_LEFT);

        // Create registration form record
        $registrationForm = RegistrationForm::create([
            'scholar_id' => $scholar->id,
            'dispatch_number' => $dispatchNumber,
            'form_file_path' => '', // Will be updated after PDF generation
            'generated_by_da_id' => Auth::id(),
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);
        $workflowSyncService->syncRegistrationWorkflow($registrationForm, 'da_generate', Auth::user());

        // Generate PDF form using official letter blade template
        $synopsis = $scholar->synopses()->where('status', 'approved')->first();

        // Generate PDF
        $pdf = Pdf::loadView('registration.official_letter', compact('scholar', 'registrationForm', 'synopsis'));
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
        Storage::disk('public')->put($formPath, $pdf->output());
        $registrationForm->update(['form_file_path' => $formPath]);

        // Update scholar's registration form relationship
        $scholar->update(['registration_form_id' => $registrationForm->id]);

        return redirect()->back()->with('success', 'Registration form generated successfully with dispatch number: ' . $dispatchNumber);
    }

    /**
     * DR signs the registration form
     */
    public function signByDR(RegistrationForm $registrationForm)
    {
        // Check if user is DR
        if (Auth::user()->user_type !== 'dr') {
            abort(403, 'Only Deputy Registrar can sign registration forms.');
        }

        // Check if form is in correct status
        if ($registrationForm->status !== 'generated') {
            abort(403, 'Registration form is not ready for DR signature.');
        }

        // Generate signature file (placeholder)
        $signaturePath = 'signatures/dr_' . $registrationForm->id . '_' . time() . '.png';
        Storage::disk('public')->put($signaturePath, 'DR Signature Placeholder');

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        // Update registration form
        $registrationForm->update([
            'dr_signature_file' => $signaturePath,
        ]);

        // Set Date of Confirmation (DOC) for the scholar
        $scholar = $registrationForm->scholar;
        $scholar->update([
            'date_of_confirmation' => now()->toDateString(),
        ]);

        // Sync workflow
        $workflowSyncService->syncRegistrationWorkflow($registrationForm, 'dr_sign', Auth::user());

        return redirect()->back()->with('success', 'Registration form signed by Deputy Registrar.');
    }

    /**
     * AR signs the registration form - DISABLED (no longer needed)
     */
    /*
    public function signByAR(RegistrationForm $registrationForm)
    {
        // Check if user is AR
        if (Auth::user()->user_type !== 'ar') {
            abort(403, 'Only Assistant Registrar can sign registration forms.');
        }

        // Check if form is in correct status
        if (!in_array($registrationForm->status, ['generated', 'signed_by_dr'])) {
            abort(403, 'Registration form is not ready for AR signature.');
        }

        // Generate signature file (placeholder)
        $signaturePath = 'signatures/ar_' . $registrationForm->id . '_' . time() . '.png';
        Storage::disk('public')->put($signaturePath, 'AR Signature Placeholder');

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        // Update registration form
        $registrationForm->update([
            'ar_signature_file' => $signaturePath,
        ]);

        // Sync workflow
        $workflowSyncService->syncRegistrationWorkflow($registrationForm, 'ar_sign', Auth::user());

        // If both DR and AR have signed, mark as completed
        if ($registrationForm->signed_by_dr_id && $registrationForm->signed_by_ar_id) {
            $workflowSyncService->syncRegistrationWorkflow($registrationForm, 'complete', Auth::user());
        }

        return redirect()->back()->with('success', 'Registration form signed by Assistant Registrar.');
    }
    */

    /**
     * Scholar downloads the registration form
     */
    public function downloadRegistrationForm(RegistrationForm $registrationForm)
    {
        // Check if scholar owns this form
        if (Auth::user()->scholar->id !== $registrationForm->scholar_id) {
            abort(403, 'Unauthorized access to registration form.');
        }

        // Check if form is completed
        if (!$registrationForm->canBeDownloaded()) {
            abort(403, 'Registration form is not ready for download.');
        }

        // Increment download count
        $registrationForm->incrementDownloadCount();

        // Determine file extension based on file type
        $fileExtension = pathinfo($registrationForm->form_file_path, PATHINFO_EXTENSION);
        $downloadFileName = 'Registration_Form_' . $registrationForm->dispatch_number . '.' . $fileExtension;

        // Return file download
        return response()->download(storage_path('app/public/' . $registrationForm->form_file_path),
            $downloadFileName);
    }

    /**
     * Show official registration letter format
     */
    public function showOfficialLetter(RegistrationForm $registrationForm)
    {
        $scholar = $registrationForm->scholar;
        $synopsis = $scholar->synopses()->where('status', 'approved')->first();

        return view('registration.official_letter', compact('scholar', 'registrationForm', 'synopsis'));
    }

    /**
     * List registration forms for DA
     */
    public function listRegistrationForms()
    {
        // Check if user is DA
        if (Auth::user()->user_type !== 'da') {
            abort(403, 'Only Dean\'s Assistant can view registration forms.');
        }

        $registrationForms = RegistrationForm::with(['scholar.user', 'generatedByDA', 'signedByDR', 'signedByAR'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('da.registration_forms.list', compact('registrationForms'));
    }

    /**
     * List scholars eligible for registration form generation
     */
    public function listEligibleScholars()
    {
        // Check if user is DA
        if (Auth::user()->user_type !== 'da') {
            abort(403, 'Only Dean\'s Assistant can view eligible scholars.');
        }

        $eligibleScholars = Scholar::whereHas('synopses', function ($query) {
            $query->where('status', 'approved');
        })
        ->whereDoesntHave('registrationForm')
        ->with(['user', 'synopses' => function ($query) {
            $query->where('status', 'approved');
        }])
        ->get();

        return view('da.registration_forms.eligible_scholars', compact('eligibleScholars'));
    }

    /**
     * Generate form content (placeholder)
     */
    private function generateFormContent(Scholar $scholar, RegistrationForm $registrationForm)
    {
        $content = "REGISTRATION FORM\n";
        $content .= "Dispatch Number: " . $registrationForm->dispatch_number . "\n";
        $content .= "Generated Date: " . $registrationForm->generated_at->format('Y-m-d H:i:s') . "\n\n";
        $content .= "SCHOLAR INFORMATION\n";
        $content .= "Name: " . $scholar->first_name . " " . $scholar->last_name . "\n";
        $content .= "Enrollment Number: " . $scholar->enrollment_number . "\n";
        $content .= "Department: " . $scholar->admission->department->name . "\n";
        $content .= "Research Area: " . $scholar->research_area . "\n\n";
        $content .= "SUPERVISOR INFORMATION\n";
        if ($scholar->currentSupervisor) {
            $content .= "Supervisor: " . $scholar->currentSupervisor->supervisor->user->name . "\n";
        }
        $content .= "\nSYNOPSIS APPROVAL\n";
        $approvedSynopsis = $scholar->synopses()->where('status', 'approved')->first();
        if ($approvedSynopsis) {
            $content .= "Synopsis Title: " . $approvedSynopsis->proposed_topic . "\n";
            $content .= "Approval Date: " . $approvedSynopsis->hvc_approved_at->format('Y-m-d H:i:s') . "\n";
        }
        $content .= "\nThis form confirms the official enrollment of the scholar in the Research Portal.\n";
        $content .= "Generated by: " . Auth::user()->name . " (Dean's Assistant)\n";
        $content .= "Generation Date: " . now()->format('Y-m-d H:i:s') . "\n";

        return $content;
    }

    /**
     * List pending registration forms for DR
     */
    public function listPendingForDR()
    {
        $registrationForms = RegistrationForm::where('status', 'generated')
            ->with(['scholar', 'generatedByDA'])
            ->latest()
            ->get();

        return view('dr.registration_forms.pending', compact('registrationForms'));
    }

    /**
     * List pending registration forms for AR - DISABLED (no longer needed)
     */
    /*
    public function listPendingForAR()
    {
        $registrationForms = RegistrationForm::where('status', 'pending_ar_signature')
            ->with(['scholar', 'generatedByDA'])
            ->latest()
            ->get();

        return view('ar.registration_forms.pending', compact('registrationForms'));
    }
    */
}
