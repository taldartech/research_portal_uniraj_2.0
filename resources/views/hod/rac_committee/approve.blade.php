<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approve RAC Committee Submission') }}
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
                                <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->scholar->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar ID</label>
                                <p class="mt-1 text-sm text-gray-900">SCH-{{ str_pad($racCommitteeSubmission->scholar->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->scholar->admission->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Supervisor Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Supervisor Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->supervisor->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supervisor Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->supervisor->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- RAC Committee Members -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">RAC Committee Members</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Member 1</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->member1_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Member 2</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->member2_name }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Submitted At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $racCommitteeSubmission->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Approval Form -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">HOD Decision</h3>
                        <form action="{{ route('hod.rac_committee.approve.store', $racCommitteeSubmission) }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700">Decision <span class="text-red-500">*</span></label>
                                <select id="action" name="action" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Decision</option>
                                    <option value="approve">Approved</option>
                                    <option value="reject">Not - Approved</option>
                                </select>
                                <x-input-error :messages="$errors->get('action')" class="mt-2" />
                            </div>

                            <div>
                                <label for="drc_date" class="block text-sm font-medium text-gray-700">
                                    DRC Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="drc_date" name="drc_date"
                                       value="{{ old('drc_date') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">Select the date when the DRC meeting was held</p>
                                <x-input-error :messages="$errors->get('drc_date')" class="mt-2" />
                            </div>

                            <div>
                                <label for="hod_remarks" class="block text-sm font-medium text-gray-700">
                                    Remarks <span class="text-red-500">*</span>
                                </label>
                                <textarea id="hod_remarks" name="hod_remarks" rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Enter your remarks about this RAC committee submission..."
                                          required>{{ old('hod_remarks') }}</textarea>
                                <x-input-error :messages="$errors->get('hod_remarks')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <a href="{{ route('hod.rac_committee.pending') }}"
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Back to Pending
                                </a>

                                <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
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

