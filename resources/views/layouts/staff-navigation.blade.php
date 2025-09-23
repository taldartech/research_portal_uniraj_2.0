<div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="shrink-0 flex items-center p-4">
        <a href="{{ route('staff.dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col space-y-2 p-4 flex-1">
        <x-nav-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>

        @if(Auth::user()->user_type === 'supervisor')
            <x-nav-link :href="route('staff.scholars.list')" :active="request()->routeIs('staff.scholars.list')">
                {{ __('My Scholars') }}
            </x-nav-link>
            <x-nav-link :href="route('staff.scholars.all_submissions')" :active="request()->routeIs('staff.scholars.all_submissions')">
                {{ __('All Scholar Submissions') }}
            </x-nav-link>
            @if(isset($canUploadRacMinutes) && $canUploadRacMinutes)
                <x-nav-link :href="route('staff.rac_minutes.upload')" :active="request()->routeIs('staff.rac_minutes.upload')">
                    {{ __('Upload RAC Minutes') }}
                </x-nav-link>
            @endif
            <x-nav-link :href="route('staff.thesis_evaluation.experts')" :active="request()->routeIs('staff.thesis_evaluation.experts')">
                {{ __('Suggest Experts') }}
            </x-nav-link>
            <x-nav-link :href="route('staff.synopsis.pending')" :active="request()->routeIs('staff.synopsis.pending')">
                {{ __('Pending Synopses') }}
            </x-nav-link>
            <x-nav-link :href="route('staff.coursework_exemption.pending')" :active="request()->routeIs('staff.coursework_exemption.pending')">
                {{ __('Pending Coursework Exemption Requests') }}
            </x-nav-link>
            <x-nav-link :href="route('staff.progress_reports.pending')" :active="request()->routeIs('staff.progress_reports.pending')">
                {{ __('Pending Progress Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('staff.thesis.pending')" :active="request()->routeIs('staff.thesis.pending')">
                {{ __('Pending Thesis Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('staff.viva.examinations')" :active="request()->routeIs('staff.viva.*')">
                {{ __('Viva Examinations') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'hod')
            <x-nav-link :href="route('hod.admissions.upload_merit_list')" :active="request()->routeIs('hod.admissions.upload_merit_list')">
                {{ __('Upload Merit List') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.admissions.view_merit_lists')" :active="request()->routeIs('hod.admissions.view_merit_lists')">
                {{ __('Uploaded Merit Lists') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.scholars.list')" :active="request()->routeIs('hod.scholars.list')">
                {{ __('Scholar List') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.scholars.all_submissions')" :active="request()->routeIs('hod.scholars.all_submissions')">
                {{ __('All Scholar Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.supervisors.list')" :active="request()->routeIs('hod.supervisors.list')">
                {{ __('Supervisors') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.supervisor_assignments.pending')" :active="request()->routeIs('hod.supervisor_assignments.*')">
                {{ __('Supervisor Assignments') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.synopsis.pending')" :active="request()->routeIs('hod.synopsis.*')">
                {{ __('Pending Synopses') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.progress_reports.pending')" :active="request()->routeIs('hod.progress_reports.*')">
                {{ __('Pending Progress Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.thesis.pending')" :active="request()->routeIs('hod.thesis.*')">
                {{ __('Pending Thesis Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('hod.viva.examinations')" :active="request()->routeIs('hod.viva.*')">
                {{ __('Viva Examinations') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'dean')
            <x-nav-link :href="route('dean.coursework_exemptions.pending')" :active="request()->routeIs('dean.coursework_exemptions.pending')">
                {{ __('Coursework Exemptions') }}
            </x-nav-link>
            <x-nav-link :href="route('dean.scholars.list')" :active="request()->routeIs('dean.scholars.list')">
                {{ __('All Scholars') }}
            </x-nav-link>
            <x-nav-link :href="route('dean.supervisors.list')" :active="request()->routeIs('dean.supervisors.list')">
                {{ __('All Supervisors') }}
            </x-nav-link>
            <x-nav-link :href="route('dean.thesis.list')" :active="request()->routeIs('dean.thesis.list')">
                {{ __('All Thesis') }}
            </x-nav-link>
            <x-nav-link :href="route('dean.synopsis.list')" :active="request()->routeIs('dean.synopsis.list')">
                {{ __('All Synopsis') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'da')
            <x-nav-link :href="route('da.capacity_requests.pending')" :active="request()->routeIs('da.capacity_requests.pending')">
                {{ __('Pending Capacity Requests') }}
            </x-nav-link>
            <x-nav-link :href="route('da.synopses.pending')" :active="request()->routeIs('da.synopses.pending')">
                {{ __('Pending Synopses') }}
            </x-nav-link>
            <x-nav-link :href="route('da.coursework_exemptions.pending')" :active="request()->routeIs('da.coursework_exemptions.pending')">
                {{ __('Pending Coursework Exemptions') }}
            </x-nav-link>
            <x-nav-link :href="route('da.progress_reports.pending')" :active="request()->routeIs('da.progress_reports.pending')">
                {{ __('Pending Progress Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('da.thesis.pending')" :active="request()->routeIs('da.thesis.pending')">
                {{ __('Pending Thesis Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('da.registration_forms.eligible_scholars')" :active="request()->routeIs('da.registration_forms.eligible_scholars')">
                {{ __('Eligible Scholars for Registration') }}
            </x-nav-link>
            <x-nav-link :href="route('da.registration_forms.list')" :active="request()->routeIs('da.registration_forms.list')">
                {{ __('Registration Forms') }}
            </x-nav-link>
            <x-nav-link :href="route('da.thesis.evaluations_for_assignment')" :active="request()->routeIs('da.thesis.evaluations_for_assignment')">
                {{ __('Thesis Evaluations for Assignment') }}
            </x-nav-link>

            <x-nav-link :href="route('da.scholars.all_submissions')" :active="request()->routeIs('da.scholars.all_submissions')">
                {{ __('All Scholar Submissions') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'so')
            <x-nav-link :href="route('so.capacity_requests.pending')" :active="request()->routeIs('so.capacity_requests.pending')">
                {{ __('Pending Capacity Requests') }}
            </x-nav-link>
            <x-nav-link :href="route('so.synopses.pending')" :active="request()->routeIs('so.synopses.pending')">
                {{ __('Pending Synopses') }}
            </x-nav-link>
            <x-nav-link :href="route('so.progress_reports.pending')" :active="request()->routeIs('so.progress_reports.pending')">
                {{ __('Pending Progress Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('so.thesis.pending')" :active="request()->routeIs('so.thesis.pending')">
                {{ __('Pending Thesis Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('so.coursework_exemptions.pending')" :active="request()->routeIs('so.coursework_exemptions.pending')">
                {{ __('Pending Coursework Exemptions') }}
            </x-nav-link>

            <x-nav-link :href="route('so.scholars.all_submissions')" :active="request()->routeIs('so.scholars.all_submissions')">
                {{ __('All Scholar Submissions') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'ar')
            <x-nav-link :href="route('ar.capacity_requests.pending')" :active="request()->routeIs('ar.capacity_requests.pending')">
                {{ __('Pending Capacity Requests') }}
            </x-nav-link>
            <x-nav-link :href="route('ar.synopses.pending')" :active="request()->routeIs('ar.synopses.pending')">
                {{ __('Pending Synopses') }}
            </x-nav-link>
            <x-nav-link :href="route('ar.progress_reports.pending')" :active="request()->routeIs('ar.progress_reports.pending')">
                {{ __('Pending Progress Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('ar.thesis.pending')" :active="request()->routeIs('ar.thesis.pending')">
                {{ __('Pending Thesis Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('ar.coursework_exemptions.pending')" :active="request()->routeIs('ar.coursework_exemptions.pending')">
                {{ __('Pending Coursework Exemptions') }}
            </x-nav-link>
            <x-nav-link :href="route('ar.registration_forms.pending')" :active="request()->routeIs('ar.registration_forms.pending')">
                {{ __('Pending Registration Forms for Signing') }}
            </x-nav-link>

            <x-nav-link :href="route('ar.scholars.all_submissions')" :active="request()->routeIs('ar.scholars.all_submissions')">
                {{ __('All Scholar Submissions') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'dr')
            <x-nav-link :href="route('dr.capacity_requests.pending')" :active="request()->routeIs('dr.capacity_requests.pending')">
                {{ __('Pending Capacity Requests') }}
            </x-nav-link>
            <x-nav-link :href="route('dr.synopses.pending')" :active="request()->routeIs('dr.synopses.pending')">
                {{ __('Pending Synopses') }}
            </x-nav-link>
            <x-nav-link :href="route('dr.progress_reports.pending')" :active="request()->routeIs('dr.progress_reports.pending')">
                {{ __('Pending Progress Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('dr.thesis.pending')" :active="request()->routeIs('dr.thesis.pending')">
                {{ __('Pending Thesis Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('dr.coursework_exemptions.pending')" :active="request()->routeIs('dr.coursework_exemptions.pending')">
                {{ __('Pending Coursework Exemptions') }}
            </x-nav-link>
            <x-nav-link :href="route('dr.registration_forms.pending')" :active="request()->routeIs('dr.registration_forms.pending')">
                {{ __('Pending Registration Forms for Signing') }}
            </x-nav-link>

            <x-nav-link :href="route('dr.scholars.all_submissions')" :active="request()->routeIs('dr.scholars.all_submissions')">
                {{ __('All Scholar Submissions') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'hvc')
            <x-nav-link :href="route('hvc.capacity_requests.pending')" :active="request()->routeIs('hvc.capacity_requests.pending')">
                {{ __('Pending Capacity Requests') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.synopses.pending')" :active="request()->routeIs('hvc.synopses.pending')">
                {{ __('Pending Synopses') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.progress_reports.pending')" :active="request()->routeIs('hvc.progress_reports.pending')">
                {{ __('Pending Progress Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.thesis.pending')" :active="request()->routeIs('hvc.thesis.pending')">
                {{ __('Pending Thesis Submissions') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.coursework_exemptions.pending')" :active="request()->routeIs('hvc.coursework_exemptions.pending')">
                {{ __('Pending Coursework Exemptions') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.thesis.pending_approval')" :active="request()->routeIs('hvc.thesis.pending_approval')">
                {{ __('Pending Thesis Approvals') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.thesis.approved')" :active="request()->routeIs('hvc.thesis.approved')">
                {{ __('Approved Thesis for Evaluation') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.thesis.evaluations')" :active="request()->routeIs('hvc.thesis.evaluations')">
                {{ __('Thesis Evaluations') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.viva.candidates')" :active="request()->routeIs('hvc.viva.candidates')">
                {{ __('Viva Candidates') }}
            </x-nav-link>
            <x-nav-link :href="route('hvc.viva.scheduled')" :active="request()->routeIs('hvc.viva.scheduled')">
                {{ __('Scheduled Vivas') }}
            </x-nav-link>

            <x-nav-link :href="route('hvc.scholars.all_submissions')" :active="request()->routeIs('hvc.scholars.all_submissions')">
                {{ __('All Scholar Submissions') }}
            </x-nav-link>
        @endif

        @if(Auth::user()->user_type === 'expert')
            <x-nav-link :href="route('expert.evaluations.list')" :active="request()->routeIs('expert.evaluations.list')">
                {{ __('My Thesis Evaluations') }}
            </x-nav-link>
        @endif

        {{-- Add more staff roles and their specific links here --}}

    </div>

    <!-- Settings Dropdown -->
    <div class="p-4 border-t border-gray-200">
        <x-dropdown align="left" width="48" dropup>
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 w-full justify-start">
                    <div>{{ Auth::user()->name }}</div>

                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('staff.logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</div>
