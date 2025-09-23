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
                                <span class="text-gray-900">{{ $progressReport->scholar->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Enrollment Number:</span>
                                <span class="text-gray-900">{{ $progressReport->scholar->enrollment_number ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Department:</span>
                                <span class="text-gray-900">{{ $progressReport->scholar->admission->department->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Supervisor:</span>
                                <span class="text-gray-900">{{ $progressReport->supervisor->user->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Report Details -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Progress Report Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Report Period:</span>
                                <span class="text-gray-900">{{ $progressReport->report_period }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Submission Date:</span>
                                <span class="text-gray-900">{{ $progressReport->submission_date ? $progressReport->submission_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Report Content -->
                    @if($progressReport->work_completed)
                        <div class="mb-6 p-4 bg-green-50 rounded-lg">
                            <h3 class="text-lg font-medium text-green-900 mb-2">Work Completed</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $progressReport->work_completed }}</div>
                        </div>
                    @endif

                    @if($progressReport->work_planned)
                        <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                            <h3 class="text-lg font-medium text-yellow-900 mb-2">Work Planned</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $progressReport->work_planned }}</div>
                        </div>
                    @endif

                    @if($progressReport->challenges_faced)
                        <div class="mb-6 p-4 bg-red-50 rounded-lg">
                            <h3 class="text-lg font-medium text-red-900 mb-2">Challenges Faced</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $progressReport->challenges_faced }}</div>
                        </div>
                    @endif

                    @if($progressReport->supervisor_remarks)
                        <div class="mb-6 p-4 bg-purple-50 rounded-lg">
                            <h3 class="text-lg font-medium text-purple-900 mb-2">Supervisor Remarks</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $progressReport->supervisor_remarks }}</div>
                        </div>
                    @endif

                    <!-- File Download -->
                    @if($progressReport->file_path)
                        <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                            <h3 class="text-lg font-medium text-indigo-900 mb-2">Attached File</h3>
                            <a href="{{ Storage::url($progressReport->file_path) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Progress Report
                            </a>
                        </div>
                    @endif

                    <!-- Approval Form -->
                    <div class="mt-8 p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">HOD Decision</h3>
                        <form method="POST" action="{{ route('hod.progress_reports.approve.store', $progressReport) }}">
                            @csrf
                            @method('POST')

                            <!-- Action Selection -->
                            <div class="mb-4">
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Decision</label>
                                <select id="action" name="action" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Decision</option>
                                    <option value="approve" {{ old('action') == 'approve' ? 'selected' : '' }}>Approve</option>
                                    <option value="reject" {{ old('action') == 'reject' ? 'selected' : '' }}>Reject</option>
                                </select>
                                @error('action')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="mb-6">
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                                <textarea id="remarks" name="remarks" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your remarks..." required>{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('hod.progress_reports.pending') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit Decision
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
