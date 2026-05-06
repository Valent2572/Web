@extends('layout.app')

@section('styles')
<style>
    /* --- HERO SECTION (TRUE FULLSCREEN) --- */
    .hero-section {
        position: relative;
        width: 100%;
        height: 100vh;
        height: 100dvh;
        min-height: 750px;
        background-color: #000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .hero-video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%);
        object-fit: cover;
        z-index: 1;
        opacity: 0.8;
        pointer-events: none;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.8) 100%);
        z-index: 2;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 10;
        text-align: center !important;
        color: #fff;
        width: 100%;
        max-width: 1000px;
        padding: 0 30px;
        margin: 0 auto;
    }

    .hero-content .display-1 {
        font-family: 'Playfair Display', serif;
        font-weight: 900;
        font-size: clamp(3.5rem, 10vw, 7.5rem);
        margin-bottom: 1.5rem;
        background: linear-gradient(to bottom, #ffffff, #C5A059);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.1;
        filter: drop-shadow(0 10px 30px rgba(0,0,0,0.5));
    }

    .hero-content .lead {
        font-size: clamp(1.1rem, 2.5vw, 1.8rem);
        font-weight: 300;
        letter-spacing: 3px;
        text-transform: uppercase;
        max-width: 800px;
        margin: 0 auto 3rem auto;
        text-shadow: 0 5px 15px rgba(0,0,0,0.9);
        opacity: 0.9;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hero-content .hero-fade {
        animation: fadeInUp 1s ease-out both;
    }
    .hero-content .hero-fade:nth-child(1) { animation-delay: 0.2s; }
    .hero-content .hero-fade:nth-child(2) { animation-delay: 0.4s; }
    .hero-content .hero-fade:nth-child(3) { animation-delay: 0.6s; }
    .hero-content .hero-fade:nth-child(4) { animation-delay: 0.8s; }

    /* --- PARTICLES --- */
    .particles-container {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 2;
    }
    .particle {
        position: absolute;
        color: rgba(197, 160, 89, 0.4);
        font-size: 20px;
        animation: float 15s infinite linear;
        will-change: transform;
    }
    @keyframes float {
        0% { transform: translate3d(0, 100vh, 0) rotate(0deg); opacity: 0; }
        10% { opacity: 0.8; }
        90% { opacity: 0.8; }
        100% { transform: translate3d(50px, -100px, 0) rotate(360deg); opacity: 0; }
    }

    /* --- MARQUEE --- */
    .marquee-container {
        background: var(--bg-body); /* Standardized to match body theme and ensure solid opacity */
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        padding: 20px 0;
        overflow: hidden;
        white-space: nowrap;
        position: relative;
        z-index: 5; /* Ensure it sits on top of hero on scroll */
    }
    .marquee-content {
        display: inline-block;
        animation: marquee 30s linear infinite;
    }
    .marquee-item {
        display: inline-block;
        color: var(--gold);
        font-size: 1.2rem;
        font-weight: 500;
        font-style: italic;
        margin-right: 120px;
        font-family: 'Playfair Display', serif;
        letter-spacing: 1px;
    }
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* --- SIGNATURE EXPERIENCE --- */
    .experience-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.4s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .experience-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 0;
        background: var(--gold);
        opacity: 0.05;
        transition: height 0.4s ease;
        z-index: -1;
    }
    .experience-card:hover {
        transform: translateY(-10px);
        border-color: var(--gold);
        box-shadow: 0 15px 30px rgba(197, 160, 89, 0.15);
    }
    .experience-card:hover::before {
        height: 100%;
    }
    .experience-icon {
        width: 80px;
        height: 80px;
        background: var(--bg-body);
        border: 1px solid var(--gold);
        color: var(--gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 25px auto;
        transition: all 0.4s ease;
    }
    .experience-card:hover .experience-icon {
        background: var(--gold);
        color: #fff;
        transform: rotateY(360deg);
    }

    /* --- BEANS SHOWCASE --- */
    .bean-card {
        background: var(--card-bg);
        border: 2px solid rgba(0, 0, 0, 0.08); /* Dipertebal agar kontras dengan karung goni */
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        z-index: 1;
    }
    [data-bs-theme="dark"] .bean-card {
        border-width: 1px;
        border-color: var(--border-color);
    }
    .bean-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        border-color: var(--gold);
    }
    .bean-img-wrapper {
        position: relative;
        padding-top: 66%; /* 3:2 Aspect Ratio */
        overflow: hidden;
    }
    .bean-img-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
        will-change: transform;
    }
    .bean-card:hover .bean-img-wrapper img {
        transform: scale(1.1);
    }
    .bean-roast-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: var(--gold);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(197, 160, 89, 0.3);
    }
    /* --- WATERFALL EFFECT --- */
    .waterfall-container {
        position: absolute;
        inset: 0;
        overflow: hidden;
        z-index: 0;
        pointer-events: none;
        opacity: 0.5;
    }
    .water-streak {
        position: absolute;
        top: -100px;
        width: 1px;
        height: 100px;
        background: linear-gradient(to bottom, transparent, var(--gold), transparent);
        animation: waterfall-fall linear infinite;
        will-change: transform;
    }
    @keyframes waterfall-fall {
        0% { transform: translateY(-100px); opacity: 0; }
        20% { opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translateY(1200px); opacity: 0; }
    }

    /* --- BURLAP TEXTURE ANIMATION --- */
    .burlap-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('https://images.unsplash.com/photo-1549490349-8643362247b5?auto=format&fit=crop&w=1000&q=80');
        background-size: 600px;
        opacity: 0.18; /* Diturunkan agar tidak mengganggu keterbacaan di mode terang */
        mix-blend-mode: multiply;
        pointer-events: none;
        z-index: 1;
        animation: burlap-move 60s linear infinite;
    }
    [data-bs-theme="dark"] .burlap-overlay {
        opacity: 0.6; /* Tetap kuat di mode gelap agar serat terlihat estetik */
        mix-blend-mode: soft-light;
        filter: brightness(0.6) contrast(1.3);
    }
    @keyframes burlap-move {
        0% { background-position: 0 0; }
        100% { background-position: 600px 600px; }
    }
