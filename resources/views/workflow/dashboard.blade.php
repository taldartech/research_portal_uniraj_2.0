@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Workflow Dashboard
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Welcome back, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </p>
                </div>

                @if(isset($error))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ $error }}
                    </div>
                @endif

                <!-- Role-specific content -->
                @if(Auth::user()->user_type === 'scholar')
                    @include('workflow.dashboard.scholar')
                @elseif(Auth::user()->user_type === 'supervisor')
                    @include('workflow.dashboard.supervisor')
                @elseif(Auth::user()->user_type === 'hod')
                    @include('workflow.dashboard.hod')
                @elseif(Auth::user()->user_type === 'da')
                    @include('workflow.dashboard.da')
                @elseif(Auth::user()->user_type === 'so')
                    @include('workflow.dashboard.so')
                @elseif(Auth::user()->user_type === 'ar')
                    @include('workflow.dashboard.ar')
                @elseif(Auth::user()->user_type === 'dr')
                    @include('workflow.dashboard.dr')
                @elseif(Auth::user()->user_type === 'hvc')
                    @include('workflow.dashboard.hvc')
                @elseif(Auth::user()->user_type === 'admin')
                    @include('workflow.dashboard.admin')
                @else
                    @include('workflow.dashboard.default')
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh dashboard every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);

    // Real-time notifications
    if (typeof(EventSource) !== "undefined") {
        var source = new EventSource("/workflow/stream");
        source.onmessage = function(event) {
            var data = JSON.parse(event.data);
            if (data.type === 'workflow_update') {
                showNotification(data.message);
            }
        };
    }

    function showNotification(message) {
        // Create notification element
        var notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove notification after 5 seconds
        setTimeout(function() {
            notification.remove();
        }, 5000);
    }
</script>
@endpush
