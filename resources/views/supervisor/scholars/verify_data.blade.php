<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verify Scholar Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Verify Data for {{ $scholar->user->name }}</h3>

                    <form method="POST" action="{{ route('staff.scholars.verify_data.update', $scholar) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Personal Information Verification -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Personal Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->date_of_birth ? $scholar->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->contact_number ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information Verification -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Academic Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Research Area</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->research_area ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Post Graduate Degree</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->post_graduate_degree ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Post Graduate University</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->post_graduate_university ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Post Graduate Percentage</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->post_graduate_percentage ?? 'Not provided' }}%</p>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Status -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Verification Status</h4>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="radio" id="verified" name="verification_status" value="verified"
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <label for="verified" class="ml-2 block text-sm font-medium text-gray-900">
                                        <span class="text-green-600">✓ Verified</span> - All information is accurate and complete
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="needs_correction" name="verification_status" value="needs_correction"
                                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300">
                                    <label for="needs_correction" class="ml-2 block text-sm font-medium text-gray-900">
                                        <span class="text-yellow-600">⚠ Needs Correction</span> - Some information requires updates
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="rejected" name="verification_status" value="rejected"
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                    <label for="rejected" class="ml-2 block text-sm font-medium text-gray-900">
                                        <span class="text-red-600">✗ Rejected</span> - Information is incomplete or inaccurate
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-8">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                Remarks <span class="text-red-500">*</span>
                            </label>
                            <textarea id="remarks" name="remarks" rows="4" required
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Please provide your verification remarks...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('staff.scholars.show', $scholar) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Submit Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
