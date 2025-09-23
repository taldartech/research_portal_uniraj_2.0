<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Late Submission Request Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Late Submission Request Status</h3>
                        <p class="mt-1 text-sm text-gray-600">Track the progress of your late submission requests.</p>
                    </div>

                    @if($lateSubmissionRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Extension</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($lateSubmissionRequests as $request)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $request->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $request->original_due_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $request->requested_extension_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending_supervisor_approval' => 'bg-yellow-100 text-yellow-800',
                                                        'pending_hod_approval' => 'bg-blue-100 text-blue-800',
                                                        'pending_dean_approval' => 'bg-purple-100 text-purple-800',
                                                        'pending_da_approval' => 'bg-indigo-100 text-indigo-800',
                                                        'pending_so_approval' => 'bg-pink-100 text-pink-800',
                                                        'pending_ar_approval' => 'bg-teal-100 text-teal-800',
                                                        'pending_dr_approval' => 'bg-orange-100 text-orange-800',
                                                        'pending_hvc_approval' => 'bg-red-100 text-red-800',
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'rejected_by_supervisor' => 'bg-red-100 text-red-800',
                                                        'rejected_by_hod' => 'bg-red-100 text-red-800',
                                                        'rejected_by_dean' => 'bg-red-100 text-red-800',
                                                        'rejected_by_da' => 'bg-red-100 text-red-800',
                                                        'rejected_by_so' => 'bg-red-100 text-red-800',
                                                        'rejected_by_ar' => 'bg-red-100 text-red-800',
                                                        'rejected_by_dr' => 'bg-red-100 text-red-800',
                                                        'rejected_by_hvc' => 'bg-red-100 text-red-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $request->getCurrentApprovalStage())) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($request->isRejected() && $request->canResubmit())
                                                    <a href="{{ route('scholar.late_submission.request') }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded-md text-sm font-medium transition-colors">
                                                        Resubmit
                                                    </a>
                                                @elseif($request->isApproved())
                                                    <span class="text-green-600 text-sm">Approved</span>
                                                @else
                                                    <span class="text-gray-500 text-sm">In Progress</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">No late submission requests found.</div>
                            <div class="text-gray-400 text-sm mt-2">
                                @if($scholar->canRequestLateSubmission())
                                    <a href="{{ route('scholar.late_submission.request') }}" class="text-indigo-600 hover:text-indigo-900">
                                        Submit a late submission request
                                    </a>
                                @else
                                    You are not currently eligible to submit a late submission request.
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
