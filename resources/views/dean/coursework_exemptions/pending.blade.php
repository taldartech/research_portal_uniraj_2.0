<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Coursework Exemptions for Dean Approval') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($pendingExemptions->isEmpty())
                        <p>No pending coursework exemptions for approval.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($pendingExemptions as $exemption)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $exemption->scholar->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $exemption->supervisor->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $exemption->reason }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $exemption->request_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ Storage::url($exemption->minutes_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-3">View RAC Minutes</a>
                                            <form method="POST" action="{{ route('dean.coursework_exemptions.approve', $exemption) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <x-primary-button class="ml-3">Approve</x-primary-button>
                                            </form>
                                            <form method="POST" action="{{ route('dean.coursework_exemptions.approve', $exemption) }}" class="inline-block ml-2">
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
