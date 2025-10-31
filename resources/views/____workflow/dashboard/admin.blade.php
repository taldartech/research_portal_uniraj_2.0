<!-- Admin Dashboard -->
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
                    <div class="text-2xl font-bold">{{ $statistics['enrolled_scholars'] }}</div>
                    <div class="text-green-100">Enrolled Scholars</div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['pending_synopses'] }}</div>
                    <div class="text-yellow-100">Pending Synopses</div>
                </div>
            </div>
        </div>

        <div class="bg-purple-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['completed_registrations'] }}</div>
                    <div class="text-purple-100">Completed Registrations</div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            System Overview
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Synopsis Workflow</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-blue-700 dark:text-blue-300">Pending:</span>
                        <span class="font-medium text-blue-900 dark:text-blue-100">{{ $statistics['pending_synopses'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-700 dark:text-blue-300">Approved:</span>
                        <span class="font-medium text-blue-900 dark:text-blue-100">{{ $statistics['approved_synopses'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                <h4 class="font-medium text-green-900 dark:text-green-100 mb-2">Registration Workflow</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-green-700 dark:text-green-300">Completed:</span>
                        <span class="font-medium text-green-900 dark:text-green-100">{{ $statistics['completed_registrations'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-green-700 dark:text-green-300">Enrolled:</span>
                        <span class="font-medium text-green-900 dark:text-green-100">{{ $statistics['enrolled_scholars'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                <h4 class="font-medium text-purple-900 dark:text-purple-100 mb-2">Scholar Status</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-purple-700 dark:text-purple-300">Total:</span>
                        <span class="font-medium text-purple-900 dark:text-purple-100">{{ $statistics['total_scholars'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-purple-700 dark:text-purple-300">Active:</span>
                        <span class="font-medium text-purple-900 dark:text-purple-100">{{ $statistics['enrolled_scholars'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Recent Activity
        </h3>

        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent activity</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                System activity will appear here as users interact with the workflow.
            </p>
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
            <a href="{{ route('admin.users') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-blue-800 dark:text-blue-200">Manage Users</p>
                    <p class="text-sm text-blue-600 dark:text-blue-300">View and manage all users</p>
                </div>
            </a>

            <a href="{{ route('admin.departments') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <div>
                    <p class="font-medium text-green-800 dark:text-green-200">Manage Departments</p>
                    <p class="text-sm text-green-600 dark:text-green-300">View and manage departments</p>
                </div>
            </a>

            <a href="{{ route('admin.settings') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-purple-800 dark:text-purple-200">System Settings</p>
                    <p class="text-sm text-purple-600 dark:text-purple-300">Configure system settings</p>
                </div>
            </a>
        </div>
    </div>
</div>
