<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Supervisor Preferences') }}
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

                    @if($pendingPreferences->count() > 0)
                        <div class="space-y-6">
                            @foreach($pendingPreferences as $scholarId => $preferences)
                                @php
                                    $scholar = $preferences->first()->scholar;
                                @endphp
                                <div class="border rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ $scholar->user->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                Scholar ID: SCH-{{ str_pad($scholar->id, 6, '0', STR_PAD_LEFT) }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Department: {{ $scholar->admission->department->name }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $preferences->count() }} Preference{{ $preferences->count() > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach($preferences as $preference)
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">
                                                            {{ $preference->preference_order }}{{ $preference->preference_order == 1 ? 'st' : ($preference->preference_order == 2 ? 'nd' : 'rd') }} Preference
                                                        </h4>
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Supervisor:</strong> {{ $preference->supervisor->user->name }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Specialization:</strong> {{ $preference->supervisor->research_specialization }}
                                                        </p>
                                                        <p class="text-sm text-gray-600 mt-2">
                                                            <strong>Justification:</strong> {{ $preference->justification }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('hod.supervisor_preferences.approve', $scholarId) }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Review & Approve
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto h-5 w-5 text-gray-400">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending preferences</h3>
                            <p class="mt-1 text-sm text-gray-500">There are no pending supervisor preferences to review.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
