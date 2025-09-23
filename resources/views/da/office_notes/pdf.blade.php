<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Note for Registration - {{ $officeNote->candidate_name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
            text-decoration: underline;
        }
        .content {
            line-height: 1.8;
        }
        .item {
            margin-bottom: 15px;
        }
        .item-number {
            font-weight: bold;
            margin-right: 10px;
        }
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            margin: 0 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .notes {
            margin-top: 30px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        @media print {
            body { margin: 0; padding: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div>File No. {{ $officeNote->file_number }}</div>
        <div>Dated: {{ $officeNote->dated ? $officeNote->dated->format('d/m/Y') : 'N/A' }}</div>
    </div>

    <!-- Title -->
    <div class="title">Office Note for Registration</div>

    <!-- Content -->
    <div class="content">
        <!-- 1. Name of Candidate -->
        <div class="item">
            <span class="item-number">1.</span>
            Name of Candidate Mr./Mrs./Miss. <span class="underline">{{ $officeNote->candidate_name ?? 'N/A' }}</span>
        </div>

        <!-- 2. Proposed Subject of Research -->
        <div class="item">
            <span class="item-number">2.</span>
            Proposed Subject of Research. <span class="underline">{{ $officeNote->research_subject ?? 'N/A' }}</span>
        </div>

        <!-- 3. Supervisor Information -->
        <div class="item">
            <span class="item-number">3.</span>
            Name of Designation and address of the proposed Prof./Dr./<br>
            <div style="margin-left: 20px;">
                <div class="underline" style="min-width: 300px;">{{ $officeNote->supervisor_name ?? 'N/A' }}</div>
                <div class="underline" style="min-width: 300px;">{{ $officeNote->supervisor_designation ?? 'N/A' }}</div>
                <div class="underline" style="min-width: 300px;">{{ $officeNote->supervisor_address ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- 4. Date of Retirement -->
        <div class="item">
            <span class="item-number">4.</span>
            Date of Retirement <span class="underline">{{ $officeNote->supervisor_retirement_date ? $officeNote->supervisor_retirement_date->format('d/m/Y') : 'N/A' }}</span>
        </div>

        <!-- 5. Co-Supervisor Information -->
        @if($officeNote->co_supervisor_name)
        <div class="item">
            <span class="item-number">5.</span>
            Name of designation and address of the proposed Co-Supervisor (If applicable) Dr...<br>
            <div style="margin-left: 20px;">
                <div class="underline" style="min-width: 300px;">{{ $officeNote->co_supervisor_name }}</div>
                <div class="underline" style="min-width: 300px;">{{ $officeNote->co_supervisor_designation ?? 'N/A' }}</div>
                <div class="underline" style="min-width: 300px;">{{ $officeNote->co_supervisor_address ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- 6. Co-Supervisor Retirement Date -->
        <div class="item">
            <span class="item-number">6.</span>
            Date of Retirement. <span class="underline">{{ $officeNote->co_supervisor_retirement_date ? $officeNote->co_supervisor_retirement_date->format('d/m/Y') : 'N/A' }}</span>
        </div>
        @endif

        <!-- 7. Eligibility Table -->
        <div class="item">
            <span class="item-number">7.</span>
            Whether the Candidate is eligible of Ph.D. Registration<br>
            <table class="table">
                <thead>
                    <tr>
                        <th>University/Board</th>
                        <th>Class Exam</th>
                        <th>Marks</th>
                        <th>%</th>
                        <th>Div</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>a) {{ $officeNote->ug_university ?? 'N/A' }}</td>
                        <td>U.G.</td>
                        <td>{{ $officeNote->ug_marks ?? 'N/A' }}</td>
                        <td>{{ $officeNote->ug_percentage ?? 'N/A' }}</td>
                        <td>{{ $officeNote->ug_division ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>b) {{ $officeNote->pg_university ?? 'N/A' }}</td>
                        <td>P.G.</td>
                        <td>{{ $officeNote->pg_marks ?? 'N/A' }}</td>
                        <td>{{ $officeNote->pg_percentage ?? 'N/A' }}</td>
                        <td>{{ $officeNote->pg_division ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 8. PAT Exam -->
        <div class="item">
            <span class="item-number">8.</span>
            Passing of PAT Exam.<br>
            <div style="margin-left: 20px;">
                PAT {{ $officeNote->pat_year ?? 'N/A' }} Final Score /Phase-II
                Year: <span class="underline" style="min-width: 50px;">{{ $officeNote->pat_year ?? 'N/A' }}</span>
                Merit No. out of 150: <span class="underline" style="min-width: 50px;">{{ $officeNote->pat_merit_number ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- 9. Course work Exam -->
        <div class="item">
            <span class="item-number">9.</span>
            Course work Exam.<br>
            <div style="margin-left: 20px;">
                UOR, Jaipur
                Mark obtained /400: <span class="underline" style="min-width: 50px;">{{ $officeNote->coursework_marks_obtained ?? 'N/A' }}</span>
                Merit No.: <span class="underline" style="min-width: 50px;">{{ $officeNote->coursework_merit_number ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- 10. DRC Approval -->
        <div class="item">
            <span class="item-number">10.</span>
            Proposed subject approved by the D.R.C. held on <span class="underline">{{ $officeNote->drc_approval_date ? $officeNote->drc_approval_date->format('d/m/Y') : 'N/A' }}</span>
        </div>

        <!-- 11. Registration Fee -->
        <div class="item">
            <span class="item-number">11.</span>
            Registration fee Received vide Receipt No. <span class="underline" style="min-width: 100px;">{{ $officeNote->registration_fee_receipt_number ?? 'N/A' }}</span>
            Date: <span class="underline" style="min-width: 100px;">{{ $officeNote->registration_fee_date ? $officeNote->registration_fee_date->format('d/m/Y') : 'N/A' }}</span>
        </div>

        <!-- 12. Date of Commencement -->
        <div class="item">
            <span class="item-number">12.</span>
            Date of Commencement <span class="underline">{{ $officeNote->commencement_date ? $officeNote->commencement_date->format('d/m/Y') : 'N/A' }}</span>
        </div>

        <!-- 13. Approval Statement -->
        <div class="item">
            <span class="item-number">13.</span>
            If approved Mr./Mrs./Miss. <span class="underline" style="min-width: 150px;">{{ $officeNote->candidate_name ?? 'N/A' }}</span> may be permitted to<br>
            <div style="margin-left: 20px;">
                research on the proposed subject under guidance of Prof./Dr. <span class="underline" style="min-width: 200px;">{{ $officeNote->supervisor_name ?? 'N/A' }}</span><br>
                and Co Guidance of Dr. <span class="underline" style="min-width: 200px;">{{ $officeNote->co_supervisor_name ?? 'N/A' }}</span><br>
                who has <span class="underline" style="min-width: 50px;">{{ $officeNote->supervisor_seats_available ?? 'N/A' }}</span> Seats (Supervisor Reg. Page No. <span class="underline" style="min-width: 50px;">{{ $officeNote->supervisor_registration_page_number ?? 'N/A' }}</span>, Candidates under his/her guidance including the present one.
            </div>
        </div>

        <!-- 14. Office Proposal -->
        <div class="item">
            <span class="item-number">14.</span>
            Office proposal contained in Para No. N/13 above may kindly be submitted to the Vice Chancellor for approval.
        </div>

        <!-- 15. Enrollment Number -->
        <div class="item">
            <span class="item-number">15.</span>
            Enrollment No. <span class="underline">{{ $officeNote->enrollment_number ?? 'N/A' }}</span>
        </div>
    </div>

    <!-- Additional Notes -->
    @if($officeNote->notes)
    <div class="notes">
        <strong>Additional Notes:</strong><br>
        {{ $officeNote->notes }}
    </div>
    @endif

    <!-- Print Button (hidden when printing) -->
    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Print / Save as PDF
        </button>
    </div>
</body>
</html>
