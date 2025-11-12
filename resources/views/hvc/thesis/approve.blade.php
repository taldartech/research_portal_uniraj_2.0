<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approve Thesis Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Thesis: {{ $thesis->title }}</h3>

                    <!-- Thesis Information -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Thesis Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $thesis->scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $thesis->scholar->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supervisor</label>
                                <p class="mt-1 text-sm text-gray-900">
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
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $thesis->scholar->admission->department->name ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submission Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $thesis->submission_date->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending HVC Approval
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Abstract -->
                    @if($thesis->abstract)
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Abstract</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-900">{{ $thesis->abstract }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Approval History -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Approval History</h4>
                        <div class="space-y-3">
                            @if($thesis->supervisor_approved_at)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">Supervisor Approval</p>
                                        <p class="text-xs text-green-600">{{ $thesis->supervisorApprover->name ?? 'N/A' }} - {{ $thesis->supervisor_approved_at->format('M d, Y H:i') }}</p>
                                        @if($thesis->supervisor_remarks)
                                            <p class="text-xs text-gray-600 mt-1">{{ $thesis->supervisor_remarks }}</p>
                                        @endif
                                    </div>
                                    <span class="text-green-600">✓</span>
                                </div>
                            @endif

                            @if($thesis->hod_approved_at)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">HOD Approval</p>
                                        <p class="text-xs text-green-600">{{ $thesis->hodApprover->name ?? 'N/A' }} - {{ $thesis->hod_approved_at->format('M d, Y H:i') }}</p>
                                        @if($thesis->hod_remarks)
                                            <p class="text-xs text-gray-600 mt-1">{{ $thesis->hod_remarks }}</p>
                                        @endif
                                    </div>
                                    <span class="text-green-600">✓</span>
                                </div>
                            @endif

                            @if($thesis->da_approved_at)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">DA Approval</p>
                                        <p class="text-xs text-green-600">{{ $thesis->daApprover->name ?? 'N/A' }} - {{ $thesis->da_approved_at->format('M d, Y H:i') }}</p>
                                        @if($thesis->da_remarks)
                                            <p class="text-xs text-gray-600 mt-1">{{ $thesis->da_remarks }}</p>
                                        @endif
                                    </div>
                                    <span class="text-green-600">✓</span>
                                </div>
                            @endif

                            @if($thesis->so_approved_at)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">SO Approval</p>
                                        <p class="text-xs text-green-600">{{ $thesis->soApprover->name ?? 'N/A' }} - {{ $thesis->so_approved_at->format('M d, Y H:i') }}</p>
                                        @if($thesis->so_remarks)
                                            <p class="text-xs text-gray-600 mt-1">{{ $thesis->so_remarks }}</p>
                                        @endif
                                    </div>
                                    <span class="text-green-600">✓</span>
                                </div>
                            @endif

                            @if($thesis->ar_approved_at)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">AR Approval</p>
                                        <p class="text-xs text-green-600">{{ $thesis->arApprover->name ?? 'N/A' }} - {{ $thesis->ar_approved_at->format('M d, Y H:i') }}</p>
                                        @if($thesis->ar_remarks)
                                            <p class="text-xs text-gray-600 mt-1">{{ $thesis->ar_remarks }}</p>
                                        @endif
                                    </div>
                                    <span class="text-green-600">✓</span>
                                </div>
                            @endif

                            @if($thesis->dr_approved_at)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">DR Approval</p>
                                        <p class="text-xs text-green-600">{{ $thesis->drApprover->name ?? 'N/A' }} - {{ $thesis->dr_approved_at->format('M d, Y H:i') }}</p>
                                        @if($thesis->dr_remarks)
                                            <p class="text-xs text-gray-600 mt-1">{{ $thesis->dr_remarks }}</p>
                                        @endif
                                    </div>
                                    <span class="text-green-600">✓</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Approval Form -->
                    <form method="POST" action="{{ route('hvc.thesis.approve.store', $thesis) }}">
                        @csrf
                        @method('POST')

                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">HVC Remark</h4>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="radio" id="approve" name="action" value="approve"
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <label for="approve" class="ml-2 block text-sm font-medium text-gray-900">
                                        <span class="text-green-600">✓ Approve</span> - Thesis meets all requirements
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="reject" name="action" value="reject"
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                    <label for="reject" class="ml-2 block text-sm font-medium text-gray-900">
                                        <span class="text-red-600">✗ Reject</span> - Thesis does not meet requirements
                                    </label>
                                </div>
                            </div>
                            @error('action')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                HVC Remarks <span class="text-red-500">*</span>
                            </label>
                            <textarea id="remarks" name="remarks" rows="4" required
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Please provide your detailed remarks...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('hvc.thesis.pending_approval') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>

                            <div class="flex space-x-4">
                                @if($thesis->file_path)
                                    <a href="{{ Storage::url($thesis->file_path) }}" target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Download Thesis
                                    </a>
                                @endif

                                <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Submit Remark
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
