<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('HOD Certificate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($scholar->hod_certificate_completed)
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <strong>Certificate Completed!</strong> This certificate has been completed and signed.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('scholar.registration.hod_certificate.store') }}" class="space-y-8">
                        @csrf
                        @method('patch')

                        <!-- HOD Certificate Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Certificate of Head of the Department</h3>

                            <div class="space-y-6">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                        This is to certify that {{ $scholar->first_name }} {{ $scholar->last_name }} has been recommended for registration in Ph.D. through a DRC held on the date mentioned below. The candidate has been considered eligible on the basis of the following:
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="candidate_name" :value="__('Candidate Name')" />
                                        <x-text-input id="candidate_name" name="candidate_name" type="text" class="mt-1 block w-full"
                                            :value="old('candidate_name', $scholar->first_name . ' ' . $scholar->last_name)" readonly />
                                    </div>

                                    <div>
                                        <x-input-label for="candidate_relation" :value="__('S/o, D/o, W/o')" />
                                        <x-text-input id="candidate_relation" name="candidate_relation" type="text" class="mt-1 block w-full"
                                            :value="old('candidate_relation')" placeholder="Father's/Husband's name" required />
                                        <x-input-error :messages="$errors->get('candidate_relation')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="drc_date" :value="__('DRC Date')" />
                                    <x-text-input id="drc_date" name="drc_date" type="date" class="mt-1 block w-full"
                                        :value="old('drc_date')" required />
                                    <x-input-error :messages="$errors->get('drc_date')" class="mt-2" />
                                </div>

                                <!-- Eligibility Criteria -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Eligibility Criteria</h4>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="eligibility_criteria" value="net_slet_csir_gate" class="form-radio"
                                                    {{ old('eligibility_criteria') == 'net_slet_csir_gate' ? 'checked' : '' }}>
                                                <span class="ml-2">A. NET/SLET/CSIR/GATE</span>
                                            </label>

                                            <div id="net_slet_csir_gate_details" class="mt-2 ml-6 {{ old('eligibility_criteria') == 'net_slet_csir_gate' ? '' : 'hidden' }}">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="net_exam_type" :value="__('Exam Type')" />
                                                        <select id="net_exam_type" name="net_exam_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                            <option value="">Select Exam</option>
                                                            <option value="NET" {{ old('net_exam_type') == 'NET' ? 'selected' : '' }}>NET</option>
                                                            <option value="SLET" {{ old('net_exam_type') == 'SLET' ? 'selected' : '' }}>SLET</option>
                                                            <option value="CSIR" {{ old('net_exam_type') == 'CSIR' ? 'selected' : '' }}>CSIR</option>
                                                            <option value="GATE" {{ old('net_exam_type') == 'GATE' ? 'selected' : '' }}>GATE</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="net_exam_date" :value="__('Exam Date')" />
                                                        <x-text-input id="net_exam_date" name="net_exam_date" type="text" class="mt-1 block w-full"
                                                            :value="old('net_exam_date')" placeholder="e.g., June 2023" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="net_roll_number" :value="__('Roll Number')" />
                                                        <x-text-input id="net_roll_number" name="net_roll_number" type="text" class="mt-1 block w-full"
                                                            :value="old('net_roll_number')" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="eligibility_criteria" value="mpat" class="form-radio"
                                                    {{ old('eligibility_criteria') == 'mpat' ? 'checked' : '' }}>
                                                <span class="ml-2">B. MPAT</span>
                                            </label>

                                            <div id="mpat_details" class="mt-2 ml-6 {{ old('eligibility_criteria') == 'mpat' ? '' : 'hidden' }}">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="mpat_year" :value="__('MPAT Year')" />
                                                        <x-text-input id="mpat_year" name="mpat_year" type="text" class="mt-1 block w-full"
                                                            :value="old('mpat_year')" placeholder="e.g., 2023" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="mpat_roll_number" :value="__('Roll Number')" />
                                                        <x-text-input id="mpat_roll_number" name="mpat_roll_number" type="text" class="mt-1 block w-full"
                                                            :value="old('mpat_roll_number')" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="mpat_merit_number" :value="__('Merit Number')" />
                                                        <x-text-input id="mpat_merit_number" name="mpat_merit_number" type="text" class="mt-1 block w-full"
                                                            :value="old('mpat_merit_number')" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="mpat_subject" :value="__('Subject')" />
                                                        <x-text-input id="mpat_subject" name="mpat_subject" type="text" class="mt-1 block w-full"
                                                            :value="old('mpat_subject')" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="eligibility_criteria" value="percentage" class="form-radio"
                                                    {{ old('eligibility_criteria') == 'percentage' ? 'checked' : '' }}>
                                                <span class="ml-2">C. Post Graduate Percentage</span>
                                            </label>

                                            <div id="percentage_details" class="mt-2 ml-6 {{ old('eligibility_criteria') == 'percentage' ? '' : 'hidden' }}">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="pg_percentage" :value="__('Percentage')" />
                                                        <x-text-input id="pg_percentage" name="pg_percentage" type="number" step="0.01" class="mt-1 block w-full"
                                                            :value="old('pg_percentage')" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="candidate_type" :value="__('Candidate Type')" />
                                                        <select id="candidate_type" name="candidate_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                            <option value="">Select Type</option>
                                                            <option value="General" {{ old('candidate_type') == 'General' ? 'selected' : '' }}>General</option>
                                                            <option value="Teacher" {{ old('candidate_type') == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="eligibility_criteria" value="coursework" class="form-radio"
                                                    {{ old('eligibility_criteria') == 'coursework' ? 'checked' : '' }}>
                                                <span class="ml-2">D. Coursework Examination</span>
                                            </label>

                                            <div id="coursework_details" class="mt-2 ml-6 {{ old('eligibility_criteria') == 'coursework' ? '' : 'hidden' }}">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div>
                                                        <x-input-label for="coursework_exam_date" :value="__('Exam Date')" />
                                                        <x-text-input id="coursework_exam_date" name="coursework_exam_date" type="text" class="mt-1 block w-full"
                                                            :value="old('coursework_exam_date')" placeholder="e.g., June 2023" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="coursework_marks_obtained" :value="__('Marks Obtained')" />
                                                        <x-text-input id="coursework_marks_obtained" name="coursework_marks_obtained" type="text" class="mt-1 block w-full"
                                                            :value="old('coursework_marks_obtained')" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="coursework_max_marks" :value="__('Maximum Marks')" />
                                                        <x-text-input id="coursework_max_marks" name="coursework_max_marks" type="text" class="mt-1 block w-full"
                                                            :value="old('coursework_max_marks')" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="supervisor_retirement_date" :value="__('Supervisor Retirement Date')" />
                                    <x-text-input id="supervisor_retirement_date" name="supervisor_retirement_date" type="date" class="mt-1 block w-full"
                                        :value="old('supervisor_retirement_date')" required />
                                    <x-input-error :messages="$errors->get('supervisor_retirement_date')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="hod_date" :value="__('Date')" />
                                        <x-text-input id="hod_date" name="hod_date" type="date" class="mt-1 block w-full"
                                            :value="old('hod_date', now()->format('Y-m-d'))" required />
                                        <x-input-error :messages="$errors->get('hod_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="hod_signature" :value="__('Head of the Department Signature & Seal')" />
                                        <textarea id="hod_signature" name="hod_signature" rows="2"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            placeholder="Digital signature or typed name" required>{{ old('hod_signature') }}</textarea>
                                        <x-input-error :messages="$errors->get('hod_signature')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('scholar.registration.phd_form') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Registration Form
                            </a>

                            @if(!$scholar->hod_certificate_completed)
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

    <!-- JavaScript for dynamic form behavior -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eligibilityRadios = document.querySelectorAll('input[name="eligibility_criteria"]');
            const detailsDivs = {
                'net_slet_csir_gate': document.getElementById('net_slet_csir_gate_details'),
                'mpat': document.getElementById('mpat_details'),
                'percentage': document.getElementById('percentage_details'),
                'coursework': document.getElementById('coursework_details')
            };

            eligibilityRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hide all detail divs
                    Object.values(detailsDivs).forEach(div => {
                        div.classList.add('hidden');
                    });

                    // Show selected detail div
                    if (detailsDivs[this.value]) {
                        detailsDivs[this.value].classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>
