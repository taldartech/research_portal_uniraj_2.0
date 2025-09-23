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
                    <h3 class="text-lg font-medium text-gray-900">Synopsis Details</h3>
                    <p><strong>Scholar:</strong> {{ $synopsis->scholar->user->name }}</p>
                    <p><strong>Proposed Topic:</strong> {{ $synopsis->proposed_topic }}</p>
                    <p><strong>Submission Date:</strong> {{ $synopsis->submission_date->format('Y-m-d') }}</p>
                    <p>
                        <strong>Synopsis File:</strong>
                        <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View Synopsis</a>
                    </p>
                    <p><strong>Current Status:</strong> {{ ucfirst(str_replace('_', ' ', $synopsis->status)) }}</p>

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
                            <div class="mb-4">
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
