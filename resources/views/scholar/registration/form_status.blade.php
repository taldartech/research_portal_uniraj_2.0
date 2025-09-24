<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registration Form Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Registration Form Status</h3>
                        <p class="mt-1 text-sm text-gray-600">Track the progress of your registration form generation and signing.</p>
                    </div>

                    @if($scholar->hasRegistrationForm())
                        @php
                            $form = $scholar->registrationForm;
                        @endphp

                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h4 class="font-medium text-gray-900 mb-4">Registration Form Details</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Dispatch Number:</span> {{ $form->dispatch_number }}
                                </div>
                                <div>
                                    <span class="font-medium">Generated Date:</span> {{ $form->generated_at->format('M d, Y') }}
                                </div>
                                <div>
                                    <span class="font-medium">Status:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $form->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $form->status)) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium">Download Count:</span> {{ $form->download_count }} times
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-4">Signing Progress</h4>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($form->isSignedByDR())
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">Deputy Registrar (DR) Signature</div>
                                        <div class="text-sm text-gray-500">
                                            @if($form->isSignedByDR())
                                                Signed on {{ $form->signed_by_dr_at->format('M d, Y') }}
                                            @else
                                                Pending signature
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- AR Signature section removed - no longer required -->
                            </div>
                        </div>

                        @if($form->canBeDownloaded())
                            <div class="text-center">
                                <a href="{{ route('scholar.registration_form.download', $form) }}"
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Registration Form
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-500 text-lg">Registration form is not ready for download yet.</div>
                                <div class="text-gray-400 text-sm mt-2">Please wait for all required signatures to be completed.</div>
                            </div>
                        @endif

                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">No registration form has been generated yet.</div>
                            <div class="text-gray-400 text-sm mt-2">Your registration form will be generated after your synopsis is approved by HVC.</div>

                            @if($scholar->canBeEnrolled())
                                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="text-green-800 text-sm">
                                        <strong>Good news!</strong> You are eligible for registration form generation.
                                        The Dean's Assistant will generate your registration form soon.
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
