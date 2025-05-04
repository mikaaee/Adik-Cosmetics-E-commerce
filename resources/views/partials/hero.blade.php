@if (count($ads))
    <section class="hero-ads">
        @foreach ($ads as $ad)
            <div class="ad-banner">
                <img src="{{ $ad['image_url'] }}" alt="{{ $ad['title'] }}">
            </div>
        @endforeach
    </section>
@endif


