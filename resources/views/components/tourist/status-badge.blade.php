{{-- resources/views/components/tourist/status-badge.blade.php --}}
@props(['status'])

@php
$colors = [
    'pending' => 'warning',
    'confirmed' => 'success',
    'cancelled' => 'danger',
    'completed' => 'info'
];
$color = $colors[$status] ?? 'secondary';
@endphp

<span class="badge bg-{{ $color }}">
    {{ ucfirst($status) }}
</span>
