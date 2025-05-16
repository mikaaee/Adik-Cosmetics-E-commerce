 @if (!empty($ads) && count($ads))
     <div class="hero-banner">
         <img src="{{ $ads[0]['image_url'] }}" alt="{{ $ads[0]['title'] }}">
         <div class="hero-text">
             <h1>{{ $ads[0]['title'] }}</h1>
             <a href="{{ route('promo.page') }}" class="btn-hero">Shop Promo Now! </a>
         </div>
     </div>

     <style>
         .hero-banner {
             position: relative;
             width: 100%;
             height: 60vh;
             overflow: hidden;
         }

         .hero-banner::before {
             content: '';
             position: absolute;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             background: rgba(255, 255, 255, 0.4);
             z-index: 1;
         }

         .hero-banner img {
             width: 100%;
             height: 100%;
             object-fit: cover;
             display: block;
             opacity: 0.3;
             /* Optional */
         }

         .hero-text {
             position: absolute;
             bottom: 30px;
             left: 50%;
             transform: translateX(-50%);
             text-align: center;
             padding: 20px 40px;
             border-radius: 12px;
             z-index: 2;
         }

         .hero-text h1 {
             color: #c96c9c;
             /* White for better contrast */
             font-size: 3rem;
             /* Larger on desktop */
             text-shadow: 2px 2px 8px rgba(159, 112, 148, 0.5);
             margin-bottom: 20px;
             animation: fadeInUp 0.8s ease;
         }

         /* ---- Modern Button Design ---- */
         .btn-hero {
             display: inline-block;
             padding: 12px 30px;
             background: #fff;
             color: #c96c9c;
             text-decoration: none;
             border-radius: 50px;
             /* Pill shape */
             font-weight: bold;
             font-size: 1.1rem;
             transition: all 0.3s ease;
             box-shadow: 0 4px 15px rgba(231, 146, 164, 0.4);
             border: none;
             cursor: pointer;
         }

         .btn-hero:hover {
             transform: translateY(-3px);
             box-shadow: 0 6px 20px rgba(255, 107, 139, 0.6);
             background: #fff;
         }

         /* Arrow animation on hover */
         .btn-hero::after {
             margin-left: 8px;
             transition: transform 0.3s ease;
         }

         .btn-hero:hover::after {
             transform: translateX(5px);
         }

         /* Responsive Adjustments */
         @media (max-width: 768px) {
             .hero-banner {
                 height: 40vh;
             }

             .hero-text {
                 padding: 15px 25px;
                 width: 90%;
             }

             .hero-text h1 {
                 font-size: 1.5rem;
             }

             .btn-hero {
                 padding: 10px 20px;
                 font-size: 1rem;
             }
         }
     </style>
 @else
     <div style="text-align: center; padding: 30px;">
         <p style="color: red;">No ads to be displayed.</p>
     </div>
 @endif
