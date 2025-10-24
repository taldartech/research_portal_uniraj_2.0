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
                    @if($pendingPreferences->count() > 0)
                        <div class="space-y-6">
                            @foreach($pendingPreferences as $scholarId => $preferences)
                                @php
                                    $scholar = $preferences->first()->scholar;
                                @endphp
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">
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
                                            <p class="text-xs text-gray-500 mt-1">
                                                Submitted: {{ $preferences->first()->created_at->format('M d, Y H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-3 mb-4">
                                        @foreach($preferences as $preference)
                                            <div class="bg-gray-50 p-3 rounded">
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

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Scholar Details</h4>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-sm"><strong>Research Area:</strong> {{ $scholar->research_area ?? 'Not specified' }}</p>
                                                <p class="text-sm"><strong>Email:</strong> {{ $scholar->user->email }}</p>
                                                <p class="text-sm"><strong>Contact:</strong> {{ $scholar->contact_number ?? 'Not provided' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex space-x-4 pt-4 border-t border-gray-200">
                                        <a href="{{ route('hod.supervisor_preferences.approve', $scholarId) }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Review & Approve
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-1">
                            <svg class="mx-auto h-5 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending supervisor preferences</h3>
                            <p class="mt-1 text-sm text-gray-500">All supervisor preferences have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
