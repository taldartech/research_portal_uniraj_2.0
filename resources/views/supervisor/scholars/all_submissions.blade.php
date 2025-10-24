<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Scholar Submissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                    <div class="text-2xl font-bold text-blue-600">{{ $scholars->count() }}</div>
                    <div class="text-sm text-blue-800">Total Scholars</div>
                </div>
                <div class="bg-green-50 p-6 rounded-lg shadow-md">
                    <div class="text-2xl font-bold text-green-600">{{ $synopses->count() }}</div>
                    <div class="text-sm text-green-800">Synopses</div>
                </div>
                <div class="bg-yellow-50 p-6 rounded-lg shadow-md">
                    <div class="text-2xl font-bold text-yellow-600">{{ $progressReports->count() }}</div>
                    <div class="text-sm text-yellow-800">Progress Reports</div>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg shadow-md">
                    <div class="text-2xl font-bold text-purple-600">{{ $thesisSubmissions->count() }}</div>
                    <div class="text-sm text-purple-800">Thesis Submissions</div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-6">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <button onclick="showTab('synopses')" id="synopses-tab" class="tab-button active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Synopses ({{ $synopses->count() }})
                    </button>
                    <button onclick="showTab('coursework')" id="coursework-tab" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Coursework Exemptions ({{ $courseworkExemptions->count() }})
                    </button>
                    <button onclick="showTab('progress')" id="progress-tab" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Progress Reports ({{ $progressReports->count() }})
                    </button>
                    <button onclick="showTab('thesis')" id="thesis-tab" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Thesis Submissions ({{ $thesisSubmissions->count() }})
                    </button>
                </nav>
            </div>

            <!-- Synopses Tab -->
            <div id="synopses-content" class="tab-content">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Synopsis Submissions</h3>
                        @if ($synopses->isEmpty())
                            <p class="text-gray-600">No synopsis submissions found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($synopses as $synopsis)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $synopsis->scholar->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $synopsis->proposed_topic }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $synopsis->submission_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusColors = [
                                                            'pending_supervisor_approval' => 'bg-yellow-100 text-yellow-800',
                                                            'pending_hod_approval' => 'bg-blue-100 text-blue-800',
                                                            'pending_da_approval' => 'bg-indigo-100 text-indigo-800',
                                                            'pending_so_approval' => 'bg-purple-100 text-purple-800',
                                                            'pending_ar_approval' => 'bg-pink-100 text-pink-800',
                                                            'pending_dr_approval' => 'bg-red-100 text-red-800',
                                                            'pending_hvc_approval' => 'bg-orange-100 text-orange-800',
                                                            'approved' => 'bg-green-100 text-green-800',
                                                            'rejected' => 'bg-red-100 text-red-800',
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$synopsis->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $synopsis->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($synopsis->status === 'pending_supervisor_approval')
                                                        <a href="{{ route('staff.scholars.review', $synopsis->scholar) }}" class="text-indigo-600 hover:text-indigo-900">Review & Approve</a>
                                                    @else
                                                        <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-blue-600 hover:text-blue-900">View File</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Coursework Exemptions Tab -->
            <div id="coursework-content" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Coursework Exemption Requests</h3>
                        @if ($courseworkExemptions->isEmpty())
                            <p class="text-gray-600">No coursework exemption requests found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($courseworkExemptions as $exemption)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $exemption->scholar->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $exemption->reason }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $exemption->request_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusColors = [
                                                            'pending_supervisor_approval' => 'bg-yellow-100 text-yellow-800',
                                                            'pending_dean_approval' => 'bg-blue-100 text-blue-800',
                                                            'pending_da_approval' => 'bg-indigo-100 text-indigo-800',
                                                            'pending_so_approval' => 'bg-purple-100 text-purple-800',
                                                            'pending_ar_approval' => 'bg-pink-100 text-pink-800',
                                                            'pending_dr_approval' => 'bg-red-100 text-red-800',
                                                            'pending_hvc_approval' => 'bg-orange-100 text-orange-800',
                                                            'approved' => 'bg-green-100 text-green-800',
                                                            'rejected' => 'bg-red-100 text-red-800',
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$exemption->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $exemption->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($exemption->status === 'pending_supervisor_approval')
                                                        <a href="{{ route('staff.coursework_exemption.approve', $exemption) }}" class="text-indigo-600 hover:text-indigo-900">Review & Approve</a>
                                                    @else
                                                        <span class="text-gray-500">View Details</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Progress Reports Tab -->
            <div id="progress-content" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Reports</h3>
                        @if ($progressReports->isEmpty())
                            <p class="text-gray-600">No progress reports found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Period</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($progressReports as $report)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $report->scholar->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $report->report_period }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $report->submission_date ? $report->submission_date->format('M d, Y') : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusColors = [
                                                            'pending_supervisor_approval' => 'bg-yellow-100 text-yellow-800',
                                                            'pending_hod_approval' => 'bg-blue-100 text-blue-800',
                                                            'pending_da_approval' => 'bg-indigo-100 text-indigo-800',
                                                            'pending_so_approval' => 'bg-purple-100 text-purple-800',
                                                            'pending_ar_approval' => 'bg-pink-100 text-pink-800',
                                                            'pending_dr_approval' => 'bg-red-100 text-red-800',
                                                            'pending_hvc_approval' => 'bg-orange-100 text-orange-800',
                                                            'approved' => 'bg-green-100 text-green-800',
                                                            'rejected' => 'bg-red-100 text-red-800',
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($report->status === 'pending_supervisor_approval')
                                                        <a href="{{ route('staff.progress_reports.approve', $report) }}" class="text-indigo-600 hover:text-indigo-900">Review & Approve</a>
                                                    @else
                                                        <a href="{{ route('staff.progress_reports.show', $report) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thesis Submissions Tab -->
            <div id="thesis-content" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thesis Submissions</h3>
                        @if ($thesisSubmissions->isEmpty())
                            <p class="text-gray-600">No thesis submissions found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($thesisSubmissions as $thesis)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $thesis->scholar->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $thesis->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $thesis->submission_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusColors = [
                                                            'pending_supervisor_approval' => 'bg-yellow-100 text-yellow-800',
                                                            'pending_hod_approval' => 'bg-blue-100 text-blue-800',
                                                            'pending_da_approval' => 'bg-indigo-100 text-indigo-800',
                                                            'pending_so_approval' => 'bg-purple-100 text-purple-800',
                                                            'pending_ar_approval' => 'bg-pink-100 text-pink-800',
                                                            'pending_dr_approval' => 'bg-red-100 text-red-800',
                                                            'pending_hvc_approval' => 'bg-orange-100 text-orange-800',
                                                            'approved' => 'bg-green-100 text-green-800',
                                                            'rejected' => 'bg-red-100 text-red-800',
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$thesis->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($thesis->status === 'pending_supervisor_approval')
                                                        <a href="{{ route('staff.thesis.approve', $thesis) }}" class="text-indigo-600 hover:text-indigo-900">Review & Approve</a>
                                                    @else
                                                        <a href="{{ Storage::url($thesis->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900">View File</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-button {
            border-color: transparent;
            color: #6b7280;
        }
        .tab-button.active {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        .tab-button:hover {
            color: #374151;
            border-color: #d1d5db;
        }
    </style>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));

            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab-button');
            tabs.forEach(tab => tab.classList.remove('active'));

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');

            // Add active class to selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
        }
    </script>
</x-app-layout>
