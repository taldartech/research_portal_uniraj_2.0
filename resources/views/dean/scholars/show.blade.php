<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholar Details') }}: {{ $scholar->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <p><strong>Name:</strong> {{ $scholar->user->name }}</p>
                        <p><strong>Email:</strong> {{ $scholar->user->email }}</p>
                        <p><strong>Date of Birth:</strong> {{ $scholar->date_of_birth ? $scholar->date_of_birth->format('Y-m-d') : 'N/A' }}</p>
                        <p><strong>Contact Number:</strong> {{ $scholar->contact_number ?? 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $scholar->address ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                        <p><strong>Department:</strong> {{ $scholar->admission->department->name ?? 'N/A' }}</p>
                        <p><strong>Admission Date:</strong> {{ $scholar->admission->admission_date ? $scholar->admission->admission_date->format('Y-m-d') : 'N/A' }}</p>
                        <p><strong>Program:</strong> {{ $scholar->academic_information['program'] ?? 'N/A' }}</p>
                        <p><strong>Enrollment Number:</strong> {{ $scholar->academic_information['enrollment_number'] ?? 'N/A' }}</p>
                        <p><strong>Research Area:</strong> {{ $scholar->academic_information['research_area'] ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Supervisor Information</h3>
                        @if ($scholar->currentSupervisor)
                            <p><strong>Assigned Supervisor:</strong> {{ $scholar->currentSupervisor->supervisor->user->name }}</p>
                            <p><strong>Assigned Date:</strong> {{ $scholar->currentSupervisor->assigned_date->format('Y-m-d') }}</p>
                        @else
                            <p>No supervisor assigned yet.</p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Synopsis Details</h3>
                        @if ($scholar->synopses->isNotEmpty())
                            @foreach ($scholar->synopses as $synopsis)
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <p><strong>Proposed Topic:</strong> {{ $synopsis->proposed_topic }}</p>
                                    <p><strong>Submission Date:</strong> {{ $synopsis->submission_date->format('Y-m-d') }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $synopsis->status)) }}</p>
                                    @if ($synopsis->synopsis_file)
                                        <p><a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Download Synopsis</a></p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No synopses submitted yet.</p>
                        @endif
                    </div>

                    <div class="mt-6">
                        <x-secondary-button onclick="window.history.back()">
                            {{ __('Back to Scholars List') }}
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
