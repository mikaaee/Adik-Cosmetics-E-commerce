@extends('layouts.main')

@section('title', 'About')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - [Adik Cosmetics]</title>
    <style>
        /* ===== Core Styles ===== */
        :root {
            --primary: #ff6b81;
            /* Romantic pink (adjust to brand) */
            --secondary: #ff4757;
            /* Deeper pink */
            --light: #fff5f5;
            /* Soft pink bg */
            --dark: #333;
            --text: #555;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text);
            background: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== Hero Section ===== */
        .about-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                url('https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            text-align: center;
        }

        .about-hero h1 {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .about-hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }

        /* ===== Story Sections ===== */
        .story-section {
            padding: 80px 0;
            display: flex;
            align-items: center;
            gap: 50px;
        }

        .story-text {
            flex: 1;
        }

        .story-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .story-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s;
        }

        .story-image:hover img {
            transform: scale(1.03);
        }

        /* Alternate layout for even sections */
        .story-section:nth-child(even) {
            flex-direction: row-reverse;
            background: var(--light);
        }

        /* ===== Team Section ===== */
        .team-section {
            padding: 80px 0;
            text-align: center;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .team-member {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .team-member:hover {
            transform: translateY(-10px);
        }

        .team-member img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .member-info {
            padding: 20px;
        }

        .member-info h3 {
            margin: 10px 0 5px;
            color: var(--dark);
        }

        .member-info p {
            color: var(--primary);
            font-style: italic;
        }

        /* ===== Values Section ===== */
        .values-section {
            padding: 80px 0;
            background: var(--light);
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .value-card {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .value-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        /* ===== CTA Section ===== */
        .cta-section {
            padding: 100px 0;
            text-align: center;
            background:whitesmoke;
            color: black;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: var(--primary);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>Adik Cosmetics: From Kampung to Clicks</h1>
            <p>Rooted in Kampung Paya Teratai, our journey is about bringing local beauty to the digital world—one
                handcrafted solution at a time.</p>
        </div>
    </section>

    <!-- Story Section 1 -->
    <section class="story-section">
        <div class="container">
            <div class="story-text">
                <h2>A Humble Beginning</h2>
                <p>Adik Cosmetics began as a small store in Kampung Paya Teratai, Beris Kubor Besar, Bachok, Kelantan.
                    Run solely by Salmimah bt Mohammed, she managed everything herself—from inventory to customer
                    service—without digital tools. The store has long served its community with love and care, despite
                    operating entirely offline.</p>
            </div>
            <div class="story-image">
                <img src="#"
                    alt="Traditional store setup">
            </div>
        </div>
    </section>

    <!-- Story Section 2 -->
    <section class="story-section">
        <div class="container">
            <div class="story-text">
                <h2>Why Go Digital?</h2>
                <p>Manual order-taking through WhatsApp and physical logbooks often caused delays, errors, and missed
                    sales opportunities. To better serve a growing customer base and reach audiences beyond the village,
                    Salmimah envisioned a more efficient way to run her business—through a dedicated online store with
                    AI chatbot support, offering 24/7 assistance and easy product access.</p>
            </div>
            <div class="story-image">
                <img src="#"
                    alt="Digital transformation">
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 style="text-align: center;">Our Values</h2>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🛍️</div>
                    <h3>Customer Convenience</h3>
                    <p>We aim to provide a smooth shopping experience where customers can shop anytime, anywhere.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">🤖</div>
                    <h3>Smart Assistance</h3>
                    <p>Our AI-powered chatbot is ready to assist you 24/7, ensuring quick and helpful responses.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">🌸</div>
                    <h3>Local Roots, Global Reach</h3>
                    <p>Though we started in a small kampung, our vision is to serve customers far and wide.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Support Local, Shop Smart</h2>
            <p>Join countless others supporting local entrepreneurs like Salmimah, and enjoy a seamless, smart beauty
                shopping experience.</p>
            <a href="{{route('products.all')}}" class="cta-button">Explore Our Products</a>
        </div>
    </section>

</body>

</html>
@endsection
