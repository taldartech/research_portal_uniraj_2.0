<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit DRC Minutes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('hod.drc_minutes.update', $drc_minute) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Meeting Date (Required) -->
                        <div class="mb-4">
                            <x-input-label for="meeting_date" :value="__('Meeting Date')" :required="true" />
                            <x-text-input id="meeting_date" class="block mt-1 w-full" type="date" name="meeting_date" :value="old('meeting_date', $drc_minute->meeting_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('meeting_date')" class="mt-2" />
                        </div>

                        <!-- Current File Display -->
                        <div class="mb-4">
                            <x-input-label :value="__('Current File')" />
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                @if($drc_minute->minutes_file)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700">{{ basename($drc_minute->minutes_file) }}</span>
                                        <a href="{{ route('hod.drc_minutes.download', $drc_minute) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm">Download</a>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">No file uploaded</span>
                                @endif
                            </div>
                        </div>

                        <!-- Minutes File Upload (Optional - only if replacing) -->
                        <div class="mb-4">
                            <x-input-label for="minutes_file" :value="__('Upload New Minutes File (Optional)')" />
                            <p class="text-sm text-gray-600 mb-2">Leave empty to keep current file. Upload PDF, DOC, or DOCX file (max 5MB).</p>
                            <input id="minutes_file" class="block mt-1 w-full" type="file" name="minutes_file" accept=".pdf,.doc,.docx" />
                            <x-input-error :messages="$errors->get('minutes_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end space-x-4 mt-6">
                            <a href="{{ route('hod.drc_minutes.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update DRC Minutes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

