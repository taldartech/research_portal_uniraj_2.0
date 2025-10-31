<!-- Scholar Dashboard -->
<div class="space-y-8">
    <!-- Overall Status Card -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-4">Your Research Progress</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold">{{ $workflow_status['overall_status'] }}</div>
                <div class="text-blue-100">Overall Status</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ $workflow_status['synopsis']['progress_percentage'] }}%</div>
                <div class="text-blue-100">Synopsis Progress</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ $workflow_status['registration']['progress_percentage'] }}%</div>
                <div class="text-blue-100">Registration Progress</div>
            </div>
        </div>
    </div>

    <!-- Synopsis Status -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Synopsis Status
        </h3>
        
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</span>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $workflow_status['synopsis']['progress_percentage'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $workflow_status['synopsis']['progress_percentage'] }}%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Stage</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $workflow_status['synopsis']['current_stage'] }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($workflow_status['synopsis']['status'] === 'approved') bg-green-100 text-green-800
                    @elseif(str_starts_with($workflow_status['synopsis']['status'], 'rejected')) bg-red-100 text-red-800
                    @elseif(str_starts_with($workflow_status['synopsis']['status'], 'pending')) bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $workflow_status['synopsis']['status'])) }}
                </span>
            </div>
        </div>

        @if($workflow_status['synopsis']['submission_date'])
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Submission Date</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($workflow_status['synopsis']['submission_date'])->format('M d, Y') }}</p>
            </div>
        @endif
    </div>

    <!-- Registration Status -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Registration Status
        </h3>
        
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</span>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $workflow_status['registration']['progress_percentage'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $workflow_status['registration']['progress_percentage'] }}%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Stage</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $workflow_status['registration']['current_stage'] }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($workflow_status['registration']['status'] === 'completed') bg-green-100 text-green-800
                    @elseif($workflow_status['registration']['status'] === 'not_started') bg-gray-100 text-gray-800
                    @else bg-blue-100 text-blue-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $workflow_status['registration']['status'])) }}
                </span>
            </div>
        </div>

        @if($workflow_status['registration']['submission_date'])
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Submission Date</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($workflow_status['registration']['submission_date'])->format('M d, Y') }}</p>
            </div>
        @endif
    </div>

    <!-- Pending Actions -->
    @if(!empty($pending_actions))
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Pending Actions
            </h3>
            
            <div class="space-y-3">
                @foreach($pending_actions as $action)
                    <div class="flex items-center justify-between p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                        <div>
                            <p class="font-medium text-orange-800 dark:text-orange-200">{{ $action['message'] }}</p>
                            <p class="text-sm text-orange-600 dark:text-orange-300">Priority: {{ ucfirst($action['priority']) }}</p>
                        </div>
                        <a href="{{ $action['url'] }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Take Action
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Updates -->
    @if(!empty($recent_updates))
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Recent Updates
            </h3>
            
            <div class="space-y-3">
                @foreach($recent_updates as $update)
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-600 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $update['message'] }}</p>
                            @if($update['date'])
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($update['date'])->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Quick Actions
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('scholar.registration.phd_form') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-blue-800 dark:text-blue-200">Registration Form</p>
                    <p class="text-sm text-blue-600 dark:text-blue-300">Complete your PhD registration</p>
                </div>
            </a>

            <a href="{{ route('scholar.synopsis.create') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-green-800 dark:text-green-200">Submit Synopsis</p>
                    <p class="text-sm text-green-600 dark:text-green-300">Submit your research synopsis</p>
                </div>
            </a>

            <a href="{{ route('scholar.profile') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
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
