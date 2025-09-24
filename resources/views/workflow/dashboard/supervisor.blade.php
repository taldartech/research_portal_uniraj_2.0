<!-- Supervisor Dashboard -->
<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-blue-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['total_scholars'] }}</div>
                    <div class="text-blue-100">Total Scholars</div>
                </div>
            </div>
        </div>

        <div class="bg-green-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['synopsis_approved'] }}</div>
                    <div class="text-green-100">Synopsis Approved</div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['synopsis_pending'] }}</div>
                    <div class="text-yellow-100">Pending Approval</div>
                </div>
            </div>
        </div>

        <div class="bg-purple-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['registration_completed'] }}</div>
                    <div class="text-purple-100">Enrolled</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    @if($pending_approvals->count() > 0)
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Pending Synopsis Approvals
            </h3>
            
            <div class="space-y-4">
                @foreach($pending_approvals as $synopsis)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">
                                    {{ $synopsis->scholar->user->first_name }} {{ $synopsis->scholar->user->last_name }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Synopsis submitted on {{ $synopsis->submission_date->format('M d, Y') }}
                                </p>
                                @if($synopsis->proposed_topic)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                        Topic: {{ $synopsis->proposed_topic }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('supervisor.synopsis.approve', $synopsis) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Review
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Scholars Overview -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Your Scholars
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Scholar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Synopsis Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registration Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Overall Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($workflow_statuses as $status)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ substr($status['scholar_name'], 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $status['scholar_name'] }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            ID: {{ $status['scholar_id'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $status['synopsis']['progress_percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $status['synopsis']['progress_percentage'] }}%</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $status['synopsis']['current_stage'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $status['registration']['progress_percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $status['registration']['progress_percentage'] }}%</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $status['registration']['current_stage'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($status['overall_status'] === 'Enrolled') bg-green-100 text-green-800
                                    @elseif(str_contains($status['overall_status'], 'Approved')) bg-blue-100 text-blue-800
                                    @elseif(str_contains($status['overall_status'], 'Rejected')) bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $status['overall_status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('supervisor.scholars.show', $status['scholar_id']) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    View Details
                                </a>
                                @if($status['synopsis']['status'] === 'pending_supervisor_approval')
                                    <a href="{{ route('supervisor.synopsis.approve', $status['scholar_id']) }}" 
                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                        Approve Synopsis
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Quick Actions
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('supervisor.scholars.index') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-blue-800 dark:text-blue-200">Manage Scholars</p>
                    <p class="text-sm text-blue-600 dark:text-blue-300">View and manage your scholars</p>
                </div>
            </a>

            <a href="{{ route('supervisor.synopsis.pending') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-green-800 dark:text-green-200">Review Synopses</p>
                    <p class="text-sm text-green-600 dark:text-green-300">Approve pending synopses</p>
                </div>
            </a>

            <a href="{{ route('supervisor.profile') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <div>
                    <p class="font-medium text-purple-800 dark:text-purple-200">Profile</p>
                    <p class="text-sm text-purple-600 dark:text-purple-300">Update your profile</p>
                </div>
            </a>
        </div>
    </div>
</div>
