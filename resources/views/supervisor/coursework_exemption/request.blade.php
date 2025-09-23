<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Coursework Exemption') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Scholar: {{ $scholar->user->name }}</h3>

                    <form method="POST" action="{{ route('staff.coursework_exemption.request.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf

                        <input type="hidden" name="scholar_id" value="{{ $scholar->id }}">

                        <!-- RAC Selection (assuming a supervisor can select from their scholar's RACs) -->
                        <div class="mb-4">
                            <x-input-label for="rac_id" :value="__('Relevant RAC')" />
                            {{-- For simplicity, let's assume the latest active RAC is pre-selected or there's a simple dropdown --}}
                            <x-select-input id="rac_id" name="rac_id" class="block mt-1 w-full" required>
                                <option value="">Select RAC</option>
                                @foreach($scholar->racs as $rac)
                                    <option value="{{ $rac->id }}" {{ old('rac_id') == $rac->id ? 'selected' : '' }}>RAC formed on {{ $rac->formed_date->format('Y-m-d') }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('rac_id')" class="mt-2" />
                        </div>

                        <!-- Reason for Exemption -->
                        <div class="mb-4">
                            <x-input-label for="reason" :value="__('Reason for Exemption')" />
                            <x-textarea-input id="reason" name="reason" class="block mt-1 w-full" rows="5" required>{{ old('reason') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <!-- RAC Minutes File Upload -->
                        <div class="mb-4">
                            <x-input-label for="minutes_file" :value="__('RAC Minutes (PDF, DOC, DOCX)')" />
                            <input id="minutes_file" class="block mt-1 w-full" type="file" name="minutes_file" required />
                            <x-input-error :messages="$errors->get('minutes_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Submit Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
