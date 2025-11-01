<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Scholar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Review Scholar: {{ $scholar->user->name }}</h3>

                    <!-- Scholar Basic Information -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
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
                    <div class="mb-8 p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->first_name ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->last_name ?? 'Not provided' }}</p>
                            </div>
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
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->user->email }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->address ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Family Information -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Family Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Father's Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->father_name ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mother's Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->mother_name ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nationality</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->nationality ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->category ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Occupation</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->occupation ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Working as Teacher</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->is_teacher ? 'Yes' : 'No' }}</p>
                            </div>
                            @if($scholar->is_teacher && $scholar->teacher_employer)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Teacher Employer Details</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->teacher_employer }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Academic Qualifications -->
                    <div class="mb-8 p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Qualifications</h3>
                        @if($scholar->academic_qualifications && count($scholar->academic_qualifications) > 0)
                            <div class="space-y-4">
                                @foreach($scholar->academic_qualifications as $index => $qualification)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h4 class="text-md font-medium text-gray-900 mb-3">Qualification {{ $index + 1 }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Degree/Qualification</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $qualification['degree'] ?? 'Not provided' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">University/Institution</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $qualification['university'] ?? 'Not provided' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Year of Completion</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $qualification['year'] ?? 'Not provided' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Percentage/CGPA</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $qualification['percentage'] ?? 'Not provided' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
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
                            </div>
                        @endif
                    </div>

                    <!-- PhD Information -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">PhD Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">PhD Faculty</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->phd_faculty ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">PhD Subject</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->phd_subject ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Research Area</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->research_area ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Research Information -->
                    @if($scholar->research_topic_title || $scholar->research_scheme_outline || $scholar->research_bibliography)
                        <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Research Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                @if($scholar->research_topic_title)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Research Topic Title</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->research_topic_title }}</p>
                                    </div>
                                @endif
                                @if($scholar->research_scheme_outline)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Research Scheme Outline</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->research_scheme_outline }}</p>
                                    </div>
                                @endif
                                @if($scholar->research_bibliography)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Research Bibliography</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->research_bibliography }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Admission & Enrollment Information -->
                    <div class="mb-8 p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Admission & Enrollment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Form Number</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->form_number ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Enrollment Number</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->enrollment_number ?? 'Not assigned' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date of Confirmation</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $scholar->date_of_confirmation ? $scholar->date_of_confirmation->format('M d, Y') : 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Enrollment Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($scholar->enrollment_status === 'enrolled') bg-green-100 text-green-800
                                    @elseif($scholar->enrollment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($scholar->enrollment_status ?? 'not_enrolled') }}
                                </span>
                            </div>
                            @if($scholar->enrolled_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Enrolled At</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->enrolled_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Examination Information -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Examination Information</h3>

                        <!-- NET/SLET/CSIR/GATE Information -->
                        @if($scholar->net_slet_csir_gate_exam || $scholar->net_slet_csir_gate_year || $scholar->net_slet_csir_gate_roll_number)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-900 mb-3">NET/SLET/CSIR/GATE</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Exam Type</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->net_slet_csir_gate_exam ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Exam Year</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->net_slet_csir_gate_year ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Roll Number</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->net_slet_csir_gate_roll_number ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- PhD admission Information Information -->
                        @if($scholar->mpat_year || $scholar->mpat_roll_number || $scholar->mpat_merit_number || $scholar->mpat_subject)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-900 mb-3">PhD admission Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">PhD admission Information Year</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->mpat_year ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Roll Number</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->mpat_roll_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Merit Number</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->mpat_merit_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Subject</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->mpat_subject ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Coursework Information -->
                        @if($scholar->coursework_exam_date || $scholar->coursework_marks_obtained || $scholar->coursework_max_marks)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-900 mb-3">Coursework</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Exam Date</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->coursework_exam_date ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Marks Obtained</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->coursework_marks_obtained ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Maximum Marks</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->coursework_max_marks ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Other Examination Information -->
                        @if($scholar->appearing_other_exam)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-900 mb-3">Other Examinations</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Intending to appear at other examinations</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->appearing_other_exam ? 'Yes' : 'No' }}</p>
                                    </div>
                                    @if($scholar->other_exam_details)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Examination Details</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $scholar->other_exam_details }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Synopsis Information -->
                    @if($scholar->synopsis_topic || $scholar->synopsis_file || $scholar->synopsis_submitted_at || $scholar->synopses->isNotEmpty())
                        <div class="mb-8 p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Synopsis Information</h3>
                            <!-- Formal Synopsis Submissions -->
                            @if($scholar->synopses->isNotEmpty())
                                <div class="mb-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-3">Formal Synopsis Submissions</h4>
                                    <div class="space-y-4">
                                        @foreach($scholar->synopses as $synopsis)
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Proposed Topic</label>
                                                        <p class="mt-1 text-sm text-gray-900">{{ $synopsis->proposed_topic ?? 'Not provided' }}</p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($synopsis->status === 'approved') bg-green-100 text-green-800
                                                            @elseif(str_starts_with($synopsis->status, 'rejected')) bg-red-100 text-red-800
                                                            @elseif(str_starts_with($synopsis->status, 'pending')) bg-yellow-100 text-yellow-800
                                                            @else bg-gray-100 text-gray-800 @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $synopsis->status)) }}
                                                        </span>
                                                    </div>
                                                    @if($synopsis->submission_date)
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Submission Date</label>
                                                            <p class="mt-1 text-sm text-gray-900">{{ $synopsis->submission_date->format('M d, Y') }}</p>
                                                        </div>
                                                    @endif
                                                    @if($synopsis->synopsis_file)
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Synopsis File</label>
                                                            <p class="mt-1 text-sm text-gray-900">
                                                                <a href="{{ Storage::url($synopsis->synopsis_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                                    View Synopsis Document
                                                                </a>
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Document Information -->
                    @if($scholar->registration_documents && count($scholar->registration_documents) > 0)
                        <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Uploaded Documents</h3>
                            <div class="space-y-3">
                                @foreach($scholar->registration_documents as $index => $document)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $document['filename'] ?? 'Document ' . ($index + 1) }}</p>
                                                @if(isset($document['uploaded_at']))
                                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($document['uploaded_at'])->format('M d, Y H:i') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if(isset($document['path']))
                                            <a href="{{ Storage::url($document['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                                View Document
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    <!-- Action Form -->
                    <form method="POST" action="{{ route('staff.scholars.review.update', $scholar) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Fee Receipt Section (for synopsis approval) -->
                        @if($synopsis && $synopsis->status === 'pending_supervisor_approval' && $scholar->fee_receipt_file)
                            <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-blue-50">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Fee Receipt</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Uploaded Fee Receipt</label>
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ Storage::url($scholar->fee_receipt_file) }}" target="_blank"
                                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View Fee Receipt
                                            </a>
                                            @if($scholar->fee_receipt_submitted_at)
                                                <span class="text-xs text-gray-500">
                                                    Submitted on: {{ $scholar->fee_receipt_submitted_at->format('M d, Y H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($scholar->transaction_amount || $scholar->transaction_date || $scholar->transaction_number || $scholar->pay_mode)
                                            <div class="mt-3 p-3 bg-white rounded border border-gray-200">
                                                <p class="text-xs font-semibold text-gray-700 mb-2">Transaction Details:</p>
                                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                                    @if($scholar->transaction_amount)
                                                        <span><strong>Amount:</strong> ₹{{ number_format($scholar->transaction_amount, 2) }}</span>
                                                    @endif
                                                    @if($scholar->transaction_date)
                                                        <span><strong>Date:</strong> {{ $scholar->transaction_date->format('M d, Y') }}</span>
                                                    @endif
                                                    @if($scholar->transaction_number)
                                                        <span><strong>Transaction No:</strong> {{ $scholar->transaction_number }}</span>
                                                    @endif
                                                    @if($scholar->pay_mode)
                                                        <span><strong>Payment Mode:</strong> {{ $scholar->pay_mode }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="fee_receipt_verified" name="fee_receipt_verified" value="1"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                               required>
                                        <label for="fee_receipt_verified" class="ml-2 block text-sm text-gray-900">
                                            <span class="font-medium">I confirm that I have verified the fee receipt</span>
                                            <span class="text-red-500">*</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">You must verify the fee receipt before approving or rejecting the synopsis.</p>
                                    <x-input-error :messages="$errors->get('fee_receipt_verified')" class="mt-2" />
                                </div>
                            </div>
                        @endif

                        <!-- Research Topic (for synopsis approval) -->
                        @if($synopsis && $synopsis->status === 'pending_supervisor_approval')
                            <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Research Topic</h4>
                                <div>
                                    <label for="research_topic" class="block text-sm font-medium text-gray-700">Research Topic <span class="text-red-500">*</span></label>
                                    <input type="text" id="research_topic" name="research_topic"
                                               class="mt-1 block w-full text-sm text-gray-500" placeholder="Enter the scholar's research topic..." value="{{ old('research_topic', $scholar->research_topic) }}" required/>
                                    <p class="mt-1 text-sm text-gray-500">This will be set as the scholar's research topic after synopsis approval.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea id="remarks" name="remarks" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Enter your remarks...">{{ old('remarks') }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <!-- RAC Minutes (only for synopsis approval/rejection) -->
                        <div id="rac-minutes-section" class="mb-6 p-4 border border-gray-200 rounded-lg bg-yellow-50">
                            <h4 class="text-md font-medium text-gray-900 mb-4">RAC Minutes Information</h4>
                            <div class="space-y-4">
                                <div>
                                    <label for="rac_minutes_file" class="block text-sm font-medium text-gray-700">
                                        RAC Minutes File <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" id="rac_minutes_file" name="rac_minutes_file" accept=".pdf,.doc,.docx"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                    <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX (Max size: 5MB)</p>
                                    <x-input-error :messages="$errors->get('rac_minutes_file')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="rac_meeting_date" class="block text-sm font-medium text-gray-700">
                                        RAC Meeting Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="rac_meeting_date" name="rac_meeting_date"
                                            value="{{ old('rac_meeting_date') }}" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <x-input-error :messages="$errors->get('rac_meeting_date')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="action" class="block text-sm font-medium text-gray-700">
                                        Action <span class="text-red-500">*</span>
                                    </label>
                                    <select id="action" name="action" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="approve_synopsis">Approve Synopsis</option>
                                        <option value="reject_synopsis">Reject Synopsis</option>
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('action')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionRadios = document.querySelectorAll('input[name="action"]');
            const racMinutesSection = document.getElementById('rac-minutes-section');
            const racMinutesFile = document.getElementById('rac_minutes_file');
            const racMeetingDate = document.getElementById('rac_meeting_date');
            const form = document.querySelector('form[method="POST"]');

            function updateRACFieldsRequirement() {
                const selectedAction = document.querySelector('input[name="action"]:checked');

                if (selectedAction && (selectedAction.value === 'approve_synopsis' || selectedAction.value === 'reject_synopsis')) {
                    // Show RAC minutes section
                    if (racMinutesSection) {
                        racMinutesSection.style.display = 'block';
                    }
                    // Make fields required
                    if (racMinutesFile) {
                        racMinutesFile.setAttribute('required', 'required');
                        racMinutesFile.setAttribute('aria-required', 'true');
                    }
                    if (racMeetingDate) {
                        racMeetingDate.setAttribute('required', 'required');
                        racMeetingDate.setAttribute('aria-required', 'true');
                    }
                } else {
                    // Hide RAC minutes section
                    if (racMinutesSection) {
                        racMinutesSection.style.display = 'none';
                    }
                    // Remove required attribute
                    if (racMinutesFile) {
                        racMinutesFile.removeAttribute('required');
                        racMinutesFile.removeAttribute('aria-required');
                        racMinutesFile.value = '';
                    }
                    if (racMeetingDate) {
                        racMeetingDate.removeAttribute('required');
                        racMeetingDate.removeAttribute('aria-required');
                        racMeetingDate.value = '';
                    }
                }
            }

            // Add event listeners to all action radio buttons
            if (actionRadios.length > 0) {
                actionRadios.forEach(radio => {
                    radio.addEventListener('change', updateRACFieldsRequirement);
                });

                // Check initial state
                updateRACFieldsRequirement();
            }

            // Add form validation before submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    const selectedAction = document.querySelector('select[name="action"]');
                    const feeReceiptVerified = document.getElementById('fee_receipt_verified');

                    if (selectedAction && (selectedAction.value === 'approve_synopsis' || selectedAction.value === 'reject_synopsis')) {
                        // Validate fee receipt verification if fee receipt exists
                        if (feeReceiptVerified && !feeReceiptVerified.checked) {
                            e.preventDefault();
                            alert('Please confirm that you have verified the fee receipt before submitting.');
                            feeReceiptVerified.focus();
                            return false;
                        }

                        // Validate RAC minutes file
                        if (racMinutesFile && racMinutesFile.files.length === 0) {
                            e.preventDefault();
                            alert('Please upload RAC minutes file.');
                            racMinutesFile.focus();
                            return false;
                        }

                        // Validate RAC meeting date
                        if (racMeetingDate && !racMeetingDate.value) {
                            e.preventDefault();
                            alert('Please select RAC meeting date.');
                            racMeetingDate.focus();
                            return false;
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
