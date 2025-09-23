<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Progress Report Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">📊 Progress Report Settings</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Configuration -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Current Configuration</h4>

                            @php
                                $allowedMonths = \App\Helpers\ProgressReportHelper::getAllowedMonthNames();
                                $isSubmissionAllowed = \App\Helpers\ProgressReportHelper::isSubmissionAllowed();
                                $statusMessage = \App\Helpers\ProgressReportHelper::getSubmissionStatusMessage();
                            @endphp

                            <div class="space-y-2">
                                <p><strong>Allowed Months:</strong> {{ implode(', ', $allowedMonths) }}</p>
                                <p><strong>Current Status:</strong>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $isSubmissionAllowed ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ $isSubmissionAllowed ? 'Open' : 'Closed' }}
                                    </span>
                                </p>
                                <p><strong>Status Message:</strong> {{ $statusMessage }}</p>
                            </div>
                        </div>

                        <!-- Configuration Info -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Configuration Info</h4>
                            <div class="space-y-2 text-sm">
                                <p><strong>Environment Variable:</strong> <code class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">PROGRESS_REPORT_MONTHS</code></p>
                                <p><strong>Current Value:</strong> <code class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">{{ config('app.progress_report_months') }}</code></p>
                                <p><strong>Format:</strong> Comma-separated month numbers (1-12)</p>
                                <p><strong>Example:</strong> <code class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">4,10</code> for April and October</p>
                            </div>
                        </div>
                    </div>

                    <!-- Month Reference -->
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Month Reference</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                            @php
                                $months = [
                                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                ];
                            @endphp
                            @foreach($months as $num => $name)
                                <div class="text-center p-2 border rounded {{ in_array($num, \App\Helpers\ProgressReportHelper::getAllowedMonths()) ? 'bg-green-100 dark:bg-green-900 border-green-300 dark:border-green-700' : 'bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600' }}">
                                    <div class="text-sm font-medium">{{ $num }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ $name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-800 dark:text-yellow-200 mb-2">⚠️ Configuration Instructions</h4>
                        <div class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                            <p>• To change the allowed months, update the <code>PROGRESS_REPORT_MONTHS</code> environment variable in your <code>.env</code> file</p>
                            <p>• Use comma-separated month numbers (1-12) without spaces</p>
                            <p>• After updating, restart your application server</p>
                            <p>• Example: <code>PROGRESS_REPORT_MONTHS=4,10</code> for April and October</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
