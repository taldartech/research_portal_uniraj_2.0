@props(['scholar'])

<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Reports</h3>
    @if ($scholar->progressReports->isEmpty())
        <p class="text-gray-500 mb-4">No progress reports found.</p>
    @else
        <div class="space-y-4">
            @foreach($scholar->progressReports->sortByDesc('submission_date') as $index => $report)
                <div class="border border-gray-200 rounded-lg bg-white shadow-sm" x-data="{ expanded: false }">
                    <!-- Report Header - Clickable -->
                    <button @click="expanded = !expanded" class="w-full flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex-1 text-left">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">Report Period: {{ $report->report_period ?? 'N/A' }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Submitted: {{ $report->submission_date ? $report->submission_date->format('M d, Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($report->status === 'approved') bg-green-100 text-green-800
                                @elseif(str_starts_with($report->status, 'rejected')) bg-red-100 text-red-800
                                @elseif(str_starts_with($report->status, 'pending')) bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </div>
                    </button>

                    <!-- Report Details - Collapsible -->
                    <div x-show="expanded"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="border-t border-gray-200">
                        <div class="p-6 bg-gray-50">
                                    <!-- Report Files -->
                            @if($report->report_file || $report->rac_minutes_file || $report->drc_minutes_file)
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Files</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @if($report->report_file)
                                            <a href="{{ Storage::url($report->report_file) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm hover:bg-blue-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Report File
                                            </a>
                                        @endif
                                        @if($report->rac_minutes_file)
                                            <a href="{{ Storage::url($report->rac_minutes_file) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded text-sm hover:bg-green-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                RAC Minutes
                                            </a>
                                        @endif
                                        @if($report->drc_minutes_file)
                                            <a href="{{ Storage::url($report->drc_minutes_file) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded text-sm hover:bg-purple-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                DRC Minutes
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- RAC Meeting Date -->
                            @if($report->rac_meeting_date)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">RAC Meeting Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $report->rac_meeting_date->format('M d, Y') }}</p>
                                </div>
                            @endif

                            <!-- Comments & Remarks Section -->
                            <div class="mt-6 pt-6 border-t border-gray-300">
                                <h5 class="text-md font-semibold text-gray-900 mb-4">Comments & Approvals</h5>
                                <div class="space-y-4">
                                    @php
                                $comments = [];

                                if($report->supervisor_remarks && $report->supervisor_approved_at) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('supervisor'),
                                        'remarks' => $report->supervisor_remarks,
                                        'date' => $report->supervisor_approved_at,
                                        'approver' => $report->supervisorApprover ? $report->supervisorApprover->name : 'N/A',
                                        'color' => 'blue',
                                        'status' => $report->supervisor_warning ? 'warning' : 'approved'
                                    ];
                                }

                                if($report->hod_remarks && $report->hod_approved_at) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('hod'),
                                        'remarks' => $report->hod_remarks,
                                        'date' => $report->hod_approved_at,
                                        'approver' => $report->hodApprover ? $report->hodApprover->name : 'N/A',
                                        'color' => 'green',
                                        'status' => $report->hod_warning ? 'warning' : 'approved'
                                    ];
                                }

                                if($report->da_remarks && $report->da_approved_at) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('da'),
                                        'remarks' => $report->da_remarks,
                                        'date' => $report->da_approved_at,
                                        'approver' => $report->daApprover ? $report->daApprover->name : 'N/A',
                                        'color' => 'yellow',
                                        'status' => str_contains($report->status, 'rejected') && $report->rejected_by === $report->da_approver_id ? 'rejected' : 'approved'
                                    ];
                                }

                                if($report->da_negative_remarks) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('da') . ' (Negative Remarks)',
                                        'remarks' => $report->da_negative_remarks,
                                        'date' => $report->da_approved_at ?? now(),
                                        'approver' => $report->daApprover ? $report->daApprover->name : 'N/A',
                                        'color' => 'red',
                                        'status' => 'negative'
                                    ];
                                }

                                if($report->so_remarks && $report->so_approved_at) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('so'),
                                        'remarks' => $report->so_remarks,
                                        'date' => $report->so_approved_at,
                                        'approver' => $report->soApprover ? $report->soApprover->name : 'N/A',
                                        'color' => 'purple',
                                        'status' => str_contains($report->status, 'rejected') && $report->rejected_by === $report->so_approver_id ? 'rejected' : 'approved'
                                    ];
                                }

                                if($report->ar_remarks && $report->ar_approved_at) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('ar'),
                                        'remarks' => $report->ar_remarks,
                                        'date' => $report->ar_approved_at,
                                        'approver' => $report->arApprover ? $report->arApprover->name : 'N/A',
                                        'color' => 'pink',
                                        'status' => str_contains($report->status, 'rejected') && $report->rejected_by === $report->ar_approver_id ? 'rejected' : 'approved'
                                    ];
                                }

                                if($report->dr_remarks && $report->dr_approved_at) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('dr'),
                                        'remarks' => $report->dr_remarks,
                                        'date' => $report->dr_approved_at,
                                        'approver' => $report->drApprover ? $report->drApprover->name : 'N/A',
                                        'color' => 'red',
                                        'status' => str_contains($report->status, 'rejected') && $report->rejected_by === $report->dr_approver_id ? 'rejected' : 'approved'
                                    ];
                                }

                                if($report->hvc_remarks && $report->hvc_approved_at) {
                                    $comments[] = [
                                        'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('hvc'),
                                        'remarks' => $report->hvc_remarks,
                                        'date' => $report->hvc_approved_at,
                                        'approver' => $report->hvcApprover ? $report->hvcApprover->name : 'N/A',
                                        'color' => 'orange',
                                        'status' => str_contains($report->status, 'rejected') && $report->rejected_by === $report->hvc_approver_id ? 'rejected' : 'approved'
                                    ];
                                }

                                    // Sort by date
                                    usort($comments, function($a, $b) {
                                        return $a['date'] <=> $b['date'];
                                    });
                                @endphp

                                @forelse($comments as $comment)
                                    @php
                                        $colorClasses = [
                                            'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-400', 'text' => 'text-blue-800'],
                                            'green' => ['bg' => 'bg-green-50', 'border' => 'border-green-400', 'text' => 'text-green-800'],
                                            'yellow' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-400', 'text' => 'text-yellow-800'],
                                            'purple' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-400', 'text' => 'text-purple-800'],
                                            'pink' => ['bg' => 'bg-pink-50', 'border' => 'border-pink-400', 'text' => 'text-pink-800'],
                                            'red' => ['bg' => 'bg-red-50', 'border' => 'border-red-400', 'text' => 'text-red-800'],
                                            'orange' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-400', 'text' => 'text-orange-800'],
                                        ];
                                        $classes = $colorClasses[$comment['color']] ?? $colorClasses['blue'];
                                    @endphp
                                    <div class="p-4 {{ $classes['bg'] }} border-l-4 {{ $classes['border'] }} rounded">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex-1">
                                                    <h6 class="font-medium {{ $classes['text'] }}">
                                                        {{ $comment['role'] }} Comments
                                                        @if($comment['status'] === 'rejected')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 ml-2">Rejected</span>
                                                        @elseif($comment['status'] === 'warning')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">⚠️ Warning (Unsatisfied)</span>
                                                        @elseif($comment['status'] === 'negative')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 ml-2">Negative Remarks</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-2">Approved</span>
                                                        @endif
                                                    </h6>
                                                <p class="text-xs text-gray-600 mt-1">By: {{ $comment['approver'] }}</p>
                                                <p class="text-sm {{ $classes['text'] }} mt-2 whitespace-pre-wrap">{{ $comment['remarks'] }}</p>
                                            </div>
                                            <div class="text-xs text-gray-600 ml-4 whitespace-nowrap">
                                                {{ $comment['date']->format('M d, Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No comments or approvals yet.</p>
                                @endforelse

                                @if($report->rejection_reason && $report->rejected_at)
                                    <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded">
                                        <h6 class="font-medium text-red-800 mb-2">Rejection Reason</h6>
                                        <p class="text-sm text-red-800 whitespace-pre-wrap">{{ $report->rejection_reason }}</p>
                                        <p class="text-xs text-red-600 mt-2">Rejected on: {{ $report->rejected_at->format('M d, Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($report->feedback_da)
                            <div class="mt-4 pt-4 border-t border-gray-300">
                                <label class="block text-sm font-medium text-gray-700 mb-2">DA Feedback</label>
                                <div class="bg-white p-3 rounded-md border border-gray-200">
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $report->feedback_da }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


<div class="mt-6 bg-white p-6 rounded-lg shadow-sm">
    @if(auth()->user()->user_type === 'supervisor' && $scholar->supervisorAssignments()->where('supervisor_id', auth()->user()->supervisor->id)->where('status', 'assigned')->exists())
        @php
            $canSubmitInfo = \App\Helpers\ProgressReportHelper::canSubmitProgressReport($scholar);
        @endphp
        <div class="flex flex-wrap gap-2">
            @if(isset($canSubmitInfo) && $canSubmitInfo['can_submit'])
                <a href="{{ route('staff.progress_report.submit.for_scholar', $scholar) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Submit Progress Report ({{ $canSubmitInfo['report_period'] }})
                </a>
            @endif
        </div>
    @endif
</div>
