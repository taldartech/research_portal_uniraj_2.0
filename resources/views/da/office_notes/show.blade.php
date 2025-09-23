<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Office Note for Registration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Office Note Document -->
                    <div class="bg-white border-2 border-gray-300 p-8 rounded-lg shadow-lg">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <p class="text-sm text-gray-600">File No. {{ $officeNote->file_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Dated: {{ $officeNote->dated ? $officeNote->dated->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="text-center mb-8">
                            <h1 class="text-2xl font-bold text-gray-900">Office Note for Registration</h1>
                        </div>

                        <!-- Content -->
                        <div class="space-y-6">
                            <!-- 1. Name of Candidate -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">1.</span>
                                <span class="mr-2">Name of Candidate Mr./Mrs./Miss.</span>
                                <span class="border-b border-gray-400 flex-1 ml-2">{{ $officeNote->candidate_name ?? 'N/A' }}</span>
                            </div>

                            <!-- 2. Proposed Subject of Research -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">2.</span>
                                <span class="mr-2">Proposed Subject of Research.</span>
                                <span class="border-b border-gray-400 flex-1 ml-2">{{ $officeNote->research_subject ?? 'N/A' }}</span>
                            </div>

                            <!-- 3. Supervisor Information -->
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-4">3.</span>
                                    <span class="mr-2">Name of Designation and address of the proposed Prof./Dr./</span>
                                </div>
                                <div class="ml-8 space-y-1">
                                    <div class="border-b border-gray-400">{{ $officeNote->supervisor_name ?? 'N/A' }}</div>
                                    <div class="border-b border-gray-400">{{ $officeNote->supervisor_designation ?? 'N/A' }}</div>
                                    <div class="border-b border-gray-400">{{ $officeNote->supervisor_address ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- 4. Date of Retirement -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">4.</span>
                                <span class="mr-2">Date of Retirement</span>
                                <span class="border-b border-gray-400 flex-1 ml-2">{{ $officeNote->supervisor_retirement_date ? $officeNote->supervisor_retirement_date->format('d/m/Y') : 'N/A' }}</span>
                            </div>

                            <!-- 5. Co-Supervisor Information -->
                            @if($officeNote->co_supervisor_name)
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-4">5.</span>
                                    <span class="mr-2">Name of designation and address of the proposed Co-Supervisor (If applicable) Dr...</span>
                                </div>
                                <div class="ml-8 space-y-1">
                                    <div class="border-b border-gray-400">{{ $officeNote->co_supervisor_name }}</div>
                                    <div class="border-b border-gray-400">{{ $officeNote->co_supervisor_designation ?? 'N/A' }}</div>
                                    <div class="border-b border-gray-400">{{ $officeNote->co_supervisor_address ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- 6. Co-Supervisor Retirement Date -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">6.</span>
                                <span class="mr-2">Date of Retirement.</span>
                                <span class="border-b border-gray-400 flex-1 ml-2">{{ $officeNote->co_supervisor_retirement_date ? $officeNote->co_supervisor_retirement_date->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            @endif

                            <!-- 7. Eligibility Table -->
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-4">7.</span>
                                    <span class="mr-2">Whether the Candidate is eligible of Ph.D. Registration</span>
                                </div>
                                <div class="ml-8">
                                    <table class="w-full border border-gray-400">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-400 px-2 py-1 text-left">University/Board</th>
                                                <th class="border border-gray-400 px-2 py-1 text-left">Class Exam</th>
                                                <th class="border border-gray-400 px-2 py-1 text-left">Marks</th>
                                                <th class="border border-gray-400 px-2 py-1 text-left">%</th>
                                                <th class="border border-gray-400 px-2 py-1 text-left">Div</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1">a) {{ $officeNote->ug_university ?? 'N/A' }}</td>
                                                <td class="border border-gray-400 px-2 py-1">U.G.</td>
                                                <td class="border border-gray-400 px-2 py-1">{{ $officeNote->ug_marks ?? 'N/A' }}</td>
                                                <td class="border border-gray-400 px-2 py-1">{{ $officeNote->ug_percentage ?? 'N/A' }}</td>
                                                <td class="border border-gray-400 px-2 py-1">{{ $officeNote->ug_division ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1">b) {{ $officeNote->pg_university ?? 'N/A' }}</td>
                                                <td class="border border-gray-400 px-2 py-1">P.G.</td>
                                                <td class="border border-gray-400 px-2 py-1">{{ $officeNote->pg_marks ?? 'N/A' }}</td>
                                                <td class="border border-gray-400 px-2 py-1">{{ $officeNote->pg_percentage ?? 'N/A' }}</td>
                                                <td class="border border-gray-400 px-2 py-1">{{ $officeNote->pg_division ?? 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 8. PAT Exam -->
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-4">8.</span>
                                    <span class="mr-2">Passing of PAT Exam.</span>
                                </div>
                                <div class="ml-8">
                                    <div class="flex items-center space-x-4">
                                        <span>PAT {{ $officeNote->pat_year ?? 'N/A' }} Final Score /Phase-II</span>
                                        <span>Year: <span class="border-b border-gray-400 inline-block w-20">{{ $officeNote->pat_year ?? 'N/A' }}</span></span>
                                        <span>Merit No. out of 150: <span class="border-b border-gray-400 inline-block w-20">{{ $officeNote->pat_merit_number ?? 'N/A' }}</span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- 9. Course work Exam -->
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-4">9.</span>
                                    <span class="mr-2">Course work Exam.</span>
                                </div>
                                <div class="ml-8">
                                    <div class="flex items-center space-x-4">
                                        <span>UOR, Jaipur</span>
                                        <span>Mark obtained /400: <span class="border-b border-gray-400 inline-block w-20">{{ $officeNote->coursework_marks_obtained ?? 'N/A' }}</span></span>
                                        <span>Merit No.: <span class="border-b border-gray-400 inline-block w-20">{{ $officeNote->coursework_merit_number ?? 'N/A' }}</span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- 10. DRC Approval -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">10.</span>
                                <span class="mr-2">Proposed subject approved by the D.R.C. held on</span>
                                <span class="border-b border-gray-400 flex-1 ml-2">{{ $officeNote->drc_approval_date ? $officeNote->drc_approval_date->format('d/m/Y') : 'N/A' }}</span>
                            </div>

                            <!-- 11. Registration Fee -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">11.</span>
                                <span class="mr-2">Registration fee Received vide Receipt No.</span>
                                <span class="border-b border-gray-400 w-32 ml-2">{{ $officeNote->registration_fee_receipt_number ?? 'N/A' }}</span>
                                <span class="ml-4">Date:</span>
                                <span class="border-b border-gray-400 w-32 ml-2">{{ $officeNote->registration_fee_date ? $officeNote->registration_fee_date->format('d/m/Y') : 'N/A' }}</span>
                            </div>

                            <!-- 12. Date of Commencement -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">12.</span>
                                <span class="mr-2">Date of Commencement</span>
                                <span class="border-b border-gray-400 flex-1 ml-2">{{ $officeNote->commencement_date ? $officeNote->commencement_date->format('d/m/Y') : 'N/A' }}</span>
                            </div>

                            <!-- 13. Approval Statement -->
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-4">13.</span>
                                    <span class="mr-2">If approved Mr./Mrs./Miss.</span>
                                    <span class="border-b border-gray-400 w-48 ml-2">{{ $officeNote->candidate_name ?? 'N/A' }}</span>
                                    <span class="ml-2">may be permitted to</span>
                                </div>
                                <div class="ml-8">
                                    <div>research on the proposed subject under guidance of Prof./Dr.</div>
                                    <div class="border-b border-gray-400 w-64 mt-1">{{ $officeNote->supervisor_name ?? 'N/A' }}</div>
                                    <div class="mt-2">and Co Guidance of Dr.</div>
                                    <div class="border-b border-gray-400 w-64 mt-1">{{ $officeNote->co_supervisor_name ?? 'N/A' }}</div>
                                    <div class="mt-2">who has</div>
                                    <div class="border-b border-gray-400 w-16 inline-block">{{ $officeNote->supervisor_seats_available ?? 'N/A' }}</div>
                                    <span class="ml-2">Seats (Supervisor Reg. Page No.</span>
                                    <span class="border-b border-gray-400 w-16 inline-block">{{ $officeNote->supervisor_registration_page_number ?? 'N/A' }}</span>
                                    <span class="ml-2">, Candidates under his/her guidance including the present one.</span>
                                </div>
                            </div>

                            <!-- 14. Office Proposal -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">14.</span>
                                <span>Office proposal contained in Para No. N/13 above may kindly be submitted to the Vice Chancellor for approval.</span>
                            </div>

                            <!-- 15. Enrollment Number -->
                            <div class="flex items-center">
                                <span class="font-semibold mr-4">15.</span>
                                <span class="mr-2">Enrollment No.</span>
                                <span class="border-b border-gray-400 flex-1 ml-2">{{ $officeNote->enrollment_number ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        @if($officeNote->notes)
                        <div class="mt-8 p-4 bg-gray-50 rounded">
                            <h4 class="font-semibold text-gray-900 mb-2">Additional Notes:</h4>
                            <p class="text-sm text-gray-700">{{ $officeNote->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('da.office_notes.eligible_scholars') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                            <a href="{{ route('da.office_notes.edit', $officeNote) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit Office Note
                            </a>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('da.office_notes.download', $officeNote) }}"
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
