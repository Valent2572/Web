@extends('layout.app')

@section('styles')
<style>
    /* --- CONTACT PAGE ANIMATIONS --- */
    .contact-bg-anim {
        position: absolute;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }
    
    /* Rising Aroma (Steam) */
    .steam-particle {
        position: absolute;
        bottom: -50px;
        background: rgba(255, 255, 255, 0.4); /* Opacity dinaikkan */
        filter: blur(25px);
        border-radius: 50%;
        animation: steam-rise linear infinite;
        opacity: 0;
        z-index: 1;
    }
    [data-bs-theme="dark"] .steam-particle {
        background: rgba(255, 255, 255, 0.15); /* Lebih terlihat di dark mode */
    }

    @keyframes steam-rise {
        0% { transform: translateY(0) scale(1) translateX(0); opacity: 0; }
        20% { opacity: 0.6; }
        50% { transform: translateY(-300px) scale(1.8) translateX(40px); }
        80% { opacity: 0.3; }
        100% { transform: translateY(-600px) scale(2.5) translateX(-40px); opacity: 0; }
    }

    /* Pulsing Golden Aura */
    .golden-aura {
        position: absolute;
        width: 700px; /* Ukuran diperbesar */
        height: 700px;
        background: radial-gradient(circle, rgba(197, 160, 89, 0.25) 0%, transparent 70%); /* Lebih terang */
        border-radius: 50%;
        filter: blur(50px);
        animation: aura-pulse 10s ease-in-out infinite alternate;
        z-index: 0; /* Pindah ke z-index 0 agar tidak ketutup background section */
    }
    @keyframes aura-pulse {
        0% { transform: scale(1); opacity: 0.3; }
        100% { transform: scale(1.3); opacity: 0.6; }
    }
</style>
@endsection

