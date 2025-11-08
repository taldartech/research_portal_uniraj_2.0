<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Pre-PhD Viva') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Pre-PhD Viva Request</h3>
                        <p class="text-gray-600">
                            Submit a request for Pre-PhD Viva. After approval, RAC will schedule your viva date (at least 1 month from today). 
                            You must submit your thesis within 6 months from the viva date.
                        </p>
                    </div>

                    <form action="{{ route('scholar.pre_phd_viva.request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Scholar Information -->
                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <h4 class="text-md font-semibold text-blue-900 mb-3">Scholar Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Name:</span>
                                    <span class="text-gray-900 ml-2">{{ $scholar->user->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Scholar ID:</span>
                                    <span class="text-gray-900 ml-2">SCH-{{ str_pad($scholar->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Supervisor:</span>
                                    <span class="text-gray-900 ml-2">{{ $scholar->currentSupervisor->user->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Department:</span>
                                    <span class="text-gray-900 ml-2">{{ $scholar->admission->department->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Thesis Summary File -->
                        <div>
                            <label for="thesis_summary_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Thesis Summary File <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   id="thesis_summary_file" 
                                   name="thesis_summary_file" 
                                   accept=".pdf,.doc,.docx"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Upload your thesis summary document. Accepted formats: PDF, DOC, DOCX (Max size: 10MB)</p>
                            @error('thesis_summary_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Supportive Documents -->
                        <div>
                            <label for="supportive_documents" class="block text-sm font-medium text-gray-700 mb-2">
                                Supportive Documents <span class="text-red-500">*</span>
                                <span class="text-xs font-normal text-gray-500">(Multiple files allowed - NOC, Pre-PhD Viva Certificate, Fees Receipt, etc.)</span>
                            </label>
                            <input type="file" 
                                   id="supportive_documents" 
                                   name="supportive_documents[]" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   multiple
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Upload supporting documents (NOC, Pre-PhD Viva Certificate, Fees Receipt, Publications, Certificates, etc.).</p>
                            <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max size per file: 10MB)</p>
                            <p class="mt-1 text-xs text-gray-500 font-medium">Minimum 1 file required. You can select multiple files at once (Ctrl+Click or Cmd+Click to select multiple).</p>
                            <div id="selected-files" class="mt-2 space-y-1"></div>
                            @error('supportive_documents')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('supportive_documents.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Request Remarks -->
                        <div>
                            <label for="request_remarks" class="block text-sm font-medium text-gray-700 mb-2">Request Remarks (Optional)</label>
                            <textarea id="request_remarks" name="request_remarks" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Add any remarks or notes about your Pre-PhD Viva request...">{{ old('request_remarks') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">You can provide any additional information or context for your request.</p>
                            @error('request_remarks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Important Notice -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-yellow-900 mb-2">Important Information</h4>
                            <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                                <li>RAC will review your request and set a Pre-PhD Viva date (at least 1 month from approval)</li>
                                <li>You must submit your thesis within 6 months from the Pre-PhD Viva date</li>
                                <li>If you don't submit within 6 months, you'll need to submit a new Pre-PhD Viva request</li>
                                <li>You cannot submit your thesis until the Pre-PhD Viva is completed</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('scholar.dashboard') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supportiveDocumentsInput = document.getElementById('supportive_documents');
        const selectedFilesDiv = document.getElementById('selected-files');

        if (supportiveDocumentsInput && selectedFilesDiv) {
            supportiveDocumentsInput.addEventListener('change', function() {
                const files = this.files;
                selectedFilesDiv.innerHTML = '';

                if (files.length > 0) {
                    const fileList = document.createElement('div');
                    fileList.className = 'bg-blue-50 border border-blue-200 rounded-md p-3';
                    fileList.innerHTML = '<p class="text-xs font-medium text-blue-900 mb-2">Selected Files (' + files.length + '):</p>';
                    
                    const fileItems = document.createElement('ul');
                    fileItems.className = 'space-y-1 text-xs text-blue-800';
                    
                    for (let i = 0; i < files.length; i++) {
                        const fileItem = document.createElement('li');
                        fileItem.className = 'flex items-center';
                        fileItem.innerHTML = '<svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' + 
                                            '<span class="flex-1">' + files[i].name + '</span>' +
                                            '<span class="text-blue-600">(' + (files[i].size / 1024 / 1024).toFixed(2) + ' MB)</span>';
                        fileItems.appendChild(fileItem);
                    }
                    
                    fileList.appendChild(fileItems);
                    selectedFilesDiv.appendChild(fileList);
                }
            });
        }
    });
</script>

