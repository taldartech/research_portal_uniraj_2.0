<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Certificate: ') . $certificate->certificate_type_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Certificate Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $certificate->certificate_type_name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Generated on {{ $certificate->generated_at->format('F d, Y') }}
                        </p>
                    </div>

                    <!-- Certificate Content -->
                    <div class="border-2 border-gray-300 dark:border-gray-600 p-8 rounded-lg">
                        @if($certificate->certificate_type === 'pre_phd_presentation')
                            <!-- Pre-Ph.D. Presentation Certificate -->
                            <div class="text-center">
                                <h2 class="text-xl font-bold mb-6">CERTIFICATE</h2>
                                <p class="text-lg mb-4">This is to certify that</p>
                                <h3 class="text-xl font-bold mb-6">{{ $certificate->scholar->name }}</h3>
                                <p class="text-lg mb-4">Research Scholar, Department of {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                                <p class="text-lg mb-4">University of Rajasthan, Jaipur</p>
                                <p class="text-lg mb-4">has presented his/her Pre-Ph.D. seminar on</p>
                                <h4 class="text-lg font-semibold mb-4">"{{ $certificate->thesisSubmission->title }}"</h4>
                                <p class="text-lg mb-4">on {{ \Carbon\Carbon::parse($certificate->certificate_data['presentation_date'])->format('F d, Y') }}</p>
                                <p class="text-lg mb-4">at {{ $certificate->certificate_data['venue'] }}</p>
                                <p class="text-lg mb-6">This certificate is issued for the purpose of thesis submission.</p>

                                <div class="flex justify-between mt-8">
                                    <div class="text-center">
                                        <p class="font-semibold">Supervisor</p>
                                        <p class="mt-2">{{ $certificate->scholar->supervisor_name ?? 'N/A' }}</p>
                                        <p>{{ $certificate->scholar->supervisor_designation ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-semibold">Head of Department</p>
                                        <p class="mt-2">{{ $certificate->scholar->admission->department->hod->name ?? 'N/A' }}</p>
                                        <p>HOD, {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                        @elseif($certificate->certificate_type === 'research_papers')
                            <!-- Research Papers Presentation Certificate -->
                            <div class="text-center">
                                <h2 class="text-xl font-bold mb-6">CERTIFICATE</h2>
                                <p class="text-lg mb-4">This is to certify that</p>
                                <h3 class="text-xl font-bold mb-6">{{ $certificate->scholar->name }} {{ $certificate->scholar->last_name }}</h3>
                                <p class="text-lg mb-4">Research Scholar, Department of {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                                <p class="text-lg mb-4">University of Rajasthan, Jaipur</p>
                                <p class="text-lg mb-4">has presented research papers in</p>
                                <h4 class="text-lg font-semibold mb-4">"{{ $certificate->certificate_data['conference_name'] }}"</h4>
                                <p class="text-lg mb-4">on {{ \Carbon\Carbon::parse($certificate->certificate_data['date'])->format('F d, Y') }}</p>
                                <p class="text-lg mb-4">at {{ $certificate->certificate_data['venue'] }}</p>
                                <p class="text-lg mb-6">This certificate is issued for the purpose of thesis submission.</p>

                                <div class="flex justify-between mt-8">
                                    <div class="text-center">
                                        <p class="font-semibold">Supervisor</p>
                                        <p class="mt-2">{{ $certificate->scholar->supervisor_name ?? 'N/A' }}</p>
                                        <p>{{ $certificate->scholar->supervisor_designation ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-semibold">Head of Department</p>
                                        <p class="mt-2">{{ $certificate->scholar->admission->department->hod->name ?? 'N/A' }}</p>
                                        <p>HOD, {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                        @elseif($certificate->certificate_type === 'peer_reviewed_journal')
                            <!-- Peer Reviewed Journal Certificate -->
                            <div class="text-center">
                                <h2 class="text-xl font-bold mb-6">CERTIFICATE</h2>
                                <p class="text-lg mb-4">This is to certify that</p>
                                <h3 class="text-xl font-bold mb-6">{{ $certificate->scholar->name }} {{ $certificate->scholar->last_name }}</h3>
                                <p class="text-lg mb-4">Research Scholar, Department of {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                                <p class="text-lg mb-4">University of Rajasthan, Jaipur</p>
                                <p class="text-lg mb-4">has published research papers in peer reviewed journal</p>
                                <h4 class="text-lg font-semibold mb-4">"{{ $certificate->certificate_data['journal_name'] }}"</h4>
                                <p class="text-lg mb-4">Volume/Issue: {{ $certificate->certificate_data['volume_issue'] }}</p>
                                <p class="text-lg mb-4">Publication Date: {{ \Carbon\Carbon::parse($certificate->certificate_data['publication_date'])->format('F d, Y') }}</p>
                                <p class="text-lg mb-6">This certificate is issued for the purpose of thesis submission.</p>

                                <div class="flex justify-between mt-8">
                                    <div class="text-center">
                                        <p class="font-semibold">Supervisor</p>
                                        <p class="mt-2">{{ $certificate->scholar->supervisor_name ?? 'N/A' }}</p>
                                        <p>{{ $certificate->scholar->supervisor_designation ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-semibold">Head of Department</p>
                                        <p class="mt-2">{{ $certificate->scholar->admission->department->hod->name ?? 'N/A' }}</p>
                                        <p>HOD, {{ $certificate->scholar->admission->department->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4 mt-8">
                        <a href="{{ route('scholar.thesis.certificate.download', $certificate) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Download PDF
                        </a>
                        <a href="{{ route('scholar.thesis.submissions.status') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
