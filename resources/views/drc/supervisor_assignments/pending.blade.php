<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Supervisor Assignments for HOD Approval (DRC)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($pendingAssignments->isEmpty())
                        <p>No pending supervisor assignments.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proposed Supervisor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justification</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($pendingAssignments as $assignment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->scholar->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->supervisor->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->justification }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->assigned_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form method="POST" action="{{ route('drc.supervisor_assignments.approve', $assignment) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <x-primary-button class="ml-3">Approve</x-primary-button>
                                            </form>
                                            <form method="POST" action="{{ route('drc.supervisor_assignments.approve', $assignment) }}" class="inline-block ml-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <x-danger-button class="ml-3">Reject</x-danger-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
