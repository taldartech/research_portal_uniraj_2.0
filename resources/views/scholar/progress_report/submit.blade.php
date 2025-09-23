<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Submit Progress Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('scholar.progress_report.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="report_period" :value="__('Report Period')" />
                            <select id="report_period" name="report_period" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select Report Period</option>
                                @foreach($allowedMonths as $month)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('report_period')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Progress reports can only be submitted during: {{ implode(', ', $allowedMonths) }}
                            </p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="report_file" :value="__('Progress Report File (PDF/DOC/DOCX)')" />
                            <input id="report_file" class="block mt-1 w-full" type="file" name="report_file" required />
                            <x-input-error :messages="$errors->get('report_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-3">
                                {{ __('Submit Report') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
