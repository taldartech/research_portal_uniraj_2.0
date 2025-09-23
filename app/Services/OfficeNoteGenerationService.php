<?php

namespace App\Services;

use App\Models\VivaExamination;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class OfficeNoteGenerationService
{
    public function generateOfficeNote(VivaExamination $vivaExamination)
    {
        // Generate PDF office note
        $pdf = $this->createOfficeNotePDF($vivaExamination);

        // Store the office note
        $fileName = 'office_note_' . $vivaExamination->id . '_' . time() . '.pdf';
        $filePath = 'office_notes/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update viva examination with office note path
        $vivaExamination->update([
            'office_note_file' => $filePath,
            'office_note_generated' => true,
            'office_note_generated_at' => now(),
        ]);

        return $filePath;
    }

    public function generateSignedOfficeNote(VivaExamination $vivaExamination, $signatureUserId = null)
    {
        // Generate PDF office note with digital signature
        $pdf = $this->createSignedOfficeNotePDF($vivaExamination, $signatureUserId);

        // Store the signed office note
        $fileName = 'signed_office_note_' . $vivaExamination->id . '_' . time() . '.pdf';
        $filePath = 'office_notes/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update viva examination with signed office note path
        $vivaExamination->update([
            'office_note_file' => $filePath,
            'office_note_generated' => true,
            'office_note_generated_at' => now(),
            'office_note_signed_by' => $signatureUserId,
            'office_note_signed_at' => now(),
        ]);

        return $filePath;
    }

    private function createOfficeNotePDF(VivaExamination $vivaExamination)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->generateOfficeNoteHTML($vivaExamination);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function createSignedOfficeNotePDF(VivaExamination $vivaExamination, $signatureUserId = null)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->generateSignedOfficeNoteHTML($vivaExamination, $signatureUserId);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function generateSignedOfficeNoteHTML(VivaExamination $vivaExamination, $signatureUserId = null)
    {
        $scholar = $vivaExamination->scholar;
        $user = $scholar->user;
        $supervisor = $vivaExamination->supervisor;
        $department = $scholar->admission->department;
        $thesis = $vivaExamination->thesisSubmission;

        // Get digital signature if user ID provided
        $signatureData = '';
        if ($signatureUserId) {
            $signatureService = new \App\Services\DigitalSignatureService();
            $signature = $signatureService->getUserSignature($signatureUserId);
            if ($signature && $signature->isSignatureFileExists()) {
                $signatureData = base64_encode(file_get_contents($signature->getSignaturePath()));
            }
        }

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Office Note - Ph.D. Degree Award Recommendation (Signed)</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                    line-height: 1.6;
                }
                .office-note-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 40px;
                    position: relative;
                }
                .reference-info {
                    text-align: right;
                    margin-bottom: 30px;
                    font-size: 12px;
                    color: #666;
                }
                .university-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .university-name {
                    font-size: 18px;
                    font-weight: bold;
                    text-decoration: underline;
                    margin-bottom: 10px;
                }
                .seal {
                    text-align: center;
                    margin: 20px 0;
                }
                .seal-circle {
                    width: 100px;
                    height: 100px;
                    border: 2px solid #000;
                    border-radius: 50%;
                    margin: 0 auto;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    position: relative;
                }
                .seal-hindi {
                    font-size: 8px;
                    font-weight: bold;
                    margin-top: 3px;
                }
                .seal-english {
                    font-size: 6px;
                    font-weight: bold;
                    margin-top: 1px;
                }
                .seal-year {
                    font-size: 10px;
                    font-weight: bold;
                    margin-top: 3px;
                }
                .office-note-title {
                    text-align: center;
                    font-size: 16px;
                    font-weight: bold;
                    margin: 30px 0;
                    text-decoration: underline;
                }
                .content {
                    font-size: 14px;
                    margin: 20px 0;
                }
                .dotted-line {
                    border-bottom: 1px dotted #000;
                    display: inline-block;
                    min-width: 150px;
                    margin: 0 5px;
                }
                .signature-section {
                    margin-top: 50px;
                    text-align: right;
                }
                .signature-title {
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .digital-signature {
                    margin-top: 20px;
                    text-align: right;
                }
                .signature-image {
                    max-width: 200px;
                    max-height: 80px;
                    border: 1px solid #ccc;
                }
                .date-section {
                    margin-top: 30px;
                    text-align: right;
                }
                .reference-section {
                    margin-top: 20px;
                    font-size: 12px;
                }
            </style>
        </head>
        <body>
            <div class="office-note-container">
                <div class="reference-info">
                    R.H.P. A.R.(Res.) 152 2,500-11,2024
                </div>

                <div class="university-header">
                    <div class="seal">
                        <div class="seal-circle">
                            <div class="seal-hindi">राजस्थान विश्वविद्यालय</div>
                            <div class="seal-english">UNIVERSITY OF RAJASTHAN</div>
                            <div class="seal-year">19 47</div>
                        </div>
                    </div>
                    <div class="university-name">University of Rajasthan, Jaipur</div>
                </div>

                <div class="office-note-title">OFFICE NOTE</div>

                <div class="content">
                    <p><strong>Ref. Orders at para</strong> <span class="dotted-line"></span></p>

                    <p>
                        The viva-voce Examination (<span class="dotted-line"></span>Online / Offline) of
                        <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span>
                        was conducted by the external examiner Dr. <span class="dotted-line"></span><strong>' . htmlspecialchars($vivaExamination->externalExaminer->name ?? 'External Examiner') . '</strong><span class="dotted-line"></span>
                        and Dr. <span class="dotted-line"></span><strong>' . htmlspecialchars($vivaExamination->internalExaminer->name ?? 'Internal Examiner') . '</strong><span class="dotted-line"></span>
                        (Supervisor) in University Department of <span class="dotted-line"></span><strong>' . htmlspecialchars($department->name) . '</strong><span class="dotted-line"></span>
                        on <span class="dotted-line"></span><strong>' . $vivaExamination->examination_date->format('d-m-Y') . '</strong><span class="dotted-line"></span>
                    </p>

                    <p>
                        In view of the unanimous recommendations of all the examiners including those conducted the viva-voce examination,
                        the case is submitted to the Vice-Chancellor for awarding <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span>
                        to the Ph. D. Degree of the University in the faculty of <span class="dotted-line"></span><strong>' . htmlspecialchars($thesis->faculty ?? 'Science') . '</strong><span class="dotted-line"></span>
                    </p>

                    <p><strong>Orders of the Vice-Chancellor will be reported to the Syndicate.</strong></p>
                </div>

                <div class="date-section">
                    <p>Date: <strong>' . now()->format('d-m-Y') . '</strong></p>
                </div>

                <div class="signature-section">
                    <div class="signature-title">Assistant Registrar (Research)</div>
                    <div class="signature-title">University of Rajasthan, Jaipur</div>

                    ' . ($signatureData ? '
                    <div class="digital-signature">
                        <div class="signature-title">Digital Signature:</div>
                        <img src="data:image/png;base64,' . $signatureData . '" alt="Digital Signature" class="signature-image">
                        <div class="signature-title">Date: ' . now()->format('d-m-Y') . '</div>
                    </div>
                    ' : '') . '
                </div>

                <div class="reference-section">
                    <p><strong>Copy forwarded to:</strong></p>
                    <p>1. (Supervisor) <span class="dotted-line"></span><strong>' . htmlspecialchars($supervisor->name) . '</strong><span class="dotted-line"></span></p>
                    <p>2. (Scholar) <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span></p>
                    <p>3. (HOD) <span class="dotted-line"></span><strong>' . htmlspecialchars($department->hod->name) . '</strong><span class="dotted-line"></span></p>
                </div>
            </div>
        </body>
        </html>';
    }

    private function generateOfficeNoteHTML(VivaExamination $vivaExamination)
    {
        $scholar = $vivaExamination->scholar;
        $user = $scholar->user;
        $supervisor = $vivaExamination->supervisor;
        $department = $scholar->admission->department;
        $thesis = $vivaExamination->thesisSubmission;

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Office Note - Ph.D. Degree Award Recommendation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                    line-height: 1.6;
                }
                .office-note-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 40px;
                    position: relative;
                }
                .reference-info {
                    text-align: right;
                    margin-bottom: 30px;
                    font-size: 12px;
                    color: #666;
                }
                .university-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .university-name {
                    font-size: 18px;
                    font-weight: bold;
                    text-decoration: underline;
                    margin-bottom: 10px;
                }
                .seal {
                    text-align: center;
                    margin: 20px 0;
                }
                .seal-circle {
                    width: 100px;
                    height: 100px;
                    border: 2px solid #000;
                    border-radius: 50%;
                    margin: 0 auto;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    position: relative;
                }
                .seal-hindi {
                    font-size: 8px;
                    font-weight: bold;
                    margin-top: 3px;
                }
                .seal-english {
                    font-size: 6px;
                    font-weight: bold;
                    margin-top: 1px;
                }
                .seal-year {
                    font-size: 10px;
                    font-weight: bold;
                    margin-top: 3px;
                }
                .office-note-title {
                    text-align: center;
                    font-size: 16px;
                    font-weight: bold;
                    margin: 30px 0;
                    text-decoration: underline;
                }
                .content {
                    font-size: 14px;
                    margin: 20px 0;
                }
                .dotted-line {
                    border-bottom: 1px dotted #000;
                    display: inline-block;
                    min-width: 150px;
                    margin: 0 5px;
                }
                .signature-section {
                    margin-top: 50px;
                    text-align: right;
                }
                .signature-title {
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .date-section {
                    margin-top: 30px;
                    text-align: right;
                }
                .reference-section {
                    margin-top: 20px;
                    font-size: 12px;
                }
            </style>
        </head>
        <body>
            <div class="office-note-container">
                <div class="reference-info">
                    R.H.P. A.R.(Res.) 152 2,500-11,2024
                </div>

                <div class="university-header">
                    <div class="seal">
                        <div class="seal-circle">
                            <div class="seal-hindi">राजस्थान विश्वविद्यालय</div>
                            <div class="seal-english">UNIVERSITY OF RAJASTHAN</div>
                            <div class="seal-year">19 47</div>
                        </div>
                    </div>
                    <div class="university-name">University of Rajasthan, Jaipur</div>
                </div>

                <div class="office-note-title">OFFICE NOTE</div>

                <div class="content">
                    <p><strong>Ref. Orders at para</strong> <span class="dotted-line"></span></p>

                    <p>
                        The viva-voce Examination (<span class="dotted-line"></span>Online / Offline) of
                        <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span>
                        was conducted by the external examiner Dr. <span class="dotted-line"></span><strong>' . htmlspecialchars($vivaExamination->externalExaminer->name ?? 'External Examiner') . '</strong><span class="dotted-line"></span>
                        and Dr. <span class="dotted-line"></span><strong>' . htmlspecialchars($vivaExamination->internalExaminer->name ?? 'Internal Examiner') . '</strong><span class="dotted-line"></span>
                        (Supervisor) in University Department of <span class="dotted-line"></span><strong>' . htmlspecialchars($department->name) . '</strong><span class="dotted-line"></span>
                        on <span class="dotted-line"></span><strong>' . $vivaExamination->examination_date->format('d-m-Y') . '</strong><span class="dotted-line"></span>
                    </p>

                    <p>
                        In view of the unanimous recommendations of all the examiners including those conducted the viva-voce examination,
                        the case is submitted to the Vice-Chancellor for awarding <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span>
                        to the Ph. D. Degree of the University in the faculty of <span class="dotted-line"></span><strong>' . htmlspecialchars($thesis->faculty ?? 'Science') . '</strong><span class="dotted-line"></span>
                    </p>

                    <p><strong>Orders of the Vice-Chancellor will be reported to the Syndicate.</strong></p>
                </div>

                <div class="date-section">
                    <p>Date: <strong>' . now()->format('d-m-Y') . '</strong></p>
                </div>

                <div class="signature-section">
                    <div class="signature-title">Assistant Registrar (Research)</div>
                    <div class="signature-title">University of Rajasthan, Jaipur</div>
                </div>

                <div class="reference-section">
                    <p><strong>Copy forwarded to:</strong></p>
                    <p>1. (Supervisor) <span class="dotted-line"></span><strong>' . htmlspecialchars($supervisor->name) . '</strong><span class="dotted-line"></span></p>
                    <p>2. (Scholar) <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span></p>
                    <p>3. (HOD) <span class="dotted-line"></span><strong>' . htmlspecialchars($department->hod->name) . '</strong><span class="dotted-line"></span></p>
                </div>
            </div>
        </body>
        </html>';
    }

    public function downloadOfficeNote(VivaExamination $vivaExamination)
    {
        if (!$vivaExamination->office_note_file) {
            return null;
        }

        $filePath = storage_path('app/public/' . $vivaExamination->office_note_file);

        if (!file_exists($filePath)) {
            return null;
        }

        return $filePath;
    }

    /**
     * Generate office note for supervisor selection request
     */
    public function generateSupervisorSelectionOfficeNote(\App\Models\SupervisorAssignment $assignment)
    {
        // Generate PDF office note
        $pdf = $this->createSupervisorSelectionOfficeNotePDF($assignment);

        // Store the office note
        $fileName = 'supervisor_selection_office_note_' . $assignment->id . '_' . time() . '.pdf';
        $filePath = 'office_notes/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update assignment with office note path
        $assignment->update([
            'office_note_file' => $filePath,
            'office_note_generated' => true,
            'office_note_generated_at' => now(),
        ]);

        return $filePath;
    }

    private function createSupervisorSelectionOfficeNotePDF(\App\Models\SupervisorAssignment $assignment)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->generateSupervisorSelectionOfficeNoteHTML($assignment);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function generateSupervisorSelectionOfficeNoteHTML(\App\Models\SupervisorAssignment $assignment)
    {
        $scholar = $assignment->scholar;
        $user = $scholar->user;
        $supervisor = $assignment->supervisor;
        $department = $scholar->admission->department;

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Office Note - Supervisor Selection Request</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                    line-height: 1.6;
                }
                .office-note {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 40px;
                    position: relative;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .university-name {
                    font-size: 18px;
                    font-weight: bold;
                    text-decoration: underline;
                    margin-bottom: 10px;
                }
                .office-note-title {
                    text-align: center;
                    font-size: 16px;
                    font-weight: bold;
                    margin: 30px 0;
                    text-decoration: underline;
                }
                .content {
                    font-size: 14px;
                    margin: 20px 0;
                }
                .dotted-line {
                    border-bottom: 1px dotted #000;
                    display: inline-block;
                    width: 100px;
                }
                .signature-section {
                    margin-top: 50px;
                    text-align: right;
                }
                .signature-title {
                    font-weight: bold;
                    margin-bottom: 5px;
                }
            </style>
        </head>
        <body>
            <div class="office-note">
                <div class="header">
                    <div class="university-name">University of Rajasthan, Jaipur</div>
                </div>

                <div class="office-note-title">OFFICE NOTE</div>

                <div class="content">
                    <p><strong>Subject:</strong> Request for Supervisor Selection for Ph.D. Research Scholar</p>

                    <p>This is to bring to your notice that <strong>' . htmlspecialchars($user->name) . '</strong>, Research Scholar in the Department of <strong>' . htmlspecialchars($department->name) . '</strong>, has submitted a request for supervisor selection.</p>

                    <p><strong>Scholar Details:</strong></p>
                    <ul>
                        <li><strong>Name:</strong> ' . htmlspecialchars($user->name) . '</li>
                        <li><strong>Enrollment Number:</strong> ' . htmlspecialchars($scholar->enrollment_number ?? '') . '</li>
                        <li><strong>Department:</strong> ' . htmlspecialchars($department->name ?? '') . '</li>
                        <li><strong>Research Area:</strong> ' . htmlspecialchars($scholar->research_area ?? '') . '</li>
                    </ul>

                    <p><strong>Preferred Supervisor:</strong></p>
                    <ul>
                        <li><strong>Name:</strong> ' . htmlspecialchars($supervisor->user->name) . '</li>
                        <li><strong>Designation:</strong> ' . htmlspecialchars($supervisor->designation ?? '') . '</li>
                        <li><strong>Research Specialization:</strong> ' . htmlspecialchars($supervisor->research_specialization ?? '') . '</li>
                    </ul>

                    <p><strong>Justification:</strong></p>
                    <p>' . htmlspecialchars($assignment->justification) . '</p>

                    <p>Please review the request and provide your approval for the supervisor selection.</p>

                    <p><strong>Copy forwarded to:</strong></p>
                    <p>1. (Supervisor) <span class="dotted-line"></span><strong>' . htmlspecialchars($supervisor->user->name) . '</strong><span class="dotted-line"></span></p>
                    <p>2. (Scholar) <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span></p>
                    <p>3. (HOD) <span class="dotted-line"></span><strong>' . htmlspecialchars($department->hod->name) . '</strong><span class="dotted-line"></span></p>
                </div>

                <div class="signature-section">
                    <div class="signature-title">Assistant Registrar (Research)</div>
                    <div class="signature-title">University of Rajasthan, Jaipur</div>
                </div>
            </div>
        </body>
        </html>';
    }
}
