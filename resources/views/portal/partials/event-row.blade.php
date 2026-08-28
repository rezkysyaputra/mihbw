<div class="event-row">
    <div class="event-date">
        <span class="event-day">{{ $event->starts_at->format('d') }}</span>
        <span class="event-month">{{ $event->starts_at->translatedFormat('M') }}</span>
    </div>
    <div class="min-w-0 flex-1">
        <h4 class="clean-list-title">{{ $event->title }}</h4>
        @if($event->description)
            <p class="clean-list-text">{{ $event->description }}</p>
        @endif
        <p class="clean-list-meta">
            {{ $event->starts_at->translatedFormat('l, d F Y') }}
            @if($event->location)
                &middot; {{ $event->location }}
            @endif
        </p>
    </div>
</div>
