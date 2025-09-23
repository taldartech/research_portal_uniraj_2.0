<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload RAC Minutes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('staff.rac_minutes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- RAC Selection -->
                        <div class="mb-4">
                            <x-input-label for="rac_id" :value="__('Select RAC')" />
                            <x-select-input id="rac_id" name="rac_id" class="block mt-1 w-full" required>
                                <option value="">Select a RAC</option>
                                @foreach($racs as $rac)
                                    <option value="{{ $rac->id }}">RAC for Scholar: {{ $rac->scholar->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('rac_id')" class="mt-2" />
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