</style>
@endsection

@section('content')
<!-- HERO SECTION -->
<div class="hero-section">
    <video class="hero-video" autoplay muted loop playsinline>
        <source src="{{ asset('assets/videos/slide1.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    <div class="particles-container"></div>
    
    <div class="hero-content">
        <h6 class="hero-fade text-uppercase mb-3" style="color: var(--gold); letter-spacing: 5px; font-weight: 600; font-size: 0.9rem;">☕ Since 2024</h6>
        <h1 class="hero-fade display-1">Selamat Datang</h1>
        <p class="hero-fade lead">Kenyamanan dalam setiap tegukan, kehangatan di setiap pertemuan.</p>
        <div class="hero-fade d-flex justify-content-center gap-3 mt-4">
            <button class="btn rounded-pill px-5 py-3 fw-bold shadow-lg" 
                    onclick="window.lenis ? window.lenis.scrollTo('#filosofi') : document.getElementById('filosofi').scrollIntoView({behavior:'smooth'})"
                    style="background: var(--gold); color: #1a1a1a; letter-spacing: 2px; border: none;">
                JELAJAHI SENJA <i class="fas fa-chevron-down ms-2"></i>
            </button>
        </div>
    </div>

    <!-- Scroll Down Indicator -->
    <div style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 10; text-align: center; animation: bounceDown 2s infinite;">
        <i class="fas fa-chevron-down" style="color: var(--gold); font-size: 1.2rem; opacity: 0.7;"></i>
    </div>
</div>

<style>
    @keyframes bounceDown {
        0%, 100% { transform: translateX(-50%) translateY(0); opacity: 0.7; }
        50% { transform: translateX(-50%) translateY(10px); opacity: 1; }
    }
</style>

<!-- TESTIMONIALS MARQUEE -->
<div class="marquee-container">
    <div class="marquee-content">
        <span class="marquee-item">"Kopi terbaik di Jakarta!" - Budi S.</span>
        <span class="marquee-item">"Suasananya sangat menenangkan." - Maya K.</span>
        <span class="marquee-item">"Aren Latte-nya juara banget!" - Andi R.</span>
        <span class="marquee-item">"Pelayanan ramah dan cepat." - Siti H.</span>
        <!-- Duplicate for seamless loop -->
        <span class="marquee-item">"Kopi terbaik di Jakarta!" - Budi S.</span>
        <span class="marquee-item">"Suasananya sangat menenangkan." - Maya K.</span>
        <span class="marquee-item">"Aren Latte-nya juara banget!" - Andi R.</span>
        <span class="marquee-item">"Pelayanan ramah dan cepat." - Siti H.</span>
    </div>
</div>

<!-- FILOSOFI KAMI -->
<section id="filosofi" class="py-5 reveal" style="background-color: var(--bg-body); color: var(--text-main); position: relative; padding-top: 100px !important; z-index: 5;">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
                <h6 class="text-uppercase tracking-widest mb-3" style="color: var(--gold); letter-spacing: 3px; font-weight: 700;">Filosofi Kami</h6>
                <h2 class="display-4 fw-bold text-primary mb-4" style="font-family: 'Playfair Display', serif; line-height: 1.2;">
                    Kisah Di Balik<br>Setiap Cangkir
                </h2>
            </div>
            <div class="col-lg-6">
                <p class="lead text-muted" style="line-height: 1.8; font-size: 1.1rem; border-left: 3px solid var(--gold); padding-left: 20px;">
                    Berawal dari kecintaan pada aroma yang membangkitkan memori, <strong>Senja Coffee</strong> hadir bukan sekadar sebagai kedai, melainkan <em>ruang temu</em>. Kami memadukan seni meracik kopi dengan suasana hangat yang dirancang khusus untuk memberi Anda ketenangan di tengah hiruk pikuk kota. Kami percaya, kopi terbaik selalu diiringi oleh obrolan yang tulus.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- SIGNATURE EXPERIENCE (NEW) -->
