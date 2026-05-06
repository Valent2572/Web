@extends('layout.app')

@section('styles')
<style>
    /* --- HERO STORY --- */
    .story-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1500&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-top: -80px; /* Offset for navbar */
    }

    /* --- TIMELINE --- */
    .timeline {
        position: relative;
        padding: 50px 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gold);
        opacity: 0.3;
        transform: translateX(-50%);
    }
    .timeline-item {
        margin-bottom: 80px;
        position: relative;
        z-index: 1;
    }
    .timeline-dot {
        width: 20px;
        height: 20px;
        background: var(--gold);
        border-radius: 50%;
        position: absolute;
        left: 50%;
        top: 0;
        transform: translateX(-50%);
        box-shadow: 0 0 15px var(--gold);
    }
    .timeline-content {
        width: 45%;
        padding: 30px;
        background: var(--card-bg);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease;
    }
    .timeline-content:hover {
        transform: translateY(-5px);
    }
    .timeline-item:nth-child(even) .timeline-content {
        margin-left: auto;
    }
    .timeline-date {
        font-family: 'Playfair Display', serif;
        font-weight: 900;
        color: var(--gold);
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }

    /* --- VALUES --- */
    .value-card {
        padding: 40px;
        text-align: center;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        height: 100%;
        transition: all 0.3s ease;
    }
    .value-card:hover {
        background: var(--gold);
        color: #1a1a1a;
        transform: translateY(-10px);
    }
    .value-card:hover p, .value-card:hover h4, .value-card:hover .value-icon { color: #1a1a1a !important; }
    .value-icon {
        font-size: 3rem;
        color: var(--gold);
        margin-bottom: 20px;
    }

    .since-2024-banner {
        background: var(--bs-primary);
        color: white;
        padding: 60px 0;
        position: relative;
        overflow: hidden;
    }
    .since-2024-banner::after {
        content: '2024';
        position: absolute;
        right: -50px;
        bottom: -50px;
        font-size: 20rem;
        font-weight: 900;
        opacity: 0.05;
        color: white;
    }

    @media (max-width: 768px) {
        .timeline::before { left: 20px; }
        .timeline-dot { left: 20px; }
        .timeline-content { width: calc(100% - 50px); margin-left: 50px !important; }
    }

    /* --- MILESTONE BG ANIM --- */
    .milestone-bg-anim {
        position: absolute;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }
    .memory-dot {
        position: absolute;
        background: var(--gold);
        border-radius: 50%;
        opacity: 0;
        filter: blur(3px);
        box-shadow: 0 0 15px var(--gold);
        animation: float-memory linear infinite;
        will-change: transform, opacity;
    }
    @keyframes float-memory {
        0% { transform: translateY(0) translateX(0) scale(1); opacity: 0; }
        25% { opacity: 0.25; transform: translateY(-150px) translateX(30px) scale(1.2); }
        50% { transform: translateY(-300px) translateX(-30px) scale(1); }
        75% { opacity: 0.25; transform: translateY(-450px) translateX(30px) scale(0.8); }
        100% { transform: translateY(-600px) translateX(0) scale(0.5); opacity: 0; }
    }

    /* --- MOLTEN LAVA BG --- */
    .lava-bg-container {
        position: absolute;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }
    .lava-bg {
        position: absolute;
        width: 200%;
        height: 200%;
        top: -50%;
        left: -50%;
        background-image: url('https://images.unsplash.com/photo-1541701494587-cb58502866ab?auto=format&fit=crop&w=1500&q=80');
        background-size: cover;
        filter: grayscale(1) contrast(1.8) brightness(0.8) opacity(0.15);
        animation: lava-slow-pan 15s linear infinite alternate;
        will-change: transform;
    }
    @keyframes lava-slow-pan {
        0% { transform: translate(0, 0) rotate(0deg); }
        50% { transform: translate(-5%, -5%) rotate(2deg); }
        100% { transform: translate(-10%, -10%) rotate(0deg); }
    }

    /* --- GOLD LASER DIVIDER --- */
    .gold-divider-container {
        height: 4px;
        width: 100%;
        background: rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
        z-index: 10;
    }
    .gold-streak {
        position: absolute;
        height: 100%;
        width: 200px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
        opacity: 0;
        filter: blur(1px);
    }
    .streak-1 { animation: streak-ltr 1.5s infinite; animation-delay: 0s; }
    .streak-2 { animation: streak-rtl 2s infinite; animation-delay: 0.5s; }
    .streak-3 { animation: streak-ltr 1.2s infinite; animation-delay: 1s; }
    .streak-4 { animation: streak-rtl 1.8s infinite; animation-delay: 1.5s; }

    @keyframes streak-ltr {
        0% { left: -200px; opacity: 0; }
        50% { opacity: 1; }
        100% { left: 100%; opacity: 0; }
    }
    @keyframes streak-rtl {
        0% { right: -200px; opacity: 0; }
        50% { opacity: 1; }
        100% { right: 100%; opacity: 0; }
    }

    /* --- MILESTONE (SIDE-REVEAL VERSION) --- */
    .timeline {
        position: relative;
        padding: 50px 0; /* Padding normal kembali */
    }
    .timeline-item {
        margin-bottom: 100px;
        position: relative;
        transition: z-index 0.3s;
    }
    .timeline-item:hover {
        z-index: 999; /* Pastikan kartu yang di-hover selalu berada di paling depan */
    }
    .timeline-content {
        position: relative;
        background: var(--card-bg);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        transition: all 0.4s ease;
        z-index: 2;
    }
    .timeline-item:hover .timeline-content {
        border-color: var(--gold);
    }

    /* Detail Card Base */
    .timeline-detail {
        position: absolute;
        top: 0;
        width: 280px; /* Diperkecil agar lebih proporsional */
        background: var(--card-bg);
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        border: 1px solid var(--border-color);
        opacity: 0;
        visibility: hidden;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 10;
    }

    /* 2020 & 2022 (Item 1 & 3) -> Pop Right */
    .timeline-item:nth-child(1) .timeline-detail,
    .timeline-item:nth-child(3) .timeline-detail {
        left: 105%;
        transform: translateX(20px);
    }
    .timeline-item:nth-child(1):hover .timeline-detail,
    .timeline-item:nth-child(3):hover .timeline-detail {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    /* 2021 & 2024 (Item 2 & 4) -> Pop Left */
    .timeline-item:nth-child(2) .timeline-detail,
    .timeline-item:nth-child(4) .timeline-detail {
        right: 105%;
        transform: translateX(-20px);
    }
    .timeline-item:nth-child(2):hover .timeline-detail,
    .timeline-item:nth-child(4):hover .timeline-detail {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .milestone-sketch {
        width: 100%;
        height: 160px; /* Batasi tinggi agar tidak "Guede Banget" */
        object-fit: cover; /* Agar gambar tetap proporsional meskipun di-crop */
        border-radius: 10px;
        margin-bottom: 15px;
        filter: grayscale(1);
        transition: all 0.5s ease;
    }
    .timeline-item:hover .milestone-sketch {
        filter: grayscale(0);
    }
    .detailed-story {
        font-size: 0.95rem; /* Dinaikkan 2 poin agar lebih jelas dibaca */
        line-height: 1.6;
        color: var(--text-main);
        font-style: italic;
        margin-bottom: 0;
    }

    @media (max-width: 1200px) {
        .timeline-detail {
            position: relative;
            width: 100%;
            left: 0 !important;
            right: 0 !important;
            top: 20px;
            box-shadow: none;
            transform: none !important;
            display: none;
        }
        .timeline-item:hover .timeline-detail {
            display: block;
        }
    }
</style>
@endsection

@section('content')
<!-- HERO SECTION -->
<section class="story-hero">
    <div class="container">
        <h6 class="text-uppercase mb-3" style="letter-spacing: 4px; color: var(--gold);">Our Legacy</h6>
        <h1 class="display-3 fw-bold" style="font-family: 'Playfair Display', serif;">The Spirit of Senja</h1>
        <p class="lead mx-auto mt-4" style="max-width: 700px;">
            Dari garasi kecil hingga menjadi rumah bagi para pemimpi. Inilah perjalanan kami meracik kehangatan di setiap cangkir.
        </p>
    </div>
</section>

<!-- GOLD LASER DIVIDER -->
<div class="gold-divider-container">
    <div class="gold-streak streak-1"></div>
    <div class="gold-streak streak-2"></div>
    <div class="gold-streak streak-3"></div>
    <div class="gold-streak streak-4"></div>
</div>

<!-- THE ORIGIN -->
<section class="py-5" style="background: var(--bg-body); color: var(--text-main); position: relative; overflow: hidden;">
    <div class="lava-bg-container">
        <div class="lava-bg"></div>
    </div>
    <div class="container py-5" style="position: relative; z-index: 1;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <img src="https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=800&q=80" 
                     class="img-fluid rounded-4 shadow-lg" alt="Senja Coffee Shop" loading="lazy" decoding="async">
            </div>
            <div class="col-lg-6 reveal">
                <h6 class="text-uppercase mb-2" style="color: var(--gold); letter-spacing: 2px;">The Beginning</h6>
                <h2 class="display-5 fw-bold mb-4" style="font-family: 'Playfair Display', serif;">Lahir dari Garasi & Mimpi</h2>
                <p class="lead text-muted">
                    Semua bermula di tahun 2020, ketika dua sahabat memutuskan bahwa Jakarta Selatan butuh tempat yang lebih dari sekadar menjual kopi.
                </p>
                <p>
                    Senja Coffee didirikan atas dasar kerinduan akan suasana tenang saat matahari terbenam. Kami memulai dengan satu mesin espresso bekas dan beberapa kantong biji kopi pilihan dari petani lokal. Nama "Senja" dipilih bukan tanpa alasan—itu adalah waktu di mana dunia melambat, dan obrolan menjadi lebih tulus.
                </p>
                <div class="d-flex gap-4 mt-5">
                    <div>
                        <h4 class="fw-bold mb-0 text-primary">2020</h4>
                        <small class="text-muted">Tahun Berdiri</small>
                    </div>
                    <div class="border-start ps-4">
                        <h4 class="fw-bold mb-0 text-primary">50k+</h4>
                        <small class="text-muted">Cangkir Terjual</small>
                    </div>
                    <div class="border-start ps-4">
                        <h4 class="fw-bold mb-0 text-primary">15+</h4>
                        <small class="text-muted">Petani Lokal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TIMELINE JOURNEY -->
<section class="py-5" style="background: var(--bs-light); position: relative; overflow: hidden;">
    <div class="milestone-bg-anim"></div>
    <div class="container py-5" style="position: relative; z-index: 1;">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary" style="font-family: 'Playfair Display', serif;">Milestones Kami</h2>
            <div class="mx-auto" style="width: 80px; height: 3px; background: var(--gold);"></div>
        </div>

        <div class="timeline">
            <!-- 2020 -->
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2020</span>
                    <h4 class="fw-bold">The Garage Days</h4>
                    <p class="text-muted mb-0">Senja Coffee lahir di sebuah garasi kecil di Tebet. Melayani hanya 10 pelanggan per hari, namun dengan dedikasi penuh pada kualitas.</p>
                    <div class="timeline-detail">
                        <img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?auto=format&fit=crop&w=800&q=80" class="milestone-sketch" alt="2020 Garage Style" loading="lazy" decoding="async">
                        <p class="detailed-story">
                            "Kami hanya memiliki dua meja kayu tua dan satu mesin kopi rumahan. Setiap hari kami menunggu dengan antusias pelanggan pertama yang akan mampir ke garasi kami. Kopi-kopi pertama itu adalah fondasi dari cinta kami pada industri ini."
                        </p>
                    </div>
                </div>
            </div>
            <!-- 2021 -->
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2021</span>
                    <h4 class="fw-bold">Mastering the Roast</h4>
                    <p class="text-muted mb-0">Kami mulai memanggang biji kopi kami sendiri. Mesin "Gento" menjadi saksi bisu eksperimen tanpa henti kami mencari profil rasa sempurna.</p>
                    <div class="timeline-detail">
                        <img src="https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?auto=format&fit=crop&w=800&q=80" class="milestone-sketch" alt="Coffee Roasting Machine" loading="lazy" decoding="async">
                        <p class="detailed-story">
                            "Mesin Gento adalah jantung dari operasional kami. Saat ia rusak, kami tidak menyerah. Kami mempelajarinya baut demi baut, memperbaikinya sendiri hingga larut malam, hingga akhirnya ia kembali mendesing meracik aroma kopi yang lebih kaya."
                        </p>
                    </div>
                </div>
            </div>
            <!-- 2022 -->
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2022</span>
                    <h4 class="fw-bold">Senja Selatan</h4>
                    <p class="text-muted mb-0">Cabang pertama resmi dibuka dengan konsep interior industrial-hangat. Senja mulai menjadi rumah kedua bagi para komunitas kreatif.</p>
                    <div class="timeline-detail">
                        <img src="https://images.unsplash.com/photo-1453614512568-c4024d13c247?auto=format&fit=crop&w=800&q=80" class="milestone-sketch" alt="Industrial Coffee Shop" loading="lazy" decoding="async">
                        <p class="detailed-story">
                            "Hari itu adalah mimpi yang menjadi nyata. Dengan gunting pita sederhana, kami membuka pintu Senja Selatan. Kami tidak menyangka antusiasme komunitas begitu besar, mengisi setiap sudut dengan tawa dan obrolan."
                        </p>
                    </div>
                </div>
            </div>
            <!-- 2024 -->
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2024</span>
                    <h4 class="fw-bold">Digital Presence & Beyond</h4>
                    <p class="text-muted mb-0">Meluncurkan portofolio digital dan mempersiapkan ekspansi ke luar kota. Mimpi kami tetap sama: menyebarkan kehangatan Senja.</p>
                    <div class="timeline-detail">
                        <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80" class="milestone-sketch" alt="Modern Premium Cafe" loading="lazy" decoding="async">
                        <p class="detailed-story">
                            "Era baru telah tiba. Senja Coffee berevolusi menjadi standar premium. Outlet terbaru kami bukan sekadar tempat minum kopi, melainkan mahakarya arsitektur yang merayakan harmoni antara alam dan kemewahan."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VALUES SECTION -->
<section class="py-5" style="background: var(--bg-body);">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4 reveal">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-seedling"></i></div>
                    <h4 class="fw-bold">Ethical Sourcing</h4>
                    <p class="text-muted">Kami bekerja langsung dengan petani lokal untuk memastikan setiap biji kopi ditanam dengan cara yang berkelanjutan dan adil.</p>
                </div>
            </div>
            <div class="col-lg-4 reveal">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-heart"></i></div>
                    <h4 class="fw-bold">Community First</h4>
                    <p class="text-muted">Senja bukan sekadar bisnis, tapi sebuah ruang temu. Kami mendukung seniman lokal dan komunitas untuk berkembang bersama.</p>
                </div>
            </div>
            <div class="col-lg-4 reveal">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-award"></i></div>
                    <h4 class="fw-bold">Uncompromising Quality</h4>
                    <p class="text-muted">Dari pemilihan air hingga suhu seduhan, kami tidak mengenal kata kompromi untuk memberikan cangkir kopi terbaik Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- WHY SINCE 2024? -->
<section class="since-2024-banner reveal">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <span class="d-block mb-3 text-uppercase fw-bold" style="letter-spacing: 3px; color: var(--gold);">The Brand Signature</span>
                <h2 class="display-4 fw-bold mb-4" style="font-family: 'Playfair Display', serif;">Kenapa "Since 2024"?</h2>
                <p class="lead opacity-75">
                    Meskipun mimpi ini telah bersemi sejak 2020, tahun <strong>2024</strong> adalah momen di mana kami melahirkan kembali identitas kami. 2024 menandai standarisasi kualitas premium, peluncuran pengalaman digital, dan peresmian visi baru Senja Coffee sebagai <em>Premium Coffee Experience</em>. Itulah sebabnya, bagi kami, Senja yang sesungguhnya dimulai di sini.
                </p>
                <div class="mt-4">
                    <span style="font-size: 3rem; font-family: 'Playfair Display', serif; font-weight: 900; color: var(--gold);">2024</span>
                    <p class="small text-uppercase mt-2" style="letter-spacing: 2px;">Born for Excellence</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATISTICS & CHART (INTEGRATED) -->
<section id="statistics" class="py-5 reveal" style="background: var(--bs-light); color: var(--text-main);">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <h6 class="text-uppercase mb-2" style="color: var(--gold); letter-spacing: 2px;">Transparency</h6>
                <h2 class="display-6 fw-bold text-primary mb-4" style="font-family: 'Playfair Display', serif;">Data Dibalik Cangkir</h2>
                <p class="text-muted">
                    Kami percaya pada transparansi. Grafik di samping menunjukkan bagaimana komunitas kami menikmati berbagai pilihan kopi yang kami tawarkan setiap bulannya.
                </p>
                
                <!-- Contact Person Section -->
                <div class="mt-5 p-4 rounded shadow-lg border-0" style="background-color: var(--card-bg); border-left: 5px solid var(--gold) !important;">
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-headset me-2"></i>Inquiry & Partnership</h5>
                    
                    @if(isset($contacts['whatsapp']))
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 rounded-circle shadow-sm d-flex justify-content-center align-items-center me-3" 
                             style="width: 45px; height: 45px; background: var(--bg-body); border: 1px solid var(--border-color);">
                            <i class="fab fa-whatsapp text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: var(--text-main);">{{ $contacts['whatsapp']['role'] }} ({{ $contacts['whatsapp']['person_name'] }})</h6>
                            <a href="{{ $contacts['whatsapp']['link_url'] }}" target="_blank" class="text-decoration-none text-muted small">{{ $contacts['whatsapp']['display_value'] }}</a>
                        </div>
                    </div>
                    @endif

                    @if(isset($contacts['email']))
                    <div class="d-flex align-items-center">
                        <div class="p-2 rounded-circle shadow-sm d-flex justify-content-center align-items-center me-3" 
                             style="width: 45px; height: 45px; background: var(--bg-body); border: 1px solid var(--border-color);">
                            <i class="fas fa-envelope text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: var(--text-main);">{{ $contacts['email']['role'] }}</h6>
                            <a href="{{ $contacts['email']['link_url'] }}" class="text-decoration-none text-muted small">{{ $contacts['email']['display_value'] }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- Chart Column -->
            <div class="col-lg-7">
                <div class="card shadow-lg border-0" style="background: var(--card-bg); color: var(--text-main); border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-5">
                        <h5 class="card-title text-center text-primary mb-4 fw-bold" style="font-family: 'Playfair Display', serif;">Customer Preferences (Last Month)</h5>
                        <canvas id="aboutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- CHARTJS ---
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('aboutChart').getContext('2d');

        const coffeeData = {
            labels: @json($chart['labels']),
            datasets: [{
                label: 'Gelas Terjual',
                data: @json($chart['data']), 
                backgroundColor: [
                    'rgba(197, 160, 89, 0.8)', 
                    'rgba(74, 59, 50, 0.8)', 
                    'rgba(141, 110, 99, 0.8)', 
                    'rgba(45, 27, 21, 0.8)', 
                    'rgba(168, 159, 145, 0.8)'  
                ],
                borderColor: 'var(--gold)',
                borderWidth: 2,
                borderRadius: 10,
                hoverBackgroundColor: 'var(--gold)'
            }]
        };

        const config = {
            type: 'bar',
            data: coffeeData,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleFont: { size: 14, family: 'Playfair Display' },
                        padding: 12
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false },
                        ticks: { color: '#888' }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#888', font: { weight: 'bold' } }
                    }
                }
            }
        };

        new Chart(ctx, config);

        // Card Hover Tilt Effect
        const chartCard = document.querySelector('.card.shadow-lg');
        if(chartCard) {
            chartCard.addEventListener('mousemove', (e) => {
                const rect = chartCard.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 25;
                const rotateY = (centerX - x) / 25;
                chartCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            chartCard.addEventListener('mouseleave', () => {
                chartCard.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg)`;
            });
        }
    });

    // --- MILESTONE MEMORY DUST ---
    function createMemoryDust() {
        const container = document.querySelector('.milestone-bg-anim');
        if(!container) return;
        for (let i = 0; i < 60; i++) {
            const dot = document.createElement('div');
            dot.className = 'memory-dot';
            const size = Math.random() * 10 + 2;
            dot.style.width = size + 'px';
            dot.style.height = size + 'px';
            dot.style.left = Math.random() * 100 + '%';
            dot.style.top = (Math.random() * 120) + '%';
            dot.style.animationDuration = (Math.random() * 20 + 15) + 's';
            dot.style.animationDelay = '-' + (Math.random() * 20) + 's';
            container.appendChild(dot);
        }
    }
    window.addEventListener('load', createMemoryDust);
</script>
@endsection
