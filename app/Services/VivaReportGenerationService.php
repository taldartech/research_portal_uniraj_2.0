<?php

namespace App\Services;

use App\Models\VivaReport;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class VivaReportGenerationService
{
    public function generateVivaReport(VivaReport $vivaReport)
    {
        // Generate PDF viva report
        $pdf = $this->createVivaReportPDF($vivaReport);

        // Store the viva report
        $fileName = 'viva_report_' . $vivaReport->id . '_' . time() . '.pdf';
        $filePath = 'viva_reports/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update viva report with file path
        $vivaReport->update([
            'report_file' => $filePath,
            'report_completed' => true,
            'report_submitted_at' => now(),
        ]);

        return $filePath;
    }

    private function createVivaReportPDF(VivaReport $vivaReport)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->generateVivaReportHTML($vivaReport);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function generateVivaReportHTML(VivaReport $vivaReport)
    {
        $scholar = $vivaReport->scholar;
        $user = $scholar->user;
        $supervisor = $vivaReport->supervisor;
        $department = $scholar->admission->department;
        $thesis = $vivaReport->thesisSubmission;

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Ph.D. Viva-Voce Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                    line-height: 1.6;
                }
                .report-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 40px;
                    position: relative;
                }
                .reference-info {
                    text-align: left;
                    margin-bottom: 20px;
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
                .department-info {
                    text-align: right;
                    margin-bottom: 20px;
                    font-size: 14px;
                }
                .report-title {
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
                    min-width: 200px;
                    margin: 0 5px;
                }
                .signature-section {
                    margin-top: 50px;
                    display: flex;
                    justify-content: space-between;
                }
                .signature-block {
                    text-align: center;
                    width: 30%;
                }
                .signature-title {
                    font-weight: bold;
                    margin-bottom: 20px;
                }
                .signature-line {
                    border-bottom: 1px solid #000;
                    width: 100%;
                    height: 40px;
                    margin-top: 10px;
                }
                .outcome-section {
                    margin: 30px 0;
                    padding: 20px;
                    background-color: #f9f9f9;
                    border: 1px solid #ddd;
                }
                .remarks-section {
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="report-container">
                <div class="reference-info">
                    <p>ds) 08-2024-</p>
                </div>

                <div class="university-header">
                    <div class="university-name">UNIVERSITY OF RAJASTHAN, JAIPUR</div>
                </div>

                <div class="department-info">
                    <p>Department <span class="dotted-line"></span><strong>' . htmlspecialchars($department->name) . '</strong></p>
                </div>

                <div class="report-title">Ph.D. viva-voce report</div>

                <div class="content">
                    <p><strong>Name of Research Scholar:</strong> <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span></p>

                    <p><strong>Topic of Research:</strong> <span class="dotted-line"></span><strong>' . htmlspecialchars($thesis->title) . '</strong><span class="dotted-line"></span></p>

                    <p><strong>Name of Supervisor:</strong> <span class="dotted-line"></span><strong>' . htmlspecialchars($supervisor->name) . '</strong><span class="dotted-line"></span></p>

                    <p><strong>Name of External Examiner:</strong> <span class="dotted-line"></span><strong>' . htmlspecialchars($vivaReport->external_examiner_name) . '</strong><span class="dotted-line"></span></p>

                    <p>
                        The viva-voce of the above candidate was conducted in the Department of
                        <span class="dotted-line"></span><strong>' . htmlspecialchars($department->name) . '</strong><span class="dotted-line"></span>
                        dated <span class="dotted-line"></span><strong>' . $vivaReport->viva_date->format('d-m-Y') . '</strong><span class="dotted-line"></span>
                        at <span class="dotted-line"></span><strong>' . $vivaReport->viva_time . '</strong><span class="dotted-line"></span>
                        in the presence of faculty members/research scholars
                        for award of Ph.D. degree to the candidate.
                    </p>
                </div>

                <div class="outcome-section">
                    <p><strong>Viva-voce Outcome:</strong></p>
                    <p>
                        The viva-voce of the candidate was conducted
                        <span class="dotted-line"></span><strong>' . ($vivaReport->viva_successful ? 'successful and satisfactory' : 'unsuccessful') . '</strong><span class="dotted-line"></span>
                        thus it is recommended that the candidate
                        <span class="dotted-line"></span><strong>' . ($vivaReport->viva_successful ? 'be awarded' : 'not be awarded') . '</strong><span class="dotted-line"></span>
                        Ph.D. degree.
                    </p>
                </div>

                <div class="remarks-section">
                    <p><strong>Additional remarks if any:</strong></p>
                    <p><span class="dotted-line"></span><strong>' . htmlspecialchars($vivaReport->additional_remarks ?? '') . '</strong><span class="dotted-line"></span></p>
                </div>

                <div class="signature-section">
                    <div class="signature-block">
                        <div class="signature-title">Signature and seal of</div>
                        <div class="signature-title">Head of the Department</div>
                        <div class="signature-line"></div>
                    </div>

                    <div class="signature-block">
                        <div class="signature-title">Signature supervisor</div>
                        <div class="signature-line"></div>
                    </div>

                    <div class="signature-block">
                        <div class="signature-title">Signature external examiner</div>
                        <div class="signature-line"></div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    public function downloadVivaReport(VivaReport $vivaReport)
    {
        if (!$vivaReport->report_file) {
            return null;
        }

        $filePath = storage_path('app/public/' . $vivaReport->report_file);

        if (!file_exists($filePath)) {
            return null;
        }

        return $filePath;
    }
}
