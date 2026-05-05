<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>K-Clean — Perawatan Sepatu Premium di Bandung</title>
    <meta name="description" content="Layanan perawatan sepatu premium di Bandung. Deep Clean, Unyellowing, Repaint oleh tim ahli.">
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <x-theme-script />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --neon: #FEFE01;
            --dark: #080808;
            --surface: #111111;
            --surface2: #1a1a1a;
            --gray: #888;
            --white: #f0f0f0;
        }
        html:not(.dark) {
            --neon: #FEFE01;
            --dark: #f0f0f0;
            --surface: #ffffff;
            --surface2: #e0e0e0;
            --gray: #555555;
            --white: #111111;
        }
        /* Fix readability for neon yellow in day mode */
        html:not(.dark) .nav-logo,
        html:not(.dark) .hero h1 span,
        html:not(.dark) .hero-badge,
        html:not(.dark) .hero-stat-num,
        html:not(.dark) .section-label,
        html:not(.dark) .card:not(:hover) .card-icon svg:not([style*="stroke:#000"]),
        html:not(.dark) .card-price,
        html:not(.dark) [style*="color: var(--neon)"],
        html:not(.dark) [style*="color: #FEFE01"],
        html:not(.dark) .text-neon {
            text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
        }
        html:not(.dark) .card:not(:hover) .card-icon svg:not([style*="stroke:#000"]) {
            filter: drop-shadow(0px 0px 1px #000) drop-shadow(0px 0px 1px #000);
        }
        html { scroll-behavior: smooth; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark); 
            color: var(--white); 
            overflow-x: hidden;
            transition: background-color 0.5s ease, color 0.5s ease;
        }
        /* Smooth transitions for all theme-affected elements */
        .card, .nav, .footer, section, div, p, h1, h2, h3, span, button, input, textarea {
            transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease, box-shadow 0.5s ease, text-shadow 0.5s ease;
        }
        a { text-decoration: none; color: inherit; transition: color 0.3s; }

        /* NAV (Always Dark) */
        .nav { position: fixed; top: 0; width: 100%; z-index: 100; background: #080808; border-bottom: 1px solid rgba(128,128,128,0.2); }
        .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center; height: 72px; }
        .nav-logo { display: flex; align-items: center; gap: 10px; font-weight: 900; font-size: 1.1rem; letter-spacing: 2px; text-transform: uppercase; color: var(--neon); }
        .nav-logo-icon { width: 36px; height: 36px; background: var(--neon); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .nav-logo-icon svg { width: 20px; height: 20px; }
        .nav-links { display: flex; align-items: center; gap: 2rem; font-size: 0.85rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; }
        .nav-links a { color: #f0f0f0 !important; transition: color 0.3s; }
        .nav-links a:hover { color: var(--neon) !important; }
        .nav-cta { padding: 10px 24px; background: var(--neon); color: #000; font-weight: 800; border-radius: 6px; font-size: 0.8rem; letter-spacing: 1px; transition: all 0.3s; }
        .nav-cta:hover { background: #fff; color: #000; box-shadow: 0 0 20px rgba(254,254,1,0.4); }
        .hamburger { display: none; background: none; border: none; cursor: pointer; }
        .hamburger svg { width: 28px; height: 28px; stroke: var(--neon); }
        .theme-toggle-btn { background: none; border: none; cursor: pointer; color: #f0f0f0 !important; display: flex; align-items: center; justify-content: center; position: relative; width: 24px; height: 24px; }
        .theme-toggle-btn svg { width: 24px; height: 24px; stroke: currentColor; fill: none; position: absolute; top: 0; left: 0; transition: opacity 0.5s ease, transform 0.5s ease; }
        html.dark .sun-icon { opacity: 1; transform: rotate(0deg); }
        html.dark .moon-icon { opacity: 0; transform: rotate(90deg); }
        html:not(.dark) .sun-icon { opacity: 0; transform: rotate(-90deg); }
        html:not(.dark) .moon-icon { opacity: 1; transform: rotate(0deg); }
        @media (max-width: 768px) {
            .nav-links { 
                display: none; 
                position: absolute; 
                top: 72px; 
                left: 0; 
                width: 100%; 
                background: rgba(8,8,8,0.95); 
                backdrop-filter: blur(12px); 
                flex-direction: column; 
                padding: 1.5rem; 
                gap: 1.5rem; 
                border-bottom: 1px solid rgba(255,255,255,0.06); 
                box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            }
            .nav-links.active { display: flex; }
            .hamburger { display: block; }
        }

        /* HERO */
        .hero { padding: 140px 1.5rem 80px; max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; min-height: 100vh; }
        .hero-badge { display: inline-block; padding: 6px 16px; border: 1px solid rgba(254,254,1,0.25); border-radius: 50px; font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; color: var(--neon); background: rgba(254,254,1,0.05); text-transform: uppercase; margin-bottom: 1.5rem; }
        .hero h1 { font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 900; line-height: 1.05; letter-spacing: -1px; }
        .hero h1 span { color: var(--neon); text-shadow: 0 0 30px rgba(254,254,1,0.3); }
        .hero-desc { color: var(--gray); font-size: 1.05rem; line-height: 1.7; margin: 1.5rem 0 2.5rem; max-width: 480px; }
        .hero-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-primary { padding: 16px 40px; background: var(--neon); color: #000; font-weight: 800; font-size: 0.95rem; border-radius: 8px; letter-spacing: 1px; box-shadow: 0 0 30px rgba(254,254,1,0.3); transition: all 0.3s; display: inline-block; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 0 50px rgba(254,254,1,0.5); background: #fff; }
        .btn-outline { padding: 16px 40px; border: 2px solid rgba(255,255,255,0.15); color: var(--white); font-weight: 700; font-size: 0.95rem; border-radius: 8px; transition: all 0.3s; }
        .btn-outline:hover { border-color: var(--neon); color: var(--neon); }
        .hero-stats { display: flex; gap: 2rem; margin-top: 3rem; }
        .hero-stat-num { font-size: 1.8rem; font-weight: 900; color: var(--neon); }
        .hero-stat-label { font-size: 0.75rem; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }

        /* SLIDER */
        .slider-wrapper { position: relative; border-radius: 16px; overflow: hidden; aspect-ratio: 4/3; cursor: ew-resize; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 0 40px rgba(254,254,1,0.08); }
        .slider-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; }
        .slider-before-img { z-index: 2; clip-path: inset(0 50% 0 0); }
        .slider-divider { position: absolute; top: 0; left: 50%; width: 3px; height: 100%; background: var(--neon); z-index: 3; transform: translateX(-50%); pointer-events: none; }
        .slider-handle { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); z-index: 4; width: 44px; height: 44px; background: var(--neon); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px rgba(254,254,1,0.6); font-weight: 900; color: #000; font-size: 1.1rem; pointer-events: none; }
        .slider-label { position: absolute; bottom: 12px; z-index: 5; padding: 4px 12px; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); border-radius: 4px; font-size: 0.65rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; border: 1px solid rgba(255,255,255,0.1); transition: opacity 0.3s ease; }
        .slider-label.before { left: 12px; color: #ff6b6b; opacity: 1; }
        .slider-label.after { right: 12px; color: var(--neon); opacity: 0; }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; gap: 2.5rem; padding-top: 110px; min-height: auto; }
            .hero-content { order: 2; text-align: center; }
            .hero-content .hero-desc { margin-left: auto; margin-right: auto; }
            .hero-buttons { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-visual { order: 1; }
        }

        /* SERVICES */
        .services { padding: 100px 1.5rem; background: var(--surface); }
        .services-inner { max-width: 1200px; margin: 0 auto; }
        .section-label { text-align: center; font-size: 0.75rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: var(--neon); margin-bottom: 0.75rem; }
        .section-title { text-align: center; font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1rem; }
        .section-desc { text-align: center; color: var(--gray); max-width: 550px; margin: 0 auto 3.5rem; line-height: 1.7; }
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .card { background: var(--surface2); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 2.5rem 2rem; transition: all 0.4s; position: relative; overflow: hidden; }
        .card:hover { border-color: rgba(254,254,1,0.3); transform: translateY(-6px); box-shadow: 0 12px 40px rgba(254,254,1,0.06); }
        .card.featured { border-color: rgba(254,254,1,0.3); }
        .card-badge { position: absolute; top: 16px; right: -8px; background: var(--neon); color: #000; font-size: 0.6rem; font-weight: 800; padding: 4px 16px 4px 10px; letter-spacing: 1px; text-transform: uppercase; }
        .card-icon { width: 56px; height: 56px; background: rgba(254,254,1,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; transition: all 0.3s; }
        .card:hover .card-icon { background: var(--neon); }
        .card-icon svg { width: 28px; height: 28px; stroke: var(--neon); fill: none; stroke-width: 1.5; transition: all 0.3s; }
        .card:hover .card-icon svg { stroke: #000; }
        .card h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.75rem; letter-spacing: -0.5px; }
        .card p { color: var(--gray); font-size: 0.9rem; line-height: 1.7; margin-bottom: 1.5rem; }
        .card-price { color: var(--neon); font-weight: 800; font-size: 1.15rem; margin-bottom: 1.25rem; }
        .card-link { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: 0.85rem; color: var(--white); transition: color 0.3s; }
        .card-link:hover { color: var(--neon); }
        .card-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
        .card-link:hover svg { transform: translateX(4px); }
        @media (max-width: 900px) { .cards { grid-template-columns: 1fr; max-width: 420px; margin: 0 auto; } }

        /* PROCESS */
        .process { padding: 100px 1.5rem; max-width: 1200px; margin: 0 auto; }
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-top: 3rem; }
        .step { text-align: center; position: relative; }
        .step-num { width: 48px; height: 48px; background: var(--neon); color: #000; font-weight: 900; font-size: 1.1rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 0 20px rgba(254,254,1,0.2); }
        .step h4 { font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem; }
        .step p { color: var(--gray); font-size: 0.85rem; line-height: 1.6; }
        @media (max-width: 768px) { .steps { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 480px) { .steps { grid-template-columns: 1fr; } }

        /* FOOTER */
        .footer { padding: 3rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); text-align: center; color: var(--gray); font-size: 0.85rem; }

        /* WHATSAPP FAB */
        .wa-fab { position: fixed; bottom: 28px; right: 28px; z-index: 200; width: 60px; height: 60px; background: var(--neon); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 30px rgba(254,254,1,0.4); transition: all 0.3s; }
        .wa-fab:hover { transform: scale(1.12); box-shadow: 0 0 50px rgba(254,254,1,0.6); }
        .wa-fab svg { width: 32px; height: 32px; fill: #000; }

        /* GRID BG */
        .bg-grid { background-image: radial-gradient(rgba(254,254,1,0.04) 1px, transparent 1px); background-size: 28px 28px; }
    </style>
</head>
<body class="bg-grid">

<!-- NAV -->
<nav class="nav">
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <img src="/images/logo.jpg" alt="Logo" style="height: 44px; width: auto; border-radius: 6px; object-fit: contain;">
            <!-- K-Clean -->
        </a>
        <div class="nav-links">
            <a href="#services">Layanan</a>
            <a href="#process">Proses</a>
            <a href="{{ route('track') }}">Lacak Pesanan</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-cta">Dashboard</a>
            @endauth
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <button class="theme-toggle-btn" onclick="toggleTheme()" title="Toggle Theme">
                <svg class="sun-icon" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <svg class="moon-icon" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
            <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('active')">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
            </button>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">🔥 #1 Shoe Laundry di Bandung</div>
        <h1>Perawatan Sepatu <span>Premium di Bandung</span></h1>
        <p class="hero-desc">Kembalikan tampilan sepatu favorit Anda seperti baru. Tim ahli dengan teknologi pembersihan terkini — cepat, bersih, dan terjamin.</p>
        <div class="hero-buttons">
            <a href="https://wa.me/682116878685?text={{ urlencode('Halo K-Clean, saya mau booking layanan cuci sepatu.') }}" target="_blank" class="btn-primary">BOOKING SEKARANG</a>
            <a href="#services" class="btn-outline">Lihat Layanan</a>
        </div>
        <div class="hero-stats">
            <div><div class="hero-stat-num">500+</div><div class="hero-stat-label">Pelanggan Puas</div></div>
            <div><div class="hero-stat-num">3K+</div><div class="hero-stat-label">Sepatu Dicuci</div></div>
            <div><div class="hero-stat-num">4.9★</div><div class="hero-stat-label">Rating</div></div>
        </div>
    </div>
    <div class="hero-visual">
        <div class="slider-wrapper" id="baSlider">
            <img src="/images/sneaker-clean.png" alt="After — Sepatu Bersih" class="slider-img">
            <img src="/images/sneaker-dirty.png" alt="Before — Sepatu Kotor" class="slider-img slider-before-img" id="beforeWrap">
            
            <div class="slider-divider" id="sliderDivider"></div>
            <div class="slider-handle" id="sliderHandle">↔</div>
            <div class="slider-label before">Before</div>
            <div class="slider-label after">After</div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section id="services" class="services">
    <div class="services-inner">
        <div class="section-label">Layanan Kami</div>
        <h2 class="section-title">Solusi Lengkap Perawatan Sepatu</h2>
        <p class="section-desc">Dari pembersihan rutin hingga restorasi total, kami punya layanan untuk setiap kebutuhan sneakers Anda.</p>
        <div class="cards">
            <div class="card">
                <div class="card-icon"><svg viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg></div>
                <h3>Unyellowing</h3>
                <p>Proses khusus menghilangkan noda kuning pada midsole yang teroksidasi. Warna putih cemerlang kembali.</p>
                <div class="card-price">Mulai Rp 75.000</div>
                <a href="https://wa.me/682116878685?text={{ urlencode('Halo K-Clean, saya mau booking layanan Unyellowing.') }}" target="_blank" class="card-link">Book Sekarang <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
            </div>
            <div class="card featured">
                <div class="card-badge">Paling Populer</div>
                <div class="card-icon" style="background:var(--neon)"><svg viewBox="0 0 24 24" style="stroke:#000"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                <h3>Deep Clean</h3>
                <p>Pencucian menyeluruh untuk Upper, Midsole, Outsole, dan Insole. Cocok untuk perawatan rutin bulanan.</p>
                <div class="card-price">Mulai Rp 50.000</div>
                <a href="https://wa.me/682116878685?text={{ urlencode('Halo K-Clean, saya mau booking layanan Deep Clean.') }}" target="_blank" class="card-link">Book Sekarang <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
            </div>
            <div class="card">
                <div class="card-icon"><svg viewBox="0 0 24 24"><path d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42"></svg></div>
                <h3>Repaint</h3>
                <p>Pengecatan ulang sepatu pudar atau kusam. Menggunakan cat khusus yang tahan lama dan fleksibel.</p>
                <div class="card-price">Mulai Rp 150.000</div>
                <a href="https://wa.me/682116878685?text={{ urlencode('Halo K-Clean, saya mau booking layanan Repaint.') }}" target="_blank" class="card-link">Book Sekarang <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
            </div>
        </div>
    </div>
</section>

<!-- PROCESS -->
<section id="process" class="process">
    <div class="section-label">Cara Kerja</div>
    <h2 class="section-title">Proses Mudah & Transparan</h2>
    <p class="section-desc">Hanya 4 langkah sederhana untuk mendapatkan sepatu bersih seperti baru.</p>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><h4>Booking Online</h4><p>Daftar & pilih layanan yang Anda butuhkan lewat website.</p></div>
        <div class="step"><div class="step-num">2</div><h4>Drop Off / Pickup</h4><p>Antar sepatu ke workshop kami atau gunakan layanan jemput.</p></div>
        <div class="step"><div class="step-num">3</div><h4>Proses Cuci</h4><p>Tim ahli mengerjakan sepatu Anda dengan teknik premium.</p></div>
        <div class="step"><div class="step-num">4</div><h4>Selesai!</h4><p>Sepatu bersih siap diambil. Kami kirim notifikasi otomatis.</p></div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© {{ date('Y') }} K-Clean Bandung. All Rights Reserved.</p>
    <p style="margin-top: 8px; font-size: 0.65rem; opacity: 0.15;"><a href="{{ route('login') }}" style="color: inherit; text-decoration: none;">Staff</a></p>
</footer>

<!-- WHATSAPP FAB -->
<a href="https://wa.me/682116878685" target="_blank" class="wa-fab" title="Chat via WhatsApp">
    <svg viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
</a>

<!-- SLIDER JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('baSlider');
    const before = document.getElementById('beforeWrap');
    const handle = document.getElementById('sliderHandle');
    if (!slider) return;

    const move = (x) => {
        const rect = slider.getBoundingClientRect();
        let pct = ((x - rect.left) / rect.width) * 100;
        pct = Math.max(0, Math.min(100, pct));
        before.style.clipPath = `inset(0 ${100 - pct}% 0 0)`;
        document.getElementById('sliderDivider').style.left = pct + '%';
        handle.style.left = pct + '%';
        
        const labelBefore = document.querySelector('.slider-label.before');
        const labelAfter = document.querySelector('.slider-label.after');
        if (labelBefore) labelBefore.style.opacity = pct > 10 ? '1' : '0';
        if (labelAfter) labelAfter.style.opacity = pct < 90 ? '1' : '0';
    };

    let dragging = false;
    slider.addEventListener('mousedown', (e) => { dragging = true; move(e.clientX); });
    window.addEventListener('mouseup', () => { dragging = false; });
    window.addEventListener('mousemove', (e) => { if (dragging) move(e.clientX); });
    slider.addEventListener('touchstart', (e) => { dragging = true; move(e.touches[0].clientX); }, {passive:true});
    window.addEventListener('touchend', () => { dragging = false; });
    window.addEventListener('touchmove', (e) => { if (dragging) move(e.touches[0].clientX); }, {passive:true});

    // Mobile nav responsive fix
    const mq = window.matchMedia('(max-width: 768px)');
    const links = document.querySelector('.nav-links');
    const fixNav = () => { if (!mq.matches) links.classList.remove('active'); };
    mq.addEventListener('change', fixNav);
    
    // Close mobile nav when clicking a link
    links.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (mq.matches) links.classList.remove('active');
        });
    });
});
</script>
</body>
</html>
