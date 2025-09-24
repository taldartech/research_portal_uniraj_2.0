<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scholar Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">Track your Ph.D. journey progress and complete the required steps.</p>
                </div>
            </div>

            <!-- Progress Report Status -->
            @if(auth()->user()->scholar && auth()->user()->scholar->hasAssignedSupervisor())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">📊 Progress Report Status</h3>

                        @php
                            $isSubmissionAllowed = \App\Helpers\ProgressReportHelper::isSubmissionAllowed();
                            $statusMessage = \App\Helpers\ProgressReportHelper::getSubmissionStatusMessage();
                            $allowedMonths = \App\Helpers\ProgressReportHelper::getAllowedMonthNames();
                            $currentMonth = date('F');
                            $existingReport = auth()->user()->scholar->progressReports()
                                ->where('report_period', $currentMonth)
                                ->first();
                        @endphp

                        @if($existingReport)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                            Report Already Submitted for {{ $currentMonth }}
                                        </h3>
                                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                            <p>Submitted on: <strong>{{ $existingReport->submission_date->format('M d, Y') }}</strong></p>
                                            <p>Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $existingReport->status)) }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if($isSubmissionAllowed)
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $statusMessage }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        Allowed months: {{ implode(', ', $allowedMonths) }}
                                    </p>
                                    @if($isSubmissionAllowed)
                                        <div class="mt-3">
                                            <a href="{{ route('scholar.progress_report.submit') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                                Submit Progress Report
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Next Step Focus -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(auth()->user()->scholar)
                        @php
                            $nextStep = auth()->user()->scholar->getNextStep();
                        @endphp

                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            @if($nextStep['status'] === 'completed')
                                🎉 {{ $nextStep['title'] }}
                            @else
                                Next Step: {{ $nextStep['title'] }}
                            @endif
                        </h3>

                        <!-- Single Step Display -->
                        <div class="relative">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center relative z-10">
                                    @if($nextStep['status'] === 'completed')
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($nextStep['status'] === 'in_progress')
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <span class="text-blue-600 dark:text-blue-400 font-semibold text-lg">{{ $nextStep['step'] }}</span>
                                    @endif
                                </div>
                                <div class="ml-6 flex-1">
                                    <a href="{{ route($nextStep['route']) }}" class="block group">
                                        <h4 class="text-xl font-medium text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $nextStep['title'] }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                            {{ $nextStep['description'] }}
                                        </p>
                                        <div class="mt-3">
                                            @if($nextStep['status'] === 'completed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    🎉 All Done!
                                                </span>
                                            @elseif($nextStep['status'] === 'in_progress')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    ⏳ In Progress
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    📋 Ready to Start
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($nextStep['status'] !== 'completed')
                            <!-- Progress Indicator -->
                            <div class="mt-6">
                                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <span>Overall Progress</span>
                                    <span>{{ $nextStep['step'] - 1 }}/8 Steps Completed</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ (($nextStep['step'] - 1) / 8) * 100 }}%"></div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No scholar profile found. Please contact your administrator.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
