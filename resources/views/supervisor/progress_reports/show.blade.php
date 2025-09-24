<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progress Report Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholar Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->scholar->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar ID</label>
                                <p class="mt-1 text-sm text-gray-900">SCH-{{ str_pad($progressReport->scholar->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->scholar->admission->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Report Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Report Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Report Period</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->report_period ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submission Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->submission_date ? $progressReport->submission_date->format('Y-m-d H:i:s') : 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <p class="mt-1">
                                    @if($progressReport->status === 'pending_supervisor_approval')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Supervisor Approval
                                        </span>
                                    @elseif($progressReport->status === 'pending_hod_approval')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Pending HOD Approval
                                        </span>
                                    @elseif($progressReport->status === 'pending_da_approval')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Pending DA Approval
                                        </span>
                                    @elseif($progressReport->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($progressReport->status === 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst(str_replace('_', ' ', $progressReport->status)) }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Report Period (Text)</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->report_period ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Report Content -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Content</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Progress Report File</label>
                                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                    @if($progressReport->report_file)
                                        <div class="flex items-center space-x-4">
                                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Progress Report PDF</p>
                                                <p class="text-xs text-gray-500">Uploaded on {{ $progressReport->submission_date ? $progressReport->submission_date->format('M d, Y') : 'Unknown date' }}</p>
                                            </div>
                                            <a href="{{ Storage::url($progressReport->report_file) }}" target="_blank" class="ml-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                View PDF
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">No report file uploaded</p>
                                    @endif
                                </div>
                            </div>

                            @if($progressReport->feedback_da)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">DA Feedback</label>
                                <div class="mt-1 p-3 bg-blue-50 rounded-md">
                                    <p class="text-sm text-gray-900">{{ $progressReport->feedback_da }}</p>
                                </div>
                            </div>
                            @endif

                            @if($progressReport->special_remark)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Special Remarks</label>
                                <div class="mt-1 p-3 bg-yellow-50 rounded-md">
                                    <p class="text-sm text-gray-900">{{ $progressReport->special_remark }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Approval Information -->
                    @if($progressReport->supervisor_approver)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Supervisor Approval</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Approved By</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->supervisorApprover->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Approved At</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->supervisor_approved_at ? $progressReport->supervisor_approved_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Supervisor Remarks</label>
                                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                    <p class="text-sm text-gray-900">{{ $progressReport->supervisor_remarks ?? 'No remarks provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($progressReport->hod_approver)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">HOD Approval</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Approved By</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->hodApprover->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Approved At</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->hod_approved_at ? $progressReport->hod_approved_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">HOD Remarks</label>
                                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                    <p class="text-sm text-gray-900">{{ $progressReport->hod_remarks ?? 'No remarks provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('staff.progress_reports.pending') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Pending Reports
                        </a>

                        @if($progressReport->status === 'pending_supervisor_approval')
                        <a href="{{ route('staff.progress_reports.approve', $progressReport) }}"
                           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Review & Approve
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
