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
                                <p><strong>Justification:</strong> {{ $preference->justification }}</p>
                                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $preference->status)) }}</p>
                                @if ($preference->remarks)
                                    <p><strong>Remarks:</strong> {{ $preference->remarks }}</p>
                                @endif
                            </div>
                        @endforeach


                        <p class="mt-4">You have already submitted a supervisor preference. You cannot submit another one until this one is processed.</p>
                    @else
                        <form method="POST" action="{{ route('scholar.supervisor.preference.store') }}">
                            @csrf

                            <!-- Supervisor Preference 1 -->
                            <div class="mb-6 p-4 border rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">1st Preference</h4>
                                <div class="mb-4">
                                    <x-input-label for="supervisor_1_id" :value="__('Supervisor')" />
                                    <x-select-input id="supervisor_1_id" name="supervisor_1_id" class="block mt-1 w-full" required>
                                        <option value="">Select a Supervisor</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}">{{ $supervisor->user->name }} - {{ $supervisor->research_specialization }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('supervisor_1_id')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="justification_1" :value="__('Justification')" />
                                    <x-textarea-input id="justification_1" name="justification_1" class="block mt-1 w-full" rows="3" required>{{ old('justification_1') }}</x-textarea-input>
                                    <x-input-error :messages="$errors->get('justification_1')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Supervisor Preference 2 -->
                            <div class="mb-6 p-4 border rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">2nd Preference</h4>
                                <div class="mb-4">
                                    <x-input-label for="supervisor_2_id" :value="__('Supervisor')" />
                                    <x-select-input id="supervisor_2_id" name="supervisor_2_id" class="block mt-1 w-full">
                                        <option value="">Select a Supervisor</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}">{{ $supervisor->user->name }} - {{ $supervisor->research_specialization }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('supervisor_2_id')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="justification_2" :value="__('Justification')" />
                                    <x-textarea-input id="justification_2" name="justification_2" class="block mt-1 w-full" rows="3">{{ old('justification_2') }}</x-textarea-input>
                                    <x-input-error :messages="$errors->get('justification_2')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Supervisor Preference 3 -->
                            <div class="mb-6 p-4 border rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">3rd Preference</h4>
                                <div class="mb-4">
                                    <x-input-label for="supervisor_3_id" :value="__('Supervisor')" />
                                    <x-select-input id="supervisor_3_id" name="supervisor_3_id" class="block mt-1 w-full">
                                        <option value="">Select a Supervisor</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}">{{ $supervisor->user->name }} - {{ $supervisor->research_specialization }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('supervisor_3_id')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="justification_3" :value="__('Justification')" />
                                    <x-textarea-input id="justification_3" name="justification_3" class="block mt-1 w-full" rows="3">{{ old('justification_3') }}</x-textarea-input>
                                    <x-input-error :messages="$errors->get('justification_3')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Submit Preferences') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
