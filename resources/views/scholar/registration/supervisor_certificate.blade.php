<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supervisor Certificate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($scholar->supervisor_certificate_completed)
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <strong>Certificate Completed!</strong> This certificate has been completed and signed.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('scholar.registration.supervisor_certificate.store') }}" class="space-y-8">
                        @csrf
                        @method('patch')

                        <!-- Supervisor Certificate Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Supervisor's Certificate</h3>

                            <div class="space-y-6">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                        This is to certify that the proposed topic has not been studied elsewhere and is of sufficient scope to engage the candidate for three years, leading to valuable contribution. I have seen and approved the outline and bibliography.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="candidate_name" :value="__('Candidate Name (Ms./Mr.)')" />
                                        <x-text-input id="candidate_name" name="candidate_name" type="text" class="mt-1 block w-full"
                                            :value="old('candidate_name', $scholar->first_name . ' ' . $scholar->last_name)" readonly />
                                    </div>

                                    <div>
                                        <x-input-label for="research_topic" :value="__('Research Topic')" />
                                        <textarea id="research_topic" name="research_topic" rows="3"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" readonly>{{ old('research_topic', $scholar->research_topic_title) }}</textarea>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="current_research_candidates" :value="__('Number of research candidates currently working under supervision')" />
                                        <x-text-input id="current_research_candidates" name="current_research_candidates" type="number" class="mt-1 block w-full"
                                            :value="old('current_research_candidates')" required />
                                        <x-input-error :messages="$errors->get('current_research_candidates')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="university_name" :value="__('University Name')" />
                                        <x-text-input id="university_name" name="university_name" type="text" class="mt-1 block w-full"
                                            value="University of Rajasthan" readonly />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="candidate_position" :value="__('This candidate will be the ___ candidate under me')" />
                                    <x-text-input id="candidate_position" name="candidate_position" type="text" class="mt-1 block w-full"
                                        :value="old('candidate_position')" placeholder="e.g., 3rd" required />
                                    <x-input-error :messages="$errors->get('candidate_position')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="relationship_confirmation" :value="__('Relationship Confirmation')" />
                                    <div class="mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="relationship_confirmation" value="1" class="form-checkbox" required>
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                I confirm that {{ $scholar->first_name }} {{ $scholar->last_name }} is not my relative.
                                            </span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('relationship_confirmation')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="retirement_date" :value="__('My retirement date')" />
                                        <x-text-input id="retirement_date" name="retirement_date" type="date" class="mt-1 block w-full"
                                            :value="old('retirement_date')" required />
                                        <x-input-error :messages="$errors->get('retirement_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="recognition_letter_number" :value="__('Recognition Letter Number (RS)')" />
                                        <x-text-input id="recognition_letter_number" name="recognition_letter_number" type="text" class="mt-1 block w-full"
                                            :value="old('recognition_letter_number')" required />
                                        <x-input-error :messages="$errors->get('recognition_letter_number')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="recognition_letter_date" :value="__('Recognition Letter Date')" />
                                    <x-text-input id="recognition_letter_date" name="recognition_letter_date" type="date" class="mt-1 block w-full"
                                        :value="old('recognition_letter_date')" required />
                                    <x-input-error :messages="$errors->get('recognition_letter_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="supervisor_signature" :value="__('Supervisor Signature')" />
                                    <textarea id="supervisor_signature" name="supervisor_signature" rows="2"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        placeholder="Digital signature or typed name" required>{{ old('supervisor_signature') }}</textarea>
                                    <x-input-error :messages="$errors->get('supervisor_signature')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('scholar.registration.phd_form') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Registration Form
                            </a>

                            @if(!$scholar->supervisor_certificate_completed)
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Complete Certificate
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
