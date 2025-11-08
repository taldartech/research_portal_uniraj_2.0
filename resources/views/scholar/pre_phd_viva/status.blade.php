<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pre-PhD Viva Request Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Pre-PhD Viva Requests</h3>
                        <a href="{{ route('scholar.pre_phd_viva.request') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Request
                        </a>
                    </div>

                    @if ($requests->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Pre-PhD Viva requests</h3>
                            <p class="mt-1 text-sm text-gray-500">You haven't submitted any Pre-PhD Viva requests yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('scholar.pre_phd_viva.request') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Submit First Request
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($requests as $request)
                                <div class="border border-gray-200 rounded-lg p-6 {{ $request->isPending() ? 'bg-yellow-50' : ($request->isApproved() ? 'bg-green-50' : ($request->isRejected() ? 'bg-red-50' : 'bg-gray-50')) }}">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                Request #{{ $request->id }}
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($request->isPending()) bg-yellow-100 text-yellow-800
                                                    @elseif($request->isApproved()) bg-green-100 text-green-800
                                                    @elseif($request->isRejected()) bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Requested on: {{ $request->requested_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($request->thesis_summary_file)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Thesis Summary</label>
                                        <a href="{{ Storage::url($request->thesis_summary_file) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download Thesis Summary
                                        </a>
                                    </div>
                                    @endif

                                    @php
                                        $supportiveDocs = $request->getSupportiveDocumentsArray();
                                    @endphp
                                    @if(!empty($supportiveDocs))
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Supportive Documents</label>
                                        <div class="space-y-2">
                                            @foreach($supportiveDocs as $index => $document)
                                                <a href="{{ Storage::url($document) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md mr-2 mb-2">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Document {{ $index + 1 }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($request->request_remarks)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Remarks</label>
                                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $request->request_remarks }}</p>
                                    </div>
                                    @endif

                                    @if($request->isApproved())
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            @if($request->viva_date)
                                            <div class="bg-white p-3 rounded border border-green-200">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Pre-PhD Viva Date</label>
                                                <p class="text-sm font-semibold text-green-900">{{ $request->viva_date->format('d/m/Y') }}</p>
                                            </div>
                                            @endif
                                            @if($request->thesis_submission_deadline)
                                            <div class="bg-white p-3 rounded border border-blue-200">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Thesis Submission Deadline</label>
                                                <p class="text-sm font-semibold text-blue-900">{{ $request->thesis_submission_deadline->format('d/m/Y') }}</p>
                                                @if(now()->gt($request->thesis_submission_deadline) && !$request->thesis_submitted)
                                                <p class="text-xs text-red-600 mt-1">⚠️ Deadline expired</p>
                                                @elseif(now()->lte($request->thesis_submission_deadline))
                                                <p class="text-xs text-green-600 mt-1">
                                                    {{ now()->diffInDays($request->thesis_submission_deadline, false) }} days remaining
                                                </p>
                                                @endif
                                            </div>
                                            @endif
                                        </div>

                                        @if($request->thesis_submitted)
                                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                                            <p class="text-sm text-blue-900">
                                                <strong>✓ Thesis Submitted:</strong> Your thesis has been submitted successfully.
                                            </p>
                                        </div>
                                        @endif

                                        @if($request->hasExpired() && !$request->thesis_submitted)
                                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded">
                                            <p class="text-sm text-red-900">
                                                <strong>⚠️ Deadline Expired:</strong> The thesis submission deadline has passed. Please submit a new Pre-PhD Viva request.
                                            </p>
                                        </div>
                                        @endif
                                    @endif

                                    @if($request->rac_remarks)
                                    <div class="mb-4 p-3 bg-gray-50 rounded">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">RAC Remarks</label>
                                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $request->rac_remarks }}</p>
                                        @if($request->racApprover)
                                        <p class="text-xs text-gray-600 mt-1">
                                            By: {{ $request->racApprover->name }} on {{ $request->rac_approved_at->format('M d, Y H:i') }}
                                        </p>
                                        @endif
                                        @if($request->rac_minutes_file)
                                        <div class="mt-3">
                                            <a href="{{ Storage::url($request->rac_minutes_file) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                View RAC Minutes
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    @if($request->isPending())
                                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                        <p class="text-sm text-yellow-900">
                                            <strong>⏳ Pending:</strong> Waiting for RAC approval and viva date assignment.
                                        </p>
                                    </div>
                                    @endif

                                    @if($request->isRejected())
                                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                                        <p class="text-sm text-red-900">
                                            <strong>❌ Rejected:</strong> Your request was rejected. You can submit a new request.
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

