@props(['header'])

<x-layouts.suspended-app :header="$header ?? null">
    {{ $slot }}
</x-layouts.suspended-app> 