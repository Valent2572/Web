@extends('layout.app')

@section('styles')
<style>
    /* --- OPTIMIZED PARALLAX SECTION --- */
    .parallax-section {
        background-image: url('https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=1000&q=60');
        background-attachment: scroll;
        background-size: cover;
        background-position: center;
        min-height: 70vh;
        position: relative;
        overflow: hidden;
        will-change: transform;
        transform: translateZ(0);
        padding-top: 100px;
    }

    /* --- ELEGANT MENU TABS --- */
    .text-gold { color: var(--gold) !important; }

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
        background: rgba(30, 30, 30, 0.85);
        border: 1px solid var(--border-color);
        border-radius: 30px;
        padding: 40px;
        height: 100%;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }
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

    /* --- FLOATING IMAGE PREVIEW --- */
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
@endsection

@section('content')
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
                        @foreach($menus as $category => $items)
                            @php
                                // Create a slug from category name for HTML ID
                                $slug = strtolower(str_replace([' ', '&'], ['-', 'and'], $category));
                            @endphp
                            <button class="nav-link menu-tab-btn w-100 {{ $loop->first ? 'active' : '' }}" 
                                id="v-pills-{{ $slug }}-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-{{ $slug }}" type="button"
                                role="tab">{{ $category }}</button>
                        @endforeach
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
                        @foreach($menus as $category => $items)
                            @php
                                $slug = strtolower(str_replace([' ', '&'], ['-', 'and'], $category));
                            @endphp
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="v-pills-{{ $slug }}" role="tabpanel" tabindex="0">
                                <h3 class="menu-title mb-4 pb-2">{{ $category }}</h3>
                                <div id="menu-items-{{ $slug }}">
                                    @if(count($items) > 0)
                                        @foreach($items as $item)
                                            <div class="menu-item d-flex justify-content-between align-items-baseline mb-3 pb-2" 
                                                 data-img="{{ asset($item['image_url']) }}" 
                                                 data-name="{{ $item['name'] }}">
                                                <div>
                                                    <h5 class="h6 text-primary mb-0 fw-bold">
                                                        {{ $item['name'] }}
                                                        @if(($item['sold_count'] ?? 0) > 100)
                                                            <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5rem;">BEST SELLER</span>
                                                        @endif
                                                    </h5>
                                                    <small class="text-muted fst-italic">{{ $item['description'] }}</small>
                                                </div>
                                                <span class="fw-bold text-primary">{{ $item['price'] }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Menu belum tersedia.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Menu Image Preview Card -->
<div id="menu-img-preview">
    <img src="" alt="Menu Preview" decoding="async">
    <div class="preview-label"></div>
</div>
@endsection

@section('scripts')
<script>
    // --- MENU SEARCH (Debounced) ---
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
            }, 200);
        });
    }

    // --- ULTRA-OPTIMIZED MENU IMAGE PREVIEW ---
    const menuPreview = document.getElementById('menu-img-preview');
    let previewActive = false;
    let lastX = 0, lastY = 0;
    let ticking = false;

    // Staggered Preloader - Only preload when user is likely to interact
    function preloadMenuImages() {
        const items = Array.from(document.querySelectorAll('.menu-item[data-img]'));
        // Preload in batches of 5 to avoid network congestion
        const batchSize = 5;
        let index = 0;

        function loadNextBatch() {
            const batch = items.slice(index, index + batchSize);
            batch.forEach(item => {
                const img = new Image();
                img.decoding = 'async';
                img.src = item.getAttribute('data-img');
            });
            index += batchSize;
            if (index < items.length) {
                setTimeout(loadNextBatch, 500); // Wait 500ms between batches
            }
        }
        loadNextBatch();
    }
    
    // Start preloading after the main page is fully interactive and idle
    window.addEventListener('load', () => {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => setTimeout(preloadMenuImages, 1500));
        } else {
            setTimeout(preloadMenuImages, 3000);
        }
    });

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
</script>
@endsection
