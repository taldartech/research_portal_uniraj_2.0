<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Review Thesis Submission - Assistant Registrar Approval') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Thesis Details</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p><strong>Scholar:</strong> {{ $thesis->scholar->user->name ?? 'N/A' }}</p>
                            <p><strong>Title:</strong> {{ $thesis->title }}</p>
                            <p><strong>Supervisor:</strong>
                                @php
                                    $supervisorName = 'N/A';
                                    if ($thesis->supervisor && $thesis->supervisor->user) {
                                        $supervisorName = $thesis->supervisor->user->name;
                                    } elseif ($thesis->scholar->currentSupervisor && $thesis->scholar->currentSupervisor->supervisor && $thesis->scholar->currentSupervisor->supervisor->user) {
                                        $supervisorName = $thesis->scholar->currentSupervisor->supervisor->user->name;
                                    }
                                @endphp
                                {{ $supervisorName }}
                            </p>
                            <p><strong>Department:</strong> {{ $thesis->scholar->admission->department->name ?? 'N/A' }}</p>
                            <p><strong>Submission Date:</strong> {{ $thesis->submission_date ? $thesis->submission_date->format('M d, Y') : 'N/A' }}</p>
                            <p class="mt-2">
                                <strong>Thesis File:</strong>
                                <a href="{{ Storage::url($thesis->file_path ?? $thesis->thesis_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Download Thesis
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($thesis->supervisor_remarks)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Supervisor Remarks</h4>
                            <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $thesis->supervisor_remarks }}</p>
                            </div>
                        </div>
                    @endif

                    @if($thesis->hod_remarks)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">HOD Remarks</h4>
                            <div class="bg-green-50 dark:bg-green-900 p-3 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $thesis->hod_remarks }}</p>
                                @if($thesis->drc_date)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-2"><strong>DRC Date:</strong> {{ $thesis->drc_date->format('M d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($thesis->da_remarks)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">DA Remarks</h4>
                            <div class="bg-purple-50 dark:bg-purple-900 p-3 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $thesis->da_remarks }}</p>
                                @if($thesis->drc_minutes_file)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                        <strong>DRC Minutes File:</strong>
                                        <a href="{{ $thesis->drc_minutes_file }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            View DRC Minutes
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($thesis->so_remarks)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">SO Remarks</h4>
                            <div class="bg-indigo-50 dark:bg-indigo-900 p-3 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $thesis->so_remarks }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- RAC Minutes File -->
                    @if($thesis->rac_minutes_file)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">RAC Minutes File</h4>
                            <div class="bg-yellow-50 dark:bg-yellow-900 p-3 rounded-lg">
                                <a href="{{ Storage::url($thesis->rac_minutes_file) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download RAC Minutes
                                </a>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ar.thesis.approve.store', $thesis) }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="action" :value="__('Action')" />
                            <select id="action" name="action" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select Action</option>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                            </select>
                            <x-input-error :messages="$errors->get('action')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="remarks" :value="__('Remarks')" />
                            <textarea id="remarks" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="remarks" rows="4" required>{{ old('remarks') }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('ar.thesis.pending') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('Submit Remark') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

