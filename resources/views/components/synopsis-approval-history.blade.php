@props(['synopsis'])

<!-- Previous Comments & Approvals Section -->
<div class="mb-6 p-4 bg-gray-50 rounded-lg">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Previous Comments & Approvals</h3>
    <div class="space-y-4">
        @php
            // Build history array with all comments and actions
            $history = [];

            if($synopsis->supervisor_remarks && $synopsis->supervisor_approved_at) {
                $history[] = [
                    'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('supervisor'),
                    'remarks' => $synopsis->supervisor_remarks,
                    'date' => $synopsis->supervisor_approved_at,
                    'color' => 'blue',
                    'status' => str_contains($synopsis->status, 'rejected_by_supervisor') ? 'rejected' : 'approved'
                ];
            }

            if($synopsis->hod_remarks && $synopsis->hod_approved_at) {
                $isRejected = str_contains($synopsis->status, 'rejected_by_hod');
                $history[] = [
                    'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('hod'),
                    'remarks' => $synopsis->hod_remarks,
                    'date' => $synopsis->hod_approved_at,
                    'color' => 'green',
                    'status' => $isRejected ? 'rejected' : 'approved',
                    'reassigned_to' => ($isRejected && $synopsis->reassigned_to_role) ? \App\Helpers\WorkflowHelper::getRoleFullForm($synopsis->reassigned_to_role) : null,
                    'reassignment_reason' => ($isRejected && $synopsis->reassigned_to_role) ? $synopsis->reassignment_reason : null
                ];
            }

            if($synopsis->da_remarks && $synopsis->da_approved_at) {
                $isRejected = str_contains($synopsis->status, 'rejected_by_da');
                $history[] = [
                    'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('da'),
                    'remarks' => $synopsis->da_remarks,
                    'date' => $synopsis->da_approved_at,
                    'color' => 'yellow',
                    'status' => $isRejected ? 'rejected' : 'approved',
                    'reassigned_to' => ($isRejected && $synopsis->reassigned_to_role) ? \App\Helpers\WorkflowHelper::getRoleFullForm($synopsis->reassigned_to_role) : null,
                    'reassignment_reason' => ($isRejected && $synopsis->reassigned_to_role) ? $synopsis->reassignment_reason : null
                ];
            }

            if($synopsis->so_remarks && $synopsis->so_approved_at) {
                $isRejected = str_contains($synopsis->status, 'rejected_by_so');
                $history[] = [
                    'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('so'),
                    'remarks' => $synopsis->so_remarks,
                    'date' => $synopsis->so_approved_at,
                    'color' => 'purple',
                    'status' => $isRejected ? 'rejected' : 'approved',
                    'reassigned_to' => ($isRejected && $synopsis->reassigned_to_role) ? \App\Helpers\WorkflowHelper::getRoleFullForm($synopsis->reassigned_to_role) : null,
                    'reassignment_reason' => ($isRejected && $synopsis->reassigned_to_role) ? $synopsis->reassignment_reason : null
                ];
            }

            if($synopsis->ar_remarks && $synopsis->ar_approved_at) {
                $isRejected = str_contains($synopsis->status, 'rejected_by_ar');
                $history[] = [
                    'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('ar'),
                    'remarks' => $synopsis->ar_remarks,
                    'date' => $synopsis->ar_approved_at,
                    'color' => 'pink',
                    'status' => $isRejected ? 'rejected' : 'approved',
                    'reassigned_to' => ($isRejected && $synopsis->reassigned_to_role) ? \App\Helpers\WorkflowHelper::getRoleFullForm($synopsis->reassigned_to_role) : null,
                    'reassignment_reason' => ($isRejected && $synopsis->reassigned_to_role) ? $synopsis->reassignment_reason : null
                ];
            }

            if($synopsis->dr_remarks && $synopsis->dr_approved_at) {
                $isRejected = str_contains($synopsis->status, 'rejected_by_dr');
                $history[] = [
                    'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('dr'),
                    'remarks' => $synopsis->dr_remarks,
                    'date' => $synopsis->dr_approved_at,
                    'color' => 'red',
                    'status' => $isRejected ? 'rejected' : 'approved',
                    'reassigned_to' => ($isRejected && $synopsis->reassigned_to_role) ? \App\Helpers\WorkflowHelper::getRoleFullForm($synopsis->reassigned_to_role) : null,
                    'reassignment_reason' => ($isRejected && $synopsis->reassigned_to_role) ? $synopsis->reassignment_reason : null
                ];
            }

            if($synopsis->hvc_remarks && $synopsis->hvc_approved_at) {
                // HVC rejection detection:
                // Since HVC is the last approver, if hvc_approved_at exists but status is NOT 'approved',
                // it means HVC rejected it (either explicitly with status 'rejected_by_hvc' or with reassignment)
                $isRejected = str_contains($synopsis->status, 'rejected_by_hvc') || 
                              ($synopsis->status !== 'approved' && str_starts_with($synopsis->status, 'pending_'));
                $history[] = [
                    'role' => \App\Helpers\WorkflowHelper::getRoleFullForm('hvc'),
                    'remarks' => $synopsis->hvc_remarks,
                    'date' => $synopsis->hvc_approved_at,
                    'color' => 'orange',
                    'status' => $isRejected ? 'rejected' : 'approved',
                    'reassigned_to' => ($isRejected && $synopsis->reassigned_to_role) ? \App\Helpers\WorkflowHelper::getRoleFullForm($synopsis->reassigned_to_role) : null,
                    'reassignment_reason' => ($isRejected && $synopsis->reassigned_to_role) ? $synopsis->reassignment_reason : null
                ];
            }

            // Sort by date (oldest first)
            usort($history, function($a, $b) {
                return $a['date'] <=> $b['date'];
            });
        @endphp

        @forelse($history as $entry)
            @php
                $colorClasses = [
                    'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-400', 'text' => 'text-blue-800', 'text_light' => 'text-blue-700', 'text_dark' => 'text-blue-900', 'text_date' => 'text-blue-600', 'bg_light' => 'bg-blue-100', 'border_light' => 'border-blue-500'],
                    'green' => ['bg' => 'bg-green-50', 'border' => 'border-green-400', 'text' => 'text-green-800', 'text_light' => 'text-green-700', 'text_dark' => 'text-green-900', 'text_date' => 'text-green-600', 'bg_light' => 'bg-green-100', 'border_light' => 'border-green-500'],
                    'yellow' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-400', 'text' => 'text-yellow-800', 'text_light' => 'text-yellow-700', 'text_dark' => 'text-yellow-900', 'text_date' => 'text-yellow-600', 'bg_light' => 'bg-yellow-100', 'border_light' => 'border-yellow-500'],
                    'purple' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-400', 'text' => 'text-purple-800', 'text_light' => 'text-purple-700', 'text_dark' => 'text-purple-900', 'text_date' => 'text-purple-600', 'bg_light' => 'bg-purple-100', 'border_light' => 'border-purple-500'],
                    'pink' => ['bg' => 'bg-pink-50', 'border' => 'border-pink-400', 'text' => 'text-pink-800', 'text_light' => 'text-pink-700', 'text_dark' => 'text-pink-900', 'text_date' => 'text-pink-600', 'bg_light' => 'bg-pink-100', 'border_light' => 'border-pink-500'],
                    'red' => ['bg' => 'bg-red-50', 'border' => 'border-red-400', 'text' => 'text-red-800', 'text_light' => 'text-red-700', 'text_dark' => 'text-red-900', 'text_date' => 'text-red-600', 'bg_light' => 'bg-red-100', 'border_light' => 'border-red-500'],
                    'orange' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-400', 'text' => 'text-orange-800', 'text_light' => 'text-orange-700', 'text_dark' => 'text-orange-900', 'text_date' => 'text-orange-600', 'bg_light' => 'bg-orange-100', 'border_light' => 'border-orange-500'],
                ];
                $classes = $colorClasses[$entry['color']] ?? $colorClasses['blue'];
            @endphp
            <div class="p-3 {{ $classes['bg'] }} border-l-4 {{ $classes['border'] }} rounded">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h4 class="font-medium {{ $classes['text'] }}">
                            {{ $entry['role'] }} Comments
                            @if($entry['status'] === 'rejected')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 ml-2">Rejected</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-2">Approved</span>
                            @endif
                        </h4>
                        <p class="text-sm {{ $classes['text_light'] }} mt-1 whitespace-pre-wrap">{{ $entry['remarks'] }}</p>

                        @if(isset($entry['reassigned_to']) && $entry['reassigned_to'])
                            <div class="mt-2 p-2 {{ $classes['bg_light'] }} rounded border-l-2 {{ $classes['border_light'] }}">
                                <p class="text-xs font-medium {{ $classes['text_dark'] }}">Reassigned to: {{ $entry['reassigned_to'] }}</p>
                                @if(isset($entry['reassignment_reason']) && $entry['reassignment_reason'])
                                    <p class="text-xs {{ $classes['text_light'] }} mt-1">{{ $entry['reassignment_reason'] }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="text-xs {{ $classes['text_date'] }} ml-4 whitespace-nowrap">
                        {{ $entry['date']->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 italic">No previous comments or approvals yet.</p>
        @endforelse
    </div>
</div>

