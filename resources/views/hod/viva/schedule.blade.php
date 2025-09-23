<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Schedule Viva Examination') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Schedule Viva Examination</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Schedule a viva examination for the approved thesis.</p>
                    </div>

                    <!-- Thesis Information -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Thesis Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Scholar Name</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesis->scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thesis Title</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesis->title }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supervisor</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesis->supervisor->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesis->scholar->admission->department->name }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('hod.thesis.schedule_viva.store', $thesis) }}">
                        @csrf

                        <!-- Examination Details -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Examination Details</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="examination_type" :value="__('Examination Type *')" />
                                    <select id="examination_type" name="examination_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Select Type</option>
                                        <option value="offline" {{ old('examination_type') == 'offline' ? 'selected' : '' }}>Offline</option>
                                        <option value="online" {{ old('examination_type') == 'online' ? 'selected' : '' }}>Online</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('examination_type')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="examination_date" :value="__('Examination Date *')" />
                                    <x-text-input id="examination_date" class="block mt-1 w-full" type="date" name="examination_date" :value="old('examination_date')" required />
                                    <x-input-error :messages="$errors->get('examination_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="examination_time" :value="__('Examination Time *')" />
                                    <x-text-input id="examination_time" class="block mt-1 w-full" type="time" name="examination_time" :value="old('examination_time')" required />
                                    <x-input-error :messages="$errors->get('examination_time')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="venue" :value="__('Venue *')" />
                                    <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue')" placeholder="Examination venue or online platform" required />
                                    <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Examiner Assignment -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Examiner Assignment</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="external_examiner_id" :value="__('External Examiner *')" />
                                    <select id="external_examiner_id" name="external_examiner_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Select External Examiner</option>
                                        @foreach($examiners as $examiner)
                                            <option value="{{ $examiner->id }}" {{ old('external_examiner_id') == $examiner->id ? 'selected' : '' }}>
                                                {{ $examiner->name }} ({{ $examiner->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('external_examiner_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="internal_examiner_id" :value="__('Internal Examiner (Optional)')" />
                                    <select id="internal_examiner_id" name="internal_examiner_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Internal Examiner (Optional)</option>
                                        @foreach($examiners as $examiner)
                                            <option value="{{ $examiner->id }}" {{ old('internal_examiner_id') == $examiner->id ? 'selected' : '' }}>
                                                {{ $examiner->name }} ({{ $examiner->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('internal_examiner_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Additional Information</h4>

                            <div>
                                <x-input-label for="examination_notes" :value="__('Examination Notes')" />
                                <textarea id="examination_notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="examination_notes" rows="4" placeholder="Any special instructions or notes for the examination">{{ old('examination_notes') }}</textarea>
                                <x-input-error :messages="$errors->get('examination_notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('hod.viva.examinations') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('Schedule Viva Examination') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
