@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Scholars with Submissions
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        View scholars who have submitted registration forms and synopses
                    </p>
                </div>

                <!-- Filters -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('hod.scholars.submissions', ['filter' => 'all']) }}"
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'all' || !request('filter') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            All Scholars
                        </a>
                        <a href="{{ route('hod.scholars.submissions', ['filter' => 'submitted_forms']) }}"
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'submitted_forms' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Submitted Forms
                        </a>
                        <a href="{{ route('hod.scholars.submissions', ['filter' => 'submitted_synopses']) }}"
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'submitted_synopses' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Submitted Synopses
                        </a>
                        <a href="{{ route('hod.scholars.submissions', ['filter' => 'pending_synopsis_approval']) }}"
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'pending_synopsis_approval' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Pending Synopsis Approval
                        </a>
                        <a href="{{ route('hod.scholars.submissions', ['filter' => 'supervisor_verified']) }}"
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'supervisor_verified' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Supervisor Verified
                        </a>
                        <a href="{{ route('hod.scholars.submissions', ['filter' => 'needs_attention']) }}"
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'needs_attention' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Needs Attention
                        </a>
                    </div>
                </div>

                <!-- Search -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('hod.scholars.submissions') }}" class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by name or email..."
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Scholars Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Scholar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supervisor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registration Form</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Synopsis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Overall Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse($scholarsWithStatus as $scholar)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        {{ substr($scholar->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $scholar->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $scholar->user->email }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    ID: {{ $scholar->enrollment_number ?? 'Not enrolled' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($scholar->currentSupervisor)
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $scholar->currentSupervisor->supervisor->user->name }} {{ $scholar->currentSupervisor->supervisor->user->last_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $scholar->currentSupervisor->supervisor->user->email }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $scholar->workflow_status['registration']['progress_percentage'] }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $scholar->workflow_status['registration']['progress_percentage'] }}%</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $scholar->workflow_status['registration']['current_stage'] }}
                                        </div>
                                        @if($scholar->registration_form_submitted_at)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Submitted: {{ $scholar->registration_form_submitted_at->format('M d, Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $scholar->workflow_status['synopsis']['progress_percentage'] }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $scholar->workflow_status['synopsis']['progress_percentage'] }}%</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $scholar->workflow_status['synopsis']['current_stage'] }}
                                        </div>
                                        @if($scholar->synopses->isNotEmpty())
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Latest: {{ $scholar->synopses->first()->submission_date->format('M d, Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($scholar->workflow_status['overall_status'] === 'Enrolled') bg-green-100 text-green-800
                                            @elseif(str_contains($scholar->workflow_status['overall_status'], 'Approved')) bg-blue-100 text-blue-800
                                            @elseif(str_contains($scholar->workflow_status['overall_status'], 'Rejected')) bg-red-100 text-red-800
                                            @elseif(str_contains($scholar->workflow_status['overall_status'], 'Pending')) bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $scholar->workflow_status['overall_status'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('hod.scholars.show', $scholar) }}"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                View Details
                                            </a>
                                            @if($scholar->synopses->where('status', 'pending_hod_approval')->isNotEmpty())
                                                <a href="{{ route('hod.synopsis.approve', $scholar->synopses->where('status', 'pending_hod_approval')->first()) }}"
                                                   class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300">
                                                    Review Synopsis
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No scholars found</h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                No scholars match your current filter criteria.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary Statistics -->
                @if($scholarsWithStatus->isNotEmpty())
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $scholarsWithStatus->count() }}</div>
                            <div class="text-sm text-blue-600 dark:text-blue-400">Total Scholars</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $scholarsWithStatus->where('registration_form_status', '!=', 'not_started')->count() }}
                            </div>
                            <div class="text-sm text-green-600 dark:text-green-400">Submitted Forms</div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $scholarsWithStatus->where('synopses', '!=', null)->count() }}
                            </div>
                            <div class="text-sm text-purple-600 dark:text-purple-400">Submitted Synopses</div>
                        </div>
                        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                                {{ $scholarsWithStatus->filter(function($scholar) { return $scholar->synopses->where('status', 'pending_hod_approval')->isNotEmpty(); })->count() }}
                            </div>
                            <div class="text-sm text-orange-600 dark:text-orange-400">Pending Approval</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
