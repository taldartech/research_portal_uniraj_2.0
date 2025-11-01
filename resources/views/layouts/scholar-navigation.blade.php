<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <style>
        .nav-dropdown {
            z-index: 50;
        }
        @media (max-width: 640px) {
            .nav-links {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('scholar.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex sm:flex-wrap sm:items-center sm:max-w-4xl sm:overflow-x-auto">
                    <x-nav-link :href="route('scholar.dashboard')" :active="request()->routeIs('scholar.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    {{-- <x-nav-link :href="route('scholar.registration.phd_form')" :active="request()->routeIs('scholar.registration.*')">
                        {{ __('Ph.D. Registration') }}
                    </x-nav-link> --}}

                    {{-- Supervisor Preference link should always be visible before assignment --}}
                    @if(!Auth::user()->scholar || !Auth::user()->scholar->hasAssignedSupervisor())
                        <x-nav-link :href="route('scholar.supervisor.preference')" :active="request()->routeIs('scholar.supervisor.preference')">
                            {{ __('Submit Supervisor Preference') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->scholar && Auth::user()->scholar->hasAssignedSupervisor())
                        {{-- <x-nav-link :href="route('scholar.supervisor.preference')" :active="request()->routeIs('scholar.supervisor.preference')">
                            {{ __('Supervisor Preference') }}
                        </x-nav-link> --}}
                        <x-nav-link :href="route('scholar.progress_report.submit')" :active="request()->routeIs('scholar.progress_report.submit')">
                            {{ __('Submit Progress Report') }}
                        </x-nav-link>
                        <!-- Thesis Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                {{ __('Thesis') }}
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 nav-dropdown">
                                <a href="{{ route('scholar.thesis.eligibility') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thesis Eligibility</a>
                                <a href="{{ route('scholar.thesis.submit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Submit Thesis</a>
                                <a href="{{ route('scholar.thesis.submission_form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thesis Submission Form</a>
                                <a href="{{ route('scholar.thesis.submissions.status') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thesis Certificates</a>
                                <a href="{{ route('scholar.thesis.status') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thesis Status</a>
                            </div>
                        </div>
                        <x-nav-link :href="route('scholar.registration_form.status')" :active="request()->routeIs('scholar.registration_form.status')">
                            {{ __('Registration Form') }}
                        </x-nav-link>
                        @if(Auth::user()->scholar && Auth::user()->scholar->canRequestLateSubmission())
                            <x-nav-link :href="route('scholar.late_submission.request')" :active="request()->routeIs('scholar.late_submission.*')">
                                {{ __('Late Submission Request') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('scholar.late_submission.status')" :active="request()->routeIs('scholar.late_submission.status')">
                            {{ __('Late Submission Status') }}
                        </x-nav-link>
                    @else
                        <p class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">Awaiting Supervisor Assignment</p>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('scholar.profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('scholar.logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('scholar.logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('scholar.dashboard')" :active="request()->routeIs('scholar.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('scholar.profile.edit')" :active="request()->routeIs('scholar.profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            @if(Auth::user()->scholar && !Auth::user()->scholar->hasAssignedSupervisor())
                <x-responsive-nav-link :href="route('scholar.supervisor.preference')" :active="request()->routeIs('scholar.supervisor.preference')">
                    {{ __('Submit Supervisor Preference') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->scholar && Auth::user()->scholar->hasAssignedSupervisor())
                <x-responsive-nav-link :href="route('scholar.progress_report.submit')" :active="request()->routeIs('scholar.progress_report.submit')">
                    {{ __('Submit Progress Report') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('scholar.thesis.eligibility')" :active="request()->routeIs('scholar.thesis.eligibility')">
                    {{ __('Thesis Eligibility') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('scholar.thesis.submit')" :active="request()->routeIs('scholar.thesis.submit')">
                    {{ __('Submit Thesis') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('scholar.registration_form.status')" :active="request()->routeIs('scholar.registration_form.status')">
                    {{ __('Registration Form') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('scholar.late_submission.status')" :active="request()->routeIs('scholar.late_submission.status')">
                    {{ __('Late Submission Status') }}
                </x-responsive-nav-link>
            @endif

            {{-- DRC Minutes - Accessible to all roles --}}
            <x-responsive-nav-link :href="route('drc_minutes.index')" :active="request()->routeIs('drc_minutes.*')">
                {{ __('DRC Minutes') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('scholar.profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('scholar.logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('scholar.logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
