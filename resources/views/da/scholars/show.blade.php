<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholar Details') }}: {{ $scholar->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-scholar-profile-tabs :scholar="$scholar" />
            
            <div class="mt-6">
                <x-secondary-button onclick="window.history.back()">
                    {{ __('Back to Scholars List') }}
                </x-secondary-button>
            </div>
        </div>
    </div>
</x-app-layout>
