@props(['destination'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <img src="{{ $destination->featured_image_url }}"
         alt="{{ $destination->name }}"
         class="w-full h-48 object-cover">
    <div class="p-4">
        <h4 class="font-medium text-gray-900">{{ $destination->name }}</h4>
        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($destination->description, 100) }}</p>
        <div class="mt-4 flex justify-between items-center">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="ml-1 text-sm text-gray-600">{{ number_format($destination->average_rating, 1) }}</span>
            </div>
            <x-secondary-button wire:click="addToWishlist({{ $destination->id }})">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Wishlist
            </x-secondary-button>
        </div>
    </div>
</div>
