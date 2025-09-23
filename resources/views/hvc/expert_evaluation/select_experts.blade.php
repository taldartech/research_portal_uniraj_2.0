<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Experts for Thesis Evaluation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Expert Selection for: {{ $thesis->title }}</h3>
                        <p class="mt-1 text-sm text-gray-600">Select 4 experts from the 8 suggested by the supervisor. Prioritize them for assignment.</p>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Thesis Details</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Scholar:</span> {{ $thesis->scholar->user->name }}
                            </div>
                            <div>
                                <span class="font-medium">Supervisor:</span> {{ $thesis->supervisor->user->name }}
                            </div>
                            <div>
                                <span class="font-medium">Submission Date:</span> {{ $thesis->submission_date->format('M d, Y') }}
                            </div>
                            <div>
                                <span class="font-medium">Research Area:</span> {{ $thesis->scholar->research_area ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    @if($suggestedExperts->count() > 0)
                        <form action="{{ route('hvc.thesis.select_experts.store', $thesis) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-4">Suggested Experts (Select 4)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($suggestedExperts as $index => $expert)
                                        <div class="border rounded-lg p-4 {{ in_array($expert->id, old('selected_experts', [])) ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                            <label class="flex items-start space-x-3 cursor-pointer">
                                                <input type="checkbox"
                                                       name="selected_experts[]"
                                                       value="{{ $expert->id }}"
                                                       class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                       {{ in_array($expert->id, old('selected_experts', [])) ? 'checked' : '' }}>
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-900">{{ $expert->name }}</div>
                                                    <div class="text-sm text-gray-600">{{ $expert->email }}</div>
                                                    <div class="text-sm text-gray-500 mt-1">
                                                        @if($expert->designation)
                                                            {{ $expert->designation }}
                                                        @endif
                                                        @if($expert->institution)
                                                            @ {{ $expert->institution }}
                                                        @endif
                                                    </div>
                                                    @if($expert->specialization)
                                                        <div class="text-xs text-gray-400 mt-1">
                                                            Specialization: {{ $expert->specialization }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selected_experts')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-4">Priority Order (Drag to reorder)</h4>
                                <div id="priority-list" class="space-y-2">
                                    <!-- Priority items will be populated by JavaScript -->
                                </div>
                                <input type="hidden" name="priority_order" id="priority-order-input">
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('hvc.thesis.approved') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Select Experts
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">No experts have been suggested by the supervisor yet.</div>
                            <div class="text-gray-400 text-sm mt-2">The supervisor needs to suggest 8 experts before you can select 4 for evaluation.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_experts[]"]');
            const priorityList = document.getElementById('priority-list');
            const priorityInput = document.getElementById('priority-order-input');

            function updatePriorityList() {
                const selectedExperts = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => {
                        const expertId = cb.value;
                        const expertName = cb.closest('label').querySelector('.font-medium').textContent;
                        return { id: expertId, name: expertName };
                    });

                priorityList.innerHTML = '';
                selectedExperts.forEach((expert, index) => {
                    const item = document.createElement('div');
                    item.className = 'flex items-center space-x-3 p-3 bg-gray-50 rounded border cursor-move';
                    item.draggable = true;
                    item.innerHTML = `
                        <div class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-medium">
                            ${index + 1}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">${expert.name}</div>
                        </div>
                        <input type="hidden" name="priority_${expert.id}" value="${index + 1}">
                    `;
                    priorityList.appendChild(item);
                });

                updatePriorityInput();
            }

            function updatePriorityInput() {
                const priorityItems = priorityList.querySelectorAll('input[type="hidden"]');
                const priorityOrder = Array.from(priorityItems).map(input => input.name.replace('priority_', ''));
                priorityInput.value = priorityOrder.join(',');
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                    if (checkedCount > 4) {
                        this.checked = false;
                        alert('You can only select 4 experts.');
                        return;
                    }
                    updatePriorityList();
                });
            });

            // Make priority list sortable
            let draggedElement = null;
            priorityList.addEventListener('dragstart', function(e) {
                draggedElement = e.target;
                e.target.style.opacity = '0.5';
            });

            priorityList.addEventListener('dragend', function(e) {
                e.target.style.opacity = '';
                draggedElement = null;
            });

            priorityList.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            priorityList.addEventListener('drop', function(e) {
                e.preventDefault();
                if (draggedElement && e.target !== draggedElement) {
                    const rect = e.target.getBoundingClientRect();
                    const midpoint = rect.top + rect.height / 2;

                    if (e.clientY < midpoint) {
                        priorityList.insertBefore(draggedElement, e.target);
                    } else {
                        priorityList.insertBefore(draggedElement, e.target.nextSibling);
                    }

                    updatePriorityInput();
                }
            });
        });
    </script>
</x-app-layout>
