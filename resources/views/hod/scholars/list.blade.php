<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholars in Your Department') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($scholars->isEmpty())
                        <p>No scholars found in your department.</p>
                    @else
                        <!-- Summary Statistics -->
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $scholars->count() }}</div>
                                <div class="text-sm text-blue-800">Total Scholars</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $scholars->where('status', 'supervisor_assigned')->count() }}</div>
                                <div class="text-sm text-green-800">Assigned Supervisors</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">{{ $scholars->where('status', 'pending_supervisor_assignment')->count() }}</div>
                                <div class="text-sm text-yellow-800">Pending Assignment</div>
                            </div>
                        </div>

                        <!-- Search and Filter Options -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                            <!-- Search -->
                            <div class="flex-1 max-w-lg">
                                <form method="GET" action="{{ route('hod.scholars.list') }}" class="flex space-x-2">
                                    <input type="text"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search by name or email..."
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Search
                                    </button>
                                    @if(request('search'))
                                        <a href="{{ route('hod.scholars.list') }}"
                                           class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm font-medium hover:bg-gray-600">
                                            Clear
                                        </a>
                                    @endif
                                </form>
                            </div>

                            <!-- Filter Options -->
                            <div class="flex space-x-2">
                                <a href="{{ request()->fullUrlWithQuery(['filter' => 'all', 'search' => null]) }}"
                                   class="px-4 py-2 rounded-md text-sm font-medium {{ request('filter', 'all') === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    All Scholars
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['filter' => 'assigned', 'search' => null]) }}"
                                   class="px-4 py-2 rounded-md text-sm font-medium {{ request('filter') === 'assigned' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Assigned Only
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['filter' => 'unassigned', 'search' => null]) }}"
                                   class="px-4 py-2 rounded-md text-sm font-medium {{ request('filter') === 'unassigned' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Unassigned Only
                                </a>
                            </div>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Supervisor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($scholars as $scholar)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $scholar->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $scholar->enrollment_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $scholar->user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($scholar->status === 'supervisor_assigned')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Supervisor Assigned
                                                </span>
                                            @elseif($scholar->status === 'pending_supervisor_assignment')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending Assignment
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst(str_replace('_', ' ', $scholar->status)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($scholar->currentSupervisor)
                                                <div class="text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $scholar->currentSupervisor->supervisor->designation }}</div>
                                                <div class="text-xs text-gray-400">Assigned: {{ $scholar->currentSupervisor->assigned_date->format('M d, Y') }}</div>
                                            @else
                                                <span class="text-sm text-gray-500 italic">No supervisor assigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex flex-col space-y-2">
                                                <div class="flex space-x-4">
                                                    <a href="{{ route('hod.scholars.show', $scholar) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                                    @if (!$scholar->currentSupervisor)
                                                        <a href="{{ route('hod.scholars.assign_supervisor', $scholar) }}" class="text-green-600 hover:text-green-900">Assign Supervisor</a>
                                                    @else
                                                        <span class="text-green-600 font-medium">✓ Supervisor Assigned</span>
                                                    @endif
                                                </div>
                                                @if($scholar->thesisSubmissions->count() > 0)
                                                    <div>
                                                        <a href="{{ route('hod.thesis.schedule_viva', $scholar->thesisSubmissions->first()) }}"
                                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Schedule Viva
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
