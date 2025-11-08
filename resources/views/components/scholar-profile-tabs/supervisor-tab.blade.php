@props(['scholar', 'racSubmissions'])

<!-- Supervisor Information -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Supervisor Information</h3>
    @if ($scholar->currentSupervisor)
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Designation</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->designation }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Research Specialization</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->research_specialization ?? 'Not specified' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Assigned Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->assigned_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Department</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->department->name ?? 'Not specified' }}</p>
                </div>
            </div>
        </div>
    @else
        <p class="text-gray-500">No supervisor assigned yet.</p>
    @endif
</div>

<!-- Synopsis Details -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Synopsis Details</h3>
    @if ($scholar->synopses->isNotEmpty())
        <div class="space-y-4">
            @foreach ($scholar->synopses as $synopsis)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proposed Topic</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $synopsis->proposed_topic }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($synopsis->status === 'approved') bg-green-100 text-green-800
                                @elseif(str_starts_with($synopsis->status, 'rejected')) bg-red-100 text-red-800
                                @elseif(str_starts_with($synopsis->status, 'pending')) bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $synopsis->status)) }}
                            </span>
                        </div>
                        @if($synopsis->submission_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submission Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $synopsis->submission_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        @if($synopsis->synopsis_file)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Synopsis File</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        View Synopsis Document
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($synopsis->research_objectives)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Research Objectives</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $synopsis->research_objectives }}</p>
                        </div>
                    @endif

                    @if($synopsis->methodology)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Methodology</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $synopsis->methodology }}</p>
                        </div>
                    @endif

                    @if($synopsis->expected_outcomes)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Expected Outcomes</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $synopsis->expected_outcomes }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No synopses submitted yet.</p>
    @endif
</div>

<!-- RAC Committee Submission History -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">RAC Committee Members - Complete History</h3>
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
                            <p class="text-sm font-semibold text-gray-900">1. Member 1</p>
                            <p class="text-sm text-gray-700">{{ $racSubmission->supervisor->user->name }} <b>(Convener)</b></p>
                            <p class="text-xs text-gray-500">{{ $racSubmission->supervisor->designation ?? 'N/A' }}, {{ $racSubmission->supervisor->department->name ?? 'N/A' }}</p>
                        </div>
                        <div class="border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50 rounded-r">
                            <p class="text-sm font-semibold text-gray-900">2. Member 2</p>
                            <p class="text-sm text-gray-700">{{ $racSubmission->member1_name }}</p>
                            <p class="text-xs text-gray-500">{{ $racSubmission->member1_designation ?? 'N/A' }}, {{ $racSubmission->member1_department ?? 'N/A' }}</p>
                        </div>
                        <div class="border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50 rounded-r">
                            <p class="text-sm font-semibold text-gray-900">3. Member 3</p>
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


<div class="mt-6 bg-white p-6 rounded-lg shadow-sm">
    @if(auth()->user()->user_type === 'supervisor' && $scholar->supervisorAssignments()->where('supervisor_id', auth()->user()->supervisor->id)->where('status', 'assigned')->exists())
        @php
            $racSubmissions = \App\Models\RACCommitteeSubmission::where('scholar_id', $scholar->id)
                ->with('supervisor.user', 'supervisor.department', 'hod')
                ->latest()
                ->get();
        @endphp
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('staff.rac_committee.submit', $scholar) }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                @if($racSubmissions->first() && $racSubmissions->first()->status === 'pending_hod_approval')
                    Update RAC Committee Members
                @else
                    Submit/Update RAC Committee Members
                @endif
            </a>
        </div>
    @endif
</div>
