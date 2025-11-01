<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create DRC Minutes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('hod.drc_minutes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Department Display (Read-only) -->
                        <div class="mb-4">
                            <x-input-label for="department_name" :value="__('Department')" />
                            <x-text-input id="department_name" class="block mt-1 w-full" type="text" name="department_name" :value="$hodDepartment->name" readonly />
                        </div>

                        <!-- Meeting Date (Required) -->
                        <div class="mb-4">
                            <x-input-label for="meeting_date" :value="__('Meeting Date')" :required="true" />
                            <x-text-input id="meeting_date" class="block mt-1 w-full" type="date" name="meeting_date" :value="old('meeting_date')" required />
                            <x-input-error :messages="$errors->get('meeting_date')" class="mt-2" />
                        </div>

                        <!-- Minutes File Upload (Required) -->
                        <div class="mb-4">
                            <x-input-label for="minutes_file" :value="__('Minutes File')" :required="true" />
                            <p class="text-sm text-gray-600 mb-2">Please upload a PDF, DOC, or DOCX file (max 5MB).</p>
                            <input id="minutes_file" class="block mt-1 w-full" type="file" name="minutes_file" accept=".pdf,.doc,.docx" required />
                            <x-input-error :messages="$errors->get('minutes_file')" class="mt-2" />
                        </div>
                        <input type="hidden" name="status" value="active">

                        <div class="flex items-center justify-end space-x-4 mt-6">
                            <a href="{{ route('hod.drc_minutes.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create DRC Minutes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

