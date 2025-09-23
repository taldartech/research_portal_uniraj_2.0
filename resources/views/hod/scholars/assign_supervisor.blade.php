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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Scholar: {{ $scholar->user->name }} ({{ $scholar->user->email }})</h3>

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
                                            <div class="flex space-x-2">
                                                <form method="POST" action="{{ route('hod.supervisor_assignments.approve', $assignment) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            style="background-color: #16a34a !important; color: white !important;"
                                                            class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded text-xs font-medium text-white hover:bg-green-700 focus:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                                            onclick="return confirm('Are you sure you want to approve this supervisor assignment?')">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('hod.supervisor_assignments.reject', $assignment) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            style="background-color: #dc2626 !important; color: white !important;"
                                                            class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded text-xs font-medium text-white hover:bg-red-700 focus:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                            onclick="return confirm('Are you sure you want to reject this supervisor assignment?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hod.scholars.assign_supervisor.store', $scholar) }}">
                        @csrf

                        <!-- Supervisor Selection -->
                        <div class="mb-4">
                            <x-input-label for="supervisor_id" :value="__('Select Supervisor')" />
                            <x-select-input id="supervisor_id" name="supervisor_id" class="block mt-1 w-full" required>
                                <option value="">Select a Supervisor</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}">
                                        {{ $supervisor->user->name }}
                                        ({{ $supervisor->supervisor_type_display }} -
                                        {{ $supervisor->getCurrentScholarCount() }}/{{ $supervisor->getScholarLimit() }} scholars)
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('supervisor_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Assign Supervisor') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
