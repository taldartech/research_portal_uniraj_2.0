<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Submit Viva Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Ph.D. Viva-Voce Report</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Please fill in all required information about the viva examination.</p>
                    </div>

                    <form method="POST" action="{{ route('staff.viva.report.store', $vivaExamination) }}">
                        @csrf

                        <!-- Scholar Information -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Scholar Information</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="scholar_name" :value="__('Scholar Name')" />
                                    <x-text-input id="scholar_name" class="block mt-1 w-full" type="text" value="{{ $vivaExamination->scholar->user->name }}" readonly />
                                </div>

                                <div>
                                    <x-input-label for="thesis_title" :value="__('Thesis Title')" />
                                    <x-text-input id="thesis_title" class="block mt-1 w-full" type="text" value="{{ $vivaExamination->thesisSubmission->title }}" readonly />
                                </div>
                            </div>
                        </div>

                        <!-- Viva Examination Details -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Viva Examination Details</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="research_topic" :value="__('Research Topic *')" />
                                    <x-text-input id="research_topic" class="block mt-1 w-full" type="text" name="research_topic" :value="old('research_topic', $vivaReport->research_topic ?? $vivaExamination->thesisSubmission->title)" required />
                                    <x-input-error :messages="$errors->get('research_topic')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="external_examiner_name" :value="__('External Examiner Name *')" />
                                    <x-text-input id="external_examiner_name" class="block mt-1 w-full" type="text" name="external_examiner_name" :value="old('external_examiner_name', $vivaReport->external_examiner_name ?? $vivaExamination->externalExaminer->name ?? '')" required />
                                    <x-input-error :messages="$errors->get('external_examiner_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="viva_date" :value="__('Viva Date *')" />
                                    <x-text-input id="viva_date" class="block mt-1 w-full" type="date" name="viva_date" :value="old('viva_date', $vivaReport->viva_date ?? $vivaExamination->examination_date->format('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('viva_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="viva_time" :value="__('Viva Time *')" />
                                    <x-text-input id="viva_time" class="block mt-1 w-full" type="time" name="viva_time" :value="old('viva_time', $vivaReport->viva_time ?? $vivaExamination->examination_time->format('H:i'))" required />
                                    <x-input-error :messages="$errors->get('viva_time')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="venue" :value="__('Venue *')" />
                                    <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue', $vivaReport->venue ?? $vivaExamination->venue ?? '')" required />
                                    <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="faculty_present" :value="__('Faculty Members Present')" />
                                    <textarea id="faculty_present" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="faculty_present" rows="3" placeholder="List the names of faculty members and research scholars present during the viva">{{ old('faculty_present', $vivaReport->faculty_present ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('faculty_present')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Viva Outcome -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Viva Outcome</h4>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="viva_successful" :value="__('Was the viva examination successful? *')" />
                                    <div class="mt-2 space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="viva_successful" value="1" {{ old('viva_successful', $vivaReport->viva_successful ?? '') == '1' ? 'checked' : '' }} required class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes, Successful</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="viva_successful" value="0" {{ old('viva_successful', $vivaReport->viva_successful ?? '') == '0' ? 'checked' : '' }} required class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No, Unsuccessful</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('viva_successful')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="viva_outcome_notes" :value="__('Viva Outcome Notes *')" />
                                    <textarea id="viva_outcome_notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="viva_outcome_notes" rows="4" required placeholder="Describe the outcome of the viva examination, including performance details and recommendations">{{ old('viva_outcome_notes', $vivaReport->viva_outcome_notes ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('viva_outcome_notes')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="additional_remarks" :value="__('Additional Remarks')" />
                                    <textarea id="additional_remarks" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="additional_remarks" rows="4" placeholder="Any additional remarks or observations about the viva examination">{{ old('additional_remarks', $vivaReport->additional_remarks ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('additional_remarks')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Important Notice -->
                        <div class="mb-8 p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                            <h4 class="text-md font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Important Notice</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                If the viva examination was successful, an Office Note will be automatically generated
                                for recommending the scholar for Ph.D. degree award to the Vice-Chancellor.
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('staff.viva.examinations') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('Submit Viva Report') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
