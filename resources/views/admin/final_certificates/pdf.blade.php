<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Final Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 40px;
            background: white;
            color: #000;
        }
        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            border: 3px solid #000;
            padding: 40px;
            text-align: center;
        }
        .university-header {
            margin-bottom: 30px;
        }
        .university-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .university-subtitle {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
        }
        .certificate-body {
            font-size: 16px;
            line-height: 1.6;
            margin: 30px 0;
            text-align: left;
        }
        .scholar-name {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
        }
        .thesis-title {
            font-style: italic;
            margin: 15px 0;
            text-align: center;
        }
        .examiners-section {
            margin: 30px 0;
            text-align: left;
        }
        .examiner-list {
            margin: 15px 0;
        }
        .examiner-item {
            margin: 10px 0;
            padding-left: 20px;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 200px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 20px;
        }
        .certificate-number {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 12px;
        }
        .date-section {
            margin: 20px 0;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-number">
            Certificate No: {{ $certificate->certificate_number }}
        </div>

        <div class="university-header">
            <div class="university-name">University of Rajasthan</div>
            <div class="university-subtitle">Jaipur, Rajasthan, India</div>
        </div>

        <div class="certificate-title">CERTIFICATE OF COMPLETION</div>

        <div class="certificate-body">
            <p>This is to certify that</p>

            <div class="scholar-name">{{ $scholar->name }}</div>

            <p>Registration Number: <strong>{{ $scholar->registration_number }}</strong></p>

            <p>has successfully completed the requirements for the degree of</p>

            <div style="text-align: center; font-weight: bold; margin: 20px 0;">
                {{ $certificate->degree_title }}
            </div>

            <p>in <strong>{{ $certificate->specialization }}</strong></p>

            <div class="thesis-title">
                "{{ $thesis->thesis_title }}"
            </div>

            <p>The candidate has successfully defended the thesis in a viva-voce examination held on <strong>{{ $certificate->viva_date->format('F j, Y') }}</strong> at <strong>{{ $certificate->viva_venue }}</strong>.</p>

            <div class="examiners-section">
                <p><strong>The examination was conducted by the following examiners:</strong></p>
                <div class="examiner-list">
                    @foreach($examinerNames as $index => $name)
                        <div class="examiner-item">
                            {{ $index + 1 }}. {{ $name }}, {{ $examinerDesignations[$index] }}, {{ $examinerInstitutions[$index] }}
                        </div>
                    @endforeach
                </div>
            </div>

            @if($certificate->recommendation_notes)
                <p><strong>Recommendation:</strong> {{ $certificate->recommendation_notes }}</p>
            @endif

            <p>This certificate is issued on <strong>{{ $certificate->issue_date->format('F j, Y') }}</strong> and is valid for all official purposes.</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div>Head of Department</div>
            </div>
            <div class="signature-box">
                <div>Dean</div>
            </div>
            <div class="signature-box">
                <div>Vice Chancellor</div>
            </div>
        </div>

        <div class="date-section">
            <p>Date: {{ $certificate->issue_date->format('F j, Y') }}</p>
        </div>
    </div>
</body>
</html>
