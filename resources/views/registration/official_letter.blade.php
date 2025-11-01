<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ph.D. Registration Letter - Official Format</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
            background-color: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 200px;
            height: auto;
            margin-bottom: 10px;
        }
        .university-name {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .research-section {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .letter-content {
            margin: 20px 0;
        }
        .to-section-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .to-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .from-section {
            display: table-cell;
            text-align: right;
            width: 50%;
            vertical-align: top;
        }
        .subject {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 30px 0;
        }
        .body-text {
            margin: 20px 0;
            text-align: justify;
        }
        .research-topic {
            margin: 20px 0;
            text-indent: 20px;
        }
        .supervision {
            margin: 20px 0;
            text-indent: 20px;
        }
        .note-section {
            margin: 20px 0;
            /* font-weight: bold; */
        }
        .note-points {
            margin-left: 20px;
        }
        .copy-forwarded {
            margin: 20px 0;
        }
        .signature {
            margin-top: 40px;
        }
        .footer {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .footer-right {
            display: table-cell;
            text-align: right;
            width: 50%;
            vertical-align: top;
        }
        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 200px;
        }
        .long-dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 200px;
        }
        /* PDF-specific styles */
        @page {
            margin: 1in;
            size: A4;
        }
        .page-break {
            page-break-before: always;
        }
        .no-break {
            page-break-inside: avoid;
        }
        body {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('black-logo.png'))) }}" alt="University Logo" class="logo">
        <div class="university-name">University of Rajasthan, Jaipur</div>
        <div class="research-section">(Research Section)</div>
    </div>

    <div class="letter-content">
        <div class="to-section-header">
            <div class="to-section">
                <strong>To,</strong><br>
                <div style="margin-left: 2%;">
                    <strong>THE HEAD</strong><br>
                    Department of <span class="dotted-line">{{ $scholar->admission->department->name ?? 'Computer Science' }}</span><br>
                    University of Rajasthan, Jaipur<br>
                    <strong>No. RS/<span class="dotted-line">{{ $registrationForm->dispatch_number }}</span>/</strong>
                </div>
            </div>

            <div class="from-section">
                <strong style="margin-right: 10rem;">From:</strong><br>
                <div style="text-align: right;">
                    <strong>THE REGISTRAR</strong><br>
                    <strong>UOR, JAIPUR.</strong><br>
                    <strong>Date <span class="dotted-line">{{ $registrationForm->signed_by_dr_at ? $registrationForm->signed_by_dr_at->format('d/m/Y') : $registrationForm->generated_at->format('d/m/Y') }}</span></strong>
                </div>
            </div>
        </div>

        <div class="subject">
            Ph.D. REGISTRATION LETTER
        </div>

        <div class="body-text">
            <p><strong>Dear Sir/Madam,</strong></p>

            <p>With reference to your endorsement on the application of Mr./Mrs./Ms. <span class="dotted-line">{{ $scholar->name }}</span> registration as a Research Scholar to supplicate for the Ph.D. Degree of the University, I am pleased to inform you that he/she has been permitted by the Vice-Chancellor on behalf of the Syndicate to pursue research on the subject</p>
        </div>

        <div class="research-topic">
            <p><strong>"Research Topic :</strong> <span class="long-dotted-line">{{ $synopsis->proposed_topic ?? $scholar->research_area }}</span><strong>"</strong>under the supervision of <strong>Dr./Prof.</strong> <span class="long-dotted-line">{{ $scholar->currentSupervisor ? $scholar->currentSupervisor->supervisor->user->name : 'Supervisor Name' }}</span>Department of<span class="dotted-line">{{ $scholar->admission->department->name ?? 'Computer Science' }}</span> University of Rajasthan, Jaipur</p>
        </div>
        <div class="note-section">
            <p style="text-align: center;"><strong>The date of commencement of research work will be <span class="dotted-line">{{ $registrationForm->signed_by_dr_at ? $registrationForm->signed_by_dr_at->format('d/m/Y') : $registrationForm->generated_at->format('d/m/Y') }}</span></strong></p>
            <p><strong>Note:</strong></p>
            <div class="note-points">
                <p>1. A research scholar shall appear before the Research Advisory Committee once in six months to make a presentation of the progress of his/her work for evaluation and further guidance. The six-monthly progress reports shall be submitted by the Research Advisory Committee to the Department Research Committee with a copy to the research scholar.</p>
                <p>2. In case the progress of the research scholar is unsatisfactory, the Research Advisory Committee shall record the reasons for the same and suggest corrective measures. If the research scholar fails to implement these corrective measures, the Research Advisory Committee may recommend to the Department Research Committee with specific reasons for cancellation/registration of the research scholar.</p>
                <p>3. The Department Research Committee will consider the report of Research Advisory Committee and in case of unsatisfactory progress report of the research scholar, the DRC may recommend for the cancellation of Registration to the University.</p>
                <p>4. After the thesis is completed, the candidate should supply four printed or type-written copies of the thesis together with a sum of Rs. …… the balance on account of the fee, prescribed by the University.</p>
                <p>5. The language, used in the thesis should be English except in the case of subject connected with an oriental language, where the thesis may, at the option of the candidate be presented in that language. The thesis shall comply with the following condition:<br>
                    <span style="margin-left: 4rem;"></span> It must be a piece of research work characterized either by the discovery of fact or by a fresh approach towards interpretation of facts or theories. In either case it should prove the candidate’s capacity for critical examination and should … [the text here is partially obscured but intends to continue about judgment and the candidate indicating how far the thesis embodies results of his own investigation]. The candidate shall indicate how far the thesis embodies the results of his own investigation and in what respect it appears to him to advance the study of the subject. It shall also be satisfactory in respect of its literary presentation.</p>
                <p>6. The thesis, when it is submitted should be accompanied: -<br>
                    (i) A certificate by the Supervisor indicating how far the work is the original work of the candidate.<br>
                    (ii) A certificate from the Principal in case of the candidate who is a student of an affiliated college or from the Supervisor in other case, to the effect that the student resided and worked under the guidance of supervisor regularly.</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Date:</strong> {{ $registrationForm->signed_by_dr_at ? $registrationForm->signed_by_dr_at->format('d/m/Y') : $registrationForm->generated_at->format('d/m/Y') }}</p>
            <p><strong>Registration Number:</strong> {{ $registrationForm->dispatch_number }}</p>
        </div>
        <div class="copy-forwarded">
            <p><strong>Copy forwarded to:</strong></p>
            <ol>
                <li>(Supervisor)<span class="dotted-line">{{ $scholar->currentSupervisor ? $scholar->currentSupervisor->supervisor->user->name : 'Supervisor Name' }}</span></li>
                <li>(Scholar)<span class="dotted-line">{{ $scholar->name }} {{ $scholar->last_name }}</span></li>
                <li>(HOD)<span class="dotted-line">{{ $scholar->admission->department->hod->name }}</span></li>
            </ol>
        </div>

    </div>
</body>
</html>
