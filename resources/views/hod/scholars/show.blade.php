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

                    <!-- RAC Committee Submission History -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">RAC Committee Members - Complete History</h3>
                        @php
                            $racSubmissions = \App\Models\RACCommitteeSubmission::where('scholar_id', $scholar->id)
                                ->with('supervisor.user', 'supervisor.department', 'hod')
                                ->latest()
                                ->get();
                        @endphp

                        @if($racSubmissions->isEmpty())
                            <p class="text-gray-500 mb-4">No RAC committee submissions yet.</p>
                        @else
                            <div class="space-y-4 mb-4">
                                @foreach($racSubmissions as $index => $racSubmission)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div class="flex justify-between items-start mb-3">
                                            <h4 class="text-md font-semibold text-gray-900">
                                                Submission #{{ $racSubmissions->count() - $index }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($racSubmission->status === 'approved') bg-green-100 text-green-800
                                                @elseif($racSubmission->status === 'pending_hod_approval') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucwords(str_replace('_', ' ', $racSubmission->status)) }}
                                            </span>
                                        </div>

                                        <div class="space-y-3 mb-4">
                                            <div class="border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50 rounded-r">
                                                <p class="text-sm font-semibold text-gray-900">1. Supervisor</p>
                                                <p class="text-sm text-gray-700">{{ $racSubmission->supervisor->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->supervisor->designation ?? 'N/A' }}, {{ $racSubmission->supervisor->department->name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50 rounded-r">
                                                <p class="text-sm font-semibold text-gray-900">2. Member 1</p>
                                                <p class="text-sm text-gray-700">{{ $racSubmission->member1_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->member1_designation ?? 'N/A' }}, {{ $racSubmission->member1_department ?? 'N/A' }}</p>
                                            </div>
                                            <div class="border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50 rounded-r">
                                                <p class="text-sm font-semibold text-gray-900">3. Member 2</p>
                                                <p class="text-sm text-gray-700">{{ $racSubmission->member2_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->member2_designation ?? 'N/A' }}, {{ $racSubmission->member2_department ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Submitted By</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $racSubmission->supervisor->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            @if($racSubmission->drc_date)
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">DRC Date</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $racSubmission->drc_date->format('M d, Y') }}</p>
                                                </div>
                                            @endif
                                            @if($racSubmission->hod)
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Reviewed By</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $racSubmission->hod->name }}</p>
                                                    @if($racSubmission->approved_at)
                                                        <p class="text-xs text-gray-500">Approved: {{ $racSubmission->approved_at->format('M d, Y H:i') }}</p>
                                                    @elseif($racSubmission->rejected_at)
                                                        <p class="text-xs text-gray-500">Rejected: {{ $racSubmission->rejected_at->format('M d, Y H:i') }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        @if($racSubmission->hod_remarks)
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">HOD Remarks/Comments</label>
                                                <div class="bg-white p-3 rounded-md border border-gray-200">
                                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $racSubmission->hod_remarks }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
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
