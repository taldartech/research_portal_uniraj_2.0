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
                            Please fill out the form below to submit your thesis for review.
                        </p>
                    </div>

                    <form action="{{ route('scholar.thesis.submit_new') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Personal Details Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Personal Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="father_husband_name" :value="__('Father/Husband Name *')" />
                                    <x-text-input id="father_husband_name" class="block mt-1 w-full" type="text" name="father_husband_name" :value="old('father_husband_name')" required />
                                    <x-input-error :messages="$errors->get('father_husband_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="mother_name" :value="__('Mother Name *')" />
                                    <x-text-input id="mother_name" class="block mt-1 w-full" type="text" name="mother_name" :value="old('mother_name')" required />
                                    <x-input-error :messages="$errors->get('mother_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="subject" :value="__('Subject *')" />
                                    <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" required />
                                    <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="faculty" :value="__('Faculty *')" />
                                    <x-text-input id="faculty" class="block mt-1 w-full" type="text" name="faculty" :value="old('faculty')" required />
                                    <x-input-error :messages="$errors->get('faculty')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Academic Progress Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Academic Progress</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="mpat_passing_date" :value="__('MPAT Passing Date')" />
                                    <x-text-input id="mpat_passing_date" class="block mt-1 w-full" type="date" name="mpat_passing_date" :value="old('mpat_passing_date')" />
                                    <x-input-error :messages="$errors->get('mpat_passing_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="coursework_session" :value="__('Coursework Session')" />
                                    <x-text-input id="coursework_session" class="block mt-1 w-full" type="text" name="coursework_session" :value="old('coursework_session')" />
                                    <x-input-error :messages="$errors->get('coursework_session')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="coursework_fee_receipt_no" :value="__('Coursework Fee Receipt No.')" />
                                    <x-text-input id="coursework_fee_receipt_no" class="block mt-1 w-full" type="text" name="coursework_fee_receipt_no" :value="old('coursework_fee_receipt_no')" />
                                    <x-input-error :messages="$errors->get('coursework_fee_receipt_no')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="coursework_fee_receipt_date" :value="__('Coursework Fee Receipt Date')" />
                                    <x-text-input id="coursework_fee_receipt_date" class="block mt-1 w-full" type="date" name="coursework_fee_receipt_date" :value="old('coursework_fee_receipt_date')" />
                                    <x-input-error :messages="$errors->get('coursework_fee_receipt_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="coursework_passing_date" :value="__('Coursework Passing Date')" />
                                    <x-text-input id="coursework_passing_date" class="block mt-1 w-full" type="date" name="coursework_passing_date" :value="old('coursework_passing_date')" />
                                    <x-input-error :messages="$errors->get('coursework_passing_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="registration_fee_date" :value="__('Registration Fee Date')" />
                                    <x-text-input id="registration_fee_date" class="block mt-1 w-full" type="date" name="registration_fee_date" :value="old('registration_fee_date')" />
                                    <x-input-error :messages="$errors->get('registration_fee_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="extension_date" :value="__('Extension Date')" />
                                    <x-text-input id="extension_date" class="block mt-1 w-full" type="date" name="extension_date" :value="old('extension_date')" />
                                    <x-input-error :messages="$errors->get('extension_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="re_registration_date" :value="__('Re-registration Date')" />
                                    <x-text-input id="re_registration_date" class="block mt-1 w-full" type="date" name="re_registration_date" :value="old('re_registration_date')" />
                                    <x-input-error :messages="$errors->get('re_registration_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pre_phd_presentation_date" :value="__('Pre-PhD Presentation Date *')" />
                                    <x-text-input id="pre_phd_presentation_date" class="block mt-1 w-full" type="date" name="pre_phd_presentation_date" :value="old('pre_phd_presentation_date')" required />
                                    <x-input-error :messages="$errors->get('pre_phd_presentation_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pre_phd_presentation_certificate" :value="__('Pre-PhD Presentation Certificate')" />
                                    <input id="pre_phd_presentation_certificate" type="file" name="pre_phd_presentation_certificate" accept=".pdf,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-sm text-gray-500">PDF, JPG, PNG files only. Maximum 2MB.</p>
                                    <x-input-error :messages="$errors->get('pre_phd_presentation_certificate')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Research Output Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Research Output</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="published_research_paper_details" :value="__('Published Research Paper Details *')" />
                                    <textarea id="published_research_paper_details" name="published_research_paper_details" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('published_research_paper_details') }}</textarea>
                                    <x-input-error :messages="$errors->get('published_research_paper_details')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="published_research_paper_certificate" :value="__('Published Research Paper Certificate')" />
                                    <input id="published_research_paper_certificate" type="file" name="published_research_paper_certificate" accept=".pdf,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-sm text-gray-500">PDF, JPG, PNG files only. Maximum 2MB.</p>
                                    <x-input-error :messages="$errors->get('published_research_paper_certificate')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="conference_presentation_1" :value="__('Conference Presentation 1 *')" />
                                    <textarea id="conference_presentation_1" name="conference_presentation_1" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('conference_presentation_1') }}</textarea>
                                    <x-input-error :messages="$errors->get('conference_presentation_1')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="conference_certificate_1" :value="__('Conference Certificate 1')" />
                                    <input id="conference_certificate_1" type="file" name="conference_certificate_1" accept=".pdf,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-sm text-gray-500">PDF, JPG, PNG files only. Maximum 2MB.</p>
                                    <x-input-error :messages="$errors->get('conference_certificate_1')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="conference_presentation_2" :value="__('Conference Presentation 2 *')" />
                                    <textarea id="conference_presentation_2" name="conference_presentation_2" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('conference_presentation_2') }}</textarea>
                                    <x-input-error :messages="$errors->get('conference_presentation_2')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="conference_certificate_2" :value="__('Conference Certificate 2')" />
                                    <input id="conference_certificate_2" type="file" name="conference_certificate_2" accept=".pdf,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-sm text-gray-500">PDF, JPG, PNG files only. Maximum 2MB.</p>
                                    <x-input-error :messages="$errors->get('conference_certificate_2')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- RAC/DRC Details Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">RAC/DRC Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="rac_constitution_date" :value="__('RAC Constitution Date')" />
                                    <x-text-input id="rac_constitution_date" class="block mt-1 w-full" type="date" name="rac_constitution_date" :value="old('rac_constitution_date')" />
                                    <x-input-error :messages="$errors->get('rac_constitution_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="drc_approval_date" :value="__('DRC Approval Date')" />
                                    <x-text-input id="drc_approval_date" class="block mt-1 w-full" type="date" name="drc_approval_date" :value="old('drc_approval_date')" />
                                    <x-input-error :messages="$errors->get('drc_approval_date')" class="mt-2" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="rac_drc_undertaking" :value="__('RAC/DRC Undertaking')" />
                                    <textarea id="rac_drc_undertaking" name="rac_drc_undertaking" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('rac_drc_undertaking') }}</textarea>
                                    <x-input-error :messages="$errors->get('rac_drc_undertaking')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Thesis Details Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Thesis Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="title" :value="__('Thesis Title *')" />
                                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="abstract" :value="__('Abstract *')" />
                                    <textarea id="abstract" name="abstract" rows="6" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('abstract') }}</textarea>
                                    <x-input-error :messages="$errors->get('abstract')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="thesis_file" :value="__('Thesis File (PDF) *')" />
                                    <input id="thesis_file" type="file" name="thesis_file" accept=".pdf" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                                    <p class="mt-1 text-sm text-gray-500">Maximum file size: 10MB</p>
                                    <x-input-error :messages="$errors->get('thesis_file')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="supporting_documents" :value="__('Supporting Documents (Optional)')" />
                                    <input id="supporting_documents" type="file" name="supporting_documents[]" multiple accept=".pdf,.doc,.docx" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-sm text-gray-500">PDF, DOC, DOCX files only. Maximum 2MB per file.</p>
                                    <x-input-error :messages="$errors->get('supporting_documents')" class="mt-2" />
                                </div>
                            </div>
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
