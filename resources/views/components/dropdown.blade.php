@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-700', 'dropup' => false])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

if ($dropup) {
    $alignmentClasses = str_replace('origin-top', 'origin-bottom', $alignmentClasses);
}

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 {{ $dropup ? 'scale-95 translate-y-2' : 'scale-95 -translate-y-2' }}"
            x-transition:enter-end="opacity-100 scale-100 {{ $dropup ? 'translate-y-0' : 'translate-y-0' }}"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100 {{ $dropup ? 'translate-y-0' : 'translate-y-0' }}"
            x-transition:leave-end="opacity-0 {{ $dropup ? 'scale-95 translate-y-2' : 'scale-95 -translate-y-2' }}"
            class="absolute z-50 {{ $dropup ? 'bottom-full mb-2' : 'mt-2' }} {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
