<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Upload Coursework Result') }} - {{ $scholar->user->name }}
            </h2>
            <a href="{{ route('hod.coursework.list') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Upload Form -->
            @if(!$scholar->coursework_completed)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Upload Coursework Result</h3>
                            <form method="POST" action="{{ route('hod.coursework.store', $scholar) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="marksheet_file" :value="__('Marksheet File')" :required="true"/>
                                        <input id="marksheet_file" name="marksheet_file" type="file"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                        <x-input-error :messages="$errors->get('marksheet_file')" class="mt-2" />
                                        <p class="mt-1 text-sm text-gray-500">Upload marksheet (PDF, DOC, DOCX, JPG, PNG - max 5MB)</p>
                                    </div>

                                    <div>
                                        <x-input-label for="exam_date" :value="__('Exam Date')" :required="true"/>
                                        <x-text-input id="exam_date" name="exam_date" type="date" class="mt-1 block w-full"
                                            value="{{ old('exam_date') }}" required/>
                                        <x-input-error :messages="$errors->get('exam_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="result" :value="__('Result')" :required="true"/>
                                        <select id="result" name="result" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            <option value="">Select Result</option>
                                            <option value="pass" {{ old('result') == 'pass' ? 'selected' : '' }}>Pass</option>
                                            <option value="fail" {{ old('result') == 'fail' ? 'selected' : '' }}>Fail</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('result')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="remarks" :value="__('Remarks (Optional)')" />
                                        <textarea id="remarks" name="remarks" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('remarks') }}</textarea>
                                        <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                    </div>

                                    <div class="flex items-center justify-end mt-6">
                                        <x-primary-button>
                                            {{ __('Upload Result') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            @endif
            <!-- Previous Results -->
            @if($courseworkResults->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Previous Coursework Results</h3>

                        <div class="space-y-4">
                            @foreach($courseworkResults as $result)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $result->result == 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ strtoupper($result->result) }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    Exam Date: {{ $result->exam_date->format('M d, Y') }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    Uploaded by: {{ $result->uploadedBy->name }}
                                                </span>
                                            </div>
                                            @if($result->remarks)
                                                <p class="mt-2 text-sm text-gray-700">{{ $result->remarks }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ asset('storage/' . $result->marksheet_file) }}"
                                               target="_blank"
                                               class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                View Marksheet
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

