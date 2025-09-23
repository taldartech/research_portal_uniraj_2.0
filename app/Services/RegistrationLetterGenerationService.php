<?php

namespace App\Services;

use App\Models\Scholar;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class RegistrationLetterGenerationService
{
    public function generateRegistrationLetter(Scholar $scholar)
    {
        // Generate PDF registration letter
        $pdf = $this->createRegistrationLetterPDF($scholar);

        // Store the registration letter
        $fileName = 'registration_letter_' . $scholar->id . '_' . time() . '.pdf';
        $filePath = 'registration_letters/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update scholar with registration letter path
        $scholar->update([
            'registration_letter_file' => $filePath,
            'registration_letter_generated' => true,
            'registration_letter_generated_at' => now(),
        ]);

        return $filePath;
    }

    public function generateSignedRegistrationLetter(Scholar $scholar, $signatureUserId = null)
    {
        // Generate PDF registration letter with digital signature
        $pdf = $this->createSignedRegistrationLetterPDF($scholar, $signatureUserId);

        // Store the signed registration letter
        $fileName = 'signed_registration_letter_' . $scholar->id . '_' . time() . '.pdf';
        $filePath = 'registration_letters/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf);

        // Update scholar with signed registration letter path
        $scholar->update([
            'registration_letter_file' => $filePath,
            'registration_letter_generated' => true,
            'registration_letter_generated_at' => now(),
            'registration_letter_signed_by' => $signatureUserId,
            'registration_letter_signed_at' => now(),
        ]);

        return $filePath;
    }

    private function createRegistrationLetterPDF(Scholar $scholar)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->generateRegistrationLetterHTML($scholar);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function createSignedRegistrationLetterPDF(Scholar $scholar, $signatureUserId = null)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->generateSignedRegistrationLetterHTML($scholar, $signatureUserId);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function generateRegistrationLetterHTML(Scholar $scholar)
    {
        $user = $scholar->user;
        $department = $scholar->admission->department;
        $supervisor = $scholar->currentSupervisor;

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Ph.D. Registration Letter</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                    line-height: 1.6;
                }
                .letter-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 40px;
                    position: relative;
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
                .letter-title {
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
                .date-section {
                    margin-top: 30px;
                    text-align: right;
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
            <div class="letter-container">
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

                <div class="letter-title">Ph.D. REGISTRATION LETTER</div>

                <div class="content">
                    <p><strong>Ref. No.:</strong> R.H.P. A.R.(Res.) 152 2,500-11,2024</p>
                    <p><strong>Date:</strong> ' . now()->format('d-m-Y') . '</p>

                    <p>This is to certify that <strong>' . htmlspecialchars($user->name) . '</strong> has been registered as a Ph.D. research scholar in the Department of <strong>' . htmlspecialchars($department->name) . '</strong> under the supervision of <strong>' . htmlspecialchars($supervisor->name ?? 'Supervisor') . '</strong>.</p>

                    <p>The registration is valid from <strong>' . $scholar->admission->admission_date->format('d-m-Y') . '</strong> and the scholar is expected to complete the Ph.D. program within the prescribed time limit.</p>

                    <p>The scholar is required to follow all the rules and regulations of the University and maintain regular contact with the supervisor for guidance and progress updates.</p>

                    <p>This registration letter is issued for official purposes and academic records.</p>
                </div>

                <div class="date-section">
                    <p>Date: <strong>' . now()->format('d-m-Y') . '</strong></p>
                </div>

                <div class="signature-section">
                    <div class="signature-title">Assistant Registrar (Research)</div>
                    <div class="signature-title">University of Rajasthan, Jaipur</div>
                </div>
            </div>
        </body>
        </html>';
    }

    private function generateSignedRegistrationLetterHTML(Scholar $scholar, $signatureUserId = null)
    {
        $user = $scholar->user;
        $department = $scholar->admission->department;
        $supervisor = $scholar->currentSupervisor;

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
            <title>Ph.D. Registration Letter (Signed)</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                    line-height: 1.6;
                }
                .letter-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 40px;
                    position: relative;
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
                .letter-title {
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
                .date-section {
                    margin-top: 30px;
                    text-align: right;
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
            </style>
        </head>
        <body>
            <div class="letter-container">
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

                <div class="letter-title">Ph.D. REGISTRATION LETTER</div>

                <div class="content">
                    <p><strong>Ref. No.:</strong> R.H.P. A.R.(Res.) 152 2,500-11,2024</p>
                    <p><strong>Date:</strong> ' . now()->format('d-m-Y') . '</p>

                    <p>This is to certify that <strong>' . htmlspecialchars($user->name) . '</strong> has been registered as a Ph.D. research scholar in the Department of <strong>' . htmlspecialchars($department->name) . '</strong> under the supervision of <strong>' . htmlspecialchars($supervisor->name ?? 'Supervisor') . '</strong>.</p>

                    <p>The registration is valid from <strong>' . $scholar->admission->admission_date->format('d-m-Y') . '</strong> and the scholar is expected to complete the Ph.D. program within the prescribed time limit.</p>

                    <p>The scholar is required to follow all the rules and regulations of the University and maintain regular contact with the supervisor for guidance and progress updates.</p>

                    <p>This registration letter is issued for official purposes and academic records.</p>
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
            </div>
        </body>
        </html>';
    }

    public function downloadRegistrationLetter(Scholar $scholar)
    {
        if (!$scholar->registration_letter_file) {
            return null;
        }

        $filePath = storage_path('app/public/' . $scholar->registration_letter_file);

        if (!file_exists($filePath)) {
            return null;
        }

        return $filePath;
    }
}