@section('content')
<section id="contact-page" class="py-5 reveal" style="background: var(--bg-body); color: var(--text-main); min-height: 90vh; display: flex; align-items: center; padding-top: 120px !important; position: relative; overflow: hidden;">
    <!-- Background Animations -->
    <div class="contact-bg-anim" id="contact-anim-container">
        <div class="golden-aura" style="top: -100px; right: -100px;"></div>
        <div class="golden-aura" style="bottom: -200px; left: -200px; animation-delay: -4s;"></div>
    </div>

    <div class="container" style="position: relative; z-index: 1;">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-primary mb-3" style="font-family: 'Playfair Display', serif;">Reservasi & Kontak</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Pesan tempat Anda sekarang dan pastikan momen bersama orang terkasih berjalan sempurna di Senja Coffee.
            </p>
        </div>
        
        <div class="row g-5 justify-content-center">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); backdrop-filter: blur(10px);">
                    <div class="card-body p-5">
                        <h4 class="fw-bold text-primary mb-4 border-bottom pb-3"><i class="fas fa-map-marker-alt me-2 text-gold"></i>Kunjungi Kami</h4>
                        <div class="mb-4">
                            <h6 class="text-uppercase small fw-bold" style="color: var(--gold);">Alamat</h6>
                            <p style="color: var(--text-main); opacity: 0.9;">Jl. Kenangan No. 24, Jakarta Selatan</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-uppercase small fw-bold" style="color: var(--gold);">Jam Operasional</h6>
                            <p class="mb-1" style="color: var(--text-main); opacity: 0.9;">Senin - Jumat: 08:00 - 22:00</p>
                            <p class="mb-0" style="color: var(--text-main); opacity: 0.9;">Sabtu - Minggu: 09:00 - 23:00</p>
                        </div>
                        
                        <div>
                            <h6 class="text-uppercase small fw-bold mb-3" style="color: var(--gold);">Contact Person</h6>
                            @if(isset($contacts['whatsapp']))
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-2 rounded-circle shadow-sm d-flex justify-content-center align-items-center me-3" 
                                     style="width: 40px; height: 40px; background: var(--bg-body); border: 1px solid var(--border-color);">
                                    <i class="fab fa-whatsapp text-success"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $contacts['whatsapp']['role'] }}</p>
                                    <a href="{{ $contacts['whatsapp']['link_url'] }}" target="_blank" class="text-decoration-none text-muted small">{{ $contacts['whatsapp']['display_value'] }} ({{ $contacts['whatsapp']['person_name'] }})</a>
                                </div>
                            </div>
                            @endif

                            @if(isset($contacts['email']))
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle shadow-sm d-flex justify-content-center align-items-center me-3" 
                                     style="width: 40px; height: 40px; background: var(--bg-body); border: 1px solid var(--border-color);">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $contacts['email']['role'] }}</p>
                                    <a href="{{ $contacts['email']['link_url'] }}" class="text-decoration-none text-muted small">{{ $contacts['email']['display_value'] }}</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservation Form -->
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 h-100" style="background: var(--card-bg); border-top: 5px solid var(--gold) !important; backdrop-filter: blur(10px);">
                    <div class="card-body p-5">
                        <h4 class="fw-bold text-primary mb-4"><i class="fas fa-calendar-check me-2 text-gold"></i>Form Reservasi Meja</h4>
                        
                        <form id="reservation-form" action="#" method="GET" class="row g-4 mt-2">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control bg-transparent border-secondary py-2" style="color: var(--text-main) !important;" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Tanggal Kedatangan</label>
                                <input type="date" id="res-date" name="tanggal" class="form-control bg-transparent border-secondary py-2" style="color: var(--text-main) !important;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Waktu (Jam)</label>
                                <input type="time" id="res-time" name="waktu" class="form-control bg-transparent border-secondary py-2" style="color: var(--text-main) !important;" required>
                            </div>
                            <div class="col-12 mt-5">
                                <button type="submit" id="submit-booking" class="btn py-3 w-100 rounded-pill fw-bold" 
                                        style="background: var(--gold); color: #1a1a1a; letter-spacing: 1px; border: none;">
                                    AJUKAN RESERVASI SEKARANG
                                </button>
                                <p class="text-center small text-muted mt-3 mb-0">*Reservasi Anda akan dikonfirmasi melalui WhatsApp oleh Admin kami.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // --- RESERVATION VALIDATION ---
    document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.getElementById('res-date');
        const timeInput = document.getElementById('res-time');
        const form = document.getElementById('reservation-form');

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const yyyy = tomorrow.getFullYear();
        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd = String(tomorrow.getDate()).padStart(2, '0');
        if(dateInput) dateInput.min = `${yyyy}-${mm}-${dd}`;

        if(form) {
            form.addEventListener('submit', (e) => {
                const dateVal = new Date(dateInput.value);
                const timeVal = timeInput.value;
                const day = dateVal.getDay();
                const [hh, mm_val] = timeVal.split(':').map(Number);
                const timeNum = hh + mm_val/60;

                let minH = 8, maxH = 22;
                let dayName = "Hari Kerja";
                if(day === 0 || day === 6) {
                    minH = 9; maxH = 23;
                    dayName = "Akhir Pekan (Sabtu-Minggu)";
                }

                if(timeNum < minH || timeNum >= maxH) {
                    e.preventDefault();
                    alert(`Maaf, pada ${dayName} jam operasional kami adalah ${String(minH).padStart(2,'0')}:00 - ${maxH}:00.`);
                } else {
                    e.preventDefault();
                }
            });
        }
    });

    // --- STEAM PARTICLE GENERATOR ---
    function createSteam() {
        const container = document.getElementById('contact-anim-container');
        if(!container) return;
        
        setInterval(() => {
            const particle = document.createElement('div');
            particle.className = 'steam-particle';
            
            const size = Math.random() * 60 + 20;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            
            const duration = Math.random() * 5 + 5;
            particle.style.animationDuration = duration + 's';
            
            container.appendChild(particle);
            
            // Cleanup
            setTimeout(() => {
                particle.remove();
            }, duration * 1000);
        }, 800);
    }
    window.addEventListener('load', createSteam);
</script>
@endsection
