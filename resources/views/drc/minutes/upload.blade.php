<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload DRC Minutes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('drc.minutes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="record_id" value="{{ $record->id }}">
                        <input type="hidden" name="record_type" value="{{ $type }}">

                        <div class="mb-4">
                            <x-input-label for="record_info" :value="__('Record Details')" />
                            <p class="mt-1 text-sm text-gray-600">
                                @if($type === 'supervisor_assignment')
                                    Supervisor Assignment for Scholar: {{ $record->scholar->user->name ?? 'N/A' }} (ID: {{ $record->id }}) - Status: {{ ucfirst($record->status) }}
                                @elseif($type === 'synopsis')
                                    Synopsis for Scholar: {{ $record->rac->scholar->user->name ?? 'N/A' }} (ID: {{ $record->id }}) - Status: {{ ucfirst($record->status) }}
                                @endif
                            </p>
                        </div>

                        <!-- Meeting Date -->
                        <div class="mb-4">
                            <x-input-label for="meeting_date" :value="__('Meeting Date')" />
                            <x-text-input id="meeting_date" class="block mt-1 w-full" type="date" name="meeting_date" :value="old('meeting_date')" required />
                            <x-input-error :messages="$errors->get('meeting_date')" class="mt-2" />
                        </div>

                        <!-- Minutes File Upload -->
                        <div class="mb-4">
                            <x-input-label for="minutes_file" :value="__('Minutes File (PDF/DOC/DOCX)')" />
                            <input id="minutes_file" class="block mt-1 w-full" type="file" name="minutes_file" required />
                            <x-input-error :messages="$errors->get('minutes_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Upload Minutes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
