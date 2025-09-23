<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Merit List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Display warnings if any -->
            @if(session('warnings'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.725-1.36 3.49 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Processing Warnings</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach(session('warnings') as $warning)
                                        <li>{{ $warning }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('hod.admissions.store_merit_list') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Department Display (Read-only) and Hidden ID -->
                        <div class="mb-4">
                            <x-input-label for="department_name" :value="__('Department')" />
                            <x-text-input id="department_name" class="block mt-1 w-full" type="text" name="department_name" :value="$departments->first()->name" readonly />
                            <input type="hidden" name="department_id" value="{{ $departments->first()->id }}" />
                        </div>

                        <!-- Admission Date -->
                        <div class="mb-4">
                            <x-input-label for="admission_date" :value="__('Admission Date')" />
                            <x-text-input id="admission_date" class="block mt-1 w-full" type="date" name="admission_date" :value="old('admission_date')" required />
                            <x-input-error :messages="$errors->get('admission_date')" class="mt-2" />
                        </div>

                        <!-- Merit List File Upload -->
                        <div class="mb-4">
                            <x-input-label for="merit_list_file" :value="__('Merit List (CSV/XLSX)')" />
                            <p class="text-sm text-gray-600 mb-2">Please upload a CSV or XLSX file with the following columns: <strong>name, email, form_number, mobile_number</strong>.</p>
                            <p class="text-sm text-gray-600 mb-2">Password will be automatically generated as: <strong>form_number + # + last 5 digits of mobile_number</strong></p>

                            <!-- Download Template Button -->
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-900 mb-1">Need a template?</h4>
                                        <p class="text-xs text-blue-700">Download our sample template to see the correct format</p>
                                    </div>
                                    <a href="{{ route('hod.admissions.download_template') }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-blue-600 rounded-md font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Template
                                    </a>
                                </div>
                            </div>

                            <input id="merit_list_file" class="block mt-1 w-full" type="file" name="merit_list_file" required />
                            <x-input-error :messages="$errors->get('merit_list_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Upload Merit List') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
