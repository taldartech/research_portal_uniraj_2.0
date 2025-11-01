<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Scholars') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($scholars->isEmpty())
                        <p>You have no scholars assigned to you.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($scholarsWithSubmissionInfo))
                                    @foreach ($scholarsWithSubmissionInfo as $info)
                                        @php
                                            $scholar = $info['scholar'];
                                            $canSubmit = $info['can_submit'] ?? false;
                                            $reportPeriod = $info['report_period'] ?? null;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $scholar->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $scholar->user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ ucfirst(str_replace('_', ' ', $scholar->status)) }}
                                                @if($canSubmit)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Report Due
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('staff.scholars.show', $scholar) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                                    @if($canSubmit)
                                                        <span class="text-gray-300">|</span>
                                                        <a href="{{ route('staff.progress_report.submit.for_scholar', $scholar) }}" 
                                                           class="text-green-600 hover:text-green-900"
                                                           title="Submit Progress Report for {{ $reportPeriod }}">
                                                            Submit Progress Report
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($scholars as $scholar)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $scholar->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $scholar->user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $scholar->status)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('staff.scholars.show', $scholar) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
