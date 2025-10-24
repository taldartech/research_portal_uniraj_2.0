<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Scholar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Review Scholar: {{ $scholar->user->name }}</h3>

                    <!-- Scholar Information -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-md font-medium text-blue-900 mb-4">Scholar Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Name:</span>
                                <span class="text-gray-900">{{ $scholar->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Email:</span>
                                <span class="text-gray-900">{{ $scholar->user->email }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Scholar ID:</span>
                                <span class="text-gray-900">SCH-{{ str_pad($scholar->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Department:</span>
                                <span class="text-gray-900">{{ $scholar->admission->department->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Enrollment Number:</span>
                                <span class="text-gray-900">{{ $scholar->enrollment_number ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Research Area:</span>
                                <span class="text-gray-900">{{ $scholar->research_area ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Synopsis Information -->
                    @if($synopsis)
                        <div class="mb-8 p-4 bg-green-50 rounded-lg">
                            <h4 class="text-md font-medium text-green-900 mb-4">Synopsis Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Topic:</span>
                                    <span class="text-gray-900">{{ $synopsis->topic ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($synopsis->status === 'pending_supervisor_approval') bg-yellow-100 text-yellow-800
                                        @elseif($synopsis->status === 'supervisor_approved') bg-green-100 text-green-800
                                        @elseif($synopsis->status === 'supervisor_rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucwords(str_replace('_', ' ', $synopsis->status)) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Submitted:</span>
                                    <span class="text-gray-900">{{ $synopsis->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                @if($synopsis->synopsis_file)
                                    <div>
                                        <span class="font-medium text-gray-700">Synopsis File:</span>
                                        <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            Download Synopsis
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Action Form -->
                    <form method="POST" action="{{ route('staff.scholars.review.update', $scholar) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Action Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Action</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="action" value="verify_data" class="mr-2" required>
                                    <span class="text-sm text-gray-700">Verify Scholar Data Only</span>
                                </label>
                                @if($synopsis && $synopsis->status === 'pending_supervisor_approval')
                                    <label class="flex items-center">
                                        <input type="radio" name="action" value="approve_synopsis" class="mr-2">
                                        <span class="text-sm text-gray-700">Approve Synopsis</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="action" value="reject_synopsis" class="mr-2">
                                        <span class="text-sm text-gray-700">Reject Synopsis</span>
                                    </label>
                                @endif
                            </div>
                        </div>

                        <!-- Scholar Data Fields -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Scholar Data Verification</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="date_of_confirmation" class="block text-sm font-medium text-gray-700">Date of Confirmation</label>
                                    <input type="date" id="date_of_confirmation" name="date_of_confirmation"
                                           value="{{ old('date_of_confirmation', $scholar->date_of_confirmation) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="enrollment_number" class="block text-sm font-medium text-gray-700">Enrollment Number</label>
                                    <input type="text" id="enrollment_number" name="enrollment_number"
                                           value="{{ old('enrollment_number', $scholar->enrollment_number) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="research_area" class="block text-sm font-medium text-gray-700">Research Area</label>
                                    <input type="text" id="research_area" name="research_area"
                                           value="{{ old('research_area', $scholar->research_area) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Research Topic (for synopsis approval) -->
                        @if($synopsis && $synopsis->status === 'pending_supervisor_approval')
                            <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Research Topic</h4>
                                <div>
                                    <label for="research_topic" class="block text-sm font-medium text-gray-700">Research Topic</label>
                                    <textarea id="research_topic" name="research_topic" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                              placeholder="Enter the scholar's research topic...">{{ old('research_topic', $scholar->research_topic) }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">This will be set as the scholar's research topic after synopsis approval.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea id="remarks" name="remarks" rows="4" required
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Enter your remarks...">{{ old('remarks') }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
