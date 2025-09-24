<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Ph.D. Registration Form') }}
                </h2>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('scholar.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('scholar.profile.edit') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Profile</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Ph.D. Registration</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('scholar.profile.edit') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Profile
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Status Summary Card -->
                    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Registration Status</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Track your Ph.D. registration progress</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @switch($scholar->registration_form_status)
                                        @case('not_started')
                                            bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @break
                                        @case('in_progress')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                            @break
                                        @case('completed')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @break
                                        @case('submitted')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endswitch">
                                    @switch($scholar->registration_form_status)
                                        @case('not_started')
                                            Not Started
                                            @break
                                        @case('in_progress')
                                            In Progress
                                            @break
                                        @case('completed')
                                            Completed
                                            @break
                                        @case('submitted')
                                            Submitted
                                            @break
                                        @default
                                            Not Started
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Form Completion Progress</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($scholar->getRegistrationFormProgress(), 0) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-600">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $scholar->getRegistrationFormProgress() }}%"></div>
                            </div>
                        </div>

                        <!-- Certificate Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $scholar->supervisor_certificate_completed ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Supervisor Certificate</span>
                                </div>
                                <span class="text-sm {{ $scholar->supervisor_certificate_completed ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $scholar->supervisor_certificate_completed ? 'Completed' : 'Pending' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $scholar->hod_certificate_completed ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">HOD Certificate</span>
                                </div>
                                <span class="text-sm {{ $scholar->hod_certificate_completed ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $scholar->hod_certificate_completed ? 'Completed' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('scholar.registration.phd_form.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('patch')

                        <!-- Personal Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Personal Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Basic Profile Details -->
                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                                        :value="old('first_name', $scholar->first_name)" required />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                                        :value="old('last_name', $scholar->last_name)" required />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email Address')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                        :value="old('email', $scholar->user->email)" required readonly />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Email cannot be changed</p>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="contact_number" :value="__('Mobile Number')" />
                                    <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full"
                                        :value="old('contact_number', $scholar->contact_number)" required />
                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                    <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full"
                                        :value="old('date_of_birth', $scholar->date_of_birth ? $scholar->date_of_birth->format('Y-m-d') : '')" required />
                                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Gender')" />
                                    <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $scholar->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $scholar->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $scholar->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Address')" />
                                    <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('address', $scholar->address) }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="research_area" :value="__('Research Area')" />
                                    <x-text-input id="research_area" name="research_area" type="text" class="mt-1 block w-full"
                                        :value="old('research_area', $scholar->research_area)" required />
                                    <x-input-error :messages="$errors->get('research_area')" class="mt-2" />
                                </div>

                            </div>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Family Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="father_name" :value="__('Father\'s Name')" />
                                    <x-text-input id="father_name" name="father_name" type="text" class="mt-1 block w-full"
                                        :value="old('father_name', $scholar->father_name)" required />
                                    <x-input-error :messages="$errors->get('father_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="mother_name" :value="__('Mother\'s Name')" />
                                    <x-text-input id="mother_name" name="mother_name" type="text" class="mt-1 block w-full"
                                        :value="old('mother_name', $scholar->mother_name)" required />
                                    <x-input-error :messages="$errors->get('mother_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="nationality" :value="__('Nationality')" />
                                    <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full"
                                        :value="old('nationality', $scholar->nationality)" required />
                                    <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="category" :value="__('Category')" />
                                    <select id="category" name="category" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Select Category</option>
                                        <option value="General" {{ old('category', $scholar->category) == 'General' ? 'selected' : '' }}>General</option>
                                        <option value="SC" {{ old('category', $scholar->category) == 'SC' ? 'selected' : '' }}>SC</option>
                                        <option value="ST" {{ old('category', $scholar->category) == 'ST' ? 'selected' : '' }}>ST</option>
                                        <option value="OBC" {{ old('category', $scholar->category) == 'OBC' ? 'selected' : '' }}>OBC</option>
                                        <option value="MBC" {{ old('category', $scholar->category) == 'MBC' ? 'selected' : '' }}>MBC</option>
                                        <option value="EWS" {{ old('category', $scholar->category) == 'EWS' ? 'selected' : '' }}>EWS</option>
                                        <option value="P.H." {{ old('category', $scholar->category) == 'P.H.' ? 'selected' : '' }}>P.H.</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="occupation" :value="__('Occupation')" />
                                    <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full"
                                        :value="old('occupation', $scholar->occupation)" required />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">(N.O.C. from the employer be attached)</p>
                                    <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="is_teacher" :value="__('Are you working as teacher in University Teaching Deptt./affiliated College?')" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="is_teacher" value="1" class="form-radio"
                                                {{ old('is_teacher', $scholar->is_teacher) == 1 ? 'checked' : '' }}>
                                            <span class="ml-2">Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="is_teacher" value="0" class="form-radio"
                                                {{ old('is_teacher', $scholar->is_teacher) == 0 ? 'checked' : '' }}>
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">If teacher, Employer certificate should be enclosed.</p>
                                    <x-input-error :messages="$errors->get('is_teacher')" class="mt-2" />
                                </div>

                                <div id="teacher_employer_field" class="{{ old('is_teacher', $scholar->is_teacher) ? '' : 'hidden' }}">
                                    <x-input-label for="teacher_employer" :value="__('Teacher Employer Details')" />
                                    <x-text-input id="teacher_employer" name="teacher_employer" type="text" class="mt-1 block w-full"
                                        :value="old('teacher_employer', $scholar->teacher_employer)" />
                                    <x-input-error :messages="$errors->get('teacher_employer')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Examination Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Examination Information</h3>

                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="appearing_other_exam" :value="__('Do you intend to appear at any other examination of the University or of any other University during the period of research?')" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="appearing_other_exam" value="1" class="form-radio"
                                                {{ old('appearing_other_exam', $scholar->appearing_other_exam) == 1 ? 'checked' : '' }}>
                                            <span class="ml-2">Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="appearing_other_exam" value="0" class="form-radio"
                                                {{ old('appearing_other_exam', $scholar->appearing_other_exam) == 0 ? 'checked' : '' }}>
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('appearing_other_exam')" class="mt-2" />
                                </div>

                                <div id="other_exam_details_field" class="{{ old('appearing_other_exam', $scholar->appearing_other_exam) ? '' : 'hidden' }}">
                                    <x-input-label for="other_exam_details" :value="__('If yes, please mention the name of the examination')" />
                                    <textarea id="other_exam_details" name="other_exam_details" rows="3"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('other_exam_details', $scholar->other_exam_details) }}</textarea>
                                    <x-input-error :messages="$errors->get('other_exam_details')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Academic Qualifications Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Academic Qualifications</h3>

                            <div class="space-y-6" id="academic-qualifications-container">
                                @php
                                    $savedQualifications = $scholar->academic_qualifications ?? [];
                                    $qualificationCount = max(1, count($savedQualifications));
                                @endphp

                                @for($i = 0; $i < $qualificationCount; $i++)
                                    <div class="academic-qualification-item border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Qualification {{ $i + 1 }}</h4>
                                            <button type="button" class="remove-qualification-btn text-red-600 hover:text-red-800 text-sm font-medium" style="display: {{ $qualificationCount > 1 ? 'block' : 'none' }};">
                                                Remove
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <x-input-label for="post_graduate_degree_{{ $i + 1 }}" :value="__('Degree/Qualification')" />
                                                <x-text-input id="post_graduate_degree_{{ $i + 1 }}" name="post_graduate_degrees[]" type="text" class="mt-1 block w-full"
                                                    :value="old('post_graduate_degrees.' . $i, $savedQualifications[$i]['degree'] ?? $scholar->post_graduate_degree)" placeholder="e.g., M.Sc., M.A., M.Tech, etc." required />
                                                <x-input-error :messages="$errors->get('post_graduate_degrees')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="post_graduate_university_{{ $i + 1 }}" :value="__('University/Institution')" />
                                                <x-text-input id="post_graduate_university_{{ $i + 1 }}" name="post_graduate_universities[]" type="text" class="mt-1 block w-full"
                                                    :value="old('post_graduate_universities.' . $i, $savedQualifications[$i]['university'] ?? $scholar->post_graduate_university)" placeholder="Name of university or institution" required />
                                                <x-input-error :messages="$errors->get('post_graduate_universities')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="post_graduate_year_{{ $i + 1 }}" :value="__('Year of Completion')" />
                                                <x-text-input id="post_graduate_year_{{ $i + 1 }}" name="post_graduate_years[]" type="text" class="mt-1 block w-full"
                                                    :value="old('post_graduate_years.' . $i, $savedQualifications[$i]['year'] ?? $scholar->post_graduate_year)" placeholder="e.g., 2020" required />
                                                <x-input-error :messages="$errors->get('post_graduate_years')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="post_graduate_percentage_{{ $i + 1 }}" :value="__('Percentage/CGPA')" />
                                                <x-text-input id="post_graduate_percentage_{{ $i + 1 }}" name="post_graduate_percentages[]" type="number" step="0.01" class="mt-1 block w-full"
                                                    :value="old('post_graduate_percentages.' . $i, $savedQualifications[$i]['percentage'] ?? $scholar->post_graduate_percentage)" placeholder="e.g., 85.5 or 8.5" required />
                                                <x-input-error :messages="$errors->get('post_graduate_percentages')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <div class="mt-4">
                                <button type="button" id="add-qualification-btn" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Another Qualification
                                </button>
                            </div>

                            <!-- NET/SLET/CSIR/GATE Information -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">NET/SLET/CSIR/GATE Information</h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="net_slet_csir_gate_exam" :value="__('Exam Type')" />
                                        <select id="net_slet_csir_gate_exam" name="net_slet_csir_gate_exam" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Select Exam Type</option>
                                            <option value="NET" {{ old('net_slet_csir_gate_exam', $scholar->net_slet_csir_gate_exam) == 'NET' ? 'selected' : '' }}>NET</option>
                                            <option value="SLET" {{ old('net_slet_csir_gate_exam', $scholar->net_slet_csir_gate_exam) == 'SLET' ? 'selected' : '' }}>SLET</option>
                                            <option value="CSIR" {{ old('net_slet_csir_gate_exam', $scholar->net_slet_csir_gate_exam) == 'CSIR' ? 'selected' : '' }}>CSIR</option>
                                            <option value="GATE" {{ old('net_slet_csir_gate_exam', $scholar->net_slet_csir_gate_exam) == 'GATE' ? 'selected' : '' }}>GATE</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('net_slet_csir_gate_exam')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="net_slet_csir_gate_year" :value="__('Exam Year')" />
                                        <x-text-input id="net_slet_csir_gate_year" name="net_slet_csir_gate_year" type="text" class="mt-1 block w-full"
                                            :value="old('net_slet_csir_gate_year', $scholar->net_slet_csir_gate_year)" />
                                        <x-input-error :messages="$errors->get('net_slet_csir_gate_year')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="net_slet_csir_gate_roll_number" :value="__('Roll Number')" />
                                        <x-text-input id="net_slet_csir_gate_roll_number" name="net_slet_csir_gate_roll_number" type="text" class="mt-1 block w-full"
                                            :value="old('net_slet_csir_gate_roll_number', $scholar->net_slet_csir_gate_roll_number)" />
                                        <x-input-error :messages="$errors->get('net_slet_csir_gate_roll_number')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- MPAT Information -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">MPAT Information</h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="mpat_year" :value="__('MPAT Year')" />
                                        <x-text-input id="mpat_year" name="mpat_year" type="text" class="mt-1 block w-full"
                                            :value="old('mpat_year', $scholar->mpat_year)" />
                                        <x-input-error :messages="$errors->get('mpat_year')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="mpat_roll_number" :value="__('MPAT Roll Number')" />
                                        <x-text-input id="mpat_roll_number" name="mpat_roll_number" type="text" class="mt-1 block w-full"
                                            :value="old('mpat_roll_number', $scholar->mpat_roll_number)" />
                                        <x-input-error :messages="$errors->get('mpat_roll_number')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="mpat_merit_number" :value="__('MPAT Merit Number')" />
                                        <x-text-input id="mpat_merit_number" name="mpat_merit_number" type="text" class="mt-1 block w-full"
                                            :value="old('mpat_merit_number', $scholar->mpat_merit_number)" />
                                        <x-input-error :messages="$errors->get('mpat_merit_number')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="mpat_subject" :value="__('MPAT Subject')" />
                                        <x-text-input id="mpat_subject" name="mpat_subject" type="text" class="mt-1 block w-full"
                                            :value="old('mpat_subject', $scholar->mpat_subject)" />
                                        <x-input-error :messages="$errors->get('mpat_subject')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Coursework Information -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Coursework Information</h4>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <x-input-label for="coursework_exam_date" :value="__('Coursework Exam Date')" />
                                        <x-text-input id="coursework_exam_date" name="coursework_exam_date" type="text" class="mt-1 block w-full"
                                            :value="old('coursework_exam_date', $scholar->coursework_exam_date)" />
                                        <x-input-error :messages="$errors->get('coursework_exam_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="coursework_marks_obtained" :value="__('Marks Obtained')" />
                                        <x-text-input id="coursework_marks_obtained" name="coursework_marks_obtained" type="text" class="mt-1 block w-full"
                                            :value="old('coursework_marks_obtained', $scholar->coursework_marks_obtained)" />
                                        <x-input-error :messages="$errors->get('coursework_marks_obtained')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="coursework_max_marks" :value="__('Maximum Marks')" />
                                        <x-text-input id="coursework_max_marks" name="coursework_max_marks" type="text" class="mt-1 block w-full"
                                            :value="old('coursework_max_marks', $scholar->coursework_max_marks)" />
                                        <x-input-error :messages="$errors->get('coursework_max_marks')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Document Upload</h3>

                            <!-- Display existing documents -->
                            @if($scholar->registration_documents && count($scholar->registration_documents) > 0)
                                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                    <h4 class="text-sm font-medium text-green-800 dark:text-green-200 mb-3">Previously Uploaded Documents</h4>
                                    <div class="space-y-2">
                                        @foreach($scholar->registration_documents as $index => $document)
                                            <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-800 rounded border">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $document['filename'] ?? 'Document ' . ($index + 1) }}</span>
                                                </div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ isset($document['uploaded_at']) ? \Carbon\Carbon::parse($document['uploaded_at'])->format('M d, Y') : 'Uploaded' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-6" id="document-upload-container">
                                <div class="document-upload-item border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Document Set 1</h4>
                                        <button type="button" class="remove-document-btn text-red-600 hover:text-red-800 text-sm font-medium" style="display: none;">
                                            Remove
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="document_type_1" :value="__('Document Type')" />
                                            <select id="document_type_1" name="document_types[]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" {{ !$scholar->registration_documents || count($scholar->registration_documents) == 0 ? 'required' : '' }}>
                                                <option value="">Select Document Type</option>
                                                <option value="degree_certificate">Degree Certificate</option>
                                                <option value="marksheet">Marksheet</option>
                                                <option value="net_certificate">NET Certificate</option>
                                                <option value="slet_certificate">SLET Certificate</option>
                                                <option value="csir_certificate">CSIR Certificate</option>
                                                <option value="gate_certificate">GATE Certificate</option>
                                                <option value="mpat_certificate">MPAT Certificate</option>
                                                <option value="noc_letter">NOC Letter</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('document_types')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="registration_documents_1" :value="__('Upload Document')" />
                                            <input id="registration_documents_1" name="registration_documents[]" type="file"
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" {{ !$scholar->registration_documents || count($scholar->registration_documents) == 0 ? 'required' : '' }}>
                                            <x-input-error :messages="$errors->get('registration_documents')" class="mt-2" />
                                            @if($scholar->registration_documents && count($scholar->registration_documents) > 0)
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Optional: Upload additional documents</p>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (max 5MB per file)
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" id="add-document-btn" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Another Document
                                </button>
                            </div>
                        </div>

                        <!-- Synopsis Submission Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Research Synopsis</h3>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Synopsis Submission</h4>
                                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                            Your research synopsis will be submitted as part of the registration process.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="synopsis_topic" :value="__('Research Topic/Title')" />
                                    <x-text-input id="synopsis_topic" name="synopsis_topic" type="text" class="mt-1 block w-full"
                                        :value="old('synopsis_topic', $scholar->synopsis_topic)" placeholder="Enter your research topic or title" />
                                    <x-input-error :messages="$errors->get('synopsis_topic')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Provide a clear and concise title for your research work.
                                    </p>
                                </div>

                                <div>
                                    <x-input-label for="synopsis_file" :value="__('Synopsis Document (PDF, DOC, DOCX)')" />
                                    <input id="synopsis_file" name="synopsis_file" type="file"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        accept=".pdf,.doc,.docx">
                                    <x-input-error :messages="$errors->get('synopsis_file')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Upload your detailed research synopsis document (max 2MB).
                                    </p>

                                    @if($scholar->synopsis_file)
                                        <div class="mt-2 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-green-800 dark:text-green-200">Synopsis already uploaded</span>
                                            </div>
                                            <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                                Submitted on: {{ $scholar->synopsis_submitted_at ? $scholar->synopsis_submitted_at->format('M d, Y') : 'N/A' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex space-x-3">
                                <a href="{{ route('scholar.profile.edit') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Back to Profile
                                </a>
                            </div>

                            <div class="flex space-x-3">
                                <button type="submit" name="action" value="save"
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Save Progress
                                </button>

                                @if($scholar->canSubmitRegistrationForm())
                                    <button type="submit" name="action" value="submit"
                                        class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Submit Form
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for dynamic form behavior -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Academic Qualifications dynamic functionality
            let qualificationCount = {{ $qualificationCount }};
            const addQualificationBtn = document.getElementById('add-qualification-btn');
            const qualificationsContainer = document.getElementById('academic-qualifications-container');

            addQualificationBtn.addEventListener('click', function() {
                qualificationCount++;
                const newQualification = document.createElement('div');
                newQualification.className = 'academic-qualification-item border border-gray-200 dark:border-gray-700 rounded-lg p-4';
                newQualification.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Qualification ${qualificationCount}</h4>
                        <button type="button" class="remove-qualification-btn text-red-600 hover:text-red-800 text-sm font-medium">
                            Remove
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="post_graduate_degree_${qualificationCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Degree/Qualification</label>
                            <input id="post_graduate_degree_${qualificationCount}" name="post_graduate_degrees[]" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="e.g., M.Sc., M.A., M.Tech, etc." required>
                        </div>

                        <div>
                            <label for="post_graduate_university_${qualificationCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">University/Institution</label>
                            <input id="post_graduate_university_${qualificationCount}" name="post_graduate_universities[]" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Name of university or institution" required>
                        </div>

                        <div>
                            <label for="post_graduate_year_${qualificationCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year of Completion</label>
                            <input id="post_graduate_year_${qualificationCount}" name="post_graduate_years[]" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="e.g., 2020" required>
                        </div>

                        <div>
                            <label for="post_graduate_percentage_${qualificationCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Percentage/CGPA</label>
                            <input id="post_graduate_percentage_${qualificationCount}" name="post_graduate_percentages[]" type="number" step="0.01" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="e.g., 85.5 or 8.5" required>
                        </div>
                    </div>
                `;
                qualificationsContainer.appendChild(newQualification);
                updateRemoveButtons();
            });

            // Document Upload dynamic functionality
            let documentCount = 1;
            const addDocumentBtn = document.getElementById('add-document-btn');
            const documentsContainer = document.getElementById('document-upload-container');

            addDocumentBtn.addEventListener('click', function() {
                documentCount++;
                const newDocument = document.createElement('div');
                newDocument.className = 'document-upload-item border border-gray-200 dark:border-gray-700 rounded-lg p-4';
                newDocument.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Document Set ${documentCount}</h4>
                        <button type="button" class="remove-document-btn text-red-600 hover:text-red-800 text-sm font-medium">
                            Remove
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="document_type_${documentCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Document Type</label>
                            <select id="document_type_${documentCount}" name="document_types[]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select Document Type</option>
                                <option value="degree_certificate">Degree Certificate</option>
                                <option value="marksheet">Marksheet</option>
                                <option value="net_certificate">NET Certificate</option>
                                <option value="slet_certificate">SLET Certificate</option>
                                <option value="csir_certificate">CSIR Certificate</option>
                                <option value="gate_certificate">GATE Certificate</option>
                                <option value="mpat_certificate">MPAT Certificate</option>
                                <option value="noc_letter">NOC Letter</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="registration_documents_${documentCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Document</label>
                            <input id="registration_documents_${documentCount}" name="registration_documents[]" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        </div>
                    </div>

                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (max 5MB per file)
                    </p>
                `;
                documentsContainer.appendChild(newDocument);
                updateRemoveButtons();
            });

            // Update remove buttons visibility
            function updateRemoveButtons() {
                const qualificationItems = document.querySelectorAll('.academic-qualification-item');
                const documentItems = document.querySelectorAll('.document-upload-item');

                // Show remove buttons if more than one item
                qualificationItems.forEach((item, index) => {
                    const removeBtn = item.querySelector('.remove-qualification-btn');
                    removeBtn.style.display = qualificationItems.length > 1 ? 'block' : 'none';
                });

                documentItems.forEach((item, index) => {
                    const removeBtn = item.querySelector('.remove-document-btn');
                    removeBtn.style.display = documentItems.length > 1 ? 'block' : 'none';
                });
            }

            // Handle remove buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-qualification-btn')) {
                    e.target.closest('.academic-qualification-item').remove();
                    updateRemoveButtons();
                }

                if (e.target.classList.contains('remove-document-btn')) {
                    e.target.closest('.document-upload-item').remove();
                    updateRemoveButtons();
                }
            });

            // Handle teacher field visibility
            const teacherRadios = document.querySelectorAll('input[name="is_teacher"]');
            const teacherEmployerField = document.getElementById('teacher_employer_field');

            teacherRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        teacherEmployerField.classList.remove('hidden');
                    } else {
                        teacherEmployerField.classList.add('hidden');
                    }
                });
            });

            // Handle other exam field visibility
            const examRadios = document.querySelectorAll('input[name="appearing_other_exam"]');
            const examDetailsField = document.getElementById('other_exam_details_field');

            examRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        examDetailsField.classList.remove('hidden');
                    } else {
                        examDetailsField.classList.add('hidden');
                    }
                });
            });

            // Handle co-supervisor field visibility
            const coSupervisorRadios = document.querySelectorAll('input[name="has_co_supervisor"]');
            const coSupervisorFields = document.getElementById('co_supervisor_fields');

            coSupervisorRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        coSupervisorFields.classList.remove('hidden');
                    } else {
                        coSupervisorFields.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>
