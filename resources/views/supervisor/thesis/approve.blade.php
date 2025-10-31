<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Review Thesis Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Thesis Details</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p><strong>Scholar:</strong> {{ $thesis->scholar->user->name }}</p>
                            <p><strong>Title:</strong> {{ $thesis->title }}</p>
                            @if($thesis->is_resubmission)
                                <p class="mt-2"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Resubmission #{{ $thesis->rejection_count + 1 }}</span></p>
                            @endif
                            <p><strong>Abstract:</strong></p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $thesis->abstract }}</p>
                            <p class="mt-2"><strong>Submission Date:</strong> {{ $thesis->submission_date->format('M d, Y') }}</p>
                            <p class="mt-2">
                                <strong>Thesis File:</strong>
                                <a href="{{ Storage::url($thesis->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Download Thesis
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($thesis->is_resubmission && $thesis->originalThesis)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Previous Rejection Details</h4>
                            <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                                <p><strong>Rejected by:</strong> {{ $thesis->originalThesis->getRejectionStage() }}</p>
                                <p><strong>Rejection Date:</strong> {{ $thesis->originalThesis->rejected_at ? $thesis->originalThesis->rejected_at->format('M d, Y') : 'N/A' }}</p>
                                <p><strong>Previous Rejection Reason:</strong></p>
                                <p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $thesis->originalThesis->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staff.thesis.approve.store', $thesis) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="action" :value="__('Remark')" />
                            <select id="action" name="action" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select Remark</option>
                                <option value="approve">Satisfied</option>
                                <option value="reject">Unsatisfied</option>
                            </select>
                            <x-input-error :messages="$errors->get('action')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="remarks" :value="__('Remarks')" />
                            <textarea id="remarks" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="remarks" rows="4" required>{{ old('remarks') }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="rac_minutes_file" :value="__('RAC Minutes File')" />
                            <span class="text-red-500">*</span>
                            <input type="file" id="rac_minutes_file" name="rac_minutes_file" accept=".pdf,.doc,.docx" class="block mt-1 w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300" required>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Accepted formats: PDF, DOC, DOCX (Max size: 5MB)</p>
                            <x-input-error :messages="$errors->get('rac_minutes_file')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="rac_meeting_date" :value="__('RAC Meeting Date')" />
                            <span class="text-red-500">*</span>
                            <input type="date" id="rac_meeting_date" name="rac_meeting_date" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            <x-input-error :messages="$errors->get('rac_meeting_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('staff.thesis.pending') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('Submit Remark') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
