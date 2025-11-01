<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Progress Report for Scholar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholar Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar ID</label>
                                <p class="mt-1 text-sm text-gray-900">SCH-{{ str_pad($scholar->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->admission->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Information Notice -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> The scholar has not submitted a progress report for <strong>{{ $reportPeriod }}</strong>. 
                            You can submit RAC meeting details and remarks on their behalf. This option is only available for the current month and next month.
                        </p>
                    </div>

                    <!-- Supervisor Remark Form -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Supervisor Remark</h3>

                    <!-- Submission Form -->
                    <form action="{{ route('staff.progress_report.store.for_scholar', $scholar) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="report_period" :value="__('Report Period')" />
                            <select id="report_period" name="report_period" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100" disabled required>
                                <option value="{{ $reportPeriod }}" selected>{{ $reportPeriod }}</option>
                            </select>
                            <input type="hidden" name="report_period" value="{{ $reportPeriod }}">
                            <p class="mt-1 text-sm text-gray-500">
                                Report period: <strong>{{ $reportPeriod }}</strong>
                            </p>
                            <x-input-error :messages="$errors->get('report_period')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="action" :value="__('Remark')" />
                            <select id="action" name="action" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select Remark</option>
                                <option value="approve" {{ old('action') == 'approve' ? 'selected' : '' }}>Satisfied</option>
                                <option value="reject" {{ old('action') == 'reject' ? 'selected' : '' }}>Unsatisfied</option>
                            </select>
                            <x-input-error :messages="$errors->get('action')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="remarks" :value="__('Remarks')" />
                            <textarea id="remarks" name="remarks" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your remarks about this progress report..." required>{{ old('remarks') }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="rac_minutes_file" :value="__('RAC Minutes File')" />
                            <input type="file" id="rac_minutes_file" name="rac_minutes_file" accept=".pdf,.doc,.docx" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX (Max size: 5MB)</p>
                            <x-input-error :messages="$errors->get('rac_minutes_file')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="rac_meeting_date" :value="__('RAC Meeting Date')" />
                            <input type="date" id="rac_meeting_date" name="rac_meeting_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <x-input-error :messages="$errors->get('rac_meeting_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('staff.scholars.show', $scholar) }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Scholar Details
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

