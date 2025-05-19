{{-- resources/views/components/tourist/rating.blade.php --}}
@props(['value', 'max' => 5])

<div class="rating">
    @for($i = 1; $i <= $max; $i++)
        <i class="bi bi-star{{ $i <= $value ? '-fill' : '' }} text-warning"></i>
    @endfor
</div>
