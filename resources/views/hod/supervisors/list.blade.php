<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisors in Your Department') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($supervisors->isEmpty())
                        <p>No supervisors found in your department.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Scholars</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($supervisors as $supervisor)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $supervisor->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $supervisor->user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($supervisor->supervisor_type === 'professor') bg-purple-100 text-purple-800
                                                @elseif($supervisor->supervisor_type === 'associate') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800
                                                @endif">
                                                {{ $supervisor->supervisor_type_display }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $supervisor->assigned_scholars_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600">
                                                {{ $supervisor->assigned_scholars_count }}/{{ $supervisor->getScholarLimit() }}
                                                @if($supervisor->canAcceptMoreScholars())
                                                    <span class="text-green-600">(Available)</span>
                                                @else
                                                    <span class="text-red-600">(Full)</span>
                                                @endif
                                            </span>
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
