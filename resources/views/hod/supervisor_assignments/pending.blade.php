<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Supervisor Assignments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($pendingAssignments->count() > 0)
                        <div class="space-y-6">
                            @foreach($pendingAssignments as $assignment)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $assignment->scholar->user->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                Enrollment: {{ $assignment->scholar->enrollment_number }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Department: {{ $assignment->scholar->admission->department->name }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending Approval
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Submitted: {{ $assignment->created_at->format('M d, Y H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Preferred Supervisor</h4>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="font-medium">{{ $assignment->supervisor->user->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $assignment->supervisor->designation }}</p>
                                                <p class="text-sm text-gray-600">{{ $assignment->supervisor->research_specialization }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Scholar Details</h4>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-sm"><strong>Research Area:</strong> {{ $assignment->scholar->research_area ?? 'Not specified' }}</p>
                                                <p class="text-sm"><strong>Email:</strong> {{ $assignment->scholar->user->email }}</p>
                                                <p class="text-sm"><strong>Contact:</strong> {{ $assignment->scholar->contact_number ?? 'Not provided' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="font-medium text-gray-900 mb-2">Justification</h4>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-sm">{{ $assignment->justification }}</p>
                                        </div>
                                    </div>

                                    @if($assignment->office_note_generated)
                                        <div class="mb-4">
                                            <h4 class="font-medium text-gray-900 mb-2">Office Note</h4>
                                            <div class="bg-blue-50 p-3 rounded">
                                                <p class="text-sm text-blue-800">
                                                    <strong>Generated:</strong> {{ $assignment->office_note_generated_at->format('M d, Y H:i') }}
                                                </p>
                                                <a href="{{ route('scholar.supervisor_assignment.office_note.download', $assignment) }}"
                                                   class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Download Office Note
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex space-x-4 pt-4 border-t border-gray-200">
                                        <form method="POST" action="{{ route('hod.supervisor_assignments.approve', $assignment) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    style="background-color: #16a34a !important; color: white !important;"
                                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-semibold text-sm uppercase tracking-wider hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl"
                                                    onclick="return confirm('Are you sure you want to approve this supervisor assignment?')">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve Assignment
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('hod.supervisor_assignments.reject', $assignment) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    style="background-color: #dc2626 !important; color: white !important;"
                                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-semibold text-sm uppercase tracking-wider hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl"
                                                    onclick="return confirm('Are you sure you want to reject this supervisor assignment?')">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Reject Assignment
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-1">
                            <svg class="mx-auto h-5 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending supervisor assignments</h3>
                            <p class="mt-1 text-sm text-gray-500">All supervisor assignments have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
