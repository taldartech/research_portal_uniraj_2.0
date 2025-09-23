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

                    @if ($submittedPreference)
                        <h3 class="text-lg font-medium text-gray-900">Your Submitted Supervisor Preference:</h3>
                        <p><strong>Preferred Supervisor:</strong> {{ $submittedPreference->supervisor->user->name }}</p>
                        <p><strong>Justification:</strong> {{ $submittedPreference->justification }}</p>
                        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $submittedPreference->status)) }}</p>
                        @if ($submittedPreference->remarks)
                            <p><strong>Remarks:</strong> {{ $submittedPreference->remarks }}</p>
                        @endif

                        <!-- Office Note Download -->
                        @if($submittedPreference->office_note_generated)
                            {{-- <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h4 class="text-md font-semibold mb-2 text-gray-900 dark:text-gray-100">Office Note</h4>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            Generated: {{ $submittedPreference->office_note_generated_at->format('M d, Y H:i') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('scholar.supervisor_assignment.office_note.download', $submittedPreference) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Office Note
                                    </a>
                                </div>
                            </div> --}}
                        @endif

                        <p class="mt-4">You have already submitted a supervisor preference. You cannot submit another one until this one is processed.</p>
                    @else
                        <form method="POST" action="{{ route('scholar.supervisor.preference.store') }}">
                            @csrf

                            <!-- Supervisor Selection -->
                            <div class="mb-4">
                                <x-input-label for="preferred_supervisor_id" :value="__('Preferred Supervisor')" />
                                <x-select-input id="preferred_supervisor_id" name="preferred_supervisor_id" class="block mt-1 w-full" required>
                                    <option value="">Select a Supervisor</option>
                                    @foreach($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}">{{ $supervisor->user->name }} - {{ $supervisor->research_specialization }}</option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('preferred_supervisor_id')" class="mt-2" />
                            </div>

                            <!-- Justification -->
                            <div class="mb-4">
                                <x-input-label for="justification" :value="__('Justification')" />
                                <x-textarea-input id="justification" name="justification" class="block mt-1 w-full" rows="5" required>{{ old('justification') }}</x-textarea-input>
                                <x-input-error :messages="$errors->get('justification')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Submit Preference') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
