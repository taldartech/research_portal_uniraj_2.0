<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Digital Signatures') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Manage Digital Signatures</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Create and manage your digital signatures for document signing.</p>
                    </div>

                    <!-- Create New Signature -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Create New Signature</h4>

                        <form method="POST" action="{{ route('profile.signatures.create') }}" id="signatureForm">
                            @csrf

                            <div class="mb-4">
                                <x-input-label for="signature_name" :value="__('Signature Name (Optional)')" />
                                <x-text-input id="signature_name" class="block mt-1 w-full" type="text" name="signature_name" placeholder="e.g., Official Signature, Personal Signature" />
                                <x-input-error :messages="$errors->get('signature_name')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="signature_canvas" :value="__('Draw Your Signature *')" />
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <canvas id="signatureCanvas" width="600" height="200" class="border border-gray-300 dark:border-gray-600 rounded cursor-crosshair"></canvas>
                                    <div class="mt-2 flex space-x-2">
                                        <button type="button" id="clearSignature" class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">Clear</button>
                                        <button type="button" id="saveSignature" class="px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600">Save Signature</button>
                                    </div>
                                </div>
                                <input type="hidden" id="signature_data" name="signature_data" required>
                                <x-input-error :messages="$errors->get('signature_data')" class="mt-2" />
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button type="submit" id="submitSignature" disabled>
                                    {{ __('Create Signature') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Existing Signatures -->
                    @if($signatures->count() > 0)
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Your Signatures</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($signatures as $signature)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $signature->is_active ? 'ring-2 ring-green-500 bg-green-50 dark:bg-green-900' : '' }}">
                                        <div class="text-center">
                                            @if($signature->signature_file && $signature->isSignatureFileExists())
                                                <img src="{{ $signature->getSignatureUrl() }}" alt="Signature" class="mx-auto mb-2 max-w-full h-20 object-contain border border-gray-300 dark:border-gray-600 rounded">
                                            @else
                                                <div class="mx-auto mb-2 w-full h-20 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded flex items-center justify-center">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">Signature not available</span>
                                                </div>
                                            @endif

                                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                                                {{ $signature->signature_name ?: 'Signature ' . $signature->id }}
                                            </h5>

                                            @if($signature->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mb-2">
                                                    Active
                                                </span>
                                            @endif

                                            <div class="flex justify-center space-x-2">
                                                @if(!$signature->is_active)
                                                    <form method="POST" action="{{ route('profile.signatures.activate', $signature->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 text-sm">
                                                            Set Active
                                                        </button>
                                                    </form>
                                                @endif

                                                <form method="POST" action="{{ route('profile.signatures.delete', $signature->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this signature?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 text-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-5 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No signatures</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first digital signature above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signatureCanvas');
            const ctx = canvas.getContext('2d');
            const signatureDataInput = document.getElementById('signature_data');
            const submitButton = document.getElementById('submitSignature');
            const clearButton = document.getElementById('clearSignature');
            const saveButton = document.getElementById('saveSignature');

            let isDrawing = false;
            let hasSignature = false;

            // Set canvas background
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Mouse events
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Touch events for mobile
            canvas.addEventListener('touchstart', handleTouch);
            canvas.addEventListener('touchmove', handleTouch);
            canvas.addEventListener('touchend', stopDrawing);

            function startDrawing(e) {
                isDrawing = true;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                ctx.beginPath();
                ctx.moveTo(x, y);
            }

            function draw(e) {
                if (!isDrawing) return;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                ctx.lineTo(x, y);
                ctx.stroke();
                hasSignature = true;
            }

            function stopDrawing() {
                isDrawing = false;
                ctx.beginPath();
            }

            function handleTouch(e) {
                e.preventDefault();
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' :
                                                 e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(mouseEvent);
            }

            clearButton.addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                signatureDataInput.value = '';
                hasSignature = false;
                submitButton.disabled = true;
            });

            saveButton.addEventListener('click', function() {
                if (hasSignature) {
                    const dataURL = canvas.toDataURL('image/png');
                    signatureDataInput.value = dataURL;
                    submitButton.disabled = false;
                    alert('Signature saved! You can now submit the form.');
                } else {
                    alert('Please draw a signature first.');
                }
            });
        });
    </script>
</x-app-layout>
