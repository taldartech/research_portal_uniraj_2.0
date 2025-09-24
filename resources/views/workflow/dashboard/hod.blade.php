<!-- HOD Dashboard -->
<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                                <a href="{{ route('hod.synopsis.approve', $synopsis) }}" 
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

    <!-- Department Scholars Overview -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Department Scholars ({{ $department->name }})
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($scholars as $scholar)
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 dark:text-white">
                        {{ $scholar->user->first_name }} {{ $scholar->user->last_name }}
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        {{ $scholar->enrollment_number ?? 'Not enrolled' }}
                    </p>
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span>Synopsis:</span>
                            <span class="font-medium">{{ ucfirst($scholar->synopsis_status ?? 'Not submitted') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span>Registration:</span>
                            <span class="font-medium">{{ ucfirst($scholar->registration_form_status ?? 'Not started') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span>Enrollment:</span>
                            <span class="font-medium">{{ ucfirst($scholar->enrollment_status ?? 'Not enrolled') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
