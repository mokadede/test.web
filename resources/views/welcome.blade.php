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
            --hero-overlay: rgba(8, 8, 8, 0.9);
        }
        html:not(.dark) {
            --neon: #FEFE01;
            --dark: #f0f0f0;
            --surface: #ffffff;
            --surface2: #e0e0e0;
            --gray: #555555;
            --white: #111111;
            --hero-overlay: rgba(255, 255, 255, 0.9);
        }
        /* Fix readability for neon yellow in day mode */
        html:not(.dark) .nav-logo,
        html:not(.dark) .hero h1 span,
        html:not(.dark) .hero-badge,
        html:not(.dark) .hero-stat-num,
        html:not(.dark) .section-label,
        html:not(.dark) .card:not(:hover) .card-icon svg:not([style*="stroke:#000"]),
        html:not(.dark) .card-price,
        html:not(.dark) .footer-col h4,
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
        .nav-links a.nav-cta { padding: 10px 24px !important; background: #FEFE01 !important; color: #000000 !important; font-weight: 900 !important; border-radius: 6px; font-size: 0.8rem; letter-spacing: 1px; transition: all 0.3s; text-decoration: none !important; text-transform: uppercase; text-shadow: none !important; }
        .nav-links a.nav-cta:hover { background: #ffffff !important; color: #000000 !important; box-shadow: 0 0 20px rgba(254,254,1,0.6); }
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
        .hero { 
            position: relative;
            padding: 140px 1.5rem 80px; 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 4rem; 
            align-items: center; 
            min-height: 100vh;
            width: 100%;
            max-width: none;
            justify-content: center;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/images/bg.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: grayscale(100%);
            z-index: 0;
        }
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--hero-overlay);
            z-index: 1;
            transition: background 0.3s ease;
        }
        .hero-content { position: relative; z-index: 2; width: 100%; max-width: 580px; justify-self: end; }
        .hero-visual { position: relative; z-index: 2; width: 100%; max-width: 580px; justify-self: start; }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; gap: 3rem; padding: 120px 1.5rem 60px; }
            .hero-content, .hero-visual { max-width: 500px; justify-self: center; text-align: center; }
            .hero-desc { margin: 1.5rem auto 2.5rem; }
            .hero-buttons { justify-content: center; }
            .hero-stats { justify-content: center; }
        }

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

        /* ARTICLES CAROUSEL */
        .articles-container { position: relative; width: 100%; margin-top: 2rem; }
        .articles-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; transition: all 0.5s ease; }
        .articles-grid.is-carousel { 
            display: flex; 
            overflow-x: auto; 
            scroll-snap-type: x mandatory; 
            gap: 1.5rem; 
            padding: 1rem 0.5rem 2.5rem; 
            scrollbar-width: none; 
            -ms-overflow-style: none; 
            -webkit-overflow-scrolling: touch; 
        }
        .articles-grid.is-carousel::-webkit-scrollbar { display: none; }
        .articles-grid.is-carousel .card { 
            flex: 0 0 calc(33.333% - 1rem); 
            min-width: 300px; 
            scroll-snap-align: start; 
        }
        @media (max-width: 1024px) {
            .articles-grid.is-carousel .card { flex: 0 0 calc(50% - 0.75rem); }
            .articles-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .articles-grid.is-carousel .card { flex: 0 0 85%; }
            .articles-grid { grid-template-columns: 1fr; }
        }
        .carousel-indicator { display: flex; justify-content: center; gap: 0.75rem; margin-top: -0.5rem; }
        .indicator-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--surface2); transition: all 0.3s; }
        .indicator-dot.active { background: var(--neon); transform: scale(1.5); box-shadow: 0 0 10px var(--neon); }

        .carousel-btn { 
            position: absolute; 
            top: 50%; 
            transform: translateY(-50%); 
            width: 44px; 
            height: 44px; 
            background: var(--surface2); 
            border: 1px solid rgba(255,255,255,0.1); 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: var(--white); 
            cursor: pointer; 
            z-index: 10; 
            transition: all 0.3s;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
            opacity: 0.8;
        }
        .carousel-btn:hover { opacity: 1; background: var(--neon); color: #000; box-shadow: 0 0 20px var(--neon); transform: translateY(-50%) scale(1.1); }
        .carousel-btn svg { width: 24px; height: 24px; fill: none; stroke: currentColor; stroke-width: 3; }
        .carousel-btn.prev { left: -22px; }
        .carousel-btn.next { right: -22px; }
        
        @media (max-width: 1300px) {
            .carousel-btn.prev { left: 10px; }
            .carousel-btn.next { right: 10px; }
        }
        @media (max-width: 768px) {
            .carousel-btn { display: none; }
        }


        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; gap: 2.5rem; padding-top: 110px; min-height: auto; }
            .hero-content { order: 2; text-align: center; }
            .hero-content .hero-desc { margin-left: auto; margin-right: auto; }
            .hero-buttons { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-visual { order: 1; }
        }

        /* Smooth transitions for theme switching */
        body, nav, .card, .footer, section, h1, h2, h3, p, a, button, .hero::after, .card-icon, .nav-inner, .workshop-card {
            transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease, opacity 0.4s ease;
        }

        /* SERVICES */
        .services { padding: 100px 1.5rem; background: var(--surface); }
        .services-inner { max-width: 1200px; margin: 0 auto; }
        .section-label { text-align: center; font-size: 0.75rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: var(--neon); margin-bottom: 0.75rem; }
        .section-title { text-align: center; font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1rem; }
        .section-desc { text-align: center; color: var(--gray); max-width: 550px; margin: 0 auto 3.5rem; line-height: 1.7; }
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .card { background: var(--surface2); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 2.5rem 2rem; position: relative; overflow: hidden; }
        .card:hover { 
            border-color: var(--neon) !important; 
            transform: translateY(-10px) !important; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.4); 
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease !important;
        }
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
        .footer { padding: 5rem 1.5rem 2rem; background: #080808; color: #fff; border-top: 1px solid rgba(255,255,255,0.05); }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1.5fr 1.5fr; gap: 4rem; text-align: left; }
        .footer-col h4 { font-size: 0.85rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--neon); margin-bottom: 1.5rem; }
        .footer-col h2 { font-size: 1.8rem; font-weight: 900; letter-spacing: -0.5px; margin-bottom: 1rem; text-transform: uppercase; }
        .footer-col p { color: var(--gray); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; font-size: 0.9rem; color: var(--gray); transition: color 0.3s; }
        .footer-links li a:hover { color: var(--neon); }
        .footer-social { display: flex; gap: 12px; margin-top: 20px; }
        .footer-social a { width: 38px; height: 38px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; color: #fff; }
        .footer-social a:hover { background: var(--neon); color: #000; transform: translateY(-3px); }
        .footer-social svg { width: 20px; height: 20px; }
        .footer-contact { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; font-size: 0.9rem; color: var(--gray); }
        .footer-contact svg { width: 20px; height: 20px; color: var(--neon); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.05); margin-top: 5rem; padding-top: 2rem; text-align: center; color: var(--gray); font-size: 0.8rem; letter-spacing: 0.5px; }

        @media (max-width: 1024px) { .footer-inner { grid-template-columns: 1fr 1fr; gap: 3rem; } }
        @media (max-width: 640px) { .footer-inner { grid-template-columns: 1fr; gap: 2.5rem; } }

        /* WHATSAPP FAB */
        .wa-fab { position: fixed; bottom: 28px; right: 28px; z-index: 200; width: 60px; height: 60px; background: var(--neon); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 30px rgba(254,254,1,0.4); transition: all 0.3s; }
        .wa-fab:hover { transform: scale(1.12); box-shadow: 0 0 50px rgba(254,254,1,0.6); }
        .wa-fab svg { width: 32px; height: 32px; fill: #000; }

        /* WORKSHOPS */
        .workshops { padding: 100px 1.5rem; background: var(--surface); }
        .workshop-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2.5rem; max-width: 1000px; margin: 3rem auto 0; }
        .workshop-card { background: var(--surface2); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 2.5rem; transition: all 0.4s; }
        .workshop-card:hover { transform: translateY(-8px); border-color: var(--neon); box-shadow: 0 15px 40px rgba(0,0,0,0.4); }
        .workshop-img { width: 100%; aspect-ratio: 16/9; background: rgba(255,255,255,0.03); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.05); }
        .workshop-img svg { width: 64px; height: 64px; color: var(--gray); opacity: 0.2; }
        .workshop-card h3 { font-size: 1.3rem; font-weight: 800; margin-bottom: 0.75rem; text-transform: uppercase; }
        .workshop-card p { color: var(--gray); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .workshop-info { border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem; display: flex; gap: 12px; align-items: center; }
        .workshop-info svg { width: 20px; height: 20px; color: var(--neon); }
        .workshop-time { font-size: 0.85rem; font-weight: 700; line-height: 1.4; }
        .workshop-time span { display: block; font-size: 0.75rem; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }

        @media (max-width: 768px) {
            .workshop-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        }

        /* GRID BG */
        .bg-grid { background-image: radial-gradient(rgba(254,254,1,0.04) 1px, transparent 1px); background-size: 28px 28px; }

        /* SCROLL REVEAL */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            will-change: transform, opacity;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 1s cubic-bezier(0.2, 1, 0.3, 1), transform 1s cubic-bezier(0.2, 1, 0.3, 1);
        }

        /* Stagger delays */
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .delay-4 { transition-delay: 0.4s; }

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
            <a href="#">Beranda</a>
            <a href="#services">Layanan</a>
            <a href="#articles">Artikel</a>
            <a href="#process">Proses</a>
            <a href="#workshops">Lokasi</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-cta" style="color: #000 !important; background: #FEFE01 !important; font-weight: 900 !important;">Dashboard</a>
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
        <div class="hero-badge">🔥 #1 Shoe Laundry di Bandung Sejak 2019</div>
        <h1>Perawatan Sepatu <span>Premium di Bandung</span></h1>
        <p class="hero-desc">Kembalikan tampilan sepatu favorit Anda seperti baru. Tim ahli dengan teknologi pembersihan terkini — cepat, bersih, dan terjamin.</p>
        <div class="hero-buttons">
            <a href="https://wa.me/682116878685" target="_blank" class="btn-primary">BOOKING SEKARANG</a>
            <a href="#services" class="btn-outline">Lihat Layanan</a>
        </div>
        <div class="hero-stats">
            <div><div class="hero-stat-num" data-target="500" data-suffix="+">0</div><div class="hero-stat-label">Pelanggan Puas</div></div>
            <div><div class="hero-stat-num" data-target="3" data-suffix="K+">0</div><div class="hero-stat-label">Sepatu Dicuci</div></div>
            <div><div class="hero-stat-num" data-target="4.9" data-suffix="★" data-decimal="true">0</div><div class="hero-stat-label">Rating</div></div>
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
 
