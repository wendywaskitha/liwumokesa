<?php

namespace App\Livewire\Tourist;

use Livewire\Component;
use App\Models\Destination;
use Filament\Notifications\Notification;

class RecommendedDestinations extends Component
{
    public $destinations;
    public $showDetailModal = false;
    public $selectedDestination = null;

    protected $listeners = ['destinationAddedToWishlist' => '$refresh'];

    public function mount()
    {
        $this->loadDestinations();
    }

    public function loadDestinations()
    {
        // Get recommended destinations based on user preferences
        $this->destinations = Destination::with(['media', 'reviews'])
            ->whereActive(true)
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->map(function ($destination) {
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'description' => $destination->description,
                    'image' => $destination->getFirstMediaUrl('images'),
                    'rating' => $destination->reviews->avg('rating'),
                    'review_count' => $destination->reviews->count(),
                    'is_wishlisted' => auth()->user()->wishlist->contains($destination->id)
                ];
            });
    }

    public function toggleWishlist($destinationId)
    {
        $user = auth()->user();

        if ($user->wishlist->contains($destinationId)) {
            $user->wishlist()->detach($destinationId);
            $message = 'Dihapus dari wishlist';
        } else {
            $user->wishlist()->attach($destinationId);
            $message = 'Ditambahkan ke wishlist';
        }

        $this->loadDestinations();

        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.tourist.recommended-destinations');
    }
}
