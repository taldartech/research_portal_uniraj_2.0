<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thesis Status & Certificates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Thesis Submissions -->
            <div class="space-y-6">
                @forelse($thesisSubmissions as $thesis)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $thesis->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Submitted: {{ $thesis->submission_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @switch($thesis->status)
                                            @case('pending_supervisor_approval')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @break
                                            @case('supervisor_approved')
                                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @break
                                            @case('rejected')
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endswitch">
                                        {{ ucwords(str_replace('_', ' ', $thesis->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="text-gray-700 dark:text-gray-300">
                                </p>
                            </div>

                            <!-- Certificates Section -->
                            @if($thesis->status === 'supervisor_approved')
                                <div class="border-t pt-4">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">
                                        Generate Certificates
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Pre-Ph.D. Presentation Certificate -->
                                        <div class="border rounded-lg p-4">
                                            <h5 class="font-semibold mb-2">Pre-Ph.D. Presentation</h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                Certificate for presenting thesis in Pre-Ph.D. seminar
                                            </p>
                                            <form action="{{ route('scholar.thesis.generate_certificate', $thesis) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="certificate_type" value="pre_phd_presentation">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Presentation Date</label>
                                                    <input type="date" name="certificate_data[presentation_date]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Venue</label>
                                                    <input type="text" name="certificate_data[venue]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    Generate Certificate
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Research Papers Presentation Certificate -->
                                        <div class="border rounded-lg p-4">
                                            <h5 class="font-semibold mb-2">Research Papers Presentation</h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                Certificate for presenting research papers in seminars/conferences
                                            </p>
                                            <form action="{{ route('scholar.thesis.generate_certificate', $thesis) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="certificate_type" value="research_papers">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conference/Seminar Name</label>
                                                    <input type="text" name="certificate_data[conference_name]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                                    <input type="date" name="certificate_data[date]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Venue</label>
                                                    <input type="text" name="certificate_data[venue]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    Generate Certificate
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Peer Reviewed Journal Certificate -->
                                        <div class="border rounded-lg p-4">
                                            <h5 class="font-semibold mb-2">Peer Reviewed Journal</h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                Certificate for publication in peer reviewed journal
                                            </p>
                                            <form action="{{ route('scholar.thesis.generate_certificate', $thesis) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="certificate_type" value="peer_reviewed_journal">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Journal Name</label>
                                                    <input type="text" name="certificate_data[journal_name]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Publication Date</label>
                                                    <input type="date" name="certificate_data[publication_date]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Volume/Issue</label>
                                                    <input type="text" name="certificate_data[volume_issue]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                </div>
                                                <button type="submit" class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    Generate Certificate
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Generated Certificates -->
                            @if($thesis->certificates->count() > 0)
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">
                                        Generated Certificates
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($thesis->certificates as $certificate)
                                            <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                                <div>
                                                    <span class="font-medium">{{ $certificate->certificate_type_name }}</span>
                                                    <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                                                        Generated: {{ $certificate->generated_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                                <div class="space-x-2">
                                                    <a href="{{ route('scholar.thesis.certificate.show', $certificate) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                                    <a href="{{ route('scholar.thesis.certificate.download', $certificate) }}" class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Registration Letter Section -->
                            @if(auth()->user()->scholar->registration_letter_generated)
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">
                                        Registration Letter
                                    </h4>
                                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                        <div>
                                            <span class="font-medium">Ph.D. Registration Letter</span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                                                Generated: {{ auth()->user()->scholar->registration_letter_generated_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('registration_letter.download') }}"
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($thesis->status === 'final_approved')
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">
                                        Registration Letter
                                    </h4>
                                    <div class="bg-yellow-50 dark:bg-yellow-900 p-3 rounded">
                                        <span class="text-sm text-yellow-700 dark:text-yellow-300">Registration Letter Pending Generation</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <p class="text-gray-600 dark:text-gray-400">No thesis submissions found.</p>
                            <a href="{{ route('scholar.thesis.submission_form') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit Your First Thesis
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
