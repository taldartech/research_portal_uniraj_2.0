<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Edit Scholar Registration Form
                </h2>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
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
                                <a href="{{ route('staff.scholars.list') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Scholars</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Edit Form</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Scholar Information Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Scholar: {{ $scholar->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Enrollment: {{ $scholar->enrollment_number ?? 'Not assigned' }}
                            </p>
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
                                    @case('submitted')
                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @break
                                    @case('under_review')
                                        bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @break
                                    @case('approved')
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
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
                                    @case('submitted')
                                        Submitted
                                        @break
                                    @case('under_review')
                                        Under Review
                                        @break
                                    @case('approved')
                                        Approved
                                        @break
                                    @default
                                        Not Started
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('supervisor.scholar.form_update', $scholar->id) }}" class="space-y-8">
                        @csrf
                        @method('patch')

                        <!-- Personal Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Personal Information</h3>

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
                                        <option value="OBC" {{ old('category', $scholar->category) == 'OBC' ? 'selected' : '' }}>OBC</option>
                                        <option value="SC" {{ old('category', $scholar->category) == 'SC' ? 'selected' : '' }}>SC</option>
                                        <option value="ST" {{ old('category', $scholar->category) == 'ST' ? 'selected' : '' }}>ST</option>
                                        <option value="EWS" {{ old('category', $scholar->category) == 'EWS' ? 'selected' : '' }}>EWS</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="occupation" :value="__('Occupation')" />
                                    <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full"
                                        :value="old('occupation', $scholar->occupation)" required />
                                    <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                                </div>

                                <div class="flex items-center">
                                    <input id="is_teacher" name="is_teacher" type="checkbox" value="1"
                                        {{ old('is_teacher', $scholar->is_teacher) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <x-input-label for="is_teacher" :value="__('Are you a teacher?')" class="ml-2" />
                                </div>

                                <div id="teacher_employer_field" style="display: {{ old('is_teacher', $scholar->is_teacher) ? 'block' : 'none' }};">
                                    <x-input-label for="teacher_employer" :value="__('Teacher Employer')" />
                                    <x-text-input id="teacher_employer" name="teacher_employer" type="text" class="mt-1 block w-full"
                                        :value="old('teacher_employer', $scholar->teacher_employer)" />
                                    <x-input-error :messages="$errors->get('teacher_employer')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Ph.D. Program Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Ph.D. Program Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="phd_faculty" :value="__('Ph.D. Faculty')" />
                                    <select id="phd_faculty" name="phd_faculty" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Select Faculty</option>
                                        @foreach($faculties as $faculty)
                                            <option value="{{ $faculty->name }}" {{ old('phd_faculty', $scholar->phd_faculty) == $faculty->name ? 'selected' : '' }}>
                                                {{ $faculty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('phd_faculty')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phd_subject" :value="__('Ph.D. Subject')" />
                                    <x-text-input id="phd_subject" name="phd_subject" type="text" class="mt-1 block w-full"
                                        :value="old('phd_subject', $scholar->phd_subject)" required />
                                    <x-input-error :messages="$errors->get('phd_subject')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Academic Qualifications Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Academic Qualifications</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="post_graduate_degree" :value="__('Post Graduate Degree')" />
                                    <x-text-input id="post_graduate_degree" name="post_graduate_degree" type="text" class="mt-1 block w-full"
                                        :value="old('post_graduate_degree', $scholar->post_graduate_degree)" />
                                    <x-input-error :messages="$errors->get('post_graduate_degree')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="post_graduate_university" :value="__('Post Graduate University')" />
                                    <x-text-input id="post_graduate_university" name="post_graduate_university" type="text" class="mt-1 block w-full"
                                        :value="old('post_graduate_university', $scholar->post_graduate_university)" />
                                    <x-input-error :messages="$errors->get('post_graduate_university')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="post_graduate_year" :value="__('Post Graduate Year')" />
                                    <x-text-input id="post_graduate_year" name="post_graduate_year" type="number" class="mt-1 block w-full"
                                        :value="old('post_graduate_year', $scholar->post_graduate_year)" />
                                    <x-input-error :messages="$errors->get('post_graduate_year')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="post_graduate_percentage" :value="__('Post Graduate Percentage')" />
                                    <x-text-input id="post_graduate_percentage" name="post_graduate_percentage" type="number" step="0.01" class="mt-1 block w-full"
                                        :value="old('post_graduate_percentage', $scholar->post_graduate_percentage)" />
                                    <x-input-error :messages="$errors->get('post_graduate_percentage')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex space-x-3">
                                <a href="{{ route('staff.scholars.list') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Back to Scholars
                                </a>
                            </div>

                            <div class="flex space-x-3">
                                <button type="submit" name="action" value="save"
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Save Changes
                                </button>

                                <button type="submit" name="action" value="review"
                                    class="inline-flex items-center px-6 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Mark for Review
                                </button>

                                <button type="submit" name="action" value="approve"
                                    class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Approve Form
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle teacher checkbox toggle
        document.getElementById('is_teacher').addEventListener('change', function() {
            const teacherEmployerField = document.getElementById('teacher_employer_field');
            if (this.checked) {
                teacherEmployerField.style.display = 'block';
            } else {
                teacherEmployerField.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
