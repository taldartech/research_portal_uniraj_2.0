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
                    <div class="mb-8">
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
                    <div class="mb-8">
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
                    <div class="mb-8">
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
                        <div class="mb-8">
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
                    <div class="mb-8">
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

                    <!-- Supervisor Information (from Scholar fields) -->
                    @if($scholar->supervisor_name || $scholar->supervisor_designation || $scholar->supervisor_department)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Supervisor Information (Stored)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($scholar->supervisor_name)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->supervisor_name }}</p>
                                    </div>
                                @endif
                                @if($scholar->supervisor_designation)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Supervisor Designation</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->supervisor_designation }}</p>
                                    </div>
                                @endif
                                @if($scholar->supervisor_department)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Supervisor Department</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->supervisor_department }}</p>
                                    </div>
                                @endif
                                @if($scholar->supervisor_college)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Supervisor College</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->supervisor_college }}</p>
                                    </div>
                                @endif
                                @if($scholar->supervisor_address)
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Supervisor Address</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->supervisor_address }}</p>
                                    </div>
                                @endif
                                @if($scholar->supervisor_letter_number)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Supervisor Letter Number</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->supervisor_letter_number }}</p>
                                    </div>
                                @endif
                                @if($scholar->supervisor_letter_date)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Supervisor Letter Date</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->supervisor_letter_date->format('M d, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Co-Supervisor Information -->
                    @if($scholar->has_co_supervisor && ($scholar->co_supervisor_name || $scholar->co_supervisor_designation))
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Co-Supervisor Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($scholar->co_supervisor_name)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Co-Supervisor Name</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->co_supervisor_name }}</p>
                                    </div>
                                @endif
                                @if($scholar->co_supervisor_designation)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Co-Supervisor Designation</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->co_supervisor_designation }}</p>
                                    </div>
                                @endif
                                @if($scholar->co_supervisor_reasons)
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Reasons for Co-Supervisor</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->co_supervisor_reasons }}</p>
                                    </div>
                                @endif
                                @if($scholar->co_supervisor_letter_number)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Co-Supervisor Letter Number</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->co_supervisor_letter_number }}</p>
                                    </div>
                                @endif
                                @if($scholar->co_supervisor_letter_date)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Co-Supervisor Letter Date</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $scholar->co_supervisor_letter_date->format('M d, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Examination Information -->
                    <div class="mb-8">
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
                        <div class="mb-8">
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
                        <div class="mb-8">
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

                    <!-- Registration Form Status -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Registration Form Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Form Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($scholar->registration_form_status === 'submitted') bg-blue-100 text-blue-800
                                    @elseif($scholar->registration_form_status === 'completed') bg-green-100 text-green-800
                                    @elseif($scholar->registration_form_status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @elseif($scholar->registration_form_status === 'approved') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $scholar->registration_form_status ?? 'not_started')) }}
                                </span>
                            </div>
                            @if($scholar->registration_form_submitted_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Form Submitted At</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $scholar->registration_form_submitted_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Form Completion Progress</label>
                                <div class="mt-2">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($scholar->getRegistrationFormProgress(), 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $scholar->getRegistrationFormProgress() }}%"></div>
                                    </div>
                                </div>
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
                                @php
                                    $totalSynopsisCount = ($scholar->hasSubmittedSynopsis() ? 1 : 0) + $scholar->synopses->count();
                                @endphp
                                <div class="text-2xl font-bold text-blue-600">{{ $totalSynopsisCount }}</div>
                                <div class="text-sm text-blue-800">Synopsis Submissions</div>
                                @if($totalSynopsisCount > 0)
                                    <div class="text-xs text-blue-600 mt-1">
                                        @if($scholar->hasSubmittedSynopsis() && $scholar->synopses->count() > 0)
                                            Registration + {{ $scholar->synopses->count() }} Formal
                                        @elseif($scholar->hasSubmittedSynopsis())
                                            Registration Form
                                        @else
                                            {{ $scholar->synopses->count() }} Formal
                                        @endif
                                    </div>
                                @endif
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

                    <!-- RAC Committee Submission History -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">RAC Committee Members - Complete History</h3>
                        @php
                            $racSubmissions = \App\Models\RACCommitteeSubmission::where('scholar_id', $scholar->id)
                                ->with('supervisor.user', 'supervisor.department', 'hod')
                                ->latest()
                                ->get();
                        @endphp

                        @if($racSubmissions->isEmpty())
                            <p class="text-gray-500 mb-4">No RAC committee submissions yet.</p>
                        @else
                            <div class="space-y-4 mb-4">
                                @foreach($racSubmissions as $index => $racSubmission)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div class="flex justify-between items-start mb-3">
                                            <h4 class="text-md font-semibold text-gray-900">
                                                Submission #{{ $racSubmissions->count() - $index }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($racSubmission->status === 'approved') bg-green-100 text-green-800
                                                @elseif($racSubmission->status === 'pending_hod_approval') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucwords(str_replace('_', ' ', $racSubmission->status)) }}
                                            </span>
                                        </div>

                                        <div class="space-y-3 mb-4">
                                            <div class="border-l-4 border-indigo-500 pl-4 py-2">
                                                <p class="text-sm font-semibold text-gray-900">1. Supervisor</p>
                                                <p class="text-sm text-gray-700">{{ $racSubmission->supervisor->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->supervisor->designation ?? 'N/A' }}, {{ $racSubmission->supervisor->department->name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="border-l-4 border-indigo-500 pl-4 py-2">
                                                <p class="text-sm font-semibold text-gray-900">2. Member 1</p>
                                                <p class="text-sm text-gray-700">{{ $racSubmission->member1_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->member1_designation ?? 'N/A' }}, {{ $racSubmission->member1_department ?? 'N/A' }}</p>
                                            </div>
                                            <div class="border-l-4 border-indigo-500 pl-4 py-2">
                                                <p class="text-sm font-semibold text-gray-900">3. Member 2</p>
                                                <p class="text-sm text-gray-700">{{ $racSubmission->member2_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->member2_designation ?? 'N/A' }}, {{ $racSubmission->member2_department ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Submitted By</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $racSubmission->supervisor->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $racSubmission->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            @if($racSubmission->drc_date)
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">DRC Date</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $racSubmission->drc_date->format('M d, Y') }}</p>
                                                </div>
                                            @endif
                                            @if($racSubmission->hod)
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Reviewed By</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $racSubmission->hod->name }}</p>
                                                    @if($racSubmission->approved_at)
                                                        <p class="text-xs text-gray-500">Approved: {{ $racSubmission->approved_at->format('M d, Y H:i') }}</p>
                                                    @elseif($racSubmission->rejected_at)
                                                        <p class="text-xs text-gray-500">Rejected: {{ $racSubmission->rejected_at->format('M d, Y H:i') }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($racSubmission->hod_remarks)
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">HOD Remarks/Comments</label>
                                                    <div class="bg-white p-3 rounded-md border border-gray-200">
                                                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $racSubmission->hod_remarks }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(auth()->user()->user_type === 'supervisor' && $scholar->supervisorAssignments()->where('supervisor_id', auth()->user()->supervisor->id)->where('status', 'assigned')->exists())
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('staff.rac_committee.submit', $scholar) }}"
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    @if($racSubmissions->first() && $racSubmissions->first()->status === 'pending_hod_approval')
                                        Update RAC Committee Members
                                    @else
                                        Submit/Update RAC Committee Members
                                    @endif
                                </a>
                                
                                @if(isset($canSubmitInfo) && $canSubmitInfo['can_submit'])
                                    <a href="{{ route('staff.progress_report.submit.for_scholar', $scholar) }}"
                                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Submit Progress Report ({{ $canSubmitInfo['report_period'] }})
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('staff.scholars.list') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Scholars List
                        </a>

                        <div class="flex space-x-2">
                            <a href="{{ route('staff.scholars.review', $scholar) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Review Scholar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
