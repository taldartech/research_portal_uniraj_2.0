<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(isset($otp_sent) && $otp_sent)
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            OTP has been sent to your email address. Please check your inbox.
        </div>
    @endif

    @if(!isset($otp_sent) || !$otp_sent)
        <!-- Step 1: Email and Password Form -->
        <form method="POST" action="{{ route('staff.send-otp') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Verify & Send OTP') }}
                </x-primary-button>
            </div>
        </form>
    @else
        <!-- Step 2: OTP Verification Form -->
        <form method="POST" action="{{ route('staff.login') }}">
            @csrf

            <!-- Email (hidden, already verified) -->
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />

            <!-- OTP -->
            <div>
                <x-input-label for="otp" :value="__('OTP')" />
                <x-text-input id="otp" class="block mt-1 w-full"
                                type="text"
                                name="otp"
                                placeholder="Enter 6-digit OTP"
                                maxlength="6"
                                required autofocus autocomplete="one-time-code" />
                <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('staff.login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-3">
                    {{ __('Back') }}
                </a>
                <x-primary-button>
                    {{ __('Log in as Staff') }}
                </x-primary-button>
            </div>
        </form>
    @endif
</x-guest-layout>
