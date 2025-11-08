<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Progress Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholar Information -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Name:</span>
                                <span class="text-gray-900">{{ $report->scholar->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Scholar ID:</span>
                                <span class="text-gray-900">SCH-{{ str_pad($report->scholar->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Department:</span>
                                <span class="text-gray-900">{{ $report->scholar->admission->department->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Supervisor:</span>
                                <span class="text-gray-900">{{ $report->supervisor->user->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Report Details -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Progress Report Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Report Period:</span>
                                <span class="text-gray-900">{{ $report->report_period ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Submission Date:</span>
                                <span class="text-gray-900">{{ $report->submission_date ? $report->submission_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Report Content -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Progress Report File</h3>
                        @if($report->report_file)
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Progress Report PDF</p>
                                    <p class="text-xs text-gray-500">Uploaded on {{ $report->submission_date ? $report->submission_date->format('M d, Y') : 'Unknown date' }}</p>
                                </div>
                                <a href="{{ Storage::url($report->report_file) }}" target="_blank" class="ml-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    View PDF
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No report file uploaded</p>
                        @endif
                    </div>

                    @if($report->feedback_da)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">Dealing Assistant Feedback</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $report->feedback_da }}</div>
                    </div>
                    @endif

                    @if($report->special_remark)
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                        <h3 class="text-lg font-medium text-yellow-900 mb-2">Special Remarks</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $report->special_remark }}</div>
                    </div>
                    @endif

                    @if($report->hod_remarks && $report->hod_approved_at)
                    <div class="mb-6 p-4 {{ $report->hod_warning ? 'bg-yellow-50 border-2 border-yellow-300' : 'bg-green-50' }} rounded-lg">
                        <h3 class="text-lg font-medium {{ $report->hod_warning ? 'text-yellow-900' : 'text-green-900' }} mb-2">
                            {{ \App\Helpers\WorkflowHelper::getRoleFullForm('hod') }} Remarks
                            @if($report->hod_warning)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">⚠️ Warning (Unsatisfied)</span>
                            @else
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">Approved</span>
                            @endif
                        </h3>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap mb-2">{{ $report->hod_remarks }}</div>
                        @if($report->drc_date)
                        <div class="text-xs text-gray-600 mt-2">
                            <strong>DRC Date:</strong> {{ $report->drc_date->format('M d, Y') }}
                        </div>
                        @endif
                        @if($report->hodApprover)
                        <div class="text-xs text-gray-600 mt-1">
                            <strong>Approved by:</strong> {{ $report->hodApprover->name }} on {{ $report->hod_approved_at->format('M d, Y H:i') }}
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($report->supervisor_remarks && $report->supervisor_approved_at)
                    <div class="mb-6 p-4 {{ $report->supervisor_warning ? 'bg-yellow-50 border-2 border-yellow-300' : 'bg-blue-50' }} rounded-lg">
                        <h3 class="text-lg font-medium {{ $report->supervisor_warning ? 'text-yellow-900' : 'text-blue-900' }} mb-2">
                            {{ \App\Helpers\WorkflowHelper::getRoleFullForm('supervisor') }} Remarks
                            @if($report->supervisor_warning)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">⚠️ Warning (Unsatisfied)</span>
                            @endif
                        </h3>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap mb-2">{{ $report->supervisor_remarks }}</div>
                        @if($report->rac_meeting_date)
                        <div class="text-xs text-gray-600 mt-2">
                            <strong>RAC Meeting Date:</strong> {{ $report->rac_meeting_date->format('M d, Y') }}
                        </div>
                        @endif
                        @if($report->rac_minutes_file)
                        <div class="mt-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1">RAC Minutes File</label>
                            <a href="{{ Storage::url($report->rac_minutes_file) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                View RAC Minutes
                            </a>
                        </div>
                        @endif
                        @if($report->supervisorApprover)
                        <div class="text-xs text-gray-600 mt-1">
                            <strong>Approved by:</strong> {{ $report->supervisorApprover->name }} on {{ $report->supervisor_approved_at->format('M d, Y H:i') }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Approval Form -->
                    <style>
                        /* Force button visibility */
                        button#submitButton {
                            display: inline-flex !important;
                            opacity: 1 !important;
                            visibility: visible !important;
                            pointer-events: auto !important;
                        }
                        button#forwardToSOButton {
                            display: inline-flex !important;
                            opacity: 1 !important;
                            visibility: visible !important;
                            pointer-events: auto !important;
                        }
                        button#rejectButton {
                            display: none !important;
                        }
                        body.reject-mode button#submitButton,
                        body.reject-mode button#forwardToSOButton {
                            display: none !important;
                        }
                        body.reject-mode button#rejectButton {
                            display: inline-flex !important;
                        }
                    </style>
                    <div class="mt-8 p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dealing Assistant Remark</h3>
                        <form method="POST" action="{{ route('da.progress_reports.process', $report) }}" id="approvalForm">
                            @csrf
                            @method('POST')

                            <!-- Hidden field for action -->
                            <input type="hidden" name="action" id="formAction" value="approve">

                            <!-- Action Selection for Reject -->
                            <input type="hidden" name="reject_action" value="Approved">

                            <!-- Dealing Assistant Negative Remarks (Required when forwarding to SO) -->
                            <div class="mb-6" id="negative_remarks_section">
                                <label for="da_negative_remarks" class="block text-sm font-medium text-gray-700 mb-2">Dealing Assistant Remarks</label>
                                <textarea id="da_negative_remarks" name="da_negative_remarks" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter negative remarks...">{{ old('da_negative_remarks') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Required when forwarding to SO. Leave empty for direct submission.</p>
                                @error('da_negative_remarks')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('da.progress_reports.pending') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>

                                <!-- Reject Button (shown when reject is selected) -->
                                {{-- <button type="button" id="rejectButton" style="display: none;" class="items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject
                                </button> --}}

                                <!-- Submit Button (direct approval) -->
                                <button type="button" id="submitButton" style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important;" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Viewed
                                </button>

                                <!-- Forward to SO Button -->
                                <button type="button" id="forwardToSOButton" style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important;" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                    Forward to {{ \App\Helpers\WorkflowHelper::getRoleFullForm('so') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const rejectAction = document.getElementById('reject_action');
                            const formAction = document.getElementById('formAction');
                            const submitButton = document.getElementById('submitButton');
                            const forwardToSOButton = document.getElementById('forwardToSOButton');
                            const rejectButton = document.getElementById('rejectButton');
                            const approvalForm = document.getElementById('approvalForm');
                            const negativeRemarks = document.getElementById('da_negative_remarks');


                            function updateButtons() {
                                if (rejectAction && rejectAction.value === 'reject') {
                                    document.body.classList.add('reject-mode');
                                    if (formAction) formAction.value = 'reject';
                                } else {
                                    document.body.classList.remove('reject-mode');
                                    if (formAction) formAction.value = 'approve';
                                }
                            }

                            if (rejectAction) {
                                rejectAction.addEventListener('change', updateButtons);
                                updateButtons();
                            }

                            // Handle Submit button (direct approval)
                            submitButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                if (formAction) formAction.value = 'approve';
                                if (negativeRemarks) negativeRemarks.value = ''; // Clear negative remarks for direct approval
                                if (approvalForm) approvalForm.submit();
                            });

                            // Handle Forward to SO button
                            forwardToSOButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                // if (!negativeRemarks || !negativeRemarks.value.trim()) {
                                //     alert('Please enter negative remarks before forwarding to {{ \App\Helpers\WorkflowHelper::getRoleFullForm("so") }}.');
                                //     if (negativeRemarks) negativeRemarks.focus();
                                //     return;
                                // }
                                if (formAction) formAction.value = 'approve'; // Still approve, but with negative remarks
                                if (approvalForm) approvalForm.submit();
                            });

                            // Handle Reject button
                            rejectButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                if (confirm('Are you sure you want to reject this progress report?')) {
                                    if (formAction) formAction.value = 'reject';
                                    if (approvalForm) approvalForm.submit();
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
