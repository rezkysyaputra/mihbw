@php
    $thumbnail = \App\Support\PublicImage::storageUrl($post->cover_image, asset('images/placeholders/news-placeholder.svg'));
@endphp

<a href="{{ route('posts.show', $post->slug) }}" class="clean-list-item">
    <div class="min-w-0 flex-1">
        <h2 class="clean-list-title">{{ $post->title }}</h2>
        @if($post->excerpt)
            <p class="clean-list-text">{{ $post->excerpt }}</p>
        @endif
        <p class="clean-list-meta">{{ optional($post->published_at)->diffForHumans() }}</p>
    </div>
    <img src="{{ $thumbnail }}" alt="Gambar sampul {{ $post->title }}" loading="lazy" class="clean-list-thumb">
</a>
