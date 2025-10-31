<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approve Coursework Exemption') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Coursework Exemption Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scholar Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $exemption->scholar->user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">RAC</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $exemption->rac->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reason</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $exemption->reason }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submission Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $exemption->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        @if($exemption->minutes_file)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">RAC Minutes</label>
                            <a href="{{ Storage::url($exemption->minutes_file) }}"
                               target="_blank"
                               class="mt-1 text-sm text-indigo-600 hover:text-indigo-900">
                                Download RAC Minutes
                            </a>
                        </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('staff.coursework_exemption.approve.store', $exemption) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Remark</label>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="action" value="approve" class="form-radio text-green-600" required>
                                    <span class="ml-2 text-sm text-gray-700">Approve and Forward to Dean</span>
                                </label>
                                <br>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="action" value="reject" class="form-radio text-red-600" required>
                                    <span class="ml-2 text-sm text-gray-700">Unsatisfied</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="supervisor_remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Remarks
                            </label>
                            <textarea name="supervisor_remarks"
                                      id="supervisor_remarks"
                                      rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Add your comments or feedback...">{{ old('supervisor_remarks') }}</textarea>
                            @error('supervisor_remarks')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="rac_minutes_file" class="block text-sm font-medium text-gray-700 mb-2">
                                RAC Minutes File <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="rac_minutes_file" name="rac_minutes_file" accept=".pdf,.doc,.docx" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX (Max size: 5MB)</p>
                            @error('rac_minutes_file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="rac_meeting_date" class="block text-sm font-medium text-gray-700 mb-2">
                                RAC Meeting Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="rac_meeting_date" name="rac_meeting_date" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            @error('rac_meeting_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('staff.coursework_exemption.pending') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit Remark
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

