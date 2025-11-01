@props(['availableRoles' => []])

{{-- Reassignment Fields Component --}}
<div id="reassignment_fields" class="mb-6 hidden">
    <div class="mb-4">
        <label for="reassigned_to_role" class="block text-sm font-medium text-gray-700 mb-2">Reassign To Role</label>
        <select id="reassigned_to_role" name="reassigned_to_role" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Select Role (Optional - Leave empty for standard rejection)</option>
            @if(count($availableRoles) > 0)
                @foreach($availableRoles as $role)
                    <option value="{{ $role['value'] }}" {{ old('reassigned_to_role') == $role['value'] ? 'selected' : '' }}>{{ $role['label'] }}</option>
                @endforeach
            @endif
        </select>
        <p class="mt-1 text-sm text-gray-500">If selected, the item will be reassigned to this role for corrections instead of being rejected.</p>
        @error('reassigned_to_role')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="reassignment_reason" class="block text-sm font-medium text-gray-700 mb-2">Reassignment Reason (Optional)</label>
        <textarea id="reassignment_reason" name="reassignment_reason" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Explain why it's being reassigned to this role...">{{ old('reassignment_reason') }}</textarea>
        @error('reassignment_reason')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
    // Show/hide reassignment fields based on action selection
    document.addEventListener('DOMContentLoaded', function() {
        const actionSelect = document.getElementById('action');
        const reassignmentFields = document.getElementById('reassignment_fields');

        if (actionSelect && reassignmentFields) {
            actionSelect.addEventListener('change', function() {
                if (this.value === 'reject') {
                    reassignmentFields.classList.remove('hidden');
                } else {
                    reassignmentFields.classList.add('hidden');
                }
            });

            // Trigger on page load if old value exists
            if (actionSelect.value === 'reject') {
                reassignmentFields.classList.remove('hidden');
            }
        }
    });
</script>

