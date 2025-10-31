<!-- SO Dashboard -->
<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['pending_synopses'] }}</div>
                    <div class="text-blue-100">Pending Synopses</div>
                </div>
            </div>
        </div>

        <div class="bg-green-500 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="text-2xl font-bold">{{ $statistics['approved_synopses'] }}</div>
                    <div class="text-green-100">Approved Synopses</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Synopses -->
    @if($pending_synopses->count() > 0)
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Pending Synopsis Approvals
            </h3>

            <div class="space-y-4">
                @foreach($pending_synopses as $synopsis)
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
                                <a href="{{ route('so.synopsis.approve', $synopsis) }}"
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
</div>
