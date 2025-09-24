<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Topic Change Proposal Response') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Topic Change Proposal Details -->
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h3 class="text-lg font-medium text-yellow-800 mb-3">Topic Change Proposal</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="font-medium text-gray-700">Proposed by:</span>
                                <span class="text-gray-900">{{ $synopsis->topicChangeProposedBy->name ?? 'Unknown' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Proposed on:</span>
                                <span class="text-gray-900">{{ $synopsis->topic_change_proposed_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Current vs Proposed Topic -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-lg font-medium text-blue-800 mb-2">Current Topic</h4>
                            <p class="text-gray-700">{{ $synopsis->proposed_topic }}</p>
                        </div>
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h4 class="text-lg font-medium text-green-800 mb-2">Proposed New Topic</h4>
                            <p class="text-gray-700">{{ $synopsis->proposed_topic_change }}</p>
                        </div>
                    </div>

                    <!-- Reason for Change -->
                    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Reason for Topic Change</h4>
                        <p class="text-gray-700">{{ $synopsis->topic_change_reason }}</p>
                    </div>

                    <!-- Response Form -->
                    <form method="POST" action="{{ route('scholar.synopsis.topic-change-response.store', $synopsis) }}">
                        @csrf

                        <!-- Response Selection -->
                        <div class="mb-4">
                            <x-input-label for="response" :value="__('Your Response')" />
                            <x-select-input id="response" name="response" class="block mt-1 w-full" required>
                                <option value="">Select your response</option>
                                <option value="accept" {{ old('response') == 'accept' ? 'selected' : '' }}>Accept Topic Change</option>
                                <option value="reject" {{ old('response') == 'reject' ? 'selected' : '' }}>Reject Topic Change</option>
                            </x-select-input>
                            <x-input-error :messages="$errors->get('response')" class="mt-2" />
                        </div>

                        <!-- Remarks -->
                        <div class="mb-4">
                            <x-input-label for="remarks" :value="__('Your Remarks')" />
                            <x-textarea-input id="remarks" name="remarks" class="block mt-1 w-full" rows="5" required placeholder="Please provide your comments on this topic change proposal...">{{ old('remarks') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <!-- Important Notice -->
                        <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <h4 class="text-lg font-medium text-orange-800 mb-2">Important Notice</h4>
                            <ul class="text-sm text-orange-700 space-y-1">
                                <li>• If you <strong>accept</strong> the topic change, your synopsis will be updated with the new topic.</li>
                                <li>• If you <strong>reject</strong> the topic change, your original topic will remain unchanged.</li>
                                <li>• Your supervisor will be notified of your decision.</li>
                                <li>• This decision cannot be undone once submitted.</li>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('scholar.dashboard') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Submit Response') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
