<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ph.D. Registration Letter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .university-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .research-section {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .letter-content {
            margin: 20px 0;
        }
        .to-section {
            margin-bottom: 20px;
        }
        .from-section {
            text-align: right;
            margin-bottom: 20px;
        }
        .subject {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0;
        }
        .body-text {
            margin: 20px 0;
            text-align: justify;
        }
        .research-topic {
            margin: 20px 0;
            font-weight: bold;
        }
        .note-section {
            margin: 20px 0;
            font-style: italic;
        }
        .copy-forwarded {
            margin: 20px 0;
        }
        .signature {
            margin-top: 40px;
        }
        .footer {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="university-logo"><img src="{{ asset('black-logo.png') }}" alt="Logo" class="block w-auto fill-current text-gray-800" style="height: 3.5rem;"></div>
        <div class="university-name">University of Rajasthan, Jaipur</div>
        <div class="research-section">(Research Section)</div>
    </div>

    <div class="letter-content">
        <div class="to-section">
            <strong>To:</strong><br>
            THE HEAD<br>
            Department of {{ $scholar->admission->department->name ?? 'Computer Science' }},<br>
            University of Rajasthan, Jaipur.
        </div>

        <div class="from-section">
            <strong>No. RS/{{ $registrationForm->dispatch_number }}/</strong><br><br>
            <strong>From: THE REGISTRAR, UOR, JAIPUR.</strong><br>
            <strong>Date:</strong> {{ $registrationForm->signed_by_dr_at ? $registrationForm->signed_by_dr_at->format('d/m/Y') : $registrationForm->generated_at->format('d/m/Y') }}
        </div>

        <div class="subject">
            Ph.D. REGISTRATION LETTER
        </div>

        <div class="body-text">
            <p>Dear Sir/Madam,</p>

            <p>With reference to your endorsement on the application of Mr./Mrs./Ms. <strong>{{ $scholar->name }} {{ $scholar->last_name }}</strong> registration as a Research Scholar to supplicate for the Ph.D. Degree of the University, I am pleased to inform you that he/she has been permitted by the Vice-Chancellor on behalf of the Syndicate to pursue research on the subject <strong>{{ $synopsis->proposed_topic ?? $scholar->research_area }}</strong>.</p>
        </div>

        <div class="research-topic">
            <strong>Research Topic:</strong> {{ $synopsis->proposed_topic ?? $scholar->research_area }}
        </div>

        <div class="note-section">
            <p><strong>Note:</strong> The research scholar is advised to maintain regular contact with the supervisor and submit progress reports as required by the university guidelines.</p>
        </div>

        <div class="copy-forwarded">
            <p><strong>Copy forwarded to:</strong></p>
            <ol>
                <li>The concerned Supervisor</li>
                <li>The Research Scholar</li>
                <li>The Dean, Faculty of {{ $scholar->admission->department->name ?? 'Computer Science' }}</li>
                <li>The Controller of Examinations</li>
            </ol>
        </div>

        <div class="signature">
            <p>Yours faithfully,</p>
            <br>
            <p><strong>THE REGISTRAR</strong><br>
            University of Rajasthan, Jaipur</p>
        </div>

        <div class="footer">
            <p><strong>Date:</strong> {{ $registrationForm->signed_by_dr_at ? $registrationForm->signed_by_dr_at->format('d/m/Y') : $registrationForm->generated_at->format('d/m/Y') }}</p>
            <p><strong>Registration Number:</strong> {{ $registrationForm->dispatch_number }}</p>
        </div>
    </div>
</body>
</html>
