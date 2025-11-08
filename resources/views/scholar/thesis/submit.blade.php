<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thesis Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Submit Your Thesis</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Please upload your thesis file and supporting documents. Fields marked with * are required.
                        </p>
                    </div>

                    <form action="{{ route('scholar.thesis.submit_new') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Thesis Details Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Thesis Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="title" :value="__('Thesis Title *')" />
                                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $synopsis->proposed_topic ?? $scholar->research_topic_title ?? '')" required />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="abstract" :value="__('Abstract *')" />
                                    <textarea id="abstract" name="abstract" rows="6" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('abstract', $scholar->research_scheme_outline ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('abstract')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="thesis_file" :value="__('Thesis File (PDF) *')" />
                                    <input id="thesis_file" type="file" name="thesis_file" accept=".pdf" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                                    <p class="mt-1 text-sm text-gray-500">Maximum file size: 10MB</p>
                                    <x-input-error :messages="$errors->get('thesis_file')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Supporting Documents Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Supporting Documents</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Please upload the following supporting documents:
                            </p>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="noc_document" :value="__('NOC (No Objection Certificate)')" />
                                    <input id="noc_document" type="file" name="supporting_documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum 5MB</p>
                                </div>
                                <div>
                                    <x-input-label for="pre_phd_viva_certificate" :value="__('Pre-PhD Viva Certificate')" />
                                    <input id="pre_phd_viva_certificate" type="file" name="supporting_documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum 5MB</p>
                                </div>
                                <div>
                                    <x-input-label for="fees_receipt" :value="__('Fees Receipt')" />
                                    <input id="fees_receipt" type="file" name="supporting_documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum 5MB</p>
                                </div>
                                <div>
                                    <x-input-label for="other_documents" :value="__('Other Documents')" />
                                    <input id="other_documents" type="file" name="supporting_documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-xs text-gray-500">You can upload multiple files. PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum 5MB per file</p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('supporting_documents')" class="mt-2" />
                            <x-input-error :messages="$errors->get('supporting_documents.*')" class="mt-2" />
                        </div>

                        <!-- Declaration Section -->
                        <div class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg border border-yellow-200 dark:border-yellow-700">
                            <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-4">Declaration</h3>
                            <div class="flex items-start">
                                <input id="declaration" type="checkbox" name="declaration" value="1" class="mt-1 mr-3 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" required>
                                <label for="declaration" class="text-sm text-yellow-700 dark:text-yellow-300">
                                    I hereby declare that the information provided above is true and correct to the best of my knowledge.
                                    I understand that any false information may result in the rejection of my thesis submission.
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('declaration')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('scholar.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Submit Thesis') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
