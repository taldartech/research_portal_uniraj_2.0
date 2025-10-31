<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit RAC Committee Members') }}
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

                    @if($existingSubmission)
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <strong>Note:</strong> You have a pending submission for this scholar. Updating will replace the existing submission.
                            </p>
                            @if($existingSubmission->status === 'approved')
                                <p class="text-sm text-green-800 mt-2">
                                    <strong>Current Status:</strong> Approved on {{ $existingSubmission->approved_at->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    <strong>Current Members:</strong> {{ $existingSubmission->member1_name }} & {{ $existingSubmission->member2_name }}
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    <strong>DRC Date:</strong> {{ $existingSubmission->drc_date ? $existingSubmission->drc_date->format('M d, Y') : 'N/A' }}
                                </p>
                            @elseif($existingSubmission->status === 'pending_hod_approval')
                                <p class="text-sm text-yellow-800 mt-2">
                                    <strong>Current Status:</strong> Pending HOD Approval
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    <strong>Current Members:</strong> {{ $existingSubmission->member1_name }} & {{ $existingSubmission->member2_name }}
                                </p>
                            @elseif($existingSubmission->status === 'rejected')
                                <p class="text-sm text-red-800 mt-2">
                                    <strong>Current Status:</strong> Rejected
                                </p>
                                @if($existingSubmission->hod_remarks)
                                    <p class="text-sm text-gray-700 mt-1">
                                        <strong>HOD Remarks:</strong> {{ $existingSubmission->hod_remarks }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    @endif

                    <!-- Submission Form -->
                    <form action="{{ route('staff.rac_committee.store', $scholar) }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="member1_name" class="block text-sm font-medium text-gray-700">
                                RAC Committee Member 1 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="member1_name" name="member1_name"
                                   value="{{ old('member1_name', $existingSubmission->member1_name ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Enter full name" required>
                            <x-input-error :messages="$errors->get('member1_name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="member2_name" class="block text-sm font-medium text-gray-700">
                                RAC Committee Member 2 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="member2_name" name="member2_name"
                                   value="{{ old('member2_name', $existingSubmission->member2_name ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Enter full name" required>
                            <x-input-error :messages="$errors->get('member2_name')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('staff.scholars.show', $scholar) }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                @if($existingSubmission && $existingSubmission->status === 'pending_hod_approval')
                                    Update Submission
                                @else
                                    Submit for Approval
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

