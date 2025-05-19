{{-- resources/views/components/tourist/card.blade.php --}}
@props(['title', 'actions' => null])

<div class="card">
    @if($title)
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $title }}</h5>
            @if($actions)
                <div class="card-actions">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
