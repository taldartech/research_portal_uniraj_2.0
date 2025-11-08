<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholar Details') }}: {{ $scholar->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-scholar-profile-tabs :scholar="$scholar" />

            <!-- Actions Section -->
            <div class="mt-6 bg-white p-6 rounded-lg shadow-sm">
                <!-- Actions -->
                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('staff.scholars.list') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to Scholars List
                    </a>

                    <div class="flex space-x-2">
                        <a href="{{ route('staff.scholars.review', $scholar) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Review Scholar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
