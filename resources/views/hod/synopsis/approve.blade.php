<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Synopsis Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholar Information -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Name:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Enrollment Number:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->enrollment_number ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Department:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->admission->department->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Supervisor:</span>
                                <span class="text-gray-900">{{ $synopsis->rac->supervisor->user->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Synopsis Details -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Synopsis Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Submission Date:</span>
                                <span class="text-gray-900">{{ $synopsis->submission_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Status:</span>
                                <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $synopsis->status)) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Proposed Topic -->
                    <div class="mb-6 p-4 bg-green-50 rounded-lg">
                        <h3 class="text-lg font-medium text-green-900 mb-2">Proposed Topic</h3>
                        <div class="text-sm text-gray-700">{{ $synopsis->proposed_topic }}</div>
                    </div>

                    <!-- Research Objectives -->
                    @if($synopsis->research_objectives)
                        <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                            <h3 class="text-lg font-medium text-yellow-900 mb-2">Research Objectives</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $synopsis->research_objectives }}</div>
                        </div>
                    @endif

                    <!-- Methodology -->
                    @if($synopsis->methodology)
                        <div class="mb-6 p-4 bg-purple-50 rounded-lg">
                            <h3 class="text-lg font-medium text-purple-900 mb-2">Methodology</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $synopsis->methodology }}</div>
                        </div>
                    @endif

                    <!-- Expected Outcomes -->
                    @if($synopsis->expected_outcomes)
                        <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                            <h3 class="text-lg font-medium text-indigo-900 mb-2">Expected Outcomes</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $synopsis->expected_outcomes }}</div>
                        </div>
                    @endif

                    <!-- Supervisor Remarks -->
                    @if($synopsis->supervisor_remarks)
                        <div class="mb-6 p-4 bg-orange-50 rounded-lg">
                            <h3 class="text-lg font-medium text-orange-900 mb-2">Supervisor Remarks</h3>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $synopsis->supervisor_remarks }}</div>
                        </div>
                    @endif

                    <!-- RAC Minutes -->
                    @if($synopsis->rac_minutes_file)
                        <div class="mb-6 p-4 bg-red-50 rounded-lg">
                            <h3 class="text-lg font-medium text-red-900 mb-2">RAC Minutes</h3>
                            <a href="{{ Storage::url($synopsis->rac_minutes_file) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download RAC Minutes
                            </a>
                        </div>
                    @endif

                    <!-- DRC Minutes (if already uploaded) -->
                    @if($synopsis->drc_minutes_file)
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-900 mb-2">DRC Minutes (Previously Uploaded)</h3>
                            <a href="{{ Storage::url($synopsis->drc_minutes_file) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download DRC Minutes
                            </a>
                        </div>
                    @endif

                    <!-- Synopsis File Download -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Synopsis File</h3>
                        <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Synopsis File
                        </a>
                    </div>

                    <!-- Approval Form -->
                    <div class="mt-8 p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">HOD Decision</h3>
                        <form method="POST" action="{{ route('hod.synopsis.approve.store', $synopsis) }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <!-- Action Selection -->
                            <div class="mb-4">
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Decision</label>
                                <select id="action" name="action" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Decision</option>
                                    <option value="approve" {{ old('action') == 'approve' ? 'selected' : '' }}>Approve</option>
                                    <option value="reject" {{ old('action') == 'reject' ? 'selected' : '' }}>Reject</option>
                                </select>
                                @error('action')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="mb-4">
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                                <textarea id="remarks" name="remarks" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your remarks..." required>{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- DRC Minutes File (Required for Approval) -->
                            <div class="mb-6 p-4 bg-blue-50 border-2 border-blue-400 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h4 class="text-lg font-semibold text-blue-800">DRC Minutes File Upload</h4>
                                </div>
                                <div class="mb-3">
                                    <label for="drc_minutes_file" class="block text-sm font-medium text-blue-800 mb-2">
                                        Upload DRC Minutes (PDF) <span class="text-red-500">*</span>
                                    </label>
                                    <input id="drc_minutes_file"
                                           class="block w-full px-3 py-2 border-2 border-blue-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           type="file"
                                           name="drc_minutes_file"
                                           accept=".pdf"
                                           required />
                                </div>
                                <div class="bg-blue-100 p-3 rounded-md">
                                    <p class="text-sm text-blue-800">
                                        <strong>📋 Required for Approval:</strong> You must upload the DRC minutes file when approving this synopsis. This file will be forwarded to the Dean's Assistant along with your approval decision.
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        • File format: PDF only<br>
                                        • Maximum size: 2MB<br>
                                        • This file is mandatory for synopsis approval
                                    </p>
                                </div>
                                @error('drc_minutes_file')
                                    <div class="mt-3 p-3 bg-red-100 border border-red-300 rounded-md">
                                        <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                                    </div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('hod.synopsis.pending') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit Decision
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
