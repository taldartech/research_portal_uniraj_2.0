<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resubmit Thesis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Rejection Details -->
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                        <h3 class="text-lg font-medium text-red-800 dark:text-red-200 mb-2">Previous Rejection Details</h3>
                        <p><strong>Rejected by:</strong> {{ $thesis->getRejectionStage() }}</p>
                        <p><strong>Rejection Date:</strong> {{ $thesis->rejected_at ? $thesis->rejected_at->format('M d, Y') : 'N/A' }}</p>
                        <p><strong>Rejection Count:</strong> {{ $thesis->rejection_count }}/3</p>
                        <p><strong>Reason:</strong></p>
                        <p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $thesis->rejection_reason }}</p>
                    </div>

                    <form method="POST" action="{{ route('scholar.thesis.resubmit.store', $thesis) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Thesis Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $thesis->title)" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                        </div>

                        <div class="mt-4">
                            <x-input-label for="thesis_file" :value="__('Revised Thesis File (PDF)')" />
                            <input id="thesis_file" class="block mt-1 w-full" type="file" name="thesis_file" required />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Please upload the revised thesis file addressing the rejection feedback.</p>
                            <x-input-error :messages="$errors->get('thesis_file')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="supporting_documents" :value="__('Supporting Documents (PDF/DOC/DOCX, optional)')" />
                            <input id="supporting_documents" class="block mt-1 w-full" type="file" name="supporting_documents[]" multiple />
                            <x-input-error :messages="$errors->get('supporting_documents')" class="mt-2" />
                        </div>

                        <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                            <h4 class="text-md font-medium text-yellow-800 dark:text-yellow-200 mb-2">Important Notes:</h4>
                            <ul class="text-sm text-yellow-700 dark:text-yellow-300 list-disc list-inside">
                                <li>This is resubmission #{{ $thesis->rejection_count + 1 }} of maximum 3 allowed</li>
                                <li>Please address all feedback from the previous rejection</li>
                                <li>Ensure all required changes are incorporated</li>
                                <li>The thesis will go through the complete approval workflow again</li>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('scholar.thesis.status') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('Resubmit Thesis') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
