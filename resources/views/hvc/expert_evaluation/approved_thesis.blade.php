<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approved Thesis Submissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Thesis Submissions Ready for Expert Selection</h3>
                        <p class="mt-1 text-sm text-gray-600">These thesis submissions have been approved by HVC and are ready for expert selection and evaluation.</p>
                    </div>

                    @if($thesisSubmissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thesis Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($thesisSubmissions as $thesis)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $thesis->scholar->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $thesis->scholar->user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $thesis->title }}">
                                                    {{ $thesis->title }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $thesis->supervisor->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $thesis->submission_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending_expert_selection' => 'bg-yellow-100 text-yellow-800',
                                                        'pending_expert_assignment' => 'bg-blue-100 text-blue-800',
                                                        'pending_evaluation_letters' => 'bg-purple-100 text-purple-800',
                                                        'pending_expert_evaluation' => 'bg-orange-100 text-orange-800',
                                                        'pending_viva_scheduling' => 'bg-indigo-100 text-indigo-800',
                                                        'viva_scheduled' => 'bg-green-100 text-green-800',
                                                        'pending_viva_approval' => 'bg-teal-100 text-teal-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$thesis->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($thesis->status === 'pending_expert_selection')
                                                    <a href="{{ route('hvc.thesis.select_experts', $thesis) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded-md text-sm font-medium transition-colors">
                                                        Select Experts
                                                    </a>
                                                @elseif($thesis->status === 'pending_viva_scheduling')
                                                    <a href="{{ route('hvc.viva.completed_evaluations') }}" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded-md text-sm font-medium transition-colors">
                                                        Schedule Viva
                                                    </a>
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
                            <div class="text-gray-500 text-lg">No thesis submissions are currently ready for expert selection.</div>
                            <div class="text-gray-400 text-sm mt-2">Thesis submissions become available after HVC approval.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