<!-- WHY CHOOSE US -->
<section id="why-us" class="process" style="padding-top: 100px; padding-bottom: 50px;">
    <div class="services-inner">
        <div class="section-label">Mengapa Memilih Kami</div>
        <h2 class="section-title">MENGAPA MEMILIH KAMI</h2>
        <p class="section-desc">Standar operasional ketat untuk memastikan kepuasan maksimal di setiap layanan.</p>
        
        <div class="cards" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
            <!-- 1. Gratis Antar Jemput (Detailed Courier Icon) -->
            <div class="card" style="text-align: left;">
                <div style="width: 56px; height: 56px; background: #000; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                    <svg fill="var(--neon)" width="32" height="32" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" transform="matrix(-1, 0, 0, 1, 0, 0)">
                        <g id="SVGRepo_iconCarrier">
                            <path d="M12.37 7.53a3.67 3.67 0 0 0-2.5 1H7.16a.65.65 0 0 1-.38-.13L5 7V4h2V2.75H4.1a2.91 2.91 0 0 0-.33-.37 2.34 2.34 0 0 0-.77-.5 2.64 2.64 0 0 0-1-.18V4h1.75v3.16a.62.62 0 0 1-.09.32L2.19 9.93A2.38 2.38 0 0 0 0 12.2a2.41 2.41 0 0 0 2.5 2.3A2.41 2.41 0 0 0 5 12.2h11V11a3.54 3.54 0 0 0-3.63-3.47zm0 1.25A2.29 2.29 0 0 1 14.75 11H10a2.29 2.29 0 0 1 2.37-2.22zM2.5 13.25a1.16 1.16 0 0 1-1.25-1.05 1.16 1.16 0 0 1 1.25-1 1.16 1.16 0 0 1 1.25 1 1.16 1.16 0 0 1-1.25 1.05zM4.59 11a2.38 2.38 0 0 0-1.06-.83L4.62 8.3 6 9.36a1.9 1.9 0 0 0 1.13.38H9A3.22 3.22 0 0 0 8.75 11z"></path>
                            <path d="M14.75 1.5H11a1.25 1.25 0 0 0-1.3 1.25v3A1.25 1.25 0 0 0 11 7h3.8A1.25 1.25 0 0 0 16 5.75v-3a1.25 1.25 0 0 0-1.25-1.25zm0 4.25H11v-3h3.8zm-2.38 7.5a1.17 1.17 0 0 1-1.25-1.05H9.87a2.41 2.41 0 0 0 2.5 2.3 2.41 2.41 0 0 0 2.5-2.3h-1.25a1.16 1.16 0 0 1-1.25 1.05z"></path>
                        </g>
                    </svg>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem; color: var(--white);">Gratis Antar Jemput</h3>
                <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7;">Layanan logistik mandiri untuk kemudahan pengiriman dan pengambilan sepatu Anda.</p>
            </div>

            <!-- 2. Garansi -->
            <div class="card" style="text-align: left;">
                <div style="width: 56px; height: 56px; background: #000; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--neon)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem; color: var(--white);">Garansi Pengerjaan</h3>
                <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7;">Setiap pengerjaan dilindungi garansi mutu. Kami bertanggung jawab penuh atas hasil akhir.</p>
            </div>
            
            <!-- 3. Konsultasi -->
            <div class="card" style="text-align: left;">
                <div style="width: 56px; height: 56px; background: #000; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                    <svg viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="var(--neon)" width="32" height="32">
                        <g id="SVGRepo_iconCarrier">
                            <path d="M565.370023 772.592126c-57.039628 44.171437-100.880349 6.115667-105.80831 1.507159-8.99688-8.535108-23.163358-8.270946-31.763994 0.660406-8.644664 8.952853-8.381525 23.207385 0.572352 31.852048 15.26613 14.727567 45.315117 32.094709 82.183179 32.09471 25.143552 0 53.43248-8.062073 82.402291-30.499496 9.833395-7.621802 11.636457-21.766778 4.025917-31.599149-7.634089-9.834419-21.800566-11.627242-31.611435-4.015678zM353.90801 534.644248c-18.654167 0-33.788216 15.122786-33.788215 33.788216v45.050954c0 18.664406 15.134049 33.788216 33.788215 33.788216s33.788216-15.12381 33.788216-33.788216v-45.050954c0-18.664406-15.134049-33.788216-33.788216-33.788216zM646.739213 534.644248c-18.654167 0-33.788216 15.122786-33.788216 33.788216v45.050954c0 18.664406 15.134049 33.788216 33.788216 33.788216s33.788216-15.12381 33.788216-33.788216v-45.050954c0-18.664406-15.134049-33.788216-33.788216-33.788216z" fill="var(--neon)"></path>
                            <path d="M871.993985 455.805078c0-191.994881-168.412754-360.407634-360.407635-360.407635s-360.407634 168.412754-360.407634 360.407635c-24.777001 0-45.050954 20.271906-45.050954 45.050954v157.67834c0 24.777001 20.273953 45.050954 45.050954 45.050954h22.525477c24.777001 0 45.050954-20.273953 45.050954-45.050954v-90.101908a23.549362 23.549362 0 0 0 1.231734-0.032765c175.523637-9.523157 298.44619-137.44227 358.872831-217.791671 37.21516 32.154095 136.422481 110.490537 232.969747 125.673732-4.651511 7.084263-7.411906 15.512887-7.411906 24.574272v157.67834c0 10.760011 3.978818 20.536068 10.333051 28.30531-11.008815 82.724815-82.915257 149.847665-214.276673 199.74774-3.798615-14.498216-16.92892-25.323756-32.573888-25.323756h-112.627386c-18.583519 0-33.788216 15.204697-33.788215 33.788216s15.204697 33.788216 33.788215 33.788216h112.627386c12.477067 0 23.304654-6.931704 29.156159-17.058953 140.968532-51.288464 221.16435-122.013343 238.379957-210.670549 4.444686 1.477467 9.10746 2.474731 14.031325 2.47473h22.525477c24.777001 0 45.050954-20.273953 45.050954-45.050954v-157.67834c0.001024-24.779049-20.272929-45.050954-45.04993-45.050954z m-259.483258-199.957637c-10.910522-5.983586-24.615227-1.980194-30.576288 8.941591-1.339242 2.44811-136.912922 245.37719-363.179292 258.517734v-22.450734c0-17.009807-9.66343-31.715872-23.669157-39.378629 0.494537-1.856304 1.14368-3.659366 1.14368-5.672325 0-167.995009 147.361671-315.35668 315.35668-315.35668 160.277985 0 301.446174 134.190411 314.109588 292.405266-89.171197-10.391412-189.215032-91.837394-220.761963-119.358407 9.823156-15.07364 15.430976-25.109764 16.508103-27.081767 5.983586-10.911546 1.97917-24.593726-8.931351-30.566049z" fill="var(--neon)"></path>
                        </g>
                    </svg>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem; color: var(--white);">Konsultasi Gratis</h3>
                <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7;">Tim ahli kami siap memberikan analisa dan solusi terbaik sebelum pengerjaan dimulai.</p>
            </div>

            <!-- 4. Jaminan Kualitas -->
            <div class="card" style="text-align: left;">
                <div style="width: 56px; height: 56px; background: #000; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--neon)" stroke-width="2"><path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem; color: var(--white);">Jaminan Kualitas</h3>
                <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7;">Menggunakan produk pembersih dan teknik standar tinggi untuk hasil optimal.</p>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section id="services" class="services" style="padding-top: 50px;">
    <div class="services-inner">
        <div style="text-align: center; margin-bottom: 4.5rem;">
            <div class="section-label" style="margin-bottom: 1rem;">Layanan Kami</div>
            <h2 class="section-title" style="font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; margin-bottom: 1.5rem; color: var(--white);">Solusi Lengkap Perawatan Sepatu</h2>
            <p style="max-width: 650px; margin: 0 auto; color: var(--gray); font-size: 1.1rem; line-height: 1.7;">Dari pembersihan rutin hingga restorasi total, kami punya layanan untuk setiap kebutuhan sneakers Anda.</p>
        </div>
        
        <div class="cards" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            <!-- Fast Cleaning -->
            <div class="card" style="padding: 0; overflow: hidden; background: var(--surface2); border: none;">
                <div style="width: 100%; height: 220px; overflow: hidden;">
                    <img src="/images/fast-cleaning.png" alt="Fast Cleaning" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                </div>
                <div style="padding: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 900; text-transform: uppercase; margin-bottom: 1rem;">Fast Cleaning</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7; margin-bottom: 2rem;">Pembersihan instan untuk sepatu sehari-hari Anda dengan hasil cepat...</p>
                    <a href="https://wa.me/682116878685" target="_blank" style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--white); display: flex; align-items: center; gap: 8px;">
                        Pesan Sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            
            <!-- Deep Cleaning -->
            <div class="card" style="padding: 0; overflow: hidden; background: var(--surface2); border: none;">
                <div style="width: 100%; height: 220px; overflow: hidden;">
                    <img src="/images/deep-cleaning.png" alt="Deep Cleaning" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                </div>
                <div style="padding: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 900; text-transform: uppercase; margin-bottom: 1rem;">Deep Cleaning</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7; margin-bottom: 2rem;">Pembersihan mendalam untuk noda membandel dan mengembalikan warn...</p>
                    <a href="https://wa.me/682116878685" target="_blank" style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--white); display: flex; align-items: center; gap: 8px;">
                        Pesan Sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Premium Treatment -->
            <div class="card" style="padding: 0; overflow: hidden; background: var(--surface2); border: none;">
                <div style="width: 100%; height: 220px; overflow: hidden;">
                    <img src="/images/premium-treatment.png" alt="Premium Treatment" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                </div>
                <div style="padding: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 900; text-transform: uppercase; margin-bottom: 1rem;">Premium Treatment</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7; margin-bottom: 2rem;">Perawatan eksklusif menggunakan material premium untuk sepatu kulit...</p>
                    <a href="https://wa.me/682116878685" target="_blank" style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--white); display: flex; align-items: center; gap: 8px;">
                        Pesan Sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Unyellowing -->
            <div class="card" style="padding: 0; overflow: hidden; background: var(--surface2); border: none;">
                <div style="width: 100%; height: 220px; overflow: hidden;">
                    <img src="/images/unyellowing.png" alt="Unyellowing" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                </div>
                <div style="padding: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 900; text-transform: uppercase; margin-bottom: 1rem;">Unyellowing</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7; margin-bottom: 2rem;">Proses menghilangkan warna kuning pada midsole agar kembali putih bersih.</p>
                    <a href="https://wa.me/682116878685" target="_blank" style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--white); display: flex; align-items: center; gap: 8px;">
                        Pesan Sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Repaint -->
            <div class="card" style="padding: 0; overflow: hidden; background: var(--surface2); border: none;">
                <div style="width: 100%; height: 220px; overflow: hidden;">
                    <img src="/images/repaint.png" alt="Repaint" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                </div>
                <div style="padding: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 900; text-transform: uppercase; margin-bottom: 1rem;">Repaint</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7; margin-bottom: 2rem;">Pengecatan ulang sepatu untuk mengembalikan warna asli atau custo...</p>
                    <a href="https://wa.me/682116878685" target="_blank" style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--white); display: flex; align-items: center; gap: 8px;">
                        Pesan Sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Reglue -->
            <div class="card" style="padding: 0; overflow: hidden; background: var(--surface2); border: none;">
                <div style="width: 100%; height: 220px; overflow: hidden;">
                    <img src="/images/reglue.png" alt="Reglue" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                </div>
                <div style="padding: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 900; text-transform: uppercase; margin-bottom: 1rem;">Reglue</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.7; margin-bottom: 2rem;">Pengeleman ulang sol sepatu yang terlepas dengan lem khusus sepatu.</p>
                    <a href="https://wa.me/682116878685" target="_blank" style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--white); display: flex; align-items: center; gap: 8px;">
                        Pesan Sekarang <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ARTICLES -->
