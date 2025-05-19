@props(['review'])

<div class="border-b border-gray-200 last:border-0 py-4">
    <div class="flex justify-between">
        <div>
            <h4 class="font-medium text-gray-900">{{ $review->reviewable->name }}</h4>
            <div class="flex items-center mt-1">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
            <p class="mt-2 text-sm text-gray-600">{{ $review->comment }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
        </div>
        @if($review->status === 'pending')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                Menunggu moderasi
            </span>
        @endif
    </div>
</div>
