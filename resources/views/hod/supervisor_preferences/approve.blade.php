<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approve Supervisor Preferences') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Scholar Information -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Name:</strong> {{ $scholar->user->name }}</p>
                                <p><strong>Scholar ID:</strong> SCH-{{ str_pad($scholar->id, 6, '0', STR_PAD_LEFT) }}</p>
                                <p><strong>Email:</strong> {{ $scholar->user->email }}</p>
                            </div>
                            <div>
                                <p><strong>Department:</strong> {{ $scholar->admission->department->name }}</p>
                                <p><strong>Research Area:</strong> {{ $scholar->research_area ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Supervisor Preferences -->
                    <form method="POST" action="{{ route('hod.supervisor_preferences.approve.store', $scholar->id) }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Preferred Supervisor</h3>
                            <div class="space-y-4">
                                @foreach($preferences as $preference)
                                    <div class="border rounded-lg p-4 {{ $loop->first ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                        <label class="flex items-start">
                                            <input type="radio"
                                                   name="selected_preference_id"
                                                   value="{{ $preference->id }}"
                                                   class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                   {{ $loop->first ? 'checked' : '' }}>
                                            <div class="ml-3 flex-1">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">
                                                            {{ $preference->preference_order }}{{ $preference->preference_order == 1 ? 'st' : ($preference->preference_order == 2 ? 'nd' : 'rd') }} Preference
                                                        </h4>
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Supervisor:</strong> {{ $preference->supervisor->user->name }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Designation:</strong> {{ $preference->supervisor->designation ?? 'Not specified' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Specialization:</strong> {{ $preference->supervisor->research_specialization }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Current Scholars:</strong> {{ $preference->supervisor->assignedScholars->count() }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Capacity:</strong> {{ $preference->supervisor->getScholarLimit() }}
                                                        </p>
                                                    </div>
                                                    @if($preference->preference_order == 1)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Scholar's 1st Choice
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Justification:</strong>
                                                    </p>
                                                    <p class="text-sm text-gray-700 bg-white p-2 rounded border">
                                                        {{ $preference->justification }}
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- HOD Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                HOD Remarks (Optional)
                            </label>
                            <textarea id="remarks"
                                      name="remarks"
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Add any remarks about the supervisor selection...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('hod.supervisor_preferences.pending') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Approve Selection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
