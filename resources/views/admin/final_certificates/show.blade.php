<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Final Certificate Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Certificate #{{ $certificate->certificate_number }}</h3>
                        <div class="space-x-2">
                            @if($certificate->certificate_file)
                                <a href="{{ route('da.final_certificates.download', $certificate) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Download PDF
                                </a>
                            @endif
                            <a href="{{ route('da.final_certificates.list') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Scholar Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Scholar Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Name:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->thesisSubmission->scholar->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Registration Number:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->thesisSubmission->scholar->registration_number }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Department:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->thesisSubmission->scholar->department }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Certificate Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Certificate Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Certificate Number:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->certificate_number }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Issue Date:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->issue_date->format('Y-m-d') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Degree Title:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->degree_title }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Specialization:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->specialization }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Viva Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Viva Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Viva Date:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->viva_date->format('Y-m-d') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Venue:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->viva_venue }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Status Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                                    @if($certificate->status === 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Completed
                                        </span>
                                    @elseif($certificate->status === 'generated')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Generated
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            {{ ucfirst($certificate->status) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Generated By:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->generatedBy->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Generated At:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $certificate->generated_at->format('Y-m-d H:i:s') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Examiners Information -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Examiners</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($certificate->examiner_details as $index => $examiner)
                                <div class="border border-gray-200 dark:border-gray-600 p-3 rounded">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $examiner['name'] }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $examiner['designation'] }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-500">{{ $examiner['institution'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recommendation Notes -->
                    @if($certificate->recommendation_notes)
                        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Recommendation Notes</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $certificate->recommendation_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
