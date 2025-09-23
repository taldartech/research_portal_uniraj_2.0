<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholar Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholar Basic Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Enrollment Number</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->enrollment_number ?? 'Not assigned' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($scholar->status === 'supervisor_assigned') bg-green-100 text-green-800
                                    @elseif($scholar->status === 'pending_supervisor_assignment') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $scholar->status)) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->admission->department->name ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Research Area</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->research_area ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->date_of_birth ? $scholar->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gender</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($scholar->gender ?? 'Not provided') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->contact_number ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nationality</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->nationality ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Post Graduate Degree</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->post_graduate_degree ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Post Graduate University</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->post_graduate_university ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Post Graduate Year</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->post_graduate_year ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Post Graduate Percentage</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->post_graduate_percentage ?? 'Not provided' }}%</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">PhD Faculty</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->phd_faculty ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">PhD Subject</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->phd_subject ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Supervisor Assignment -->
                    @if($scholar->currentSupervisor)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Supervisor Assignment</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Designation</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->designation }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Research Specialization</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->supervisor->research_specialization ?? 'Not specified' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Assigned Date</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->currentSupervisor->assigned_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Progress Tracking -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Tracking</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $scholar->synopses->count() }}</div>
                                <div class="text-sm text-blue-800">Synopsis Submissions</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $scholar->progressReports->count() }}</div>
                                <div class="text-sm text-green-800">Progress Reports</div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ $scholar->thesisSubmissions->count() }}</div>
                                <div class="text-sm text-purple-800">Thesis Submissions</div>
                            </div>
                            <div class="bg-orange-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $scholar->vivaExaminations->count() }}</div>
                                <div class="text-sm text-orange-800">Viva Examinations</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('staff.scholars.list') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Scholars List
                        </a>

                        <div class="flex space-x-2">
                            <a href="{{ route('staff.scholars.verify_data', $scholar) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Verify Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
