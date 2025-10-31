<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approve Progress Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholar Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->scholar->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar ID</label>
                                <p class="mt-1 text-sm text-gray-900">SCH-{{ str_pad($progressReport->scholar->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->scholar->admission->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Report Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Report Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Report Period</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->report_period ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submission Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->submission_date ? $progressReport->submission_date->format('Y-m-d H:i:s') : 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending Supervisor Approval
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Report Period (Text)</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $progressReport->report_period ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Report Content -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Content</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Progress Report File</label>
                                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                    @if($progressReport->report_file)
                                        <div class="flex items-center space-x-4">
                                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Progress Report PDF</p>
                                                <p class="text-xs text-gray-500">Uploaded on {{ $progressReport->submission_date ? $progressReport->submission_date->format('M d, Y') : 'Unknown date' }}</p>
                                            </div>
                                            <a href="{{ Storage::url($progressReport->report_file) }}" target="_blank" class="ml-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                View PDF
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">No report file uploaded</p>
                                    @endif
                                </div>
                            </div>

                            @if($progressReport->feedback_da)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">DA Feedback</label>
                                <div class="mt-1 p-3 bg-blue-50 rounded-md">
                                    <p class="text-sm text-gray-900">{{ $progressReport->feedback_da }}</p>
                                </div>
                            </div>
                            @endif

                            @if($progressReport->special_remark)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Special Remarks</label>
                                <div class="mt-1 p-3 bg-yellow-50 rounded-md">
                                    <p class="text-sm text-gray-900">{{ $progressReport->special_remark }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Approval Form -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Supervisor Remark</h3>
                        <form action="{{ route('staff.progress_reports.approve.store', $progressReport) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700">Remark</label>
                                <select id="action" name="action" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Remark</option>
                                    <option value="approve">Satisfied</option>
                                    <option value="reject">Unsatisfied</option>
                                </select>
                                <x-input-error :messages="$errors->get('action')" class="mt-2" />
                            </div>

                            <div>
                                <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                                <textarea id="remarks" name="remarks" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your remarks about this progress report..." required></textarea>
                                <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                            </div>

                            <div>
                                <label for="rac_minutes_file" class="block text-sm font-medium text-gray-700">RAC Minutes File <span class="text-red-500">*</span></label>
                                <input type="file" id="rac_minutes_file" name="rac_minutes_file" accept=".pdf,.doc,.docx" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX (Max size: 5MB)</p>
                                <x-input-error :messages="$errors->get('rac_minutes_file')" class="mt-2" />
                            </div>

                            <div>
                                <label for="rac_meeting_date" class="block text-sm font-medium text-gray-700">RAC Meeting Date <span class="text-red-500">*</span></label>
                                <input type="date" id="rac_meeting_date" name="rac_meeting_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <x-input-error :messages="$errors->get('rac_meeting_date')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <a href="{{ route('staff.progress_reports.pending') }}"
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Back to Pending Reports
                                </a>

                                <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Submit Remark
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
