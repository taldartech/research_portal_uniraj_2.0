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
                            <x-select-input id="thesis_submission_id" name="thesis_submission_id" class="block mt-1 w-full" required>
                                <option value="">Select a Thesis Submission</option>
                                {{-- Dynamically load thesis submissions here --}}
                                {{-- Example: @foreach($thesisSubmissions as $submission) --}}
                                {{--             <option value="{{ $submission->id }}">Scholar: {{ $submission->scholar->user->name }} - Title: {{ $submission->title }}</option> --}}
                                {{--         @endforeach --}}
                            </x-select-input>
                            <x-input-error :messages="$errors->get('thesis_submission_id')" class="mt-2" />
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Suggested Experts (Minimum 8)</h3>
                        <div id="experts-container">
                            <!-- Expert fields will be added here dynamically or initially -->
                            @for ($i = 0; $i < 8; $i++)
                                <div class="expert-entry mb-4 p-4 border rounded-md">
                                    <h4 class="font-semibold text-gray-700 mb-2">Expert {{ $i + 1 }}</h4>
                                    <div class="mb-3">
                                        <x-input-label for="expert_name_{{ $i }}" :value="__('Name')" />
                                        <x-text-input id="expert_name_{{ $i }}" class="block mt-1 w-full" type="text" name="expert_suggestions[{{ $i }}][name]" :value="old('expert_suggestions.{{ $i }}.name')" required />
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.name')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input-label for="expert_affiliation_{{ $i }}" :value="__('Affiliation')" />
                                        <x-text-input id="expert_affiliation_{{ $i }}" class="block mt-1 w-full" type="text" name="expert_suggestions[{{ $i }}][affiliation]" :value="old('expert_suggestions.{{ $i }}.affiliation')" required />
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.affiliation')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input-label for="expert_email_{{ $i }}" :value="__('Email')" />
                                        <x-text-input id="expert_email_{{ $i }}" class="block mt-1 w-full" type="email" name="expert_suggestions[{{ $i }}][email]" :value="old('expert_suggestions.{{ $i }}.email')" required />
                                        <x-input-error :messages="$errors->get('expert_suggestions.' . $i . '.email')" class="mt-2" />
                                    </div>
                                </div>
                            @endfor
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
</x-app-layout>
