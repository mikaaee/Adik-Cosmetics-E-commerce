@if (!empty($ads) && count($ads))
    <div class="hero-slider-wrapper">
        <div class="hero-slider" id="heroSlider">
            @foreach ($ads as $index => $ad)
                <div class="hero-slide">
                    <img src="{{ $ad['image_url'] }}" alt="{{ $ad['title'] }}">
                    <div class="hero-text">
                        <h1>{{ $ad['title'] }}</h1>
                        <a href="{{ route('promo.page') }}" class="btn-hero">Shop Promo Now!</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="slider-dots" id="sliderDots">
            @foreach ($ads as $index => $ad)
                <span class="dot" onclick="goToSlide({{ $index }})"></span>
            @endforeach
        </div>
    </div>

    <style>
        .hero-slider-wrapper {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .hero-slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .hero-slide {
            flex: 0 0 100%;
            height: 60vh;
            position: relative;
        }

        .hero-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
        }

        .hero-text {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            padding: 20px 40px;
            z-index: 2;
        }

        .hero-text h1 {
            color: #c96c9c;
            font-size: 3rem;
            text-shadow: 2px 2px 8px rgba(159, 112, 148, 0.5);
            margin-bottom: 20px;
        }

        .btn-hero {
            padding: 12px 30px;
            background: #fff;
            color: #c96c9c;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(231, 146, 164, 0.4);
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.6);
        }

        .slider-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 3;
        }

        .slider-dots .dot {
            width: 12px;
            height: 12px;
            background: #fff;
            border: 2px solid #c96c9c;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0.6;
            transition: 0.3s;
        }

        .slider-dots .dot.active {
            background: #c96c9c;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .hero-slide {
                height: 40vh;
            }

            .hero-text h1 {
                font-size: 1.5rem;
            }

            .btn-hero {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }
    </style>

    <script>
        let currentSlide = 0;
        const slider = document.getElementById('heroSlider');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = {{ count($ads) }};

        function updateSliderPosition() {
            slider.style.transform = `translateX(-${100 * currentSlide}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSliderPosition();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSliderPosition();
        }

        // Auto-slide
        let autoSlide = setInterval(nextSlide, 4000);

        // Pause on hover
        slider.addEventListener('mouseenter', () => clearInterval(autoSlide));
        slider.addEventListener('mouseleave', () => autoSlide = setInterval(nextSlide, 4000));

        // Swipe support (mobile)
        let touchStartX = 0;
        slider.addEventListener('touchstart', e => {
            touchStartX = e.touches[0].clientX;
        });

        slider.addEventListener('touchend', e => {
            const touchEndX = e.changedTouches[0].clientX;
            if (touchEndX < touchStartX - 50) {
                nextSlide();
            } else if (touchEndX > touchStartX + 50) {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSliderPosition();
            }
        });

        // Init first dot
        updateSliderPosition();
    </script>
@else
    <div style="text-align: center; padding: 30px;">
        <p style="color: red;">No ads to be displayed.</p>
    </div>
@endif
