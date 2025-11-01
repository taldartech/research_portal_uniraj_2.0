<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Supervisor to Scholar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholar Information -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Name:</strong> {{ $scholar->user->name }}</p>
                                <p><strong>Email:</strong> {{ $scholar->user->email }}</p>
                            </div>
                            <div>
                                <p><strong>Department:</strong> {{ $scholar->admission->department->name }}</p>
                                <p><strong>Research Area:</strong> {{ $scholar->research_area ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($pendingAssignments->count() > 0)
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="text-lg font-medium text-yellow-800 mb-3">Pending Supervisor Requests</h4>
                            <p class="text-sm text-yellow-700 mb-4">
                                <strong>Note:</strong> When you assign a supervisor below, all pending requests will be automatically rejected and the selected supervisor will be directly assigned.
                            </p>
                            <div class="space-y-3">
                                @foreach($pendingAssignments as $assignment)
                                    <div class="bg-white p-4 rounded border border-yellow-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $assignment->supervisor->user->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $assignment->supervisor->designation }}</p>
                                                <p class="text-sm text-gray-600">{{ $assignment->supervisor->research_specialization }}</p>
                                                @if($assignment->justification)
                                                    <p class="text-sm text-gray-700 mt-2"><strong>Justification:</strong> {{ $assignment->justification }}</p>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-2">Submitted: {{ $assignment->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hod.scholars.assign_supervisor.store', $scholar) }}" id="supervisorAssignmentForm">
                        @csrf

                        <!-- Assignment Type Selection -->
                        @if($preferences->count() > 0)
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="text-lg font-medium text-blue-900 mb-4">Supervisor Preferences</h4>
                                <p class="text-sm text-blue-700 mb-4">The scholar has submitted the following supervisor preferences. You can select from these preferences or reject them and select manually.</p>

                                <!-- Preferences Selection (shown when preference is selected) -->
                                <div id="preferenceSelection" class="mt-4">
                                    <h5 class="text-md font-medium text-gray-900 mb-3">Select Preferred Supervisor:</h5>
                                    <div class="space-y-4">
                                        @foreach($preferences as $preference)
                                            <div class="border rounded-lg p-4 border-gray-200 bg-white">
                                                <label class="flex items-start cursor-pointer">
                                                    <input type="radio"
                                                           name="selected_preference_id"
                                                           value="{{ $preference->id }}"
                                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                           {{ $loop->first ? 'checked' : '' }}
                                                           required>
                                                    <div class="ml-3 flex-1">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <h4 class="font-medium text-gray-900">
                                                                    {{ $preference->preference_order }}{{ $preference->preference_order == 1 ? 'st' : ($preference->preference_order == 2 ? 'nd' : 'rd') }} Preference
                                                                </h4>
                                                                <p class="text-sm text-gray-600">
                                                                    <strong>Supervisor:</strong> {{ $preference->supervisor->user->name }}
                                                                </p>
                                                                <p class="text-sm text-gray-600">
                                                                    <strong>Designation:</strong> {{ $preference->supervisor->designation ?? 'Not specified' }}
                                                                </p>
                                                                <p class="text-sm text-gray-600">
                                                                    <strong>Specialization:</strong> {{ $preference->supervisor->research_specialization }}
                                                                </p>
                                                                <p class="text-sm text-gray-600">
                                                                    <strong>Current Scholars:</strong> {{ $preference->supervisor->assignedScholars->count() }} / {{ $preference->supervisor->getScholarLimit() }}
                                                                </p>
                                                            </div>
                                                            @if($preference->preference_order == 1)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    Scholar's 1st Choice
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if($preference->justification)
                                                            <div class="mt-3">
                                                                <p class="text-sm text-gray-600">
                                                                    <strong>Justification:</strong>
                                                                </p>
                                                                <p class="text-sm text-gray-700 bg-white p-2 rounded border">
                                                                    {{ $preference->justification }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- HOD Remarks for Preferences -->
                                    <div class="mt-4">
                                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                            HOD Remarks (Optional)
                                        </label>
                                        <textarea id="remarks"
                                                  name="remarks"
                                                  rows="3"
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="Add any remarks about the supervisor selection...">{{ old('remarks') }}</textarea>
                                        @error('remarks')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('hod.scholars.show', $scholar) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-3">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    {{ __('Assign Supervisor') }}
                                </x-primary-button>
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        document.getElementById('supervisorAssignmentForm').addEventListener('submit', function(e) {
            const selectedPreference = document.querySelector('input[name="selected_preference_id"]:checked');
            if (!selectedPreference) {
                e.preventDefault();
                alert('Please select a preference.');
                return false;
            }
        });
    </script>
</x-app-layout>
