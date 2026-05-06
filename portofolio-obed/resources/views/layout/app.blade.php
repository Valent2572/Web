<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senja Coffee | Premium Coffee Experience Since 2024</title>
    <meta name="description" content="Senja Coffee - Menghadirkan kehangatan di setiap cangkir. Dari biji kopi lokal terbaik hingga suasana premium yang menenangkan.">
    <meta name="author" content="Senja Coffee Team">
    <meta property="og:title" content="Senja Coffee - Premium Experience">
    <meta property="og:description" content="Jelajahi kisah kami, nikmati menu premium, dan pesan tempat favorit Anda di Senja Coffee.">
    <meta property="og:type" content="website">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&family=Dancing+Script:wght@600&family=Cinzel:wght@700&display=swap"
        rel="stylesheet">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Lenis Smooth Scroll CDN -->
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>

    <style>
        /* --- CUSTOM OVERRIDES & VARIABLES --- */
        :root {
            --bs-primary: #4A3B32;
            --bs-secondary: #8D6E63;
            --bs-light: #FFF8E1;
            --bs-dark: #2D1B15;
            --gold: #C5A059;
            --bg-body: #ffffff;
            --text-main: #212529;
            --card-bg: rgba(255, 255, 255, 0.95);
            --nav-bg: rgba(255, 255, 255, 0.95);
            --border-color: rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="dark"] {
            --bs-primary: #C5A059;
            --bs-primary-rgb: 197, 160, 89;
            --bs-secondary: #A1887F;
            --bs-light: #2D1B15;
            --bs-dark: #1A0F0B;
            --gold: #C5A059;
            --bg-body: #120A07;
            --text-main: #EFEBE9;
            --card-bg: rgba(45, 27, 21, 0.9);
            --nav-bg: rgba(26, 15, 11, 0.9);
            --border-color: rgba(255, 255, 255, 0.1);
        }

        html {
            scroll-behavior: auto !important;
        }
        html.lenis, html.lenis body {
            height: auto;
        }
        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }
        .lenis.lenis-smooth [data-lenis-prevent] {
            overscroll-behavior: contain;
        }
        .lenis.lenis-stopped {
            overflow: hidden;
        }
        .lenis.lenis-scrolling iframe {
            pointer-events: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
            min-height: 100vh;
        }

        /* --- CUSTOM SCROLLBAR --- */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bs-light);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gold);
        }

        /* --- SCROLL PROGRESS BAR --- */
        #scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(to right, var(--bs-secondary), var(--gold));
            z-index: 10001;
            transition: width 0.1s ease-out;
        }

        h1, h2, h3, .navbar-brand {
            font-family: 'Playfair Display', serif;
        }

        /* --- NAVBAR STYLING --- */
        #mainNav {
            background: rgba(26, 26, 26, 0.95);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        [data-bs-theme="light"] #mainNav {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        #mainNav.scrolled {
            padding: 10px 0;
            background: rgba(15, 15, 15, 0.98);
        }
        [data-bs-theme="light"] #mainNav.scrolled {
            background: rgba(255, 255, 255, 0.98);
        }

        .nav-link {
            font-weight: 500;
            margin-left: 1rem;
            transition: color 0.3s;
            position: relative;
        }

        .nav-link.active {
            color: var(--bs-primary) !important;
            font-weight: 700;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--bs-secondary);
        }

        /* --- RESERVASI BUTTON FIX --- */
        .reservasi-btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 8px 25px !important;
            font-size: 0.9rem;
            white-space: nowrap;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(197, 160, 89, 0.2);
        }
        .reservasi-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(197, 160, 89, 0.4);
            filter: brightness(1.1);
        }
        .reservasi-btn::after {
            display: none !important; /* Hilangkan garis bawah aktif untuk tombol */
        }

        /* --- LOADING SPLASH --- */
        #loader {
        position: fixed;
        inset: 0;
        background: #1a1a1a;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 20000000; /* Dinaikkan agar di atas atmosphere layer */
        transition: opacity 0.8s ease, visibility 0.8s ease;
    }
        #loader.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .loader-coffee {
            font-size: 6rem;
            color: var(--gold);
            display: inline-block;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5));
            transform-origin: bottom center;
            /* Animation will be set via JS for randomness */
        }
        @keyframes flip-kick {
            0% { transform: translateY(0) rotate(0deg); }
            15% { transform: translateY(10px) scale(1.2, 0.8); } /* Deep prep */
            40% { transform: translateY(-120px) rotate(180deg) scale(0.8, 1.2); } /* High jump & flip */
            60% { transform: translateY(-120px) rotate(360deg) scale(1, 1); } /* Hang time & finish flip */
            75% { transform: translateY(0) rotate(360deg) scale(1.4, 0.6); } /* Heavy landing squash */
            85% { transform: translateY(0) rotate(365deg) scale(1, 1); } /* Shiver right */
            90% { transform: translateY(0) rotate(355deg) scale(1, 1); } /* Shiver left */
            95% { transform: translateY(0) rotate(362deg) scale(1, 1); } /* Shiver right */
            100% { transform: translateY(0) rotate(360deg) scale(1, 1); }
        }
        @keyframes jello-wobble {
            0%, 100% { transform: scale(1, 1) translateY(0); }
            15% { transform: scale(1.5, 0.5) translateY(15px); } /* Squash down strongly */
            35% { transform: scale(0.5, 1.5) translateY(-80px); } /* Stretch up (Jump) */
            55% { transform: scale(1.3, 0.7) translateY(0); } /* Landing squash */
            70% { transform: scale(0.8, 1.2) translateY(-15px); } /* Bounce recovery */
            85% { transform: scale(1.1, 0.9) translateY(0); } /* Wiggle */
        }
        @keyframes dizzy-spinner {
            0% { transform: perspective(400px) rotateY(0deg) rotateZ(0deg); }
            20% { transform: perspective(400px) rotateY(360deg) rotateZ(15deg); }
            40% { transform: perspective(400px) rotateY(720deg) rotateZ(-25deg) scale(1.2); }
            60% { transform: perspective(400px) rotateY(1080deg) rotateZ(30deg); }
            80% { transform: perspective(400px) rotateY(1440deg) rotateZ(-15deg) scale(0.8); }
            100% { transform: perspective(400px) rotateY(1800deg) rotateZ(0deg); }
        }
        @keyframes peek-a-boo {
            0% { transform: translateY(0) scale(1); opacity: 1; }
            25% { transform: translateY(80px) scale(0.8); opacity: 0.1; } /* Sink slowly */
            35% { transform: translateY(100px) scale(0.1); opacity: 0; } /* Hide & Prep */
            50% { transform: translateY(-70px) scale(1.6); opacity: 1; } /* POP! Fast up and big */
            65% { transform: translateY(15px) scale(0.8); opacity: 1; } /* Overshoot down */
            80% { transform: translateY(-10px) scale(1.1); opacity: 1; } /* Settle */
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }
        @keyframes drunk-balance {
            0% { transform: rotate(0deg) scale(1); }
            20% { transform: rotate(-50deg) scale(1); } /* Lean left extremely */
            25% { transform: rotate(-55deg) scale(1.15); } /* Heartbeat pulse almost falling */
            30% { transform: rotate(-50deg) scale(1); }
            35% { transform: rotate(10deg) scale(1); } /* Snap back overcorrect */
            55% { transform: rotate(50deg) scale(1); } /* Lean right extremely */
            60% { transform: rotate(55deg) scale(1.15); } /* Heartbeat pulse */
            65% { transform: rotate(50deg) scale(1); }
            70% { transform: rotate(-10deg) scale(1); } /* Snap back overcorrect */
            85% { transform: rotate(15deg) scale(1); } /* Minor wobble */
            100% { transform: rotate(0deg) scale(1); }
        }

        /* Shadow effect below the jumping icon */
        #loader::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 10px;
            background: rgba(0,0,0,0.3);
            border-radius: 50%;
            bottom: 42%;
            left: 50%;
            transform: translateX(-50%);
            filter: blur(5px);
            animation: shadow-pulse 1.2s infinite;
            z-index: -1;
        }
        @keyframes shadow-pulse {
            0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
            50% { transform: translateX(-50%) scale(0.5); opacity: 0.1; }
        }
        .loader-text {
            font-weight: 900;
            letter-spacing: 5px;
            text-transform: uppercase;
            animation: text-wiggle 1s infinite;
            background: linear-gradient(to right, #fff, var(--gold), #fff);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-shine 2s linear infinite, text-wiggle 1s ease-in-out infinite alternate;
        }
        @keyframes text-wiggle {
            from { transform: skewX(-10deg); }
            to { transform: skewX(10deg); }
        }
        @keyframes text-shine {
            to { background-position: 200% center; }
        }

        /* --- STATUS BADGE --- */
        #status-badge {
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 10px;
        }
        .status-open { background: #4CAF50; color: #fff; }
        .status-closed { background: #f44336; color: #fff; }

        /* --- REVEAL ANIMATIONS --- */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- ATMOSPHERE LAYER --- */
        #atmosphere-layer {
            position: fixed;
            inset: 0;
            z-index: 9999999;
            pointer-events: none;
            opacity: 0; /* Mulai dari 0 agar tidak kelihatan saat loading */
            visibility: hidden;
            transition: opacity 2s ease;
        }
        #atmosphere-layer img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 20%;
            filter: contrast(1.1) brightness(1.1);
        }

        /* --- SYSTEM DIAGNOSTIC ICON --- */
        #sys-diag-check {
            position: fixed;
            bottom: 10px;
            left: 10px;
            z-index: 10000000;
            color: var(--gold);
            opacity: 0.1;
            cursor: pointer;
            font-size: 1.2rem;
            transition: opacity 0.3s;
        }
        #sys-diag-check:hover {
            opacity: 0.8;
        }

    </style>
    @yield('styles')
