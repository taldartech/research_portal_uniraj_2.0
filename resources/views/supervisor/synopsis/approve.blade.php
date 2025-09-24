<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approve Synopsis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                <span class="font-medium text-gray-700">Current Research Topic:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->research_topic_title ?? 'Not assigned' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">NET/SLET/CSIR/GATE:</span>
                                <span class="text-gray-900">{{ $synopsis->scholar->net_slet_csir_gate_exam ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">MPAT Year:</span>
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
                                <span class="font-medium text-gray-700">Proposed Topic:</span>
                                <span class="text-gray-900">{{ $synopsis->proposed_topic }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Submission Date:</span>
                                <span class="text-gray-900">{{ $synopsis->submission_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Current Status:</span>
                                <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $synopsis->status)) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Synopsis File:</span>
                                <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View Synopsis</a>
                            </div>
                        </div>
                    </div>

                    <!-- Research Topic Submission -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-800 mb-3">Research Topic Assignment</h3>
                        <div class="mb-4">
                            <x-input-label for="research_topic" :value="__('Research Topic for Scholar')" />
                            <x-textarea-input id="research_topic" name="research_topic" class="block mt-1 w-full" rows="3" placeholder="Enter the research topic that will be assigned to the scholar for their research work...">{{ old('research_topic', $synopsis->scholar->research_topic_title ?? '') }}</x-textarea-input>
                            <p class="text-sm text-blue-600 mt-1">This topic will be used for the scholar's ongoing research work and can be referenced later.</p>
                            <x-input-error :messages="$errors->get('research_topic')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <form method="POST" action="{{ route('staff.synopsis.approve.update', $synopsis) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Action Selection -->
                            <div class="mb-4">
                                <x-input-label for="action" :value="__('Action')" />
                                <x-select-input id="action" name="action" class="block mt-1 w-full" required>
                                    <option value="">Select Action</option>
                                    <option value="approve" {{ old('action') == 'approve' ? 'selected' : '' }}>Approve</option>
                                    <option value="reject" {{ old('action') == 'reject' ? 'selected' : '' }}>Reject</option>
                                </x-select-input>
                                <x-input-error :messages="$errors->get('action')" class="mt-2" />
                            </div>

                            <!-- Remarks (Required) -->
                            <div class="mb-4">
                                <x-input-label for="remarks" :value="__('Remarks')" />
                                <x-textarea-input id="remarks" name="remarks" class="block mt-1 w-full" rows="5" required>{{ old('remarks') }}</x-textarea-input>
                                <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                            </div>

                            <!-- RAC Minutes File (Required for Approval) -->
                            <div id="rac-minutes-field" class="mb-4">
                                <x-input-label for="rac_minutes_file" :value="__('RAC Minutes File (PDF)')" />
                                <input id="rac_minutes_file" class="block mt-1 w-full" type="file" name="rac_minutes_file" accept=".pdf" />
                                <p class="text-sm text-gray-600 mt-1">Required when approving the synopsis</p>
                                <x-input-error :messages="$errors->get('rac_minutes_file')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Submit Decision') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
