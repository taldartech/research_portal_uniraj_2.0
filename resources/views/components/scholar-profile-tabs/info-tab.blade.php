@props(['scholar'])

<!-- Personal Information -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
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
            <label class="block text-sm font-medium text-gray-700">First Name</label>
            <p class="mt-1 text-sm text-gray-900">{{ $scholar->name ?? 'Not provided' }}</p>
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

<!-- Academic Information -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Department</label>
            <p class="mt-1 text-sm text-gray-900">{{ $scholar->admission->department->name ?? 'Not specified' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Admission Date</label>
            <p class="mt-1 text-sm text-gray-900">{{ $scholar->admission->admission_date ? $scholar->admission->admission_date->format('M d, Y') : 'Not provided' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Enrollment Number</label>
            <p class="mt-1 text-sm text-gray-900">{{ $scholar->enrollment_number ?? 'Not assigned' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Program</label>
            <p class="mt-1 text-sm text-gray-900">{{ $scholar->program ?? ($scholar->academic_information['program'] ?? 'Not provided') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Research Area</label>
            <p class="mt-1 text-sm text-gray-900">{{ $scholar->research_area ?? 'Not specified' }}</p>
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
    </div>
</div>

<!-- Examination Information -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Examination Information</h3>
    
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
</div>

