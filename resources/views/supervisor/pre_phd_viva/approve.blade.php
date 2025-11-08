<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Pre-PhD Viva Request') }}
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
                                <span class="text-gray-900">{{ $prePhdVivaRequest->scholar->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Scholar ID:</span>
                                <span class="text-gray-900">SCH-{{ str_pad($prePhdVivaRequest->scholar->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Department:</span>
                                <span class="text-gray-900">{{ $prePhdVivaRequest->scholar->admission->department->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Supervisor:</span>
                                <span class="text-gray-900">{{ $prePhdVivaRequest->supervisor->user->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Request Information -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Request Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Requested Date:</span>
                                <span class="text-gray-900">{{ $prePhdVivaRequest->requested_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Status:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-2">
                                    Pending RAC Approval
                                </span>
                            </div>
                        </div>
                        @if($prePhdVivaRequest->thesis_summary_file)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thesis Summary</label>
                            <a href="{{ Storage::url($prePhdVivaRequest->thesis_summary_file) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Thesis Summary
                            </a>
                        </div>
                        @endif

                        @php
                            $supportiveDocs = $prePhdVivaRequest->getSupportiveDocumentsArray();
                        @endphp
                        @if(!empty($supportiveDocs))
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supportive Documents</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($supportiveDocs as $index => $document)
                                    <a href="{{ Storage::url($document) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Document {{ $index + 1 }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($prePhdVivaRequest->request_remarks)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Request Remarks</label>
                            <p class="text-sm text-gray-900 whitespace-pre-wrap bg-white p-3 rounded border border-gray-200">{{ $prePhdVivaRequest->request_remarks }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Important Notice -->
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-yellow-900 mb-2">Important Guidelines</h4>
                        <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                            <li>Pre-PhD Viva date must be at least <strong>1 month</strong> from today</li>
                            <li>Thesis submission deadline will be automatically set to <strong>6 months</strong> from the viva date</li>
                            <li>Scholar must submit thesis within the 6-month window after the viva date</li>
                        </ul>
                    </div>

                    <!-- RAC Approval Form -->
                    <div class="mt-8 p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">RAC Approval</h3>
                        <form method="POST" action="{{ route('staff.pre_phd_viva.approve.store', $prePhdVivaRequest) }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <!-- Action Selection -->
                            <div class="mb-4">
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Action <span class="text-red-500">*</span></label>
                                <select id="action" name="action" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Action</option>
                                    <option value="approve" {{ old('action') == 'approve' ? 'selected' : '' }}>Approve</option>
                                    <option value="reject" {{ old('action') == 'reject' ? 'selected' : '' }}>Reject</option>
                                </select>
                                @error('action')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Viva Date (shown only when approve is selected) -->
                            <div id="viva_date_section" class="mb-4 hidden">
                                <label for="viva_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pre-PhD Viva Date <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500">(Must be at least 1 month from today)</span>
                                </label>
                                <input type="date"
                                       id="viva_date"
                                       name="viva_date"
                                       min="{{ now()->addMonth()->format('Y-m-d') }}"
                                       value="{{ old('viva_date') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="mt-1 text-xs text-gray-500">Minimum date: {{ now()->addMonth()->format('d/m/Y') }}</p>
                                @error('viva_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- RAC Minutes File (shown only when approve is selected) -->
                            <div id="rac_minutes_section" class="mb-4 hidden">
                                <label for="rac_minutes_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    RAC Minutes File <span class="text-red-500">*</span>
                                </label>
                                <input type="file"
                                       id="rac_minutes_file"
                                       name="rac_minutes_file"
                                       accept=".pdf,.doc,.docx"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX (Max size: 5MB)</p>
                                @error('rac_minutes_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- RAC Remarks -->
                            <div class="mb-4">
                                <label for="rac_remarks" class="block text-sm font-medium text-gray-700 mb-2">RAC Remarks <span class="text-red-500">*</span></label>
                                <textarea id="rac_remarks"
                                          name="rac_remarks"
                                          rows="4"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Enter your remarks..."
                                          required>{{ old('rac_remarks') }}</textarea>
                                @error('rac_remarks')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('staff.pre_phd_viva.pending') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actionSelect = document.getElementById('action');
        const vivaDateSection = document.getElementById('viva_date_section');
        const vivaDateInput = document.getElementById('viva_date');

        const racMinutesSection = document.getElementById('rac_minutes_section');
        const racMinutesFile = document.getElementById('rac_minutes_file');

        if (actionSelect && vivaDateSection && vivaDateInput && racMinutesSection && racMinutesFile) {
            actionSelect.addEventListener('change', function() {
                if (this.value === 'approve') {
                    vivaDateSection.classList.remove('hidden');
                    vivaDateInput.setAttribute('required', 'required');
                    racMinutesSection.classList.remove('hidden');
                    racMinutesFile.setAttribute('required', 'required');
                } else {
                    vivaDateSection.classList.add('hidden');
                    vivaDateInput.removeAttribute('required');
                    vivaDateInput.value = '';
                    racMinutesSection.classList.add('hidden');
                    racMinutesFile.removeAttribute('required');
                    racMinutesFile.value = '';
                }
            });

            // Trigger on page load if old value exists
            if (actionSelect.value === 'approve') {
                vivaDateSection.classList.remove('hidden');
                vivaDateInput.setAttribute('required', 'required');
                racMinutesSection.classList.remove('hidden');
                racMinutesFile.setAttribute('required', 'required');
            }
        }
    });
</script>

