<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Submit Progress Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($existingReport)
                        @if($existingReport->status === 'rejected')
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            Report Rejected - Resubmission Required
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                            <p>Your progress report for <strong>{{ $currentMonth }}</strong> was rejected on {{ $existingReport->rejected_at->format('M d, Y') }}.</p>
                                            @if($existingReport->rejection_reason)
                                                <p class="mt-1"><strong>Reason:</strong> {{ $existingReport->rejection_reason }}</p>
                                            @endif
                                            <p class="mt-2 font-semibold">Please resubmit your progress report with the necessary corrections.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            Report Already Submitted
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>A progress report for <strong>{{ $currentMonth }}</strong> has already been submitted on {{ $existingReport->submission_date->format('M d, Y') }}.</p>
                                            <p class="mt-1">Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $existingReport->status)) }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if(!$existingReport || $existingReport->status === 'rejected')
                        @if($existingReport && $existingReport->status === 'rejected')
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Resubmit Progress Report</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Please upload a new version of your progress report with the necessary corrections.</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('scholar.progress_report.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mt-4">
                                <x-input-label for="report_period" :value="__('Report Period')" />
                                <select id="report_period" name="report_period" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm bg-gray-100 dark:bg-gray-700" disabled required>
                                    <option value="{{ $currentMonth }}" selected>{{ $currentMonth }}</option>
                                </select>
                                <input type="hidden" name="report_period" value="{{ $currentMonth }}">
                                <x-input-error :messages="$errors->get('report_period')" class="mt-2" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Report period is automatically set to the current month: <strong>{{ $currentMonth }}</strong>
                                </p>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="report_file" :value="__('Progress Report File (PDF/DOC/DOCX)')" />
                                <input id="report_file" class="block mt-1 w-full" type="file" name="report_file" accept=".pdf,.doc,.docx" required />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maximum file size: 2MB. Accepted formats: PDF, DOC, DOCX</p>
                                <x-input-error :messages="$errors->get('report_file')" class="mt-2" />
                            </div>

                            <!-- Transaction Details Section -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Transaction Details</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="transaction_amount" :value="__('Transaction Amount')" />
                                        <input id="transaction_amount" name="transaction_amount" type="number" step="0.01" min="0" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('transaction_amount') }}" required />
                                        <x-input-error :messages="$errors->get('transaction_amount')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="transaction_date" :value="__('Transaction Date')" />
                                        <input id="transaction_date" name="transaction_date" type="date" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('transaction_date') }}" required />
                                        <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="transaction_no" :value="__('Transaction Number')" />
                                        <input id="transaction_no" name="transaction_no" type="text" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('transaction_no') }}" required />
                                        <x-input-error :messages="$errors->get('transaction_no')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="pay_mode" :value="__('Payment Mode')" />
                                        <select id="pay_mode" name="pay_mode" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                            <option value="">Select Payment Mode</option>
                                            <option value="IMPS" {{ old('pay_mode') == 'IMPS' ? 'selected' : '' }}>IMPS</option>
                                            <option value="NEFT" {{ old('pay_mode') == 'NEFT' ? 'selected' : '' }}>NEFT</option>
                                            <option value="RTGS" {{ old('pay_mode') == 'RTGS' ? 'selected' : '' }}>RTGS</option>
                                            <option value="UPI" {{ old('pay_mode') == 'UPI' ? 'selected' : '' }}>UPI</option>
                                            <option value="Credit Card" {{ old('pay_mode') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                            <option value="Debit Card" {{ old('pay_mode') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                            <option value="Cash Deposit" {{ old('pay_mode') == 'Cash Deposit' ? 'selected' : '' }}>Cash Deposit</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('pay_mode')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="receipt_file" :value="__('Receipt File (PDF/Image)')" />
                                    <input id="receipt_file" class="block mt-1 w-full" type="file" name="receipt_file" accept=".pdf,.jpg,.jpeg,.png" required />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maximum file size: 2MB. Accepted formats: PDF, JPG, JPEG, PNG</p>
                                    <x-input-error :messages="$errors->get('receipt_file')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ms-3">
                                    {{ $existingReport && $existingReport->status === 'rejected' ? __('Resubmit Report') : __('Submit Report') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
