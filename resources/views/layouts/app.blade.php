<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <!-- Global Alerts -->
        <x-global-alerts />

        @auth
            @if(Auth::user()->user_type === 'scholar')
                <!-- Scholar Layout with Top Navigation -->
                <div class="min-h-screen bg-gray-100">
                    @include('layouts.scholar-navigation')

                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            @else
                <!-- Staff Layout with Sidebar -->
                <div class="min-h-screen bg-gray-100 flex">
                    <div class="flex-shrink-0 w-64 bg-white border-r border-gray-100">
                        @include('layouts.staff-navigation')
                    </div>

                    <div class="flex-1">
                        <!-- Page Heading -->
                        @if (isset($header))
                            <header class="bg-white shadow">
                                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif

                        <!-- Page Content -->
                        <main>
                            {{ $slot }}
                        </main>
                    </div>
                </div>
            @endif
        @else
            <!-- Guest Layout -->
            <div class="min-h-screen bg-gray-100">
                @include('layouts.guest-navigation')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        @endauth
    </body>
</html>
