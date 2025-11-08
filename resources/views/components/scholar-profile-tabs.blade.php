@props(['scholar'])

@php
    // Load all necessary relationships
    $scholar->load([
        'user',
        'admission.department',
        'currentSupervisor.supervisor.user',
        'synopses',
        'progressReports.supervisor.user',
        'progressReports.supervisorApprover',
        'progressReports.hodApprover',
        'progressReports.daApprover',
        'progressReports.soApprover',
        'progressReports.arApprover',
        'progressReports.drApprover',
        'progressReports.hvcApprover'
    ]);
    
    // Get RAC submissions
    $racSubmissions = \App\Models\RACCommitteeSubmission::where('scholar_id', $scholar->id)
        ->with('supervisor.user', 'supervisor.department', 'hod')
        ->latest()
        ->get();
@endphp

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('info')" id="tab-info" class="tab-button active border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Scholar Information
                </button>
                <button onclick="showTab('supervisor')" id="tab-supervisor" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Supervisor & Research
                </button>
                <button onclick="showTab('progress')" id="tab-progress" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Progress Reports
                </button>
            </nav>
        </div>

        <!-- Tab 1: Scholar Information -->
        <div id="tab-content-info" class="tab-content">
            @include('components.scholar-profile-tabs.info-tab', ['scholar' => $scholar])
        </div>

        <!-- Tab 2: Supervisor & Research Details -->
        <div id="tab-content-supervisor" class="tab-content hidden">
            @include('components.scholar-profile-tabs.supervisor-tab', ['scholar' => $scholar, 'racSubmissions' => $racSubmissions])
        </div>

        <!-- Tab 3: Progress Reports -->
        <div id="tab-content-progress" class="tab-content hidden">
            @include('components.scholar-profile-tabs.progress-tab', ['scholar' => $scholar])
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-indigo-500', 'text-indigo-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>

<style>
.tab-button.active {
    border-color: rgb(99 102 241);
    color: rgb(99 102 241);
}
</style>

