<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Viva Examination Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header with Status -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Viva Examination Details</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage and track viva examination progress</p>
                        </div>
                        <div class="flex space-x-2">
                            @if($vivaExamination->status === 'scheduled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Scheduled
                                </span>
                            @elseif($vivaExamination->status === 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Completed
                                </span>
                            @elseif($vivaExamination->status === 'cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Cancelled
                                </span>
                            @elseif($vivaExamination->status === 'rescheduled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Rescheduled
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Scholar and Thesis Information -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Scholar & Thesis Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Scholar Name</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Scholar Email</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->scholar->user->email }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thesis Title</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->thesisSubmission->title }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supervisor</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->supervisor->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->scholar->admission->department->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Examination Details -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Examination Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Examination Type</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->getExaminationTypeText() }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Examination Date</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->examination_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Examination Time</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->examination_time }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Venue</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->venue }}</p>
                            </div>
                            @if($vivaExamination->examination_notes)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Examination Notes</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->examination_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Examiners -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Examiners</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">External Examiner</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vivaExamination->externalExaminer->name ?? 'Not assigned' }}
                                    @if($vivaExamination->externalExaminer)
                                        <br><span class="text-xs text-gray-500">{{ $vivaExamination->externalExaminer->email }}</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Internal Examiner</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vivaExamination->internalExaminer->name ?? 'Not assigned' }}
                                    @if($vivaExamination->internalExaminer)
                                        <br><span class="text-xs text-gray-500">{{ $vivaExamination->internalExaminer->email }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Results and Comments -->
                    @if($vivaExamination->status === 'completed')
                        <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Examination Results</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Result</label>
                                    <p class="mt-1 text-sm">
                                        @if($vivaExamination->result === 'pass')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Pass
                                            </span>
                                        @elseif($vivaExamination->result === 'fail')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Fail
                                            </span>
                                        @elseif($vivaExamination->result === 'conditional_pass')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Conditional Pass
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                Pending
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Completed At</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->completed_at->format('M d, Y H:i') }}</p>
                                </div>
                                @if($vivaExamination->examiner_comments)
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Examiner Comments</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->examiner_comments }}</p>
                                    </div>
                                @endif
                                @if($vivaExamination->supervisor_comments)
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supervisor Comments</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->supervisor_comments }}</p>
                                    </div>
                                @endif
                                @if($vivaExamination->additional_remarks)
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Remarks</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vivaExamination->additional_remarks }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Viva Report and Office Note -->
                    @if($vivaExamination->vivaReport && $vivaExamination->vivaReport->isCompleted())
                        <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Generated Documents</h4>
                            <div class="flex space-x-4">
                                <a href="{{ route('hod.viva.report.download', $vivaExamination->vivaReport) }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Viva Report
                                </a>

                                @if($vivaExamination->office_note_generated)
                                    <a href="{{ route('hod.viva.office_note.download', $vivaExamination) }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Office Note
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Status Update Form -->
                    @if($vivaExamination->status !== 'cancelled')
                        <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Update Status</h4>
                            <form method="POST" action="{{ route('hod.viva.update_status', $vivaExamination) }}">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="status" :value="__('Status')" />
                                        <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="scheduled" {{ $vivaExamination->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="completed" {{ $vivaExamination->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $vivaExamination->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="rescheduled" {{ $vivaExamination->status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="result" :value="__('Result (if completed)')" />
                                        <select id="result" name="result" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Select Result</option>
                                            <option value="pass" {{ $vivaExamination->result === 'pass' ? 'selected' : '' }}>Pass</option>
                                            <option value="fail" {{ $vivaExamination->result === 'fail' ? 'selected' : '' }}>Fail</option>
                                            <option value="conditional_pass" {{ $vivaExamination->result === 'conditional_pass' ? 'selected' : '' }}>Conditional Pass</option>
                                            <option value="pending" {{ $vivaExamination->result === 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label for="examiner_comments" :value="__('Examiner Comments')" />
                                        <textarea id="examiner_comments" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="examiner_comments" rows="3">{{ old('examiner_comments', $vivaExamination->examiner_comments) }}</textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label for="supervisor_comments" :value="__('Supervisor Comments')" />
                                        <textarea id="supervisor_comments" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="supervisor_comments" rows="3">{{ old('supervisor_comments', $vivaExamination->supervisor_comments) }}</textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label for="additional_remarks" :value="__('Additional Remarks')" />
                                        <textarea id="additional_remarks" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="additional_remarks" rows="3">{{ old('additional_remarks', $vivaExamination->additional_remarks) }}</textarea>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <x-primary-button type="submit">
                                        {{ __('Update Status') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('hod.viva.examinations') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                            ← Back to Viva Examinations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
