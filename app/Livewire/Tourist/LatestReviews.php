<?php

namespace App\Livewire\Tourist;

use Livewire\Component;
use App\Models\Review;

class LatestReviews extends Component
{
    public $reviews;
    public $showDetailModal = false;
    public $selectedReview = null;

    protected $listeners = ['reviewDeleted' => '$refresh'];

    public function mount()
    {
        $this->loadReviews();
    }

    public function loadReviews()
    {
        $this->reviews = auth()->user()
            ->reviews()
            ->with(['reviewable'])
            ->latest()
            ->take(3)
            ->get();
    }

    public function showDetail($reviewId)
    {
        $this->selectedReview = Review::with('reviewable')
            ->findOrFail($reviewId);
        $this->showDetailModal = true;
    }

    public function deleteReview($reviewId)
    {
        $review = Review::find($reviewId);

        if ($review && $review->user_id === auth()->id()) {
            $review->delete();

            $this->notification()->success(
                $title = 'Ulasan Dihapus',
                $description = 'Ulasan berhasil dihapus'
            );

            $this->loadReviews();
        }
    }

    public function render()
    {
        return view('livewire.tourist.latest-reviews');
    }
}
