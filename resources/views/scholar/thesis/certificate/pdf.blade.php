<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $certificate->certificate_type_name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .certificate {
            border: 3px solid #000;
            padding: 40px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-decoration: underline;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .scholar-name {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        .thesis-title {
            font-size: 18px;
            font-style: italic;
            margin: 20px 0;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
        @media print {
            body { margin: 0; }
            .certificate { border: 3px solid #000; }
        }
    </style>
</head>
<body>
    <div class="certificate">
        @if($certificate->certificate_type === 'pre_phd_presentation')
            <!-- Pre-Ph.D. Presentation Certificate -->
            <div class="header">CERTIFICATE</div>

            <div class="content">
                <p>This is to certify that</p>
                <div class="scholar-name">{{ $certificate->scholar->name }}</div>
                <p>Research Scholar, Department of {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                <p>University of Rajasthan, Jaipur</p>
                <p>has presented his/her Pre-Ph.D. seminar on</p>
                <div class="thesis-title">"{{ $certificate->thesisSubmission->title }}"</div>
                <p>on {{ \Carbon\Carbon::parse($certificate->certificate_data['presentation_date'])->format('F d, Y') }}</p>
                <p>at {{ $certificate->certificate_data['venue'] }}</p>
                <p>This certificate is issued for the purpose of thesis submission.</p>
            </div>

            <div class="signatures">
                <div class="signature">
                    <p><strong>Supervisor</strong></p>
                    <p>{{ $certificate->scholar->supervisor_name ?? 'N/A' }}</p>
                    <p>{{ $certificate->scholar->supervisor_designation ?? 'N/A' }}</p>
                    <div class="signature-line"></div>
                </div>
                <div class="signature">
                    <p><strong>Head of Department</strong></p>
                    <p>{{ $certificate->scholar->admission->department->hod->name ?? 'N/A' }}</p>
                    <p>HOD, {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                    <div class="signature-line"></div>
                </div>
            </div>

        @elseif($certificate->certificate_type === 'research_papers')
            <!-- Research Papers Presentation Certificate -->
            <div class="header">CERTIFICATE</div>

            <div class="content">
                <p>This is to certify that</p>
                <div class="scholar-name">{{ $certificate->scholar->name }} {{ $certificate->scholar->last_name }}</div>
                <p>Research Scholar, Department of {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                <p>University of Rajasthan, Jaipur</p>
                <p>has presented research papers in</p>
                <div class="thesis-title">"{{ $certificate->certificate_data['conference_name'] }}"</div>
                <p>on {{ \Carbon\Carbon::parse($certificate->certificate_data['date'])->format('F d, Y') }}</p>
                <p>at {{ $certificate->certificate_data['venue'] }}</p>
                <p>This certificate is issued for the purpose of thesis submission.</p>
            </div>

            <div class="signatures">
                <div class="signature">
                    <p><strong>Supervisor</strong></p>
                    <p>{{ $certificate->scholar->supervisor_name ?? 'N/A' }}</p>
                    <p>{{ $certificate->scholar->supervisor_designation ?? 'N/A' }}</p>
                    <div class="signature-line"></div>
                </div>
                <div class="signature">
                    <p><strong>Head of Department</strong></p>
                    <p>{{ $certificate->scholar->admission->department->hod->name ?? 'N/A' }}</p>
                    <p>HOD, {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                    <div class="signature-line"></div>
                </div>
            </div>

        @elseif($certificate->certificate_type === 'peer_reviewed_journal')
            <!-- Peer Reviewed Journal Certificate -->
            <div class="header">CERTIFICATE</div>

            <div class="content">
                <p>This is to certify that</p>
                <div class="scholar-name">{{ $certificate->scholar->name }} {{ $certificate->scholar->last_name }}</div>
                <p>Research Scholar, Department of {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                <p>University of Rajasthan, Jaipur</p>
                <p>has published research papers in peer reviewed journal</p>
                <div class="thesis-title">"{{ $certificate->certificate_data['journal_name'] }}"</div>
                <p>Volume/Issue: {{ $certificate->certificate_data['volume_issue'] }}</p>
                <p>Publication Date: {{ \Carbon\Carbon::parse($certificate->certificate_data['publication_date'])->format('F d, Y') }}</p>
                <p>This certificate is issued for the purpose of thesis submission.</p>
            </div>

            <div class="signatures">
                <div class="signature">
                    <p><strong>Supervisor</strong></p>
                    <p>{{ $certificate->scholar->supervisor_name ?? 'N/A' }}</p>
                    <p>{{ $certificate->scholar->supervisor_designation ?? 'N/A' }}</p>
                    <div class="signature-line"></div>
                </div>
                <div class="signature">
                    <p><strong>Head of Department</strong></p>
                    <p>{{ $certificate->scholar->admission->department->hod->name ?? 'N/A' }}</p>
                    <p>HOD, {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                    <div class="signature-line"></div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
