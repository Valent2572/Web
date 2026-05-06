<?php
// Centralized DB connection
require_once __DIR__ . '/db.php';

// 2. Fetch Menus & Group by Category
$menu_query = "SELECT * FROM menus";
$menu_result = mysqli_query($conn, $menu_query);
$menus = [];
while($row = mysqli_fetch_assoc($menu_result)) {
    $menus[$row['category']][] = $row;
}

// 3. Fetch Contacts
$contact_query = "SELECT * FROM contacts";
$contact_result = mysqli_query($conn, $contact_query);
$contacts = [];
while($row = mysqli_fetch_assoc($contact_result)) {
    $contacts[$row['contact_type']] = $row; 
}

// 4. Fetch Chart Data
$chart_query = "SELECT * FROM favorite_coffee";
$chart_result = mysqli_query($conn, $chart_query);
$chart_labels = [];
$chart_data = [];
while($row = mysqli_fetch_assoc($chart_result)) {
    $chart_labels[] = $row['coffee_name'];
    $chart_data[] = $row['sold_count'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senja Coffee - JS Features Edition</title>

    <!-- 1. Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- 2. FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&family=Dancing+Script:wght@600&family=Cinzel:wght@700&display=swap"
        rel="stylesheet">

    <!-- 3. Chart.js CDN (Fitur Baru) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 4. Lenis Smooth Scroll CDN -->
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

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
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

        /* --- BEANS SHOWCASE --- */
        .bean-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        .bean-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
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



        /* --- PARTICLES --- */
        .particles-container {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }
        .particle {
            position: absolute;
            color: rgba(197, 160, 89, 0.2);
            font-size: 20px;
            animation: float 15s infinite linear;
            will-change: transform;
            backface-visibility: hidden;
        }
        /* Completely hide the container when paused to save CPU/GPU */
        .particles-container.paused {
            display: none;
        }
        @keyframes float {
            0% { transform: translate3d(0, 100vh, 0) rotate(0deg); opacity: 0; }
            10% { opacity: 0.5; }
            90% { opacity: 0.5; }
            100% { transform: translate3d(20px, -100px, 0) rotate(360deg); opacity: 0; }
        }

        /* Smooth Scroll Global (Fallback) */
        html {
            scroll-behavior: auto;
            /* Dinonaktifkan karena Lenis mengambil alih smooth scroll */
        }

        h1,
        h2,
        h3,
        .navbar-brand {
            font-family: 'Playfair Display', serif;
        }

        /* --- NAVBAR STYLING --- */
        #mainNav {
            background: rgba(26, 26, 26, 0.95);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        /* Light mode adjustment */
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

        /* Indikator Active Menu */
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

        /* --- HORIZONTAL SCROLL SECTION --- */
        .horizontal-scroll-wrapper {
            height: 250vh;
            position: relative;
        }

        .sticky-viewport {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;
            background-color: #000;
        }

        .horizontal-track {
            display: flex;
            height: 100%;
            width: 300vw;
            will-change: transform;
            transition: transform 0.65s cubic-bezier(0.77, 0, 0.175, 1);
        }

        .h-slide {
            width: 100vw;
            height: 100vh;
            flex-shrink: 0;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .display-1 {
            background: linear-gradient(to bottom, #ffffff, var(--gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* --- LOADING SPLASH --- */
        #loader {
            position: fixed;
            inset: 0;
            background: var(--bs-dark);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 20000;
            transition: opacity 0.8s ease, visibility 0.8s;
        }
        #loader.fade-out {
            opacity: 0;
            visibility: hidden;
        }
        .loader-coffee {
            font-size: 5rem;
            color: var(--gold);
            animation: pulse-coffee 1.5s infinite;
        }
        @keyframes pulse-coffee {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }

        /* --- SLIDE DOTS --- */
        .slide-indicators {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            z-index: 10;
        }
        .dot {
            width: 12px;
            height: 12px;
            border: 2px solid #fff;
            border-radius: 50%;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .dot.active {
            background: var(--gold);
            border-color: var(--gold);
            transform: scale(1.3);
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

        .h-slide-content {
            position: relative;
            z-index: 2;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .h-slide-content h1 {
            filter: drop-shadow(1px 1px 0px rgba(0,0,0,1)) 
                    drop-shadow(-1px -1px 0px rgba(0,0,0,1))
                    drop-shadow(1px -1px 0px rgba(0,0,0,1))
                    drop-shadow(-1px 1px 0px rgba(0,0,0,1));
        }

        .h-slide-content .lead {
            color: #fff !important;
            text-shadow: 
                -1px -1px 0 #000,  
                 1px -1px 0 #000,
                -1px  1px 0 #000,
                 1px  1px 0 #000,
                 0px  2px 10px rgba(0,0,0,0.8);
        }

        .h-slide.active .h-slide-content {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- VIDEO SLIDE BACKGROUNDS --- */
        .slide-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        /* --- OPTIMIZED PARALLAX SECTION --- */
        .parallax-section {
            background-image: url('https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=1200&q=80');
            background-attachment: scroll; /* Fixed is too heavy, scroll + position center is faster */
            background-size: cover;
            background-position: center;
            min-height: 80vh; /* Reduced from 120vh to improve repaint area */
            position: relative;
            overflow: hidden;
            will-change: transform;
            transform: translateZ(0); /* Force GPU Layer */
        }

        /* --- ELEGANT MENU TABS --- */
        .text-gold {
            color: var(--gold) !important;
        }

        .menu-tab-btn {
            text-align: left;
            border-radius: 0;
            border-left: 3px solid transparent;
            color: var(--bs-primary) !important;
            font-weight: 600;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .menu-tab-btn:hover {
            background: rgba(197, 160, 89, 0.1);
            color: var(--gold);
        }

        .nav-pills .menu-tab-btn.active {
            background-color: var(--bs-primary) !important;
            color: #fff !important;
            border-left-color: var(--gold);
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .menu-tab-btn {
            color: var(--bs-primary) !important;
        }
        [data-bs-theme="dark"] .nav-pills .menu-tab-btn.active {
            background-color: var(--bs-primary) !important;
            color: var(--bs-dark) !important;
        }

        .menu-content-card {
            background: rgba(30, 30, 30, 0.85); /* Dark mode background */
            border: 1px solid var(--border-color);
            border-radius: 30px;
            padding: 40px;
            height: 100%;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        /* Light mode adjustment for menu card */
        [data-bs-theme="light"] .menu-content-card {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(0,0,0,0.1);
        }
        
        [data-bs-theme="light"] .menu-category-card {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(0,0,0,0.1) !important;
        }

        .menu-title {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
            color: var(--bs-primary);
            border-bottom: 2px solid var(--gold);
        }

        .menu-item {
            border-bottom: 1px dashed #ccc;
            cursor: pointer;
            transition: background 0.25s ease, padding-left 0.25s ease, border-color 0.25s ease;
            border-radius: 6px;
            padding-left: 0;
        }

        .menu-item:hover {
            background: rgba(197, 160, 89, 0.07);
            padding-left: 8px;
            border-bottom-color: rgba(197, 160, 89, 0.6);
        }

        .menu-item:hover h5 {
            color: var(--gold) !important;
            transition: color 0.2s ease;
        }

        .menu-item:hover span.fw-bold {
            transform: scale(1.08);
            display: inline-block;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* --- FLOATING IMAGE PREVIEW (Optimized) --- */
        #menu-img-preview {
            position: fixed;
            width: 210px;
            height: 155px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 2px var(--gold);
            will-change: transform, opacity;
            z-index: 999999;
            background: #1a1a1a;
            top: 0;
            left: 0;
            display: block;
            pointer-events: none;
            opacity: 0;
            contain: paint;
        }

        #menu-img-preview.visible {
            opacity: 1;
            transition: opacity 0.2s ease;
        }

        #menu-img-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #menu-img-preview .preview-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 6px 10px;
            background: linear-gradient(transparent, rgba(0,0,0,0.65));
            color: #fff;
            font-size: 0.7rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

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

        /* --- MARQUEE --- */
        .marquee-container {
            background: var(--nav-bg);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 0;
            overflow: hidden;
            white-space: nowrap;
        }
        .marquee-content {
            display: inline-block;
            animation: marquee 30s linear infinite;
        }
        .marquee-item {
            display: inline-block;
            color: var(--gold);
            font-size: 1.1rem;
            font-weight: 500;
            font-style: italic;
            margin-right: 100px;
            font-family: 'Playfair Display', serif;
            text-shadow: 0 1px 2px rgba(0,0,0,0.05);
            letter-spacing: 0.5px;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* --- SOUND BUTTON --- */
        #sound-toggle {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 10001;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--bs-primary);
            color: #fff;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        /* --- TOAST --- */
        #live-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 10001;
            background: var(--card-bg);
            border-left: 4px solid var(--gold);
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(150%);
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }
        #live-toast.show { transform: translateX(0); }

        /* --- MENU SEARCH --- */
        .menu-search-wrapper {
            max-width: 400px;
            margin-bottom: 30px;
        }
        .menu-search-input {
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-main);
            border-radius: 25px;
            padding: 10px 20px;
            width: 100%;
        }
    </style>
</head>

<body data-bs-theme="light">
    <!-- Loading Splash -->
    <div id="loader">
        <i class="fas fa-coffee loader-coffee mb-3"></i>
        <h2 class="text-white font-monospace">Senja Coffee...</h2>
    </div>

    <!-- Scroll Progress Bar -->
    <div id="scroll-progress"></div>


    <!-- Live Toast -->
    <div id="live-toast">
        <i class="fas fa-sync-alt text-gold spin-icon"></i>
        <span class="small fw-bold">Data diperbarui âœ“</span>
    </div>



    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm" id="mainNav">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold d-flex align-items-center" href="#home">
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
                    <!-- Class 'active' akan diatur oleh JS -->
                    <li class="nav-item"><a class="nav-link" href="#home" onclick="smoothScroll(event, 'home')">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#menu" onclick="smoothScroll(event, 'menu')">Menu</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#about"
                            onclick="smoothScroll(event, 'about')">Story</a></li>
                    <li class="nav-item"><a
                            class="nav-link btn btn-primary px-4 ms-lg-3 mt-2 mt-lg-0 rounded-pill reservasi-btn"
                            href="#contact" onclick="smoothScroll(event, 'contact')" style="color:#fff !important; background-color:var(--bs-primary) !important;">Reservasi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HOME SECTION (Horizontal Scroll) -->
    <div id="home" class="horizontal-scroll-wrapper">
        <div class="sticky-viewport">
            <div class="horizontal-track">

                <div class="h-slide d-flex justify-content-center align-items-center active">
                    <div class="particles-container"></div>
                    <video class="slide-video" autoplay muted loop playsinline>
                        <source src="assets/videos/slide1.mp4" type="video/mp4">
                    </video>
                    <div class="h-slide-content text-center text-white px-3">
                        <h1 class="display-1 fw-bold">Selamat Datang</h1>
                        <p class="lead">Kenyamanan dalam setiap tegukan.</p>
                        <div class="mt-4">
                            <small
                                class="text-uppercase tracking-widest border border-white px-3 py-1 rounded-pill">Scroll
                                Down <i class="fas fa-arrow-down ms-2"></i></small>
                        </div>
                    </div>
                </div>

                <div class="h-slide d-flex justify-content-center align-items-center">
                    <video class="slide-video" autoplay muted loop playsinline>
                        <source src="assets/videos/slide2.mp4" type="video/mp4">
                    </video>
                    <div class="h-slide-content text-center text-white px-3">
                        <h1 class="display-1 fw-bold">Biji Pilihan</h1>
                        <p class="lead">Dipetik langsung dari dataran tinggi terbaik Nusantara.</p>
                    </div>
                </div>

                <div class="h-slide d-flex justify-content-center align-items-center">
                    <video class="slide-video" autoplay muted loop playsinline>
                        <source src="assets/videos/slide3.mp4" type="video/mp4">
                    </video>
                    <div class="h-slide-content text-center text-white px-3">
                        <h1 class="display-1 fw-bold">Suasana Tenang</h1>
                        <p class="lead">Tempat pelarian terbaik dari hiruk pikuk kota.</p>
                    </div>
                </div>

                <!-- Slide Indicators -->
                <div class="slide-indicators">
                    <div class="dot active" data-index="0"></div>
                    <div class="dot" data-index="1"></div>
                    <div class="dot" data-index="2"></div>
                </div>

            </div>
        </div>
    </div>

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

    <!-- MENU SECTION (Elegant Tabbed Split-Screen) -->
    <section id="menu" class="parallax-section reveal py-5 d-flex align-items-center">
        <div class="container my-5">
            <div class="text-center mb-5 text-white position-relative" style="z-index: 2;">
                <h2 class="display-4 fw-bold mb-3"
                    style="font-family: 'Cinzel', serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">SENJA MENU</h2>
                <div class="bg-warning mx-auto"
                    style="width: 80px; height: 3px; background-color: var(--gold) !important;"></div>
            </div>

            <div class="row g-4 position-relative" style="z-index: 2;">
                <!-- Left Column: Categories -->
                <div class="col-lg-4">
                    <div class="menu-category-card rounded-4 shadow-lg overflow-hidden border border-light sticky-lg-top" 
                         style="background: rgba(30, 30, 30, 0.85); top: 100px;">
                        <div class="p-4 border-bottom text-center" style="background: rgba(0,0,0,0.03);">
                            <h4 class="font-monospace mb-0 text-primary fw-bold" style="letter-spacing: 2px;">KATEGORI
                            </h4>
                        </div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link menu-tab-btn active w-100" id="v-pills-signature-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-signature" type="button"
                                role="tab">Signature Coffee</button>
                            <button class="nav-link menu-tab-btn w-100" id="v-pills-recom-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-recom" type="button" role="tab">Top Recommendations</button>
                            <button class="nav-link menu-tab-btn w-100" id="v-pills-manual-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-manual" type="button" role="tab">Manual Brew</button>
                            <button class="nav-link menu-tab-btn w-100" id="v-pills-noncoffee-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-noncoffee" type="button" role="tab">Non-Coffee & Tea</button>
                            <button class="nav-link menu-tab-btn w-100" id="v-pills-pastry-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-pastry" type="button" role="tab">Fresh Pastries</button>
                            <button class="nav-link menu-tab-btn w-100" id="v-pills-bites-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-bites" type="button" role="tab">Bites & Meals</button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Menu Content -->
                <div class="col-lg-8">
                    <div class="menu-search-wrapper mb-4">
                        <input type="text" id="menu-search" class="menu-search-input" placeholder="Cari kopi favoritmu...">
                    </div>
                    <div class="menu-content-card p-4 p-md-5 h-100">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- Signature Tab -->
                            <div class="tab-pane fade show active" id="v-pills-signature" role="tabpanel" tabindex="0">
                                <h3 class="menu-title mb-4 pb-2">Signature Coffee</h3>
                                <div id="menu-items-signature">
                                <?php if(isset($menus['Signature'])): ?>
                                    <?php foreach($menus['Signature'] as $item): ?>
                                        <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2" 
                                             data-img="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" 
                                             data-name="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <h5 class="h6 text-primary mb-0 fw-bold">
                                                    <?php echo $item['name']; ?>
                                                    <?php if(($item['sold_count'] ?? 0) > 100): ?>
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5rem;">BEST SELLER</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <small class="text-muted fst-italic"><?php echo $item['description']; ?></small>
                                            </div>
                                            <span class="fw-bold text-primary"><?php echo $item['price']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Menu belum tersedia.</p>
                                <?php endif; ?>
                                </div>
                            </div>

                            <!-- Recommendations Tab -->
                            <div class="tab-pane fade" id="v-pills-recom" role="tabpanel" tabindex="0">
                                <h3 class="menu-title mb-4 pb-2">Top Recommendations</h3>
                                <div id="menu-items-recom">
                                <?php if(isset($menus['Recommendations'])): ?>
                                    <?php foreach($menus['Recommendations'] as $item): ?>
                                        <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2" 
                                             data-img="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" 
                                             data-name="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <h5 class="h6 text-primary mb-0 fw-bold">
                                                    <?php echo $item['name']; ?>
                                                    <?php if(($item['sold_count'] ?? 0) > 100): ?>
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5rem;">BEST SELLER</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <small class="text-muted fst-italic"><?php echo $item['description']; ?></small>
                                            </div>
                                            <span class="fw-bold text-primary"><?php echo $item['price']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Menu belum tersedia.</p>
                                <?php endif; ?>
                                </div>
                            </div>

                            <!-- Manual Brew Tab -->
                            <div class="tab-pane fade" id="v-pills-manual" role="tabpanel" tabindex="0">
                                <h3 class="menu-title mb-4 pb-2">Manual Brew</h3>
                                <div id="menu-items-manual">
                                <?php if(isset($menus['Manual Brew'])): ?>
                                    <?php foreach($menus['Manual Brew'] as $item): ?>
                                        <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2" 
                                             data-img="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" 
                                             data-name="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <h5 class="h6 text-primary mb-0 fw-bold">
                                                    <?php echo $item['name']; ?>
                                                    <?php if(($item['sold_count'] ?? 0) > 100): ?>
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5rem;">BEST SELLER</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <small class="text-muted fst-italic"><?php echo $item['description']; ?></small>
                                            </div>
                                            <span class="fw-bold text-primary"><?php echo $item['price']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Menu belum tersedia.</p>
                                <?php endif; ?>
                                </div>
                            </div>

                            <!-- Non-Coffee Tab -->
                            <div class="tab-pane fade" id="v-pills-noncoffee" role="tabpanel" tabindex="0">
                                <h3 class="menu-title mb-4 pb-2">Non-Coffee & Tea</h3>
                                <div id="menu-items-noncoffee">
                                <?php if(isset($menus['Non-Coffee'])): ?>
                                    <?php foreach($menus['Non-Coffee'] as $item): ?>
                                        <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2" 
                                             data-img="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" 
                                             data-name="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <h5 class="h6 text-primary mb-0 fw-bold">
                                                    <?php echo $item['name']; ?>
                                                    <?php if(($item['sold_count'] ?? 0) > 100): ?>
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5rem;">BEST SELLER</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <small class="text-muted fst-italic"><?php echo $item['description']; ?></small>
                                            </div>
                                            <span class="fw-bold text-primary"><?php echo $item['price']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Menu belum tersedia.</p>
                                <?php endif; ?>
                                </div>
                            </div>

                            <!-- Pastries Tab -->
                            <div class="tab-pane fade" id="v-pills-pastry" role="tabpanel" tabindex="0">
                                <h3 class="menu-title mb-4 pb-2">Fresh Pastries</h3>
                                <div id="menu-items-pastry">
                                <?php if(isset($menus['Pastries'])): ?>
                                    <?php foreach($menus['Pastries'] as $item): ?>
                                        <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2" 
                                             data-img="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" 
                                             data-name="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <h5 class="h6 text-primary mb-0 fw-bold">
                                                    <?php echo $item['name']; ?>
                                                    <?php if(($item['sold_count'] ?? 0) > 100): ?>
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5rem;">BEST SELLER</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <small class="text-muted fst-italic"><?php echo $item['description']; ?></small>
                                            </div>
                                            <span class="fw-bold text-primary"><?php echo $item['price']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Menu belum tersedia.</p>
                                <?php endif; ?>
                                </div>
                            </div>

                            <!-- Bites & Meals Tab -->
                            <div class="tab-pane fade" id="v-pills-bites" role="tabpanel" tabindex="0">
                                <h3 class="menu-title mb-4 pb-2">Bites & Meals</h3>
                                <div id="menu-items-bites">
                                <?php if(isset($menus['Bites'])): ?>
                                    <?php foreach($menus['Bites'] as $item): ?>
                                        <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2" 
                                             data-img="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" 
                                             data-name="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <h5 class="h6 text-primary mb-0 fw-bold">
                                                    <?php echo $item['name']; ?>
                                                    <?php if(($item['sold_count'] ?? 0) > 100): ?>
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5rem;">BEST SELLER</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <small class="text-muted fst-italic"><?php echo $item['description']; ?></small>
                                            </div>
                                            <span class="fw-bold text-primary"><?php echo $item['price']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Menu belum tersedia.</p>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BEANS SHOWCASE SECTION -->
    <section id="beans-showcase" class="py-5 reveal" style="background-color: var(--bg-body); color: var(--text-main); position: relative; overflow: hidden;">
        <!-- Subtle Bean Background Pattern -->
        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(197, 160, 89, 0.05) 1px, transparent 0); background-size: 30px 30px; opacity: 0.5; z-index: 0;"></div>
        
        <div class="container py-5" style="position: relative; z-index: 1;">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-primary" style="font-family: 'Playfair Display', serif;">Our Premium Beans</h2>
                <p class="lead text-muted mx-auto" style="max-width: 600px;">
                    Jelajahi koleksi biji kopi nusantara dan mancanegara pilihan kami yang di-roasting dengan standar tertinggi.
                </p>
                <div class="d-flex justify-content-center mt-3">
                    <span class="badge text-dark px-3 py-2 me-2" style="background-color: var(--gold); border-radius: 20px;"><i class="fas fa-seedling me-1"></i> Live Update</span>
                </div>
            </div>

            <!-- AJAX Container for Beans -->
            <div class="row g-4 justify-content-center" id="beans-container">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat daftar biji kopi...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION WITH CHARTJS -->
    <section id="about" class="py-5 reveal" style="background: var(--bg-body); color: var(--text-main);">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <!-- Text Column -->
                <div class="col-lg-5">
                    <h2 class="display-5 fw-bold text-primary mb-4">Statistik & Cerita</h2>
                    <p class="lead text-muted">Kami bangga dengan transparansi kualitas kami.</p>
                    <p>
                        Grafik di samping menunjukkan preferensi pelanggan kami terhadap jenis kopi yang kami sajikan
                        setiap bulannya. Espresso Robusta tetap menjadi primadona bagi penikmat kopi sejati di Jakarta
                        Selatan.
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <div class="text-center px-3 border-end">
                            <h3 class="h2 fw-bold text-secondary mb-0 counter" data-target="10">0</h3>
                            <small style="color: var(--text-main); opacity: 0.7;">Jenis Biji</small>
                        </div>
                        <div class="text-center px-3 border-end">
                            <h3 class="h2 fw-bold text-secondary mb-0 counter" data-target="5000">0</h3>
                            <small style="color: var(--text-main); opacity: 0.7;">Pelanggan</small>
                        </div>
                        <div class="text-center px-3">
                            <h3 class="h2 fw-bold text-secondary mb-0 counter" data-target="2024" data-no-plus="true">0</h3>
                            <small style="color: var(--text-main); opacity: 0.7;">Didirikan</small>
                        </div>
                    </div>

                    <!-- Contact Person Section -->
                    <div class="mt-5 p-4 rounded shadow-sm border-start border-4 border-primary" style="background-color: var(--bs-light);">
                        <h5 class="fw-bold text-primary mb-3"><i class="fas fa-headset me-2"></i>Hubungi Kami</h5>
                        
                        <!-- WhatsApp Dynamic -->
                        <?php if(isset($contacts['whatsapp'])): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 rounded-circle shadow-sm d-flex justify-content-center align-items-center me-3" 
                                 style="width: 45px; height: 45px; background: var(--bg-body); border: 1px solid var(--border-color);">
                                <i class="fab fa-whatsapp text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 id="contact-wa-name" class="mb-0 fw-bold" style="color: var(--text-main);">Admin Reservasi (Yescitito Obed)</h6>
                                <a id="contact-wa-link" href="https://wa.me/6282223197431" target="_blank" class="text-decoration-none text-muted small">+62 822-2319-7431</a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Email Dynamic -->
                        <?php if(isset($contacts['email'])): ?>
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-2 rounded-circle shadow-sm d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-envelope text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 id="contact-email-name" class="mb-0 fw-bold" style="color: var(--text-main);"><?php echo $contacts['email']['role']; ?></h6>
                                <a id="contact-email-link" href="<?php echo $contacts['email']['link_url']; ?>" class="text-decoration-none text-muted small"><?php echo $contacts['email']['display_value']; ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Chart Column (Menggantikan SVG) -->
                <div class="col-lg-7">
                    <div class="card shadow-lg border-0" style="background: var(--card-bg); color: var(--text-main);">
                        <div class="card-body p-4">
                            <h5 class="card-title text-center text-primary mb-4 fw-bold">Kopi Terfavorit Bulan Ini</h5>
                            <!-- Canvas untuk Chart.js -->
                            <canvas id="aboutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="contact" class="py-5 reveal" style="background: var(--nav-bg); color: var(--text-main);">
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
                    <h6 class="text-uppercase small fw-bold mb-4" style="color: var(--gold);">Reservasi Meja</h6>
                    <form id="reservation-form" action="Senja-Kopi/Booking.php" method="GET" class="row g-3">
                        <div class="col-12">
                            <input type="text" name="nama" class="form-control form-control-sm bg-transparent border-secondary" style="color: var(--text-main) !important;" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-6">
                            <input type="date" id="res-date" name="tanggal" class="form-control form-control-sm bg-transparent border-secondary" style="color: var(--text-main) !important;" required>
                        </div>
                        <div class="col-6">
                            <input type="time" id="res-time" name="waktu" class="form-control form-control-sm bg-transparent border-secondary" style="color: var(--text-main) !important;" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" id="submit-booking" class="btn btn-primary btn-sm w-100 text-white">Book Table</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <h6 class="text-uppercase small fw-bold mb-4" style="color: var(--gold);">Social Media</h6>
                    <div class="d-flex justify-content-center justify-content-lg-end gap-3">
                        <a href="https://instagram.com/citobed" target="_blank" class="btn btn-sm rounded-circle" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/6282223197431" target="_blank" class="btn btn-sm rounded-circle" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://tiktok.com/@citobed" target="_blank" class="btn btn-sm rounded-circle" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="fab fa-tiktok"></i></a>
                    </div>
                    <hr class="border-secondary mt-5">
                    <small class="d-block" style="color: var(--text-main); opacity: 0.5;">&copy; 2024 Senja Coffee. Premium Experience.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Menu Image Preview Card -->
    <div id="menu-img-preview">
        <img src="" alt="Menu Preview">
        <div class="preview-label"></div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- 0. RESERVATION VALIDATION (H-1 & Operating Hours) ---
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('res-date');
            const timeInput = document.getElementById('res-time');
            const form = document.getElementById('reservation-form');

            // Set min date to tomorrow (H-1)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const yyyy = tomorrow.getFullYear();
            const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const dd = String(tomorrow.getDate()).padStart(2, '0');
            dateInput.min = `${yyyy}-${mm}-${dd}`;

            form.addEventListener('submit', (e) => {
                const dateVal = new Date(dateInput.value);
                const timeVal = timeInput.value; // "HH:MM"
                const day = dateVal.getDay(); // 0: Sunday, 1-5: Mon-Fri, 6: Sat
                const [hh, mm] = timeVal.split(':').map(Number);
                const timeNum = hh + mm/60;

                let minH = 8, maxH = 22;
                let dayName = "Hari Kerja";
                if(day === 0 || day === 6) {
                    minH = 9; maxH = 23;
                    dayName = "Akhir Pekan (Sabtu-Minggu)";
                }

                if(timeNum < minH || timeNum >= maxH) {
                    e.preventDefault();
                    alert(`Maaf, pada ${dayName} jam operasional kami adalah ${String(minH).padStart(2,'0')}:00 - ${maxH}:00.`);
                }
            });
        });

        // --- 1. HORIZONTAL SCROLL LOGIC (EXISTING) ---
        const stickyParent = document.querySelector('.horizontal-scroll-wrapper');
        const horizontalTrack = document.querySelector('.horizontal-track');
        const slides = document.querySelectorAll('.h-slide');

        function transformScroll() {
            const scrollY = window.scrollY;
            const parentTop = stickyParent.offsetTop;
            const scrollDistance = stickyParent.offsetHeight - window.innerHeight;
            const numSlides = slides.length; // 3
            const viewportWidth = window.innerWidth;

            let slideIndex = 0;

            if (scrollY >= parentTop && scrollY <= (parentTop + scrollDistance)) {
                const scrolled = scrollY - parentTop;
                const percentage = scrolled / scrollDistance;

                // Snap: round percentage to nearest slide index
                slideIndex = Math.round(percentage * (numSlides - 1));
            } else if (scrollY < parentTop) {
                slideIndex = 0;
            } else {
                slideIndex = numSlides - 1;
            }

            // Clamp just in case
            slideIndex = Math.max(0, Math.min(numSlides - 1, slideIndex));

            // Snap translateX to exact slide position (CSS transition handles smooth animation)
            const snapX = slideIndex * viewportWidth;
            horizontalTrack.style.transform = `translateX(-${snapX}px)`;

            // Update active class on slides
            slides.forEach((slide, index) => {
                if (index === slideIndex) slide.classList.add('active');
                else slide.classList.remove('active');
            });

            // Update Slide Dots
            document.querySelectorAll('.dot').forEach((dot, idx) => {
                if (idx === slideIndex) dot.classList.add('active');
                else dot.classList.remove('active');
            });

            // Typewriter trigger
            const activeSlide = slides[slideIndex];
            const leadText = activeSlide.querySelector('.lead');
            if (!leadText.dataset.typed) {
                typeWriter(leadText);
            }

            // updateActiveNav is called by the scroll listener separately
        }

        function typeWriter(element) {
            const text = element.textContent;
            element.textContent = '';
            element.dataset.typed = 'true';
            let i = 0;
            function type() {
                if (i < text.length) {
                    element.textContent += text.charAt(i);
                    i++;
                    setTimeout(type, 50);
                }
            }
            type();
        }

        // Throttled Scroll Listeners
        let scrollScheduled = false;
        window.addEventListener('scroll', () => {
            if (!scrollScheduled) {
                requestAnimationFrame(() => {
                    transformScroll();
                    updateActiveNav();
                    scrollScheduled = false;
                });
                scrollScheduled = true;
            }
        });
        window.addEventListener('resize', transformScroll);

        // --- 3. FITUR JS BARU: ACTIVE MENU HANDLER ---
        function updateActiveNav() {
            const scrollY = window.scrollY;

            const homeSection    = document.getElementById('home');
            const menuSection    = document.getElementById('menu');
            const aboutSection   = document.getElementById('about');
            const contactSection = document.getElementById('contact');

            let current = '';

            if (scrollY < homeSection.offsetTop + homeSection.offsetHeight - 200) {
                current = 'home';
            } else if (scrollY >= menuSection.offsetTop - 200 && scrollY < aboutSection.offsetTop - 200) {
                current = 'menu';
            } else if (scrollY >= aboutSection.offsetTop - 200 && scrollY < contactSection.offsetTop - 200) {
                current = 'about';
            } else if (scrollY >= contactSection.offsetTop - 400) {
                current = 'contact';
            }

            // Only apply active to plain nav-links (NOT the Reservasi pill button)
            document.querySelectorAll('#navbarNav .nav-link:not(.reservasi-btn)').forEach(link => {
                link.classList.remove('active');
                // Exact match: href must equal '#section'
                if (current && link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        }

        // --- 4. FITUR JS BARU: SMOOTH AUTOSCROLL ---
        function smoothScroll(event, targetId) {
            event.preventDefault();
            const targetElement = document.getElementById(targetId);

            // Jika Lenis aktif, gunakan Lenis untuk scroll
            if (window.lenis) {
                if (targetId === 'home') {
                    window.lenis.scrollTo(0);
                } else {
                    window.lenis.scrollTo(targetElement);
                }
            } else {
                // Fallback
                if (targetId === 'home') {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }

        // --- 5. FITUR JS BARU: CHART.JS INTEGRATION (WITH LIVE AUTO-REFRESH) ---
        let aboutChart; // We need this variable outside so our loop can talk to it

        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('aboutChart').getContext('2d');

            // Data Awal saat halaman pertama kali dibuka
            const coffeeData = {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Terjual (Gelas)',
                    data: <?php echo json_encode($chart_data); ?>, 
                    backgroundColor: [
                        '#4A3B32', '#8D6E63', '#C5A059', '#2D1B15', '#A89F91'  
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: coffeeData,
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                        x: { grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            };

            // Render Chart Pertama Kali
            aboutChart = new Chart(ctx, config);

            // === THE MAGIC: AUTO REFRESH EVERY 30 SECONDS ===
            setInterval(function() {
                // Gunakan Fetch API (Modern AJAX)
                fetch('api_chart.php')
                    .then(response => response.json())
                    .then(newData => {
                        // Timpa data lama dengan data baru dari database
                        aboutChart.data.datasets[0].data = newData;
                        // Perintahkan Chart.js untuk menggambar ulang grafiknya (animasi smooth)
                        aboutChart.update();
                    })
                    .catch(error => console.error('Gagal mengambil data live:', error));
            }, 30000); // 30 detik
        });

        // --- 6. LIVE AUTO-REFRESH: MENUS ---
        // Map of tab container ID -> category key in api_menu.php response
        const menuTabMap = {
            'menu-items-signature':  'Signature',
            'menu-items-recom':      'Recommendations',
            'menu-items-manual':     'Manual Brew',
            'menu-items-noncoffee':  'Non-Coffee',
            'menu-items-pastry':     'Pastries',
            'menu-items-bites':      'Bites'
        };

        function renderMenuItems(items) {
            if (!items || items.length === 0) {
                return '<p class="text-muted">Menu belum tersedia.</p>';
            }
            return items.map(item => `
                <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2"
                     data-img="${item.image_url || ''}" data-name="${item.name}">
                    <div>
                        <h5 class="h6 text-primary mb-0 fw-bold">
                            ${item.name}
                        </h5>
                        <small class="text-muted fst-italic">${item.description}</small>
                    </div>
                    <span class="fw-bold text-primary">${item.price}</span>
                </div>
            `).join('');
        }
        // --- 8. BEAUTIFICATION: LOADING SPLASH ---
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loader');
                if(loader) loader.classList.add('fade-out');
            }, 1000);
        });

        // --- 9. BEAUTIFICATION: SCROLL PROGRESS & REVEAL ---
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            const progress = document.getElementById("scroll-progress");
            if(progress) progress.style.width = scrolled + "%";

            // Navbar Scrolled State
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');

            // Reveal Sections
            document.querySelectorAll('.reveal').forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight - 100) {
                    el.classList.add('active');
                    if(el.id === 'about') startCounters();
                }
            });
        });

        // --- 10. BEAUTIFICATION: COUNTERS ---
        let countersStarted = false;
        function startCounters() {
            if(countersStarted) return;
            countersStarted = true;
            document.querySelectorAll('.counter').forEach(counter => {
                const target = +counter.dataset.target;
                const increment = target / 100;
                let current = 0;
                const update = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current) + (target > 1000 && !counter.dataset.noPlus ? '+' : '');
                        setTimeout(update, 20);
                    } else {
                        counter.textContent = target + (target > 1000 && !counter.dataset.noPlus ? '+' : '');
                    }
                };
                update();
            });
        }

        // --- 11. BEAUTIFICATION: DARK MODE ---
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



        // --- 14. BEAUTIFICATION: MENU SEARCH (Debounced) ---
        const menuSearchInput = document.getElementById('menu-search');
        let searchTimeout;
        if(menuSearchInput) {
            menuSearchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const term = e.target.value.toLowerCase();
                    document.querySelectorAll('.menu-item').forEach(item => {
                        const name = (item.getAttribute('data-name') || '').toLowerCase();
                        item.style.display = name.includes(term) ? 'flex' : 'none';
                    });
                }, 200); // 200ms debounce
            });
        }

        // --- 15. BEAUTIFICATION: TOAST & CHART TILT ---
        function showToast() {
            const toast = document.getElementById('live-toast');
            if(toast) {
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 3000);
            }
        }

        const chartCard = document.querySelector('.card.shadow-lg');
        if(chartCard) {
            chartCard.addEventListener('mousemove', (e) => {
                const rect = chartCard.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 15;
                const rotateY = (centerX - x) / 15;
                chartCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            chartCard.addEventListener('mouseleave', () => {
                chartCard.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg)`;
            });
        }

        // --- 16. STORE STATUS ---
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
        // --- LIVE SYNC: Single bundled request via api_sync.php ---
        function syncAll() {
            fetch('api_sync.php')
                .then(response => response.json())
                .then(data => {
                    if (data.menus) {
                        for (const [containerId, category] of Object.entries(menuTabMap)) {
                            const el = document.getElementById(containerId);
                            if (el) el.innerHTML = renderMenuItems(data.menus[category]);
                        }
                    }
                    if (data.beans) {
                        const container = document.getElementById('beans-container');
                        if (container) {
                            if (data.beans.length === 0) {
                                container.innerHTML = `<div class="col-12 text-center"><p class="text-muted">Daftar biji kopi belum tersedia.</p></div>`;
                            } else {
                                let html = '';
                                data.beans.forEach(bean => {
                                    html += `<div class="col-md-6 col-lg-3"><div class="bean-card h-100"><div class="bean-img-wrapper"><img src="${bean.image_url}" alt="${bean.name}" loading="lazy" onerror="this.src='assets/default_bean.jpg'"><span class="bean-roast-badge">${bean.roast_level}</span></div><div class="p-4"><h5 class="fw-bold mb-1" style="color:var(--text-main);font-family:'Playfair Display',serif;">${bean.name}</h5><p class="small text-muted mb-3"><i class="fas fa-map-marker-alt me-1" style="color:var(--gold);"></i>${bean.origin}</p><p class="small mb-0" style="color:var(--text-main);opacity:0.8;"><strong>Tasting Notes:</strong><br>${bean.notes}</p></div></div></div>`;
                                });
                                container.innerHTML = html;
                            }
                        }
                    }
                    if (data.chart && typeof aboutChart !== 'undefined' && aboutChart) {
                        aboutChart.data.datasets[0].data = data.chart;
                        aboutChart.update();
                    }
                    if (data.contacts) {
                        const c = data.contacts;
                        if (c.whatsapp) {
                            const waName = document.getElementById('contact-wa-name');
                            const waLink = document.getElementById('contact-wa-link');
                            if (waName) waName.textContent = `${c.whatsapp.role} (${c.whatsapp.person_name})`;
                            if (waLink) { waLink.href = c.whatsapp.link_url; waLink.textContent = c.whatsapp.display_value; }
                        }
                        if (c.email) {
                            const emailName = document.getElementById('contact-email-name');
                            const emailLink = document.getElementById('contact-email-link');
                            if (emailName) emailName.textContent = c.email.role;
                            if (emailLink) { emailLink.href = c.email.link_url; emailLink.textContent = c.email.display_value; }
                        }
                    }
                })
                .catch(err => console.error('Gagal sync data:', err));
        }
        syncAll();
        setInterval(syncAll, 30000);

        // --- VIDEO PAUSE WHEN OUT OF VIEW ---
        // Pause all slide videos when the home section is not visible
        const slideVideos = document.querySelectorAll('.slide-video');
        const homeSection = document.getElementById('home');
        let currentSlideIndex = 0;

        const videoObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Section is visible â€” play only the currently active slide
                    const activeVideo = slideVideos[currentSlideIndex];
                    if (activeVideo) activeVideo.play().catch(() => {});
                } else {
                    // Section is out of view â€” pause ALL videos to free resources
                    slideVideos.forEach(v => { v.pause(); });
                }
            });
        }, { threshold: 0.1 });

        if (homeSection) videoObserver.observe(homeSection);

        // Track which slide is active so we play the right video
        const origTransformScroll = transformScroll;
        let _lastSlideIdx = -1;
        window.addEventListener('scroll', () => {
            // Find currently active slide index
            const activeSlide = document.querySelector('.h-slide.active');
            if (!activeSlide) return;
            const newIdx = Array.from(document.querySelectorAll('.h-slide')).indexOf(activeSlide);
            if (newIdx !== _lastSlideIdx) {
                _lastSlideIdx = newIdx;
                currentSlideIndex = newIdx;
                // Pause all, play only the new active one
                slideVideos.forEach((v, i) => {
                    if (i === newIdx) v.play().catch(() => {});
                    else v.pause();
                });
            }
        });

        // Hook into sync function to show toast
        const originalSyncAll = syncAll;
        syncAll = function() {
            originalSyncAll();
            showToast();
        };

        // Init Functions
        transformScroll();

        // --- 17. RESERVATION FORM HANDLER ---
        const resForm = document.getElementById('reservation-form');
        if(resForm) {
            resForm.addEventListener('submit', (e) => {
                // Validation is handled by 'required' attribute, 
                // but we can add extra logic here if needed.
                // For now, let it submit to booking.php as requested.
                console.log("Booking submitted...");
            });
        }

        // Init Particles
        function createParticles() {
            const container = document.querySelector('.particles-container');
            if(!container) return;
            const icons = ['fa-coffee', 'fa-mug-hot', 'fa-cookie', 'fa-leaf'];
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('i');
                const iconClass = icons[Math.floor(Math.random() * icons.length)];
                particle.className = `fas ${iconClass} particle`;
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.fontSize = (Math.random() * 15 + 10) + 'px';
                container.appendChild(particle);
            }
        }
        createParticles();

        // --- 16. OPTIMIZATION: PARTICLE OBSERVER (Hard Pause) ---
        const homeEl = document.getElementById('home');
        const particleContainer = document.querySelector('.particles-container');
        if (homeEl && particleContainer) {
            const particleObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        particleContainer.style.display = 'block';
                    } else {
                        particleContainer.style.display = 'none';
                    }
                });
            }, { threshold: 0.01 });
            particleObserver.observe(homeEl);
        }

        // --- ULTRA-OPTIMIZED MENU IMAGE PREVIEW ---
        const menuPreview = document.getElementById('menu-img-preview');
        let previewActive = false;
        let lastX = 0, lastY = 0;
        let ticking = false;

        // 1. Image Preloader (Cache in RAM)
        function preloadMenuImages() {
            const items = document.querySelectorAll('.menu-item[data-img]');
            items.forEach(item => {
                const img = new Image();
                img.src = item.getAttribute('data-img');
            });
        }
        window.addEventListener('load', () => setTimeout(preloadMenuImages, 2000));

        // 2. Throttled Position Update (requestAnimationFrame)
        function updatePreviewPos() {
            if (!menuPreview) return;
            const offset = 24;
            let x = lastX + offset;
            if (x + 210 > window.innerWidth) x = lastX - 210 - offset;
            let y = lastY - 80;
            if (y < 8) y = 8;
            
            menuPreview.style.transform = `translate3d(${x}px, ${y}px, 0)`;
            ticking = false;
        }

        document.addEventListener('mousemove', (e) => {
            lastX = e.clientX;
            lastY = e.clientY;
            if (!ticking && previewActive) {
                requestAnimationFrame(updatePreviewPos);
                ticking = true;
            }
        });

        document.addEventListener('mouseover', (e) => {
            const item = e.target.closest('.menu-item');
            if (item && item.hasAttribute('data-img')) {
                const imgPath = item.getAttribute('data-img');
                const itemName = item.getAttribute('data-name');
                if (imgPath) {
                    const img = menuPreview.querySelector('img');
                    if(img) img.src = imgPath;
                    const label = menuPreview.querySelector('.preview-label');
                    if(label) label.textContent = itemName || '';
                    
                    previewActive = true;
                    menuPreview.classList.add('visible');
                    // Force one immediate update on enter
                    requestAnimationFrame(updatePreviewPos);
                }
            }
        });

        document.addEventListener('mouseout', (e) => {
            const item = e.target.closest('.menu-item');
            if (item) {
                previewActive = false;
                menuPreview.classList.remove('visible');
            }
        });

        // --- 6. FITUR JS BARU: LENIS SMOOTH SCROLL ---
        window.lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
        });

        function raf(time) {
            window.lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
    </script>
</body>
</html>
