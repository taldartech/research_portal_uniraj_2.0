<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registration Forms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Generated Registration Forms</h3>
                        <p class="mt-1 text-sm text-gray-600">Track the status of all generated registration forms and their signing progress.</p>
                    </div>

                    @if($registrationForms->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispatch Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signatures</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Downloads</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($registrationForms as $form)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $form->scholar->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $form->scholar->user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $form->dispatch_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $form->generated_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'generated' => 'bg-yellow-100 text-yellow-800',
                                                        'signed_by_dr' => 'bg-blue-100 text-blue-800',
                                                        'signed_by_ar' => 'bg-purple-100 text-purple-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'downloaded' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$form->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $form->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="space-y-1">
                                                    <div class="flex items-center">
                                                        <span class="text-xs {{ $form->isSignedByDR() ? 'text-green-600' : 'text-gray-400' }}">
                                                            DR: {{ $form->isSignedByDR() ? '✓' : '✗' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="text-xs {{ $form->isSignedByAR() ? 'text-green-600' : 'text-gray-400' }}">
                                                            AR: {{ $form->isSignedByAR() ? '✓' : '✗' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $form->download_count }} times
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">No registration forms have been generated yet.</div>
                            <div class="text-gray-400 text-sm mt-2">Generate registration forms for eligible scholars to begin the enrollment process.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
