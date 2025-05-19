{{-- resources/views/livewire/tourist/recommended-destinations.blade.php --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900">Rekomendasi untuk Anda</h3>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($destinations as $destination)
                <div class="relative group">
                    <!-- Destination Card -->
                    <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <!-- Image -->
                        <div class="relative h-48">
                            <img
                                src="{{ $destination['image'] }}"
                                alt="{{ $destination['name'] }}"
                                class="w-full h-full object-cover"
                            >
                            <!-- Wishlist Button -->
                            <button
                                wire:click="toggleWishlist({{ $destination['id'] }})"
                                class="absolute top-2 right-2 p-2 rounded-full bg-white shadow-sm hover:bg-gray-100"
                            >
                                <svg class="w-5 h-5 {{ $destination['is_wishlisted'] ? 'text-red-500' : 'text-gray-400' }}"
                                     fill="{{ $destination['is_wishlisted'] ? 'currentColor' : 'none' }}"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h4 class="font-medium text-gray-900">{{ $destination['name'] }}</h4>

                            <!-- Rating -->
                            <div class="mt-1 flex items-center">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $destination['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-1 text-sm text-gray-500">
                                    ({{ $destination['review_count'] }} ulasan)
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="mt-2 text-sm text-gray-600">
                                {{ Str::limit($destination['description'], 100) }}
                            </p>

                            <!-- Action Button -->
                            <div class="mt-4">
                                <a href="{{ route('destinations.show', $destination['id']) }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
