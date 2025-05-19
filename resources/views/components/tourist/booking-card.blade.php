@props(['booking'])

<div class="border-b border-gray-200 last:border-0 py-4">
    <div class="flex justify-between items-start">
        <div>
            <h4 class="font-medium text-gray-900">{{ $booking->bookable->name }}</h4>
            <p class="text-sm text-gray-500">
                {{ $booking->date->format('d M Y') }} • {{ $booking->time ?? 'Waktu fleksibel' }}
            </p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{
                $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
            }}">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
        <div class="flex space-x-2">
            <x-secondary-button wire:click="showBooking({{ $booking->id }})">
                Detail
            </x-secondary-button>
            @if($booking->canBeCancelled())
                <x-danger-button wire:click="cancelBooking({{ $booking->id }})">
                    Batalkan
                </x-danger-button>
            @endif
        </div>
    </div>
</div>
