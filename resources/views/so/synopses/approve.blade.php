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
                                <span class="font-medium text-gray-700">Email:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->user->email }}</span>
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
                                <span class="text-gray-900">{{ $synopsis->rac->supervisor->user->name ?? ($synopsis->scholar->currentSupervisor->supervisor->user->name ?? 'N/A') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Admission Date:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->admission->admission_date ? $synopsis->scholar->admission->admission_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-6 p-4 bg-green-50 rounded-lg">
                        <h3 class="text-lg font-medium text-green-900 mb-2">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Date of Birth:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->date_of_birth ? $synopsis->scholar->date_of_birth->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Contact Number:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->contact_number ?? 'N/A' }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="font-medium text-gray-700">Address:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                        <h3 class="text-lg font-medium text-yellow-900 mb-2">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Program:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->program ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Research Area:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->research_area ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">NET/SLET/CSIR/GATE:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->net_slet_csir_gate_exam ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">PhD admission Information Year:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->mpat_year ?? 'N/A' }}</span>
                            </div>
                        </div>

                        @if($synopsis->scholar->academic_qualifications && count($synopsis->scholar->academic_qualifications) > 0)
                            <div class="mt-4">
                                <h4 class="font-medium text-yellow-800 mb-2">Academic Qualifications:</h4>
                                <div class="space-y-2">
                                    @foreach($synopsis->scholar->academic_qualifications as $qualification)
                                        <div class="bg-white p-3 rounded border">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                                                <div><strong>Degree:</strong> {{ $qualification['degree'] ?? 'N/A' }}</div>
                                                <div><strong>University:</strong> {{ $qualification['university'] ?? 'N/A' }}</div>
                                                <div><strong>Year:</strong> {{ $qualification['year'] ?? 'N/A' }}</div>
                                                <div><strong>Percentage:</strong> {{ $qualification['percentage'] ?? 'N/A' }}%</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Document Information -->
                    @if($synopsis->scholar->registration_documents && count($synopsis->scholar->registration_documents) > 0)
                        <div class="mb-6 p-4 bg-purple-50 rounded-lg">
                            <h3 class="text-lg font-medium text-purple-900 mb-2">Uploaded Documents</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($synopsis->scholar->registration_documents as $document)
                                    <div class="bg-white p-3 rounded border">
                                        <div class="flex flex-col space-y-2">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900 break-words">{{ $document['filename'] ?? 'Document' }}</div>
                                                <div class="text-xs text-gray-500">{{ $document['uploaded_at'] ? \Carbon\Carbon::parse($document['uploaded_at'])->format('M d, Y H:i') : 'N/A' }}</div>
                                            </div>
                                            <div class="flex justify-end">
                                                <a href="{{ Storage::url($document['path']) }}" target="_blank"
                                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

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

                    <!-- Previous Comments & Approvals Section -->
                    <x-synopsis-approval-history :synopsis="$synopsis" />

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
                            <h4 class="text-lg font-medium text-red-900 mb-2">DRC Date: {{ $synopsis->drc_date ? $synopsis->drc_date : 'N/A' }}</h4>
                            <h4 class="text-lg font-medium text-red-900 mb-2">DRC file: <a target="_blank" href="{{ $synopsis->drc_minutes_file }}" class="text-blue-600 hover:text-blue-900">View</a></h4>
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

                    <!-- SO Remark Form -->
                    <div class="mt-8 p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">SO Remark</h3>
                        <form method="POST" action="{{ route('so.synopses.process', $synopsis) }}">
                            @csrf
                            @method('POST')

                            <!-- Action Selection -->
                            <div class="mb-4">
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Recommendation <span class="text-red-500">*</span></label>
                                <select id="action" name="action" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Recommendation</option>
                                    <option value="approve" {{ old('action') == 'approve' ? 'selected' : '' }}>Approved</option>
                                    <option value="reject" {{ old('action') == 'reject' ? 'selected' : '' }}>Not - Approved</option>
                                </select>
                                @error('action')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="mb-6">
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks <span class="text-red-500">*</span></label>
                                <textarea id="remarks" name="remarks" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your comments..." required>{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reassignment Fields (shown when reject is selected) -->
                            <x-reassignment-fields :availableRoles="$availableRoles ?? []" />

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('so.synopses.pending') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit Recommendation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
