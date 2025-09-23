<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Thesis Evaluations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Assigned Thesis Evaluations</h3>
                        <p class="mt-1 text-sm text-gray-600">Thesis evaluations assigned to you for review and assessment.</p>
                    </div>

                    @if($evaluations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thesis Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($evaluations as $evaluation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $evaluation->thesisSubmission->scholar->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $evaluation->thesisSubmission->scholar->user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $evaluation->thesisSubmission->title }}">
                                                    {{ $evaluation->thesisSubmission->title }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $evaluation->thesisSubmission->supervisor->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $evaluation->assigned_date ? $evaluation->assigned_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($evaluation->due_date)
                                                    <span class="{{ $evaluation->due_date < now() && $evaluation->status !== 'submitted' ? 'text-red-600 font-medium' : '' }}">
                                                        {{ $evaluation->due_date->format('M d, Y') }}
                                                    </span>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'assigned' => 'bg-yellow-100 text-yellow-800',
                                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                                        'submitted' => 'bg-green-100 text-green-800',
                                                        'overdue' => 'bg-red-100 text-red-800',
                                                        'completed' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$evaluation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $evaluation->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($evaluation->status === 'assigned' || $evaluation->status === 'in_progress')
                                                    <a href="{{ route('expert.evaluations.submit', $evaluation) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded-md text-sm font-medium transition-colors">
                                                        Submit Evaluation
                                                    </a>
                                                @elseif($evaluation->status === 'submitted')
                                                    <span class="text-green-600 text-sm">Evaluation Submitted</span>
                                                @else
                                                    <span class="text-gray-500 text-sm">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">No thesis evaluations have been assigned to you yet.</div>
                            <div class="text-gray-400 text-sm mt-2">You will receive notifications when thesis evaluations are assigned to you.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
