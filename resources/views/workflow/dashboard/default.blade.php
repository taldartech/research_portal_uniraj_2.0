<!-- Default Dashboard -->
<div class="space-y-8">
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-8 text-white text-center">
        <h2 class="text-3xl font-bold mb-4">Welcome to Research Portal</h2>
        <p class="text-xl text-blue-100">
            {{ $message ?? 'Your comprehensive research management system' }}
        </p>
    </div>

    <!-- System Information -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            System Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">About Research Portal</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    A comprehensive system for managing PhD research workflows, synopsis approvals,
                    registration processes, and academic progress tracking.
                </p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Your Role</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Current user type: <span class="font-medium">{{ ucfirst($user->user_type ?? 'User') }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Quick Links
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="/dashboard" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                </svg>
                <div>
                    <p class="font-medium text-blue-800 dark:text-blue-200">Dashboard</p>
                    <p class="text-sm text-blue-600 dark:text-blue-300">View your main dashboard</p>
                </div>
            </a>

            <a href="/profile" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <div>
                    <p class="font-medium text-green-800 dark:text-green-200">Profile</p>
                    <p class="text-sm text-green-600 dark:text-green-300">Update your profile</p>
                </div>
            </a>

            <a href="/help" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-purple-800 dark:text-purple-200">Help & Support</p>
                    <p class="text-sm text-purple-600 dark:text-purple-300">Get help and support</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Contact Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Technical Support</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    For technical issues or system problems, please contact the IT support team.
                </p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Academic Support</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    For academic or process-related questions, please contact your department or supervisor.
                </p>
            </div>
        </div>
    </div>
</div>
