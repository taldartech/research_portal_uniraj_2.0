<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suggest Thesis Evaluation Experts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('staff.thesis_evaluation.experts.store') }}">
                        @csrf

                        <!-- Thesis Submission ID (assuming it's passed or selected) -->
                        <div class="mb-4">
                            <x-input-label for="thesis_submission_id" :value="__('Select Thesis Submission')" />
                            <select id="thesis_submission_id" name="thesis_submission_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a Thesis Submission</option>
                                @forelse($thesisSubmissions as $submission)
                                    @php
                                        $supervisorName = 'N/A';
                                        if ($submission->supervisor && $submission->supervisor->user) {
                                            $supervisorName = $submission->supervisor->user->name;
                                        } elseif ($submission->scholar->currentSupervisor && $submission->scholar->currentSupervisor->supervisor && $submission->scholar->currentSupervisor->supervisor->user) {
                                            $supervisorName = $submission->scholar->currentSupervisor->supervisor->user->name;
                                        }
                                    @endphp
                                    <option value="{{ $submission->id }}">
                                        Scholar: {{ $submission->scholar->user->name ?? 'N/A' }} - Title: {{ $submission->title ?? 'N/A' }} (Approved: {{ $submission->hvc_approved_at ? $submission->hvc_approved_at->format('M d, Y') : 'N/A' }})
                                    </option>
                                @empty
                                    <option value="" disabled>No approved thesis submissions available for expert suggestions</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('thesis_submission_id')" class="mt-2" />
                            @if($thesisSubmissions->isEmpty())
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    You don't have any approved thesis submissions yet. Expert suggestions can only be made for theses that have been approved by HVC.
                                </p>
                            @endif
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Suggested Experts (Minimum 8)</h3>
                        <div id="experts-container">
                            <!-- Expert fields will be added here dynamically or initially -->
                            @for ($i = 0; $i < 8; $i++)
                                <div class="expert-entry mb-4 p-4 border rounded-md">
                                    <h4 class="font-semibold text-gray-700 mb-2">Expert {{ $i + 1 }}</h4>
                                    <div class="mb-3">
                                        <x-input-label for="expert_name_{{ $i }}" :value="__('Name')" />
                                        <x-text-input id="expert_name_{{ $i }}" class="block mt-1 w-full" type="text" name="expert_suggestions[{{ $i }}][name]" :value="old('expert_suggestions.' . $i . '.name')" required />
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.name')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input-label for="expert_email_{{ $i }}" :value="__('Email')" />
                                        <x-text-input id="expert_email_{{ $i }}" class="block mt-1 w-full" type="email" name="expert_suggestions[{{ $i }}][email]" :value="old('expert_suggestions.' . $i . '.email')" required />
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.email')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input-label for="expert_mobile_no_{{ $i }}" :value="__('Mobile No')" />
                                        <x-text-input id="expert_mobile_no_{{ $i }}" class="block mt-1 w-full" type="text" name="expert_suggestions[{{ $i }}][mobile_no]" :value="old('expert_suggestions.' . $i . '.mobile_no')" required />
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.mobile_no')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input-label for="expert_address_{{ $i }}" :value="__('Address')" />
                                        <textarea id="expert_address_{{ $i }}" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="expert_suggestions[{{ $i }}][address]" rows="3" required>{{ old('expert_suggestions.' . $i . '.address') }}</textarea>
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.address')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input-label for="expert_state_{{ $i }}" :value="__('State')" />
                                        <select id="expert_state_{{ $i }}" name="expert_suggestions[{{ $i }}][state]" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                            <option value="">Select State</option>
                                            <option value="Andhra Pradesh" {{ old('expert_suggestions.' . $i . '.state') == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                                            <option value="Arunachal Pradesh" {{ old('expert_suggestions.' . $i . '.state') == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                                            <option value="Assam" {{ old('expert_suggestions.' . $i . '.state') == 'Assam' ? 'selected' : '' }}>Assam</option>
                                            <option value="Bihar" {{ old('expert_suggestions.' . $i . '.state') == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                                            <option value="Chhattisgarh" {{ old('expert_suggestions.' . $i . '.state') == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                                            <option value="Goa" {{ old('expert_suggestions.' . $i . '.state') == 'Goa' ? 'selected' : '' }}>Goa</option>
                                            <option value="Gujarat" {{ old('expert_suggestions.' . $i . '.state') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                                            <option value="Haryana" {{ old('expert_suggestions.' . $i . '.state') == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                                            <option value="Himachal Pradesh" {{ old('expert_suggestions.' . $i . '.state') == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                                            <option value="Jharkhand" {{ old('expert_suggestions.' . $i . '.state') == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                                            <option value="Karnataka" {{ old('expert_suggestions.' . $i . '.state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                                            <option value="Kerala" {{ old('expert_suggestions.' . $i . '.state') == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                                            <option value="Madhya Pradesh" {{ old('expert_suggestions.' . $i . '.state') == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                                            <option value="Maharashtra" {{ old('expert_suggestions.' . $i . '.state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                                            <option value="Manipur" {{ old('expert_suggestions.' . $i . '.state') == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                                            <option value="Meghalaya" {{ old('expert_suggestions.' . $i . '.state') == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                                            <option value="Mizoram" {{ old('expert_suggestions.' . $i . '.state') == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                                            <option value="Nagaland" {{ old('expert_suggestions.' . $i . '.state') == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                                            <option value="Odisha" {{ old('expert_suggestions.' . $i . '.state') == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                                            <option value="Punjab" {{ old('expert_suggestions.' . $i . '.state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                                            <option value="Rajasthan" {{ old('expert_suggestions.' . $i . '.state') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                                            <option value="Sikkim" {{ old('expert_suggestions.' . $i . '.state') == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                                            <option value="Tamil Nadu" {{ old('expert_suggestions.' . $i . '.state') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                                            <option value="Telangana" {{ old('expert_suggestions.' . $i . '.state') == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                                            <option value="Tripura" {{ old('expert_suggestions.' . $i . '.state') == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                                            <option value="Uttar Pradesh" {{ old('expert_suggestions.' . $i . '.state') == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                                            <option value="Uttarakhand" {{ old('expert_suggestions.' . $i . '.state') == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                                            <option value="West Bengal" {{ old('expert_suggestions.' . $i . '.state') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                                            <option value="Andaman and Nicobar Islands" {{ old('expert_suggestions.' . $i . '.state') == 'Andaman and Nicobar Islands' ? 'selected' : '' }}>Andaman and Nicobar Islands</option>
                                            <option value="Chandigarh" {{ old('expert_suggestions.' . $i . '.state') == 'Chandigarh' ? 'selected' : '' }}>Chandigarh</option>
                                            <option value="Dadra and Nagar Haveli and Daman and Diu" {{ old('expert_suggestions.' . $i . '.state') == 'Dadra and Nagar Haveli and Daman and Diu' ? 'selected' : '' }}>Dadra and Nagar Haveli and Daman and Diu</option>
                                            <option value="Delhi" {{ old('expert_suggestions.' . $i . '.state') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                                            <option value="Jammu and Kashmir" {{ old('expert_suggestions.' . $i . '.state') == 'Jammu and Kashmir' ? 'selected' : '' }}>Jammu and Kashmir</option>
                                            <option value="Ladakh" {{ old('expert_suggestions.' . $i . '.state') == 'Ladakh' ? 'selected' : '' }}>Ladakh</option>
                                            <option value="Lakshadweep" {{ old('expert_suggestions.' . $i . '.state') == 'Lakshadweep' ? 'selected' : '' }}>Lakshadweep</option>
                                            <option value="Puducherry" {{ old('expert_suggestions.' . $i . '.state') == 'Puducherry' ? 'selected' : '' }}>Puducherry</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.state')" class="mt-2" />
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Note: Maximum 2 experts can be selected from a single state</p>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div id="state-warning" class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md hidden">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200 font-medium">⚠️ State Limit Warning</p>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1" id="state-warning-text"></p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Submit Suggestions') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stateSelects = document.querySelectorAll('select[name*="[state]"]');
            const warningDiv = document.getElementById('state-warning');
            const warningText = document.getElementById('state-warning-text');

            function checkStateLimits() {
                const stateCounts = {};
                const stateSelectsArray = Array.from(stateSelects);
                
                stateSelectsArray.forEach(select => {
                    const state = select.value.trim();
                    if (state) {
                        stateCounts[state] = (stateCounts[state] || 0) + 1;
                    }
                });

                const exceededStates = Object.entries(stateCounts)
                    .filter(([state, count]) => count > 2)
                    .map(([state, count]) => `${state} (${count} experts)`);

                if (exceededStates.length > 0) {
                    warningDiv.classList.remove('hidden');
                    warningText.textContent = `Maximum 2 experts per state allowed. Exceeded: ${exceededStates.join(', ')}`;
                } else {
                    const warningStates = Object.entries(stateCounts)
                        .filter(([state, count]) => count === 2)
                        .map(([state]) => state);
                    
                    if (warningStates.length > 0) {
                        warningDiv.classList.remove('hidden');
                        warningText.textContent = `You have reached the limit (2 experts) for: ${warningStates.join(', ')}. Adding more experts from these states will cause an error.`;
                    } else {
                        warningDiv.classList.add('hidden');
                    }
                }
            }

            stateSelects.forEach(select => {
                select.addEventListener('change', checkStateLimits);
            });

            // Initial check
            checkStateLimits();
        });
    </script>
</x-app-layout>
