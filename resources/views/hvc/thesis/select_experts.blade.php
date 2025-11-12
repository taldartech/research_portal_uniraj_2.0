<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Select Experts for Thesis Evaluation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Thesis Details</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p><strong>Scholar:</strong> {{ $thesis->scholar->user->name ?? 'N/A' }}</p>
                            <p><strong>Title:</strong> {{ $thesis->title }}</p>
                            <p><strong>Supervisor:</strong>
                                @php
                                    $supervisorName = 'N/A';
                                    if ($thesis->supervisor && $thesis->supervisor->user) {
                                        $supervisorName = $thesis->supervisor->user->name;
                                    } elseif ($thesis->scholar->currentSupervisor && $thesis->scholar->currentSupervisor->supervisor && $thesis->scholar->currentSupervisor->supervisor->user) {
                                        $supervisorName = $thesis->scholar->currentSupervisor->supervisor->user->name;
                                    }
                                @endphp
                                {{ $supervisorName }}
                            </p>
                            <p><strong>Department:</strong> {{ $thesis->scholar->admission->department->name ?? 'N/A' }}</p>
                            <p><strong>Submission Date:</strong> {{ $thesis->submission_date ? $thesis->submission_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($suggestedExperts->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-600 dark:text-gray-400">No experts have been suggested by the supervisor yet.</p>
                            <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">The supervisor needs to suggest experts before you can select them for evaluation.</p>
                        </div>
                    @elseif($suggestedExperts->count() < 4)
                        <div class="text-center py-12">
                            <p class="text-gray-600 dark:text-gray-400">The supervisor has only suggested {{ $suggestedExperts->count() }} expert(s).</p>
                            <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">At least 4 experts must be suggested before selection.</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('hvc.thesis.select_experts.store', $thesis) }}">
                            @csrf

                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Select 4 Experts from Supervisor Suggestions (Priority 1-4)</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select exactly 4 experts from the {{ $suggestedExperts->count() }} suggested experts and assign priority order (1 = highest priority, 4 = lowest priority)</p>
                                
                                <div class="space-y-4">
                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label for="expert_{{ $i }}" :value="__('Expert ' . ($i + 1))" />
                                                    <select id="expert_{{ $i }}" name="experts[{{ $i }}][expert_suggestion_id]" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                        <option value="">Select Expert</option>
                                                        @foreach($suggestedExperts as $expert)
                                                            <option value="{{ $expert->id }}" {{ old('experts.' . $i . '.expert_suggestion_id') == $expert->id ? 'selected' : '' }}>
                                                                {{ $expert->name }} ({{ $expert->email }}) - {{ $expert->state ?? 'N/A' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('experts.' . $i . '.expert_suggestion_id')" class="mt-2" />
                                                </div>
                                                <div>
                                                    <x-input-label for="priority_{{ $i }}" :value="__('Priority')" />
                                                    <select id="priority_{{ $i }}" name="experts[{{ $i }}][priority]" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                        <option value="">Select Priority</option>
                                                        <option value="1" {{ old('experts.' . $i . '.priority') == '1' ? 'selected' : '' }}>1 (Highest Priority)</option>
                                                        <option value="2" {{ old('experts.' . $i . '.priority') == '2' ? 'selected' : '' }}>2</option>
                                                        <option value="3" {{ old('experts.' . $i . '.priority') == '3' ? 'selected' : '' }}>3</option>
                                                        <option value="4" {{ old('experts.' . $i . '.priority') == '4' ? 'selected' : '' }}>4 (Lowest Priority)</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('experts.' . $i . '.priority')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                @error('experts')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('hvc.thesis.approved') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    {{ __('Select Experts') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const expertSelects = document.querySelectorAll('select[name*="[expert_suggestion_id]"]');
            const prioritySelects = document.querySelectorAll('select[name*="[priority]"]');

            // Prevent duplicate expert selection
            expertSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const selectedValue = this.value;
                    if (selectedValue) {
                        expertSelects.forEach(otherSelect => {
                            if (otherSelect !== this && otherSelect.value === selectedValue) {
                                alert('This expert has already been selected. Please choose a different expert.');
                                this.value = '';
                                return;
                            }
                        });
                    }
                });
            });

            // Prevent duplicate priority selection
            prioritySelects.forEach(select => {
                select.addEventListener('change', function() {
                    const selectedPriority = this.value;
                    if (selectedPriority) {
                        prioritySelects.forEach(otherSelect => {
                            if (otherSelect !== this && otherSelect.value === selectedPriority) {
                                alert('This priority has already been assigned. Please choose a different priority.');
                                this.value = '';
                                return;
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>

