<?php

namespace App\Http\Controllers;

use App\Models\ThesisSubmission;
use App\Models\VivaProcess;
use App\Models\VivaExamination;
use App\Models\FinalCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinalCertificateController extends Controller
{
    /**
     * List thesis ready for final certificate generation
     */
    public function listEligibleThesis()
    {
        // Check if user is HVC or DA
        if (!in_array(Auth::user()->user_type, ['hvc', 'da'])) {
            abort(403, 'Only Honorable Vice Chancellor or Dealing Assistant can generate final certificates.');
        }

        $eligibleTheses = ThesisSubmission::where('status', 'final_approved')
            ->whereDoesntHave('finalCertificate')
            ->with(['scholar.user', 'supervisor.user', 'vivaProcess', 'vivaExamination'])
            ->latest()
            ->get();

        return view('admin.final_certificates.eligible_thesis', compact('eligibleTheses'));
    }

    /**
     * Show final certificate generation form
     */
    public function showCertificateForm(ThesisSubmission $thesis)
    {
        // Check if user is HVC or DA
        if (!in_array(Auth::user()->user_type, ['hvc', 'da'])) {
            abort(403, 'Only Honorable Vice Chancellor or Dealing Assistant can generate final certificates.');
        }

        if ($thesis->status !== 'final_approved') {
            abort(403, 'This thesis is not approved for final certificate generation.');
        }

        if ($thesis->finalCertificate) {
            return redirect()->route('admin.final_certificates.show', $thesis->finalCertificate);
        }

        return view('admin.final_certificates.generate', compact('thesis'));
    }

    /**
     * Generate final certificate
     */
    public function generateCertificate(Request $request, ThesisSubmission $thesis)
    {
        // Check if user is HVC or DA
        if (!in_array(Auth::user()->user_type, ['hvc', 'da'])) {
            abort(403, 'Only Honorable Vice Chancellor or Dealing Assistant can generate final certificates.');
        }

        if ($thesis->status !== 'final_approved') {
            abort(403, 'This thesis is not approved for final certificate generation.');
        }

        $request->validate([
            'certificate_number' => 'required|string|max:255|unique:final_certificates,certificate_number',
            'issue_date' => 'required|date',
            'degree_title' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'viva_date' => 'required|date',
            'viva_venue' => 'required|string|max:255',
            'examiner_names' => 'required|array|min:2',
            'examiner_names.*' => 'required|string|max:255',
            'examiner_designations' => 'required|array|min:2',
            'examiner_designations.*' => 'required|string|max:255',
            'examiner_institutions' => 'required|array|min:2',
            'examiner_institutions.*' => 'required|string|max:255',
            'recommendation_notes' => 'nullable|string',
        ]);

        // Create final certificate
        $certificate = FinalCertificate::create([
            'thesis_submission_id' => $thesis->id,
            'scholar_id' => $thesis->scholar_id,
            'certificate_number' => $request->certificate_number,
            'issue_date' => $request->issue_date,
            'degree_title' => $request->degree_title,
            'specialization' => $request->specialization,
            'viva_date' => $request->viva_date,
            'viva_venue' => $request->viva_venue,
            'examiner_names' => json_encode($request->examiner_names),
            'examiner_designations' => json_encode($request->examiner_designations),
            'examiner_institutions' => json_encode($request->examiner_institutions),
            'recommendation_notes' => $request->recommendation_notes,
            'status' => 'generated',
            'generated_by' => Auth::id(),
            'generated_at' => now(),
        ]);

        // Generate PDF certificate
        $this->generateCertificatePDF($certificate);

        return redirect()->route('admin.final_certificates.show', $certificate)
            ->with('success', 'Final certificate generated successfully!');
    }

    /**
     * Show generated final certificate
     */
    public function showCertificate(FinalCertificate $certificate)
    {
        $certificate->load(['thesisSubmission.scholar.user', 'thesisSubmission.supervisor.user', 'generatedBy']);
        return view('admin.final_certificates.show', compact('certificate'));
    }

    /**
     * Download final certificate as PDF
     */
    public function downloadCertificate(FinalCertificate $certificate)
    {
        if (!$certificate->certificate_file) {
            return redirect()->back()->with('error', 'Certificate file not found.');
        }

        $filePath = storage_path('app/public/' . $certificate->certificate_file);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Certificate file not found on disk.');
        }

        return response()->download($filePath, 'final_certificate_' . $certificate->certificate_number . '.pdf');
    }

    /**
     * List all generated certificates
     */
    public function listCertificates()
    {
        $certificates = FinalCertificate::with(['thesisSubmission.scholar.user', 'generatedBy'])
            ->latest()
            ->paginate(20);

        return view('admin.final_certificates.list', compact('certificates'));
    }

    /**
     * Generate PDF certificate
     */
    private function generateCertificatePDF(FinalCertificate $certificate)
    {
        $pdf = $this->createCertificatePDF($certificate);

        // Store the certificate
        $fileName = 'final_certificate_' . $certificate->certificate_number . '_' . time() . '.pdf';
        $filePath = 'final_certificates/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update certificate with file path
        $certificate->update([
            'certificate_file' => $filePath,
            'status' => 'completed',
        ]);

        return $filePath;
    }

    /**
     * Create PDF certificate
     */
    private function createCertificatePDF(FinalCertificate $certificate)
    {
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new \Dompdf\Dompdf($options);

        $html = $this->generateCertificateHTML($certificate);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Generate certificate HTML
     */
    private function generateCertificateHTML(FinalCertificate $certificate)
    {
        $scholar = $certificate->thesisSubmission->scholar;
        $thesis = $certificate->thesisSubmission;
        $examinerNames = json_decode($certificate->examiner_names, true);
        $examinerDesignations = json_decode($certificate->examiner_designations, true);
        $examinerInstitutions = json_decode($certificate->examiner_institutions, true);

        return view('admin.final_certificates.pdf', compact('certificate', 'scholar', 'thesis', 'examinerNames', 'examinerDesignations', 'examinerInstitutions'))->render();
    }
}