<section class="py-5 reveal" style="background-color: var(--bs-light); color: var(--text-main); position: relative; z-index: 5;">
    <!-- Waterfall Background Effect -->
    <div class="waterfall-container"></div>
    <!-- Abstract subtle background element -->
    <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(197, 160, 89, 0.1) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
    
    <div class="container py-5" style="position: relative; z-index: 1;">
        <div class="text-center mb-5 pb-3">
            <h6 class="text-uppercase mb-2" style="color: var(--gold); letter-spacing: 2px;">Why Senja?</h6>
            <h2 class="display-5 fw-bold text-primary" style="font-family: 'Playfair Display', serif;">The Signature Experience</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="experience-card">
                    <div class="experience-icon"><i class="fas fa-fire-burner"></i></div>
                    <h4 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--text-main);">Artisan Roasting</h4>
                    <p class="text-muted small" style="line-height: 1.7;">
                        Setiap biji kopi dipanggang (*roasting*) di fasilitas mandiri kami dengan profil kematangan presisi, memastikan karakter unik setiap varietas terekstraksi sempurna.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="experience-card">
                    <div class="experience-icon"><i class="fas fa-mug-hot"></i></div>
                    <h4 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--text-main);">Manual Brew Mastery</h4>
                    <p class="text-muted small" style="line-height: 1.7;">
                        Di tangan barista berpengalaman kami, metode seduh manual seperti V60 dan Chemex diubah menjadi pertunjukan seni yang memanjakan mata sekaligus lidah Anda.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mx-auto">
                <div class="experience-card">
                    <div class="experience-icon"><i class="fas fa-couch"></i></div>
                    <h4 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--text-main);">Premium Ambience</h4>
                    <p class="text-muted small" style="line-height: 1.7;">
                        Desain interior elegan yang didominasi elemen kayu dan cahaya hangat menciptakan suasana rileks, cocok untuk *me-time*, bekerja, atau sekadar berbincang.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BEANS SHOWCASE -->
<section id="beans-showcase" class="py-5 reveal" style="background-color: var(--bg-body); color: var(--text-main); position: relative; overflow: hidden; padding-bottom: 100px !important; z-index: 5;">
    <div class="burlap-overlay"></div>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-uppercase mb-2" style="color: var(--gold); letter-spacing: 2px;">Koleksi Kami</h6>
            <h3 class="display-5 fw-bold text-primary" style="font-family: 'Playfair Display', serif;">Our Premium Beans</h3>
            <p class="text-muted mx-auto mt-3" style="max-width: 600px;">
                Jelajahi mahakarya alam nusantara dan mancanegara yang siap menemani senja Anda.
            </p>
        </div>

        <div class="row g-4 justify-content-center" id="beans-container">
            @foreach($beans as $bean)
                <div class="col-md-6 col-lg-3">
                    <div class="bean-card h-100">
                        <div class="bean-img-wrapper">
                            <img src="{{ asset($bean['image_url']) }}" alt="{{ $bean['name'] }}" loading="lazy" decoding="async" onerror="this.src='{{ asset('assets/default_bean.jpg') }}'">
                            <span class="bean-roast-badge">{{ $bean['roast_level'] }}</span>
                        </div>
                        <div class="p-4 text-center">
                            <h5 class="fw-bold mb-1" style="color:var(--text-main);font-family:'Playfair Display',serif;">{{ $bean['name'] }}</h5>
                            <p class="small text-muted mb-3"><i class="fas fa-map-marker-alt me-1" style="color:var(--gold);"></i>{{ $bean['origin'] }}</p>
                            <p class="small mb-0" style="color:var(--text-main);opacity:0.8; font-style: italic;">
                                "{{ $bean['notes'] }}"
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('service') }}" class="btn rounded-pill px-5 py-3 fw-bold shadow-lg" 
               style="background: var(--gold); color: #1a1a1a; letter-spacing: 1px; border: none;">
                LIHAT MENU LENGKAP KAMI
            </a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Init Particles for Hero Section
    function createParticles() {
        const container = document.querySelector('.particles-container');
        if(!container) return;
        const icons = ['fa-coffee', 'fa-mug-hot', 'fa-seedling', 'fa-star'];
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('i');
            const iconClass = icons[Math.floor(Math.random() * icons.length)];
            particle.className = `fas ${iconClass} particle`;
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.fontSize = (Math.random() * 15 + 10) + 'px';
            container.appendChild(particle);
        }
    }

    // Init Waterfall for Experience Section
    function createWaterfall() {
        const container = document.querySelector('.waterfall-container');
        if(!container) return;
        for (let i = 0; i < 30; i++) {
            const streak = document.createElement('div');
            streak.className = 'water-streak';
            streak.style.left = Math.random() * 100 + 'vw';
            streak.style.animationDuration = (Math.random() * 3 + 2) + 's';
            streak.style.animationDelay = Math.random() * 5 + 's';
            container.appendChild(streak);
        }
    }

    // Hero video simple play
    window.addEventListener('load', () => {
        createParticles();
        createWaterfall();
        const heroVideo = document.querySelector('.hero-video');
        if (heroVideo) {
            heroVideo.play().catch(()=>{});
        }
    });
</script>
@endsection