<section id="articles" class="services">
    <div class="services-inner">
        <div class="section-label">Berita & Artikel</div>
        <h2 class="section-title">Artikel Terbaru Kami</h2>
        <p class="section-desc">Dapatkan tips perawatan sepatu dan informasi terbaru seputar dunia fashion & sneakers.</p>
        
        <div class="articles-container">
            <div id="articlesGrid" class="articles-grid @if(count($articles) > 3) is-carousel @endif">
                @forelse($articles as $article)
                <div class="card article-card" style="display: flex; flex-direction: column;">
                    @if($article->image)
                        <div style="width: 100%; height: 200px; overflow: hidden; border-radius: 12px; margin-bottom: 1.5rem; shrink-0;">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @else
                        <div class="card-icon"><svg viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"/></svg></div>
                    @endif
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ $article->title }}</h3>
                        <p style="flex: 1;">{{ $article->excerpt }}</p>
                        <a href="{{ route('articles.show', $article->slug) }}" class="card-link" style="margin-top: auto; display: flex; align-items: center; gap: 8px; font-weight: 700; color: var(--neon); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                            Baca Selengkapnya 
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--gray);">
                    Belum ada artikel yang diterbitkan.
                </div>
                @endforelse
            </div>

            @if(count($articles) > 3)
            <button class="carousel-btn prev" id="prevBtn" title="Sebelumnya">
                <svg viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button class="carousel-btn next" id="nextBtn" title="Berikutnya">
                <svg viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="carousel-indicator" id="carouselIndicator">
                @foreach($articles as $index => $article)
                    <div class="indicator-dot {{ $index === 0 ? 'active' : '' }}"></div>
                @endforeach
            </div>
            @endif

            @if($allArticlesCount > 6)
            <div style="text-align: center; margin-top: 3rem;">
                <a href="{{ route('articles.index') }}" class="btn-outline" style="padding: 12px 32px; font-size: 0.85rem;">
                    Lihat Semua Artikel
                </a>
            </div>
            @endif
        </div>

    </div>
