{{-- resources/views/tourist/bookings/partials/_timeline-styles.blade.php --}}

<style>
/* Timeline Styling */
.booking-timeline {
    position: relative;
    padding: 20px 0;
}

.booking-timeline::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e2e8f0;
    z-index: 1;
    transform: translateY(-50%);
}

.timeline-item {
    position: relative;
    z-index: 2;
    text-align: center;
    background: white;
    padding: 0 15px;
}

.timeline-point {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #e2e8f0;
    margin: 0 auto 10px;
    border: 4px solid white;
    box-shadow: 0 0 0 2px #e2e8f0;
    transition: all 0.3s ease;
}

.timeline-item.active .timeline-point {
    background: var(--bs-primary);
    box-shadow: 0 0 0 2px var(--bs-primary);
}

.timeline-content h6 {
    font-size: 14px;
    margin-bottom: 4px;
}

.timeline-content small {
    font-size: 12px;
    color: #6c757d;
}
</style>
