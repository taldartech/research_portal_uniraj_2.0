<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upcoming Pre-PhD Viva Dates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Upcoming Pre-PhD Viva Dates (Today and Future)</h3>
                            <p class="text-sm text-gray-600 mt-1">Showing all scheduled Pre-PhD Viva dates for scholars in your department.</p>
                        </div>
                    </div>

                    @if ($upcomingVivas->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming Pre-PhD Viva dates</h3>
                            <p class="mt-1 text-sm text-gray-500">There are no scheduled Pre-PhD Viva dates for today or in the future for scholars in your department.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Viva Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thesis Deadline</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Until Viva</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($upcomingVivas as $request)
                                        @php
                                            $daysUntil = now()->startOfDay()->diffInDays($request->viva_date->startOfDay(), false);
                                            $isToday = $request->viva_date->isToday();
                                        @endphp
                                        <tr class="{{ $isToday ? 'bg-yellow-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->scholar->user->name }}</div>
                                                <div class="text-xs text-gray-500">SCH-{{ str_pad($request->scholar->id, 6, '0', STR_PAD_LEFT) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $request->supervisor->user->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $request->viva_date->format('M d, Y') }}
                                                    @if($isToday)
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Today</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $request->viva_date->format('l') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($request->thesis_submission_deadline)
                                                    {{ $request->thesis_submission_deadline->format('M d, Y') }}
                                                @else
                                                    <span class="text-gray-400">Not set</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($request->thesis_submitted)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Thesis Submitted
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Pending Thesis
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($isToday)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Today
                                                    </span>
                                                @elseif($daysUntil > 0)
                                                    <span class="text-sm text-gray-900">
                                                        {{ $daysUntil }} {{ $daysUntil === 1 ? 'day' : 'days' }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-red-600">
                                                        Past due
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

