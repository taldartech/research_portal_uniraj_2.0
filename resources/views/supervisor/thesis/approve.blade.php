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

                    <form method="POST" action="{{ route('staff.thesis.approve.store', $thesis) }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="action" :value="__('Decision')" />
                            <select id="action" name="action" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select Decision</option>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                            </select>
                            <x-input-error :messages="$errors->get('action')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="remarks" :value="__('Remarks')" />
                            <textarea id="remarks" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="remarks" rows="4" required>{{ old('remarks') }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('staff.thesis.pending') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('Submit Decision') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
