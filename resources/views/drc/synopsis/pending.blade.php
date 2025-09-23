<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Synopses for HOD Approval (DRC)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($pendingSynopses->isEmpty())
                        <p>No pending synopses for approval.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proposed Topic</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($pendingSynopses as $synopsis)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $synopsis->scholar->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $synopsis->proposed_topic }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $synopsis->rac->supervisor->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $synopsis->submission_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-3">View Synopsis</a>
                                            <form method="POST" action="{{ route('drc.synopsis.approve', $synopsis) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="remarks" value="Approved by HOD">
                                                <x-primary-button class="ml-3">Approve</x-primary-button>
                                            </form>
                                            <form method="POST" action="{{ route('drc.synopsis.approve', $synopsis) }}" class="inline-block ml-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="remarks" value="Rejected by HOD">
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
