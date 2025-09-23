<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Late Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Late Submission Request</h3>
                        <p class="mt-1 text-sm text-gray-600">Request permission to submit your thesis beyond the standard deadline.</p>
                    </div>

                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>You are {{ $scholar->getDaysOverdue() }} days overdue for thesis submission. Late submission requests require approval from multiple authorities and should only be submitted with strong justification.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('scholar.late_submission.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label for="justification" class="block text-sm font-medium text-gray-700 mb-2">
                                Justification for Late Submission <span class="text-red-500">*</span>
                            </label>
                            <textarea name="justification" id="justification" rows="8"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Please provide a detailed justification for your late submission request. Include specific reasons, circumstances, and any supporting evidence..."
                                required>{{ old('justification') }}</textarea>
                            @error('justification')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="requested_extension_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Requested Extension Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="requested_extension_date" id="requested_extension_date"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                value="{{ old('requested_extension_date') }}"
                                required>
                            <p class="mt-1 text-sm text-gray-500">Select the date by which you will submit your thesis.</p>
                            @error('requested_extension_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="supporting_documents" class="block text-sm font-medium text-gray-700 mb-2">
                                Supporting Documents (Optional)
                            </label>
                            <input type="file" name="supporting_documents[]" id="supporting_documents"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                multiple
                                accept=".pdf,.doc,.docx">
                            <p class="mt-1 text-sm text-gray-500">Upload supporting documents (PDF, DOC, DOCX) - Maximum 5 files, 10MB each.</p>
                            @error('supporting_documents')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">Approval Process</h4>
                            <div class="text-sm text-blue-700">
                                <p>Your request will go through the following approval process:</p>
                                <ol class="list-decimal list-inside mt-2 space-y-1">
                                    <li>Supervisor Review</li>
                                    <li>HOD Approval</li>
                                    <li>Dean Approval</li>
                                    <li>Dean's Assistant Approval</li>
                                    <li>Section Officer Approval</li>
                                    <li>Assistant Registrar Approval</li>
                                    <li>Deputy Registrar Approval</li>
                                    <li>HVC Final Approval</li>
                                </ol>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('scholar.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
