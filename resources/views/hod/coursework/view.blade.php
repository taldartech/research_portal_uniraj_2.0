<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Coursework Results') }} - {{ $scholar->user->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hod.coursework.upload', $scholar) }}" class="text-indigo-600 hover:text-indigo-900">
                    Upload New Result
                </a>
                <a href="{{ route('hod.coursework.list') }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Scholar Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium">{{ $scholar->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $scholar->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="font-medium">
                                @if($scholar->coursework_completed)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($courseworkResults->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        No coursework results found for this scholar.
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">All Coursework Results</h3>
                        
                        <div class="space-y-4">
                            @foreach($courseworkResults as $result)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $result->result == 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ strtoupper($result->result) }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    Exam Date: {{ $result->exam_date->format('M d, Y') }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    Uploaded by: {{ $result->uploadedBy->name }}
                                                </span>
                                                <span class="text-sm text-gray-400">
                                                    {{ $result->created_at->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                            @if($result->remarks)
                                                <p class="text-sm text-gray-700 mt-2">{{ $result->remarks }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ asset('storage/' . $result->marksheet_file) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                View Marksheet
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

