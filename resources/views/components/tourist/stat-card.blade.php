{{-- resources/views/components/tourist/stat-card.blade.php --}}
@props(['title', 'value', 'icon'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100">
                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
            </div>
        </div>
    </div>
</div>