</section>

<!-- PROCESS -->
<section id="process" class="process">
    <div class="section-label">Cara Kerja</div>
    <h2 class="section-title">Proses Mudah & Transparan</h2>
    <p class="section-desc">Hanya 4 langkah sederhana untuk mendapatkan sepatu bersih seperti baru.</p>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><h4>Booking Online</h4><p>Daftar & pilih layanan yang Anda butuhkan lewat WhatsApp.</p></div>
        <div class="step"><div class="step-num">2</div><h4>Drop Off / Pickup</h4><p>Antar sepatu ke workshop kami atau gunakan layanan jemput.</p></div>
        <div class="step"><div class="step-num">3</div><h4>Proses Cuci</h4><p>Tim ahli mengerjakan sepatu Anda dengan teknik dan sabun premium.</p></div>
        <div class="step"><div class="step-num">4</div><h4>Selesai!</h4><p>Sepatu bersih siap diambil.</p></div>
    </div>
</section>

<!-- WORKSHOPS -->
<section id="workshops" class="workshops">
    <div class="section-label">Lokasi Workshop</div>
    <h2 class="section-title">Kunjungi Workshop Kami</h2>
    <p class="section-desc">Temukan fasilitas modern kami di berbagai kota utama.</p>

    <div class="workshop-grid">
        <!-- Card 1 -->
        <div class="workshop-card">
            <div class="workshop-img">
                <img src="{{ asset('images/kolmas.webp') }}" alt="Workshop Cimahi Pusat" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
            </div>
            <h3>Cimahi</h3>
            <p>Jalan Kolmas No. 28 Kec. Cimahi, RT 02, Cimahi, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40525</p>
            <div class="workshop-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="workshop-time">
                    <span>Senin - Minggu:</span>
                    09.00 - 20.00 WIB
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="workshop-card">
            <div class="workshop-img">
                <img src="{{ asset('images/cibeber.webp') }}" alt="Workshop Cimahi Selatan" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
            </div>
            <h3>Cimahi (Cibeber)</h3>
            <p>Kampus UNJANI, Jl. Ibu Ganirah belakang No.122, Cibeber, Kec. Cimahi Sel., Kota Cimahi, Jawa Barat 40531</p>
            <div class="workshop-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="workshop-time">
                    <span>Senin - Minggu:</span>
                    10.00 - 20.00 WIB
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="footer">
    <div class="footer-inner">
        <!-- Col 1 -->
        <div class="footer-col">
            <h2>Clean Kicks</h2>
            <p>Layanan perawatan sepatu profesional terpercaya di Indonesia. Kembalikan kilau sepatu Anda bersama kami.</p>
            <div class="footer-social">
                <a href="https://instagram.com" target="_blank" title="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                <a href="https://tiktok.com" target="_blank" title="TikTok"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg></a>
                <a href="https://wa.me/682116878685" target="_blank" title="WhatsApp"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.50002 12C3.50002 7.30558 7.3056 3.5 12 3.5C16.6944 3.5 20.5 7.30558 20.5 12C20.5 16.6944 16.6944 20.5 12 20.5C10.3278 20.5 8.77127 20.0182 7.45798 19.1861C7.21357 19.0313 6.91408 18.9899 6.63684 19.0726L3.75769 19.9319L4.84173 17.3953C4.96986 17.0955 4.94379 16.7521 4.77187 16.4751C3.9657 15.176 3.50002 13.6439 3.50002 12ZM12 1.5C6.20103 1.5 1.50002 6.20101 1.50002 12C1.50002 13.8381 1.97316 15.5683 2.80465 17.0727L1.08047 21.107C0.928048 21.4637 0.99561 21.8763 1.25382 22.1657C1.51203 22.4552 1.91432 22.5692 2.28599 22.4582L6.78541 21.1155C8.32245 21.9965 10.1037 22.5 12 22.5C17.799 22.5 22.5 17.799 22.5 12C22.5 6.20101 17.799 1.5 12 1.5ZM14.2925 14.1824L12.9783 15.1081C12.3628 14.7575 11.6823 14.2681 10.9997 13.5855C10.9997 13.5855 10.9997 13.5855 10.9997 13.5855C10.2901 12.8759 9.76402 12.1433 9.37612 11.4713L10.2113 10.7624C10.5697 10.4582 10.6678 9.94533 10.447 9.53028L9.38284 7.53028C9.23954 7.26097 8.98116 7.0718 8.68115 7.01654C8.38113 6.96129 8.07231 7.046 7.84247 7.24659L7.52696 7.52195C6.76823 8.18414 6.3195 9.2723 6.69141 10.3741C7.07698 11.5163 7.89983 13.314 9.58552 14.9997C11.3991 16.8133 13.2413 17.5275 14.3186 17.8049C15.1866 18.0283 16.008 17.7288 16.5868 17.2572L17.1783 16.7752C17.4313 16.5691 17.5678 16.2524 17.544 15.9269C17.5201 15.6014 17.3389 15.308 17.0585 15.1409L15.3802 14.1409C15.0412 13.939 14.6152 13.9552 14.2925 14.1824Z" fill="currentColor"></path> </g></svg></a>
                <a href="https://shopee.co.id" target="_blank" title="Shopee"><svg viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg" fill="none"><g id="SVGRepo_iconCarrier"><path fill="currentColor" d="m29.004 157.064 5.987-.399-5.987.399ZM22 52v-6a6 6 0 0 0-5.987 6.4L22 52Zm140.996 105.064-5.987-.399 5.987.399ZM170 52l5.987.4A6 6 0 0 0 170 46v6ZM34.991 156.665 27.987 51.601l-11.974.798 7.005 105.064 11.973-.798Zm133.991.798 7.005-105.064-11.974-.798-7.004 105.064 11.973.798Zm-11.973-.798a10 10 0 0 1-9.978 9.335v12c11.582 0 21.181-8.98 21.951-20.537l-11.973-.798Zm-133.991.798C23.788 169.02 33.387 178 44.968 178v-12a10 10 0 0 1-9.977-9.335l-11.973.798ZM74 48c0-12.15 9.85-22 22-22V14c-18.778 0-34 15.222-34 34h12Zm22-22c12.15 0 22 9.85 22 22h12c0-18.778-15.222-34-34-34v12ZM22 58h148V46H22v12Zm22.969 120H147.03v-12H44.969v12Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-width="12" d="M114 84H88c-7.732 0-14 6.268-14 14v0c0 7.732 6.268 14 14 14h4m-2 0h14c7.732 0 14 6.268 14 14v0c0 7.732-6.268 14-14 14H78"></path></g></svg></a>
            </div>
        </div>

        <!-- Col 2 -->
        <div class="footer-col">
            <h4>Layanan</h4>
            <ul class="footer-links">
                <li><a href="#services">Fast Cleaning</a></li>
                <li><a href="#services">Deep Cleaning</a></li>
                <li><a href="#services">Premium Treatment</a></li>
                <li><a href="#services">Unyellowing</a></li>
            </ul>
        </div>

        <!-- Col 3 -->
        <div class="footer-col">
            <h4>Workshop Utama</h4>
            <div style="margin-bottom: 20px;">
                <div style="font-weight: 700; font-size: 0.9rem; margin-bottom: 5px;">Cimahi</div>
                <div style="font-size: 0.85rem; color: var(--gray);">Jalan Kolmas No. 28 Kec. Cimahi, RT 02, Cimahi, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40525</div>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.9rem; margin-bottom: 5px;">Cimahi (Cibeber)</div>
                <div style="font-size: 0.85rem; color: var(--gray);">Kampus UNJANI, Jl. Ibu Ganirah belakang No.122, Cibeber, Kec. Cimahi Sel., Kota Cimahi, Jawa Barat 40531</div>
            </div>
        </div>

        <!-- Col 4 -->
        <div class="footer-col">
            <h4>Hubungi Kami</h4>
            <div class="footer-contact">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l2.29-2.29a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"></path></svg>
                0821-1687-8685
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 Clean Kicks Indonesia. All rights reserved.</p>
        <p style="margin-top: 8px; font-size: 0.65rem; opacity: 0.1;"><a href="{{ route('login') }}" style="color: inherit; text-decoration: none;">Staff</a></p>
    </div>
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
    // Articles Carousel Indicator
    const articlesGrid = document.getElementById('articlesGrid');
    const dots = document.querySelectorAll('#carouselIndicator .indicator-dot');
    
    if (articlesGrid && dots.length > 0) {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        const updateDotsVisibility = () => {
            const maxScroll = articlesGrid.scrollWidth - articlesGrid.clientWidth;
            const cardWidth = articlesGrid.querySelector('.article-card').offsetWidth + 24;
            dots.forEach((dot, i) => {
                if (i * cardWidth > maxScroll + 10) {
                    dot.style.display = 'none';
                } else {
                    dot.style.display = 'block';
                }
            });
        };

        updateDotsVisibility();
        window.addEventListener('resize', updateDotsVisibility);

        articlesGrid.addEventListener('scroll', () => {
            const scrollLeft = articlesGrid.scrollLeft;
            const cardWidth = articlesGrid.querySelector('.article-card').offsetWidth + 24; 
            const index = Math.round(scrollLeft / cardWidth);
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        });

        const scrollCarousel = (direction) => {
            const cardWidth = articlesGrid.querySelector('.article-card').offsetWidth + 24;
            articlesGrid.scrollBy({
                left: direction === 'next' ? cardWidth : -cardWidth,
                behavior: 'smooth'
            });
        };

        if (prevBtn) prevBtn.addEventListener('click', () => scrollCarousel('prev'));
        if (nextBtn) nextBtn.addEventListener('click', () => scrollCarousel('next'));

        // Click dots to scroll
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                const cardWidth = articlesGrid.querySelector('.article-card').offsetWidth + 24;
                articlesGrid.scrollTo({
                    left: i * cardWidth,
                    behavior: 'smooth'
                });
            });
        });
    }

        // Stats Counter Animation
        const animateStats = () => {
            const stats = document.querySelectorAll('.hero-stat-num');
            stats.forEach(stat => {
                const target = parseFloat(stat.getAttribute('data-target'));
                const suffix = stat.getAttribute('data-suffix') || '';
                const isDecimal = stat.getAttribute('data-decimal') === 'true';
                let count = 0;
                const duration = 2000;
                const startTime = performance.now();

                const update = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Ease out expo
                    const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                    const currentCount = easeProgress * target;

                    if (progress < 1) {
                        stat.innerText = (isDecimal ? currentCount.toFixed(1) : Math.floor(currentCount)) + suffix;
                        requestAnimationFrame(update);
                    } else {
                        stat.innerText = (isDecimal ? target.toFixed(1) : target) + suffix;
                    }
                };
                requestAnimationFrame(update);
            });
        };

        const statsSection = document.querySelector('.hero-stats');
        const statsObserver = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting) {
                animateStats();
                statsObserver.disconnect();
            }
        }, { threshold: 0.1 });
        statsObserver.observe(statsSection);

        // Scroll Reveal Animation
        const revealElements = document.querySelectorAll('.section-title, .section-desc, .section-label, .card, .step, .workshop-card, .footer-col, .hero-badge, .hero h1, .hero-desc, .hero-buttons, .hero-stats > div, .hero-visual');
        
        // Add classes for staggering
        document.querySelectorAll('.cards .card, .steps .step, .workshop-grid .workshop-card, .hero-stats > div').forEach((el, index) => {
            el.classList.add(`delay-${(index % 4) + 1}`);
        });

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // revealObserver.unobserve(entry.target); // Keep observing if you want it to re-animate
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        
        revealElements.forEach(el => {
            el.classList.add('reveal');
            revealObserver.observe(el);
        });
    });
</script>
</body>
</html>
