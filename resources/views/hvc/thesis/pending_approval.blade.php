<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Thesis Approvals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($theses->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending thesis approvals</h3>
                            <p class="mt-1 text-sm text-gray-500">All thesis submissions have been processed.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach ($theses as $thesis)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $thesis->scholar->user->name }}</h3>
                                            <p class="text-sm text-gray-600">Thesis: {{ $thesis->title }}</p>
                                            <p class="text-sm text-gray-600">Department: {{ $thesis->scholar->admission->department->name ?? 'Not specified' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending HVC Approval
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Submitted: {{ $thesis->submission_date->format('M d, Y H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Scholar Details</h4>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-sm"><strong>Email:</strong> {{ $thesis->scholar->user->email }}</p>
                                                <p class="text-sm"><strong>Research Area:</strong> {{ $thesis->scholar->research_area ?? 'Not specified' }}</p>
                                                <p class="text-sm"><strong>Supervisor:</strong>
                                                    @php
                                                        $supervisorName = 'N/A';
                                                        if ($thesis->supervisor && $thesis->supervisor->user) {
                                                            $supervisorName = $thesis->supervisor->user->name;
                                                        } elseif ($thesis->scholar->currentSupervisor && $thesis->scholar->currentSupervisor->supervisor && $thesis->scholar->currentSupervisor->supervisor->user) {
                                                            $supervisorName = $thesis->scholar->currentSupervisor->supervisor->user->name;
                                                        }
                                                    @endphp
                                                    {{ $supervisorName }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Approval History</h4>
                                            <div class="bg-gray-50 p-3 rounded">
                                                @if($thesis->supervisor_approved_at)
                                                    <p class="text-sm text-green-600">✓ Supervisor: {{ $thesis->supervisorApprover->name ?? 'N/A' }}</p>
                                                @endif
                                                @if($thesis->hod_approved_at)
                                                    <p class="text-sm text-green-600">✓ HOD: {{ $thesis->hodApprover->name ?? 'N/A' }}</p>
                                                @endif
                                                @if($thesis->da_approved_at)
                                                    <p class="text-sm text-green-600">✓ DA: {{ $thesis->daApprover->name ?? 'N/A' }}</p>
                                                @endif
                                                @if($thesis->so_approved_at)
                                                    <p class="text-sm text-green-600">✓ SO: {{ $thesis->soApprover->name ?? 'N/A' }}</p>
                                                @endif
                                                @if($thesis->ar_approved_at)
                                                    <p class="text-sm text-green-600">✓ AR: {{ $thesis->arApprover->name ?? 'N/A' }}</p>
                                                @endif
                                                @if($thesis->dr_approved_at)
                                                    <p class="text-sm text-green-600">✓ DR: {{ $thesis->drApprover->name ?? 'N/A' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($thesis->abstract)
                                        <div class="mb-4">
                                            <h4 class="font-medium text-gray-900 mb-2">Abstract</h4>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-sm">{{ Str::limit($thesis->abstract, 200) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex space-x-4 pt-4 border-t border-gray-200">
                                        <a href="{{ route('hvc.thesis.approve', $thesis) }}"
                                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Review & Approve
                                        </a>

                                        @if($thesis->file_path)
                                            <a href="{{ Storage::url($thesis->file_path) }}" target="_blank"
                                               class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download Thesis
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
