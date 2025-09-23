<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thesis Eligibility Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Thesis Submission Eligibility</h3>

                        @if(isset($eligibilityCheck['eligibility']['eligible']) && $eligibilityCheck['eligibility']['eligible'])
                            <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <h4 class="text-lg font-medium text-green-800 dark:text-green-200">Eligible to Submit Thesis</h4>
                                </div>
                                <p class="mt-2 text-green-700 dark:text-green-300">{{ $eligibilityCheck['reason'] }}</p>
                            </div>
                        @else
                            <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <h4 class="text-lg font-medium text-red-800 dark:text-red-200">Not Eligible to Submit Thesis</h4>
                                </div>
                                <p class="mt-2 text-red-700 dark:text-red-300">{{ $eligibilityCheck['reason'] }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Eligibility Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Eligibility Criteria</h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                                <li class="flex items-start">
                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mt-2 mr-2"></span>
                                    <span><strong>Regular Scholars:</strong> 3 years from Date of Confirmation (DOC)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full mt-2 mr-2"></span>
                                    <span><strong>Coursework Exempted:</strong> 2.5 years from DOC</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-2"></span>
                                    <span><strong>Maximum Period:</strong> 6 years from DOC</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full mt-2 mr-2"></span>
                                    <span><strong>Special Approval:</strong> Required after 6 years</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Your Timeline</h4>
                            <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                                @if(isset($eligibilityCheck['eligibility']['eligible_date']) && $eligibilityCheck['eligibility']['eligible_date'])
                                    <p><strong>Eligible Date:</strong> {{ $eligibilityCheck['eligibility']['eligible_date']->format('M d, Y') }}</p>
                                @endif
                                @if(isset($eligibilityCheck['eligibility']['max_date']) && $eligibilityCheck['eligibility']['max_date'])
                                    <p><strong>Maximum Date:</strong> {{ $eligibilityCheck['eligibility']['max_date']->format('M d, Y') }}</p>
                                @endif
                                @if(isset($eligibilityCheck['eligibility']['days_remaining']) && $eligibilityCheck['eligibility']['days_remaining'])
                                    <p><strong>Days Remaining:</strong> {{ $eligibilityCheck['eligibility']['days_remaining'] }} days</p>
                                @endif
                                @if(isset($eligibilityCheck['eligibility']['days_until_eligible']) && $eligibilityCheck['eligibility']['days_until_eligible'])
                                    <p><strong>Days Until Eligible:</strong> {{ $eligibilityCheck['eligibility']['days_until_eligible'] }} days</p>
                                @endif
                                @if(isset($eligibilityCheck['eligibility']['is_coursework_exempted']) && $eligibilityCheck['eligibility']['is_coursework_exempted'])
                                    <p class="mt-2"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Coursework Exempted</span></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex items-center justify-between">
                        <div>
                            @if($eligibilityCheck['can_submit'])
                                <a href="{{ route('scholar.thesis.submit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Submit Thesis
                                </a>
                            @elseif(isset($eligibilityCheck['eligibility']['requires_special_approval']) && $eligibilityCheck['eligibility']['requires_special_approval'])
                                <button class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150" disabled>
                                    Special Approval Required
                                </button>
                            @else
                                <button class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed" disabled>
                                    Not Yet Eligible
                                </button>
                            @endif
                        </div>

                        <a href="{{ route('scholar.dashboard') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
