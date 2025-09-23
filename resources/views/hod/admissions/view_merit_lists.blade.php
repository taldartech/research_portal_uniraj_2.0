<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Uploaded Merit Lists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($admissions->isEmpty())
                        <p>No merit lists have been uploaded for your department.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merit List File</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($admissions as $admission)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $admission->department->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $admission->admission_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ Storage::url($admission->merit_list_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Download</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $admission->status)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
