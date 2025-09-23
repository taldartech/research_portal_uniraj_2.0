<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Assign Expert for Thesis Evaluation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Thesis Details</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p><strong>Scholar:</strong> {{ $thesis->scholar->user->name }}</p>
                            <p><strong>Title:</strong> {{ $thesis->title }}</p>
                            <p><strong>Supervisor:</strong> {{ $thesis->supervisor->user->name }}</p>
                            <p><strong>Submission Date:</strong> {{ $thesis->submission_date->format('M d, Y') }}</p>
                            <p class="mt-2">
                                <strong>Thesis File:</strong>
                                <a href="{{ Storage::url($thesis->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Download Thesis
                                </a>
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('hvc.thesis.assign_expert.store', $thesis) }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="expert_id" :value="__('Select Expert')" />
                            <select id="expert_id" name="expert_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select Expert</option>
                                @foreach($experts as $expert)
                                    <option value="{{ $expert->id }}">{{ $expert->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('expert_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="due_date" :value="__('Evaluation Due Date')" />
                            <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date')" required />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('hvc.thesis.approved') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('Assign Expert') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
