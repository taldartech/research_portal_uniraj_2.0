<?php

namespace App\Services;

use App\Models\ThesisSubmission;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class CertificateGenerationService
{
    public function generateSubmissionCertificate(ThesisSubmission $thesisSubmission)
    {
        // Generate PDF certificate
        $pdf = $this->createSubmissionCertificatePDF($thesisSubmission);

        // Store the certificate
        $fileName = 'submission_certificate_' . $thesisSubmission->id . '_' . time() . '.pdf';
        $filePath = 'certificates/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update thesis submission with certificate path
        $thesisSubmission->update([
            'submission_certificate_file' => $filePath
        ]);

        return $filePath;
    }

    private function createSubmissionCertificatePDF(ThesisSubmission $thesisSubmission)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->generateCertificateHTML($thesisSubmission);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function generateCertificateHTML(ThesisSubmission $thesisSubmission)
    {
        $scholar = $thesisSubmission->scholar;
        $user = $scholar->user;
        $department = $scholar->admission->department;

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Submission Certificate</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                }
                .certificate-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: white;
                    border: 2px solid #000;
                    padding: 40px;
                    position: relative;
                }
                .university-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .university-name {
                    font-size: 24px;
                    font-weight: bold;
                    font-style: italic;
                    margin-bottom: 5px;
                }
                .university-location {
                    font-size: 18px;
                    margin-bottom: 20px;
                }
                .reference-number {
                    position: absolute;
                    top: 20px;
                    left: 20px;
                    font-size: 12px;
                    color: #666;
                }
                .seal {
                    text-align: center;
                    margin: 20px 0;
                }
                .seal-circle {
                    width: 120px;
                    height: 120px;
                    border: 3px solid #000;
                    border-radius: 50%;
                    margin: 0 auto;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    position: relative;
                }
                .seal-hindi {
                    font-size: 10px;
                    font-weight: bold;
                    margin-top: 5px;
                }
                .seal-english {
                    font-size: 8px;
                    font-weight: bold;
                    margin-top: 2px;
                }
                .seal-year {
                    font-size: 12px;
                    font-weight: bold;
                    margin-top: 5px;
                }
                .certificate-title {
                    text-align: center;
                    font-size: 20px;
                    font-weight: bold;
                    margin: 30px 0;
                    text-decoration: underline;
                }
                .certificate-body {
                    font-size: 14px;
                    line-height: 1.6;
                    margin: 30px 0;
                }
                .dotted-line {
                    border-bottom: 1px dotted #000;
                    display: inline-block;
                    min-width: 200px;
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
                .signature-details {
                    font-size: 12px;
                    margin-top: 20px;
                }
                .date-section {
                    margin-top: 30px;
                    text-align: right;
                }
            </style>
        </head>
        <body>
            <div class="certificate-container">
                <div class="reference-number">
                    R.U.P. AR (Res.) 380 10,000/10 Pads 12-2024
                </div>

                <div class="university-header">
                    <div class="university-name">University of Rajasthan</div>
                    <div class="university-location">JAIPUR</div>
                </div>

                <div class="seal">
                    <div class="seal-circle">
                        <div class="seal-hindi">राजस्थान विश्वविद्यालय</div>
                        <div class="seal-english">UNIVERSITY OF RAJASTHAN</div>
                        <div class="seal-year">19 47</div>
                    </div>
                </div>

                <div class="certificate-title">SUBMISSION CERTIFICATE</div>

                <div class="certificate-body">
                    <p>
                        Certified that <span class="dotted-line"></span><strong>' . htmlspecialchars($user->name) . '</strong><span class="dotted-line"></span>
                    </p>
                    <p>
                        has submitted the Ph.D. thesis entitled <span class="dotted-line"></span><strong>"' . htmlspecialchars($thesisSubmission->title) . '"</strong><span class="dotted-line"></span>
                    </p>
                    <p>
                        in the subject of <span class="dotted-line"></span><strong>' . htmlspecialchars($thesisSubmission->subject) . '</strong><span class="dotted-line"></span>
                    </p>
                    <p>
                        under the supervision of <span class="dotted-line"></span><strong>' . htmlspecialchars($thesisSubmission->supervisor->name) . '</strong><span class="dotted-line"></span>
                    </p>
                    <p>
                        in the Department of <span class="dotted-line"></span><strong>' . htmlspecialchars($department->name) . '</strong><span class="dotted-line"></span>
                    </p>
                    <p>
                        on <span class="dotted-line"></span><strong>' . $thesisSubmission->submission_date->format('d-m-Y') . '</strong><span class="dotted-line"></span>
                    </p>
                </div>

                <div class="date-section">
                    <p>Date: <strong>' . now()->format('d-m-Y') . '</strong></p>
                </div>

                <div class="signature-section">
                    <div class="signature-title">Section Officer (Research)</div>
                    <div class="signature-details">
                        University of Rajasthan<br>
                        Jaipur
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    public function downloadCertificate(ThesisSubmission $thesisSubmission)
    {
        if (!$thesisSubmission->submission_certificate_file) {
            return null;
        }

        $filePath = storage_path('app/public/' . $thesisSubmission->submission_certificate_file);

        if (!file_exists($filePath)) {
            return null;
        }

        return $filePath;
    }
}
