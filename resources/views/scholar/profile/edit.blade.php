<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Edit Scholar Profile') }}
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
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Profile</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Profile Information') }}
                            </h2>

                            @if($scholar->canEditProfile())
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Update your scholar's profile information.") }}
                            </p>
                            @else
                                <div class="mt-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.725-1.36 3.49 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Profile Locked</h4>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                Your profile is locked after supervisor approval. Please contact your supervisor for any changes.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </header>

                        @if($scholar->canEditProfile())
                            <form method="post" action="{{ route('scholar.profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('patch')

                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $scholar->first_name)" required autofocus autocomplete="first_name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                                </div>

                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $scholar->last_name)" required autocomplete="last_name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                                </div>

                                <div>
                                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                    <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', $scholar->date_of_birth)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Gender')" />
                                    <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $scholar->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $scholar->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $scholar->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                </div>

                                <div>
                                    <x-input-label for="contact_number" :value="__('Contact Number')" />
                                    <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number', $scholar->contact_number)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Address')" />
                                    <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $scholar->address) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                                    @if (session('status') === 'profile-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                        >{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        @else
                            <div class="mt-6 space-y-6">
                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100">
                                        {{ $scholar->first_name ?? 'Not provided' }}
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100">
                                        {{ $scholar->last_name ?? 'Not provided' }}
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100">
                                        {{ $scholar->date_of_birth ?? 'Not provided' }}
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Gender')" />
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100">
                                        {{ $scholar->gender ?? 'Not provided' }}
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="contact_number" :value="__('Contact Number')" />
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100">
                                        {{ $scholar->contact_number ?? 'Not provided' }}
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Address')" />
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100">
                                        {{ $scholar->address ?? 'Not provided' }}
                                    </div>
                                </div>

                            </div>
                        @endif
                    </section>
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Change Password') }}
                            </h2>

                            @if($scholar->canEditProfile())
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Update your account password.") }}
                                </p>
                        @else
                                <div class="mt-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.725-1.36 3.49 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Password Change Locked</h4>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                Password change is locked after supervisor approval. Please contact your supervisor for any changes.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                        @endif
                        </header>

                        @if($scholar->canEditProfile())
                            <form method="post" action="{{ route('scholar.password.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')

                                    <div>
                                    <x-input-label for="current_password" :value="__('Current Password')" />
                                    <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                                    </div>

                                    <div>
                                    <x-input-label for="password" :value="__('New Password')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Update Password') }}</x-primary-button>

                                    @if (session('status') === 'password-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                        >{{ __('Password updated.') }}</p>
                                    @endif
                                </div>
                            </form>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