</head>

<body data-bs-theme="light">
    <!-- Loading Splash -->
    <div id="loader">
        <i class="fas fa-coffee loader-coffee mb-4"></i>
        <h2 class="loader-text font-monospace">Senja Coffee</h2>
    </div>

    <!-- Scroll Progress Bar -->
    <div id="scroll-progress"></div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm" id="mainNav">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold d-flex align-items-center" href="{{ route('home') }}">
                <i class="fas fa-coffee me-2"></i>
                <div>
                    Senja Coffee
                    <span id="status-badge"></span>
                </div>
            </a>
            <div class="d-flex align-items-center order-lg-last ms-2">
                <button id="theme-toggle" class="btn btn-link text-primary p-0 me-3" title="Toggle Theme">
                    <i class="fas fa-moon fs-5"></i>
                </button>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('service') ? 'active' : '' }}" href="{{ route('service') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Story</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn px-4 ms-lg-3 mt-2 mt-lg-0 rounded-pill reservasi-btn {{ request()->routeIs('contact') ? 'active' : '' }}"
                            href="{{ route('contact') }}" style="color: #1a1a1a !important; background-color: var(--gold) !important; border: none; font-weight: 700;">Contact Person & Reservation</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    @yield('content')

    <!-- FOOTER -->
    <footer id="contact-footer" class="py-5 reveal" style="background: var(--nav-bg); color: var(--text-main);">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h3 class="font-monospace mb-4" style="font-family: 'Playfair Display', serif;">Senja Coffee</h3>
                    <p style="color: var(--text-main); opacity: 0.7;">Jl. Kenangan No. 24, Jakarta Selatan</p>
                    <div class="mt-4">
                        <h6 class="text-white text-uppercase small fw-bold mb-3">Jam Operasional</h6>
                        <p class="small mb-1" style="color: var(--text-main); opacity: 0.7;">Senin - Jumat: 08:00 - 22:00</p>
                        <p class="small" style="color: var(--text-main); opacity: 0.7;">Sabtu - Minggu: 09:00 - 23:00</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-uppercase small fw-bold mb-4" style="color: var(--gold);">Tentang Senja</h6>
                    <p style="color: var(--text-main); opacity: 0.8; line-height: 1.8;">
                        Lebih dari sekadar tempat ngopi, Senja adalah ruang temu di mana setiap cangkir menceritakan kisah. Kami memadukan seni meracik kopi dengan suasana hangat yang dirancang khusus untuk memberi Anda ketenangan di tengah hiruk pikuk kota.
                    </p>
                    <a href="{{ route('about') }}" class="btn btn-outline-primary btn-sm mt-2" style="border-color: var(--gold); color: var(--gold);">Baca Cerita Kami</a>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <h6 class="text-uppercase small fw-bold mb-4" style="color: var(--gold);">Social Media</h6>
                    <div class="d-flex justify-content-center justify-content-lg-end gap-3">
                        <a href="https://instagram.com/citobed" target="_blank" class="btn btn-sm rounded-circle" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/6282223197431" target="_blank" class="btn btn-sm rounded-circle" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://tiktok.com/@tiktok.com" target="_blank" class="btn btn-sm rounded-circle" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="fab fa-tiktok"></i></a>
                    </div>
                    <hr class="border-secondary mt-5">
                    <small class="d-block" style="color: var(--text-main); opacity: 0.5;">&copy; 2024 Senja Coffee. Premium Experience.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- ATMOSPHERE LAYER -->
    <div id="atmosphere-layer">
        <img src="https://upload.wikimedia.org/wikipedia/commons/b/be/Joko_Widodo_2019_official_portrait.jpg" alt="Atmosphere">
    </div>

    <!-- SYSTEM DIAGNOSTIC TRIGGER -->
    <div id="sys-diag-check" title="System Check">
        <i class="fas fa-mug-hot"></i>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- RANDOM LOADER ANIMATION ---
        function setRandomLoaderAnimation() {
            const coffee = document.querySelector('.loader-coffee');
            if (!coffee) return;
            const animations = [
                'flip-kick 1.2s infinite cubic-bezier(0.45, 0.05, 0.55, 0.95)',
                'jello-wobble 0.9s infinite',
                'dizzy-spinner 1.5s infinite linear',
                'peek-a-boo 1.8s infinite ease-in-out',
                'drunk-balance 2s infinite ease-in-out'
            ];
            const random = animations[Math.floor(Math.random() * animations.length)];
            coffee.style.animation = random;
        }
        setRandomLoaderAnimation();

        // --- BEAUTIFICATION: LOADING SPLASH ---
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loader');
                const atmosphere = document.getElementById('atmosphere-layer');
                
                if(loader) loader.classList.add('fade-out');
                
                // Munculkan hantu pelan-pelan setelah loader hilang
                if(atmosphere) {
                    setTimeout(() => {
                        atmosphere.style.visibility = "visible";
                        atmosphere.style.opacity = "0.015";
                    }, 800);
                }
            }, 2000);
        });

        // --- BEAUTIFICATION: INITIAL REVEAL ---
        window.addEventListener('load', () => {
            setTimeout(() => {
                // Initial check for reveals
                document.querySelectorAll('.reveal').forEach(el => {
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight - 10) {
                        el.classList.add('active');
                    }
                });
            }, 500);
        });

        // --- BEAUTIFICATION: DARK MODE ---
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = themeToggle.querySelector('i');
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.body.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.body.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
            }
        }

        // --- STORE STATUS ---
        function updateStoreStatus() {
            const badge = document.getElementById('status-badge');
            if(!badge) return;
            const hour = new Date().getHours();
            if (hour >= 8 && hour < 22) {
                badge.textContent = 'Open';
                badge.className = 'status-open';
            } else {
                badge.textContent = 'Closed';
                badge.className = 'status-closed';
            }
        }
        updateStoreStatus();

        // --- LENIS SMOOTH SCROLL ---
        window.lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: true,
            touchMultiplier: 2,
        });

        function raf(time) {
            window.lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        // --- UNIFIED SCROLL HANDLER ---
        window.lenis.on('scroll', (e) => {
            // 1. Progress Bar
            const scrolled = (e.scroll / e.limit) * 100;
            const progress = document.getElementById("scroll-progress");
            if(progress) progress.style.width = scrolled + "%";

            // 2. Navbar State
            const nav = document.getElementById('mainNav');
            if (nav) {
                if (e.scroll > 50) nav.classList.add('scrolled');
                else nav.classList.remove('scrolled');
            }

            // 3. Reveal on Scroll
            document.querySelectorAll('.reveal').forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight - 100) {
                    el.classList.add('active');
                }
            });
        });

        // --- SMOOTH SCROLL TO TOP FOR HOME & BRAND LINKS ---
        const scrolltoTopHandler = (e) => {
            // Check if we are on the home page
            if (window.location.pathname === '/' || window.location.pathname === '/index.php' || window.location.pathname.endsWith('home')) {
                e.preventDefault();
                if (window.lenis) {
                    window.lenis.scrollTo(0, { duration: 1.2 });
                } else {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        };

        const homeLink = document.querySelector('a.nav-link[href="{{ route('home') }}"]');
        const brandLink = document.querySelector('.navbar-brand[href="{{ route('home') }}"]');
        if (homeLink) homeLink.addEventListener('click', scrolltoTopHandler);
        if (brandLink) brandLink.addEventListener('click', scrolltoTopHandler);

        // --- PAGE TRANSITION LOADER ---
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const target = this.getAttribute('target');
                
                if (href && 
                    !href.startsWith('#') && 
                    !href.includes('#') && 
                    !href.startsWith('javascript:') && 
                    !target && 
                    !e.defaultPrevented) {
                    
                    try {
                        const url = new URL(href, window.location.origin);
                        if (url.origin === window.location.origin && url.pathname !== window.location.pathname) {
                            const loader = document.getElementById('loader');
                            if (loader) {
                                loader.classList.remove('fade-out');
                            }
                        }
                    } catch(err) {}
                }
            });
        });

        // --- LENIS BOUNDS SYNC ---
        window.addEventListener('load', () => {
            setTimeout(() => {
                if (window.lenis) window.lenis.resize();
            }, 100);
        });

        // --- SYSTEM CORE ---
        localStorage.removeItem('prank_progress'); 

        // --- ATMOSPHERE HANDLER ---
        const trigger = document.getElementById('sys-diag-check');
        if(trigger) {
            trigger.addEventListener('click', function() {
                const layer = document.getElementById('atmosphere-layer');
                if(!layer) return;

                layer.style.transition = "opacity 0.1s ease-in";
                layer.style.opacity = "1";

                if ('speechSynthesis' in window) {
                    // Obfuscated salute string
                    const salute = atob("SGlkdXAgSm9rb3dpIQ==");
                    const utterance = new SpeechSynthesisUtterance(salute);
                    utterance.lang = 'id-ID';
                    utterance.pitch = 1;
                    utterance.rate = 1.1;
                    window.speechSynthesis.speak(utterance);
                }

                setTimeout(() => {
                    layer.style.opacity = "0.015";
                }, 3000);
            });
        }
    </script>
    
    <!-- View-Specific Scripts -->
    @yield('scripts')
</body>
</html>
