<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Preference') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($submittedPreferences && $submittedPreferences->count() > 0)
                        <h3 class="text-lg font-medium text-gray-900">Your Submitted Supervisor Preferences:</h3>
                        @foreach($submittedPreferences as $preference)
                            <div class="mb-4 p-4 border rounded-lg">
                                <p><strong>Preference {{ $preference->preference_order }}:</strong> {{ $preference->supervisor->user->name }}</p>
                                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $preference->status)) }}</p>
                            </div>
                            @if ($preference->remarks)
                                <p><strong>Remarks:</strong> {{ $preference->remarks }}</p>
                            @endif
                        @endforeach


                        <p class="mt-4">You have already submitted a supervisor preference. You cannot submit another one until this one is processed.</p>
                    @else
                        @if ($supervisors->count() > 0)
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-700">
                                    <strong>Note:</strong> You must rank all {{ $supervisors->count() }} supervisors in your department by preference (1st to {{ $supervisors->count() }}th).
                                </p>
                            </div>

                            <form method="POST" action="{{ route('scholar.supervisor.preference.store') }}" id="supervisorPreferenceForm">
                                @csrf

                                @php
                                    // Helper function to format ordinal numbers
                                    function ordinal($number) {
                                        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
                                        if ((($number % 100) >= 11) && (($number % 100) <= 13))
                                            return $number. 'th';
                                        return $number. $ends[$number % 10];
                                    }
                                @endphp

                                @foreach(range(1, $supervisors->count()) as $key => $preferenceOrder)
                                    <div class="mb-6 p-4 border rounded-lg">
                                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ ordinal($preferenceOrder) }} Preference</h4>
                                        <div class="mb-4">
                                            <x-input-label for="supervisor_{{ $preferenceOrder }}_id" :value="__('Supervisor')" />
                                            <select
                                                id="supervisor_{{ $preferenceOrder }}_id"
                                                name="supervisor_{{ $preferenceOrder }}_id"
                                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm supervisor-select"
                                                @if($key == 0) required @endif
                                                data-preference="{{ $preferenceOrder }}">
                                                <option value="">Select a Supervisor</option>
                                                @foreach($supervisors as $supervisor)
                                                    <option value="{{ $supervisor->id }}" {{ old("supervisor_{$preferenceOrder}_id") == $supervisor->id ? 'selected' : '' }}>
                                                        {{ $supervisor->user->name }} - {{ $supervisor->research_specialization }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get("supervisor_{$preferenceOrder}_id")" class="mt-2" />
                                        </div>
                                    </div>
                                @endforeach

                                @if ($errors->has('supervisor_selection'))
                                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <x-input-error :messages="$errors->get('supervisor_selection')" class="mt-2" />
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <x-input-label for="remarks" :value="__('Remark')" />
                                    <x-textarea-input id="remarks" name="remarks" class="block mt-1 w-full" rows="3">{{ old('remarks') }}</x-textarea-input>
                                    <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button>
                                        {{ __('Submit Preferences') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <script>
                                // Prevent selecting the same supervisor in multiple dropdowns
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Find all select elements by name pattern (more reliable)
                                    let selects = document.querySelectorAll('select[name^="supervisor_"]');

                                    // If not found, try with class
                                    if (selects.length === 0) {
                                        selects = document.querySelectorAll('select.supervisor-select');
                                    }

                                    // Ensure we have selects
                                    if (selects.length === 0) {
                                        console.error('Could not find supervisor select elements');
                                        return;
                                    }

                                    function updateDropdowns() {
                                        // Get all currently selected supervisor IDs (excluding empty selections)
                                        const selectedIds = [];
                                        selects.forEach(function(select) {
                                            if (select.value && select.value !== '') {
                                                selectedIds.push(select.value);
                                            }
                                        });

                                        // Update each dropdown
                                        selects.forEach(function(select) {
                                            const currentValue = select.value;
                                            const options = select.options;

                                            // Update each option
                                            for (let i = 0; i < options.length; i++) {
                                                const option = options[i];
                                                const optionValue = option.value;

                                                // Always show the "Select a Supervisor" option
                                                if (optionValue === '') {
                                                    option.style.display = '';
                                                    option.disabled = false;
                                                    continue;
                                                }

                                                // If this is the currently selected option in this dropdown, always show it
                                                if (optionValue === currentValue) {
                                                    option.style.display = '';
                                                    option.disabled = false;
                                                    continue;
                                                }

                                                // Hide and disable the option if it's selected in another dropdown
                                                if (selectedIds.includes(optionValue)) {
                                                    option.style.display = 'none';
                                                    option.disabled = true;
                                                } else {
                                                    option.style.display = '';
                                                    option.disabled = false;
                                                }
                                            }
                                        });
                                    }

                                    // Add change event listener to each select with duplicate prevention
                                    selects.forEach(function(select) {
                                        select.addEventListener('change', function(e) {
                                            const selectedValue = this.value;

                                            if (selectedValue && selectedValue !== '') {
                                                // Check if this value is already selected in another dropdown
                                                let isDuplicate = false;
                                                selects.forEach(function(otherSelect) {
                                                    if (otherSelect !== select && otherSelect.value === selectedValue) {
                                                        isDuplicate = true;
                                                    }
                                                });

                                                if (isDuplicate) {
                                                    // Reset to empty if duplicate
                                                    alert('This supervisor has already been selected in another preference. Please choose a different supervisor.');
                                                    this.value = '';
                                                    updateDropdowns();
                                                } else {
                                                    updateDropdowns();
                                                }
                                            } else {
                                                updateDropdowns();
                                            }
                                        });
                                    });

                                    // Initialize on page load (handles old input values from validation errors)
                                    updateDropdowns();

                                    // Prevent form submission if duplicates exist
                                    const form = document.getElementById('supervisorPreferenceForm');
                                    if (form) {
                                        form.addEventListener('submit', function(e) {
                                            const selectedValues = [];
                                            let hasDuplicate = false;

                                            selects.forEach(function(select) {
                                                if (select.value && select.value !== '') {
                                                    if (selectedValues.includes(select.value)) {
                                                        hasDuplicate = true;
                                                    } else {
                                                        selectedValues.push(select.value);
                                                    }
                                                }
                                            });

                                            if (hasDuplicate) {
                                                e.preventDefault();
                                                alert('Please ensure all supervisors are different. Duplicate selections are not allowed.');
                                                return false;
                                            }
                                        });
                                    }
                                });
                            </script>
                        @else
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-yellow-700">No supervisors are available in your department at this time. Please contact your department administrator.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
