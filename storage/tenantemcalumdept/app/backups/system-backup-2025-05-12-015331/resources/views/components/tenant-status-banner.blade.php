@props(['message', 'type' => 'warning'])

@php
    $bgColor = 'bg-yellow-100 border-yellow-400 text-yellow-700';
    $icon = '<svg class="w-5 h-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>';
    
    if ($type === 'danger') {
        $bgColor = 'bg-red-100 border-red-400 text-red-700';
        $icon = '<svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>';
    } elseif ($type === 'info') {
        $bgColor = 'bg-blue-100 border-blue-400 text-blue-700';
        $icon = '<svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>';
    }
@endphp

<div class="w-full border-l-4 {{ $bgColor }} p-4 mb-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            {!! $icon !!}
        </div>
        <div class="ml-3">
            <p class="text-sm">
                {{ $message }}
            </p>
        </div>
    </div>
</div>
