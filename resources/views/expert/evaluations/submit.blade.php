<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Thesis Evaluation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Thesis Evaluation Submission</h3>
                        <p class="mt-1 text-sm text-gray-600">Please provide your detailed evaluation of the thesis submission.</p>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Thesis Details</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Scholar:</span> {{ $evaluation->thesisSubmission->scholar->user->name }}
                            </div>
                            <div>
                                <span class="font-medium">Supervisor:</span> {{ $evaluation->thesisSubmission->supervisor->user->name }}
                            </div>
                            <div>
                                <span class="font-medium">Thesis Title:</span> {{ $evaluation->thesisSubmission->title }}
                            </div>
                            <div>
                                <span class="font-medium">Submission Date:</span> {{ $evaluation->thesisSubmission->submission_date->format('M d, Y') }}
                            </div>
                            <div>
                                <span class="font-medium">Assigned Date:</span> {{ $evaluation->assigned_date ? $evaluation->assigned_date->format('M d, Y') : 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Due Date:</span> {{ $evaluation->due_date ? $evaluation->due_date->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('expert.evaluations.submit', $evaluation) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label for="decision" class="block text-sm font-medium text-gray-700 mb-2">
                                Evaluation Decision <span class="text-red-500">*</span>
                            </label>
                            <select name="decision" id="decision" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select Decision</option>
                                <option value="approved" {{ old('decision') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="approved_with_minor_revisions" {{ old('decision') === 'approved_with_minor_revisions' ? 'selected' : '' }}>Approved with Minor Revisions</option>
                                <option value="approved_with_major_revisions" {{ old('decision') === 'approved_with_major_revisions' ? 'selected' : '' }}>Approved with Major Revisions</option>
                                <option value="rejected" {{ old('decision') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('decision')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="evaluation_report" class="block text-sm font-medium text-gray-700 mb-2">
                                Detailed Evaluation Report <span class="text-red-500">*</span>
                            </label>
                            <textarea name="evaluation_report" id="evaluation_report" rows="10"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Please provide a detailed evaluation of the thesis, including strengths, weaknesses, and specific recommendations..."
                                required>{{ old('evaluation_report') }}</textarea>
                            @error('evaluation_report')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Remarks
                            </label>
                            <textarea name="remarks" id="remarks" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Any additional comments or recommendations...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="report_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Evaluation Report (PDF)
                            </label>
                            <input type="file" name="report_file" id="report_file"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                accept=".pdf">
                            <p class="mt-1 text-sm text-gray-500">Upload a PDF file containing your detailed evaluation report (optional).</p>
                            @error('report_file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                        <p>Please ensure your evaluation is thorough and constructive. Your decision will significantly impact the scholar's academic progress.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('expert.evaluations.list') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Submit Evaluation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
