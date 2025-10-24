<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Review Thesis Submission - DA Approval') }}
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
                            <p><strong>Supervisor:</strong> {{ $thesis->supervisor->user->name }}</p>
                            <p><strong>HOD:</strong> {{ $thesis->hodApprover->name ?? 'N/A' }}</p>
                            <p><strong>Submission Date:</strong> {{ $thesis->submission_date->format('M d, Y') }}</p>
                            <p class="mt-2">
                                <strong>Thesis File:</strong>
                                <a href="{{ Storage::url($thesis->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Download Thesis
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($thesis->supervisor_remarks)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Supervisor Remarks</h4>
                            <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $thesis->supervisor_remarks }}</p>
                            </div>
                        </div>
                    @endif

                    @if($thesis->hod_remarks)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">HOD Remarks</h4>
                            <div class="bg-green-50 dark:bg-green-900 p-3 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $thesis->hod_remarks }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('da.thesis.process', $thesis) }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="action" :value="__('Remark')" />
                            <select id="action" name="action" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select Remark</option>
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
                            <a href="{{ route('da.thesis.pending') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
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
