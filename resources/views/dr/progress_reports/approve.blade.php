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

                    <!-- Report File -->
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

                    <!-- Supervisor Remarks -->
                    @if($report->supervisor_remarks && $report->supervisor_approved_at)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">{{ \App\Helpers\WorkflowHelper::getRoleFullForm('supervisor') }} Remarks</h3>
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

                    <!-- HOD Remarks -->
                    @if($report->hod_remarks && $report->hod_approved_at)
                    <div class="mb-6 p-4 {{ $report->rejected_by && $report->rejected_by === $report->hod_approver_id ? 'bg-red-50' : 'bg-green-50' }} rounded-lg">
                        <h3 class="text-lg font-medium {{ $report->rejected_by && $report->rejected_by === $report->hod_approver_id ? 'text-red-900' : 'text-green-900' }} mb-2">
                            {{ \App\Helpers\WorkflowHelper::getRoleFullForm('hod') }} Remarks
                            @if($report->rejected_by && $report->rejected_by === $report->hod_approver_id)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-800">Rejected</span>
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

                    <!-- DA Remarks -->
                    @if($report->da_remarks && $report->da_approved_at)
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                        <h3 class="text-lg font-medium text-yellow-900 mb-2">{{ \App\Helpers\WorkflowHelper::getRoleFullForm('da') }} Remarks</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap mb-2">{{ $report->da_remarks }}</div>
                        @if($report->da_negative_remarks)
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded">
                            <label class="block text-xs font-medium text-red-700 mb-1">Negative Remarks</label>
                            <div class="text-sm text-red-800 whitespace-pre-wrap">{{ $report->da_negative_remarks }}</div>
                        </div>
                        @endif
                        @if($report->daApprover)
                        <div class="text-xs text-gray-600 mt-1">
                            <strong>Approved by:</strong> {{ $report->daApprover->name }} on {{ $report->da_approved_at->format('M d, Y H:i') }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- SO Remarks -->
                    @if($report->so_remarks && $report->so_approved_at)
                    <div class="mb-6 p-4 bg-purple-50 rounded-lg">
                        <h3 class="text-lg font-medium text-purple-900 mb-2">{{ \App\Helpers\WorkflowHelper::getRoleFullForm('so') }} Remarks</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap mb-2">{{ $report->so_remarks }}</div>
                        @if($report->soApprover)
                        <div class="text-xs text-gray-600 mt-1">
                            <strong>Approved by:</strong> {{ $report->soApprover->name }} on {{ $report->so_approved_at->format('M d, Y H:i') }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- AR Remarks -->
                    @if($report->ar_remarks && $report->ar_approved_at)
                    <div class="mb-6 p-4 bg-pink-50 rounded-lg">
                        <h3 class="text-lg font-medium text-pink-900 mb-2">{{ \App\Helpers\WorkflowHelper::getRoleFullForm('ar') }} Remarks</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap mb-2">{{ $report->ar_remarks }}</div>
                        @if($report->arApprover)
                        <div class="text-xs text-gray-600 mt-1">
                            <strong>Approved by:</strong> {{ $report->arApprover->name }} on {{ $report->ar_approved_at->format('M d, Y H:i') }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- DR Approval Form -->
                    <div class="mt-8 p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ \App\Helpers\WorkflowHelper::getRoleFullForm('dr') }} Remark</h3>
                        <form method="POST" action="{{ route('dr.progress_reports.process', $report) }}">
                            @csrf
                            @method('POST')

                            <!-- Action Selection -->
                            <div class="mb-4">
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Action <span class="text-red-500">*</span></label>
                                <select id="action" name="action" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Action</option>
                                    <option value="approve" {{ old('action') == 'approve' ? 'selected' : '' }}>Approve</option>
                                    <option value="reject" {{ old('action') == 'reject' ? 'selected' : '' }}>Reject</option>
                                </select>
                                @error('action')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="mb-4">
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks <span class="text-red-500">*</span></label>
                                <textarea id="remarks" name="remarks" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your remarks..." required>{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reassignment Fields (shown only when reject is selected) -->
                            <div id="reassignment_fields" class="mb-6 hidden">
                                <div class="mb-4">
                                    <label for="reassigned_to_role" class="block text-sm font-medium text-gray-700 mb-2">Reassign To Role (Optional)</label>
                                    <select id="reassigned_to_role" name="reassigned_to_role" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Role (Optional - Leave empty for standard rejection)</option>
                                        <option value="supervisor" {{ old('reassigned_to_role') == 'supervisor' ? 'selected' : '' }}>{{ \App\Helpers\WorkflowHelper::getRoleFullForm('supervisor') }}</option>
                                        <option value="hod" {{ old('reassigned_to_role') == 'hod' ? 'selected' : '' }}>{{ \App\Helpers\WorkflowHelper::getRoleFullForm('hod') }}</option>
                                        <option value="da" {{ old('reassigned_to_role') == 'da' ? 'selected' : '' }}>{{ \App\Helpers\WorkflowHelper::getRoleFullForm('da') }}</option>
                                        <option value="so" {{ old('reassigned_to_role') == 'so' ? 'selected' : '' }}>{{ \App\Helpers\WorkflowHelper::getRoleFullForm('so') }}</option>
                                        <option value="ar" {{ old('reassigned_to_role') == 'ar' ? 'selected' : '' }}>{{ \App\Helpers\WorkflowHelper::getRoleFullForm('ar') }}</option>
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">If selected, the report will be reassigned to this role for corrections instead of being rejected.</p>
                                    @error('reassigned_to_role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="reassignment_reason" class="block text-sm font-medium text-gray-700 mb-2">Reassignment Reason (Optional)</label>
                                    <textarea id="reassignment_reason" name="reassignment_reason" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Explain why it's being reassigned to this role...">{{ old('reassignment_reason') }}</textarea>
                                    @error('reassignment_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('dr.progress_reports.pending') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actionSelect = document.getElementById('action');
        const reassignmentFields = document.getElementById('reassignment_fields');

        if (actionSelect && reassignmentFields) {
            actionSelect.addEventListener('change', function() {
                if (this.value === 'reject') {
                    reassignmentFields.classList.remove('hidden');
                } else {
                    reassignmentFields.classList.add('hidden');
                }
            });

            // Trigger on page load if old value exists
            if (actionSelect.value === 'reject') {
                reassignmentFields.classList.remove('hidden');
            }
        }
    });
</script>

