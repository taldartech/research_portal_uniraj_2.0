<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Office Note for Registration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Scholar Information Summary -->
                    <div class="mb-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Scholar Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $officeNote->scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $officeNote->scholar->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Research Topic</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $officeNote->scholar->research_topic_title ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supervisor</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $officeNote->scholar->supervisor_name ?? 'Not assigned' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Office Note Edit Form -->
                    <form method="POST" action="{{ route('da.office_notes.update', $officeNote) }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <!-- File Information -->
                            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">File Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="file_number" :value="__('File Number')" />
                                        <x-text-input id="file_number" name="file_number" type="text" class="mt-1 block w-full"
                                                     value="{{ old('file_number', $officeNote->file_number) }}" required />
                                        <x-input-error :messages="$errors->get('file_number')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="dated" :value="__('Dated')" />
                                        <x-text-input id="dated" name="dated" type="date" class="mt-1 block w-full"
                                                     value="{{ old('dated', $officeNote->dated ? $officeNote->dated->format('Y-m-d') : '') }}" required />
                                        <x-input-error :messages="$errors->get('dated')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Supervisor Information -->
                            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Supervisor Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="supervisor_retirement_date" :value="__('Supervisor Retirement Date')" />
                                        <x-text-input id="supervisor_retirement_date" name="supervisor_retirement_date" type="date"
                                                     class="mt-1 block w-full" value="{{ old('supervisor_retirement_date', $officeNote->supervisor_retirement_date ? $officeNote->supervisor_retirement_date->format('Y-m-d') : '') }}" />
                                        <x-input-error :messages="$errors->get('supervisor_retirement_date')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="co_supervisor_retirement_date" :value="__('Co-Supervisor Retirement Date')" />
                                        <x-text-input id="co_supervisor_retirement_date" name="co_supervisor_retirement_date" type="date"
                                                     class="mt-1 block w-full" value="{{ old('co_supervisor_retirement_date', $officeNote->co_supervisor_retirement_date ? $officeNote->co_supervisor_retirement_date->format('Y-m-d') : '') }}" />
                                        <x-input-error :messages="$errors->get('co_supervisor_retirement_date')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Academic Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="drc_approval_date" :value="__('DRC Approval Date')" />
                                        <x-text-input id="drc_approval_date" name="drc_approval_date" type="date"
                                                     class="mt-1 block w-full" value="{{ old('drc_approval_date', $officeNote->drc_approval_date ? $officeNote->drc_approval_date->format('Y-m-d') : '') }}" />
                                        <x-input-error :messages="$errors->get('drc_approval_date')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="commencement_date" :value="__('Commencement Date')" />
                                        <x-text-input id="commencement_date" name="commencement_date" type="date"
                                                     class="mt-1 block w-full" value="{{ old('commencement_date', $officeNote->commencement_date ? $officeNote->commencement_date->format('Y-m-d') : '') }}" />
                                        <x-input-error :messages="$errors->get('commencement_date')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Registration Information -->
                            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Registration Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="registration_fee_receipt_number" :value="__('Registration Fee Receipt Number')" />
                                        <x-text-input id="registration_fee_receipt_number" name="registration_fee_receipt_number" type="text"
                                                     class="mt-1 block w-full" value="{{ old('registration_fee_receipt_number', $officeNote->registration_fee_receipt_number) }}" />
                                        <x-input-error :messages="$errors->get('registration_fee_receipt_number')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="registration_fee_date" :value="__('Registration Fee Date')" />
                                        <x-text-input id="registration_fee_date" name="registration_fee_date" type="date"
                                                     class="mt-1 block w-full" value="{{ old('registration_fee_date', $officeNote->registration_fee_date ? $officeNote->registration_fee_date->format('Y-m-d') : '') }}" />
                                        <x-input-error :messages="$errors->get('registration_fee_date')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="enrollment_number" :value="__('Enrollment Number')" />
                                        <x-text-input id="enrollment_number" name="enrollment_number" type="text"
                                                     class="mt-1 block w-full" value="{{ old('enrollment_number', $officeNote->enrollment_number) }}" />
                                        <x-input-error :messages="$errors->get('enrollment_number')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="supervisor_registration_page_number" :value="__('Supervisor Registration Page Number')" />
                                        <x-text-input id="supervisor_registration_page_number" name="supervisor_registration_page_number" type="text"
                                                     class="mt-1 block w-full" value="{{ old('supervisor_registration_page_number', $officeNote->supervisor_registration_page_number) }}" />
                                        <x-input-error :messages="$errors->get('supervisor_registration_page_number')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Supervisor Capacity Information -->
                            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Supervisor Capacity Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="supervisor_seats_available" :value="__('Supervisor Seats Available')" />
                                        <x-text-input id="supervisor_seats_available" name="supervisor_seats_available" type="number"
                                                     class="mt-1 block w-full" value="{{ old('supervisor_seats_available', $officeNote->supervisor_seats_available) }}" min="0" />
                                        <x-input-error :messages="$errors->get('supervisor_seats_available')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="candidates_under_guidance" :value="__('Candidates Under Guidance')" />
                                        <x-text-input id="candidates_under_guidance" name="candidates_under_guidance" type="number"
                                                     class="mt-1 block w-full" value="{{ old('candidates_under_guidance', $officeNote->candidates_under_guidance) }}" min="0" />
                                        <x-input-error :messages="$errors->get('candidates_under_guidance')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Additional Notes</h4>
                                <div>
                                    <x-input-label for="notes" :value="__('Notes')" />
                                    <textarea id="notes" name="notes" rows="3"
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $officeNote->notes) }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('da.office_notes.show', $officeNote) }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Office Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
