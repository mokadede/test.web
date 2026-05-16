<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} — K-Clean Bandung</title>
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
        html { scroll-behavior: smooth; overflow-x: hidden; width: 100%; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark); 
            color: var(--white); 
            line-height: 1.6;
            transition: background-color 0.5s ease, color 0.5s ease;
            overflow-x: hidden;
            width: 100%;
        }
        .nav { position: fixed; top: 0; width: 100%; z-index: 100; background: #080808; border-bottom: 1px solid rgba(128,128,128,0.2); }
        .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center; height: 72px; }
        .nav-logo { display: flex; align-items: center; gap: 10px; font-weight: 900; color: var(--neon); text-transform: uppercase; letter-spacing: 2px; }
        .nav-links { display: flex; align-items: center; gap: 2rem; font-size: 0.85rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; }
        .nav-links a { color: #f0f0f0 !important; transition: color 0.3s; text-decoration: none !important; }
        .nav-links a:hover { color: var(--neon) !important; }
        .nav-links a.nav-cta { padding: 10px 24px !important; background: #FEFE01 !important; color: #000000 !important; font-weight: 900 !important; border-radius: 6px; font-size: 0.8rem; letter-spacing: 1px; transition: all 0.3s; }
        .nav-links a.nav-cta:hover { background: #ffffff !important; box-shadow: 0 0 20px rgba(254,254,1,0.6); }
        
        .hamburger { display: none; background: none; border: none; cursor: pointer; }
        .hamburger svg { width: 28px; height: 28px; stroke: var(--neon); }
        .theme-toggle-btn { background: none; border: none; cursor: pointer; color: #f0f0f0 !important; display: flex; align-items: center; justify-content: center; position: relative; width: 24px; height: 24px; }
        .theme-toggle-btn svg { width: 24px; height: 24px; stroke: currentColor; fill: none; position: absolute; top: 0; left: 0; transition: opacity 0.5s ease, transform 0.5s ease; }
        html.dark .sun-icon { opacity: 1; transform: rotate(0deg); }
        html.dark .moon-icon { opacity: 0; transform: rotate(90deg); }
        html:not(.dark) .sun-icon { opacity: 0; transform: rotate(-90deg); }
        html:not(.dark) .moon-icon { opacity: 1; transform: rotate(0deg); }

        .article-container { max-width: 800px; margin: 120px auto 80px; padding: 0 1.5rem; }
        .article-meta { color: var(--neon); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem; }
        .article-title { font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 2rem; letter-spacing: -1px; }
        .article-image { width: 100%; border-radius: 20px; margin-bottom: 3rem; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .article-content { font-size: 1.15rem; color: var(--white); opacity: 0.9; line-height: 1.8; }
        .article-content p { margin-bottom: 2rem; }
        
        .btn-back { display: inline-flex; align-items: center; gap: 10px; color: var(--gray); font-weight: 600; font-size: 0.9rem; margin-bottom: 3rem; transition: color 0.3s; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; }
        .btn-back:hover { color: var(--neon); }
        
        /* FOOTER */
        .footer { padding: 5rem 1.5rem 2rem; background: #080808; color: #fff; border-top: 1px solid rgba(255,255,255,0.05); }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1.5fr 1.5fr; gap: 4rem; text-align: left; }
        .footer-col h4 { font-size: 0.85rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--neon); margin-bottom: 1.5rem; }
        .footer-col h2 { font-size: 1.8rem; font-weight: 900; letter-spacing: -0.5px; margin-bottom: 1rem; text-transform: uppercase; }
        .footer-col p { color: var(--gray); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; font-size: 0.9rem; color: var(--gray); transition: color 0.3s; }
        .footer-links li a { color: var(--gray) !important; text-decoration: none !important; transition: color 0.3s; }
        .footer-links li a:hover { color: var(--neon) !important; }
        .footer-social { display: flex; gap: 12px; margin-top: 20px; }
        .footer-social a { width: 38px; height: 38px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; color: #fff; text-decoration: none; }
        .footer-social a:hover { background: var(--neon); color: #000; transform: translateY(-3px); }
        .footer-social svg { width: 20px; height: 20px; }
        .footer-contact { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; font-size: 0.9rem; color: var(--gray); }
        .footer-contact svg { width: 20px; height: 20px; color: var(--neon); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.05); margin-top: 5rem; padding-top: 2rem; text-align: center; color: var(--gray); font-size: 0.8rem; letter-spacing: 0.5px; }

        .wa-fab { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background: #FEFE01; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(254,254,1,0.3); z-index: 1000; transition: all 0.3s; }
        .wa-fab:hover { transform: scale(1.1) rotate(10deg); box-shadow: 0 15px 40px rgba(254,254,1,0.5); }
        .wa-fab svg { width: 32px; height: 32px; fill: #000; }

        @media (max-width: 1024px) { .footer-inner { grid-template-columns: 1fr 1fr; gap: 3rem; } }
        @media (max-width: 768px) {
            .nav-links { display: none; position: absolute; top: 72px; left: 0; width: 100%; background: rgba(8,8,8,0.95); backdrop-filter: blur(12px); flex-direction: column; padding: 1.5rem; gap: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); }
            .nav-links.active { display: flex; }
            .hamburger { display: block; }
        }
        @media (max-width: 640px) { .footer-inner { grid-template-columns: 1fr; gap: 2.5rem; } }
        .bg-grid { background-image: radial-gradient(rgba(254,254,1,0.04) 1px, transparent 1px); background-size: 28px 28px; }

        /* PROCESS */
        .process { padding: 100px 1.5rem; max-width: 1200px; margin: 0 auto; text-align: center; }
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-top: 3rem; }
        .step { text-align: center; position: relative; }
        .step-num { width: 48px; height: 48px; background: var(--neon); color: #000; font-weight: 900; font-size: 1.1rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 0 20px rgba(254,254,1,0.2); }
        .step h4 { font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem; color: var(--white); }
        .step p { color: var(--gray); font-size: 0.85rem; line-height: 1.6; }
        @media (max-width: 768px) { .steps { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 480px) { .steps { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="bg-grid">
    <nav class="nav">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="/images/logo.jpg" alt="Logo" style="height: 44px; width: auto; border-radius: 6px; object-fit: contain;">
            </a>
            <div class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('home') }}#services">Layanan</a>
                <a href="{{ route('home') }}#process">Proses</a>
                <a href="{{ route('home') }}#articles">Artikel</a>
                <a href="{{ route('home') }}#workshops">Lokasi</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-cta">Dashboard</a>
                @endauth
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="theme-toggle-btn" onclick="toggleTheme()" title="Toggle Theme">
                    <svg class="sun-icon" viewBox="0 0 24 24" stroke-width="2"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="moon-icon" viewBox="0 0 24 24" stroke-width="2"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('active')">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="article-container">
        <a href="{{ route('articles.index') }}" class="btn-back">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Artikel
        </a>
        
        <div class="article-meta">{{ $article->created_at->format('d F Y') }}</div>
        <h1 class="article-title">{{ $article->title }}</h1>
        
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
        @endif
        
        <div class="article-content">
            <x-editorjs-renderer :data="$article->content" />
        </div>
    </main>

    <footer class="footer">
        <div class="footer-inner">
            <!-- Col 1 -->
            <div class="footer-col">
                <h2>Clean Kicks</h2>
                <p>Layanan perawatan sepatu profesional terpercaya di Indonesia. Kembalikan kilau sepatu Anda bersama kami.</p>
                <div class="footer-social">
                    <a href="https://instagram.com" target="_blank" title="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                    <a href="https://tiktok.com" target="_blank" title="TikTok"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg></a>
                    <a href="https://wa.me/682116878685" target="_blank" title="WhatsApp"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.50002 12C3.50002 7.30558 7.3056 3.5 12 3.5C16.6944 3.5 20.5 7.30558 20.5 12C20.5 16.6944 16.6944 20.5 12 20.5C10.3278 20.5 8.77127 20.0182 7.45798 19.1861C7.21357 19.0313 6.91408 18.9899 6.63684 19.0726L3.75769 19.9319L4.84173 17.3953C4.96986 17.0955 4.94379 16.7521 4.77187 16.4751C3.9657 15.176 3.50002 13.6439 3.50002 12ZM12 1.5C6.20103 1.5 1.50002 6.20101 1.50002 12C1.50002 13.8381 1.97316 15.5683 2.80465 17.0727L1.08047 21.107C0.928048 21.4637 0.99561 21.8763 1.25382 22.1657C1.51203 22.4552 1.91432 22.5692 2.28599 22.4582L6.78541 21.1155C8.32245 21.9965 10.1037 22.5 12 22.5C17.799 22.5 22.5 17.799 22.5 12C22.5 6.20101 17.799 1.5 12 1.5ZM14.2925 14.1824L12.9783 15.1081C12.3628 14.7575 11.6823 14.2681 10.9997 13.5855C10.9997 13.5855 10.9997 13.5855 10.9997 13.5855C10.2901 12.8759 9.76402 12.1433 9.37612 11.4713L10.2113 10.7624C10.5697 10.4582 10.6678 9.94533 10.447 9.53028L9.38284 7.53028C9.23954 7.26097 8.98116 7.0718 8.68115 7.01654C8.38113 6.96129 8.07231 7.046 7.84247 7.24659L7.52696 7.52195C6.76823 8.18414 6.3195 9.2723 6.69141 10.3741C7.07698 11.5163 7.89983 13.314 9.58552 14.9997C11.3991 16.8133 13.2413 17.5275 14.3186 17.8049C15.1866 18.0283 16.008 17.7288 16.5868 17.2572L17.1783 16.7752C17.4313 16.5691 17.4313 16.5691 17.4313 16.5691C17.4313 16.5691 17.4313 16.5691 17.4313 16.5691L15.3802 14.1409C15.0412 13.939 14.6152 13.9552 14.2925 14.1824Z" fill="currentColor"></path> </g></svg></a>
                    <a href="https://shopee.co.id" target="_blank" title="Shopee"><svg viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg" fill="none"><g id="SVGRepo_iconCarrier"><path fill="currentColor" d="m29.004 157.064 5.987-.399-5.987.399ZM22 52v-6a6 6 0 0 0-5.987 6.4L22 52Zm140.996 105.064-5.987-.399 5.987.399ZM170 52l5.987.4A6 6 0 0 0 170 46v6ZM34.991 156.665 27.987 51.601l-11.974.798 7.005 105.064 11.973-.798Zm133.991.798 7.005-105.064-11.974-.798-7.004 105.064 11.973.798Zm-11.973-.798a10 10 0 0 1-9.978 9.335v12c11.582 0 21.181-8.98 21.951-20.537l-11.973-.798Zm-133.991.798C23.788 169.02 33.387 178 44.968 178v-12a10 10 0 0 1-9.977-9.335l-11.973.798ZM74 48c0-12.15 9.85-22 22-22V14c-18.778 0-34 15.222-34 34h12Zm22-22c12.15 0 22 9.85 22 22h12c0-18.778-15.222-34-34-34v12ZM22 58h148V46H22v12Zm22.969 120H147.03v-12H44.969v12Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-width="12" d="M114 84H88c-7.732 0-14 6.268-14 14v0c0 7.732 6.268 14 14 14h4m-2 0h14c7.732 0 14 6.268 14 14v0c0 7.732-6.268 14-14 14H78"></path></g></svg></a>
                </div>
            </div>

            <!-- Col 2 -->
            <div class="footer-col">
                <h4>Layanan</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}#services">Fast Cleaning</a></li>
                    <li><a href="{{ route('home') }}#services">Deep Cleaning</a></li>
                    <li><a href="{{ route('home') }}#services">Premium Treatment</a></li>
                    <li><a href="{{ route('home') }}#services">Unyellowing</a></li>
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
            <p>© 2024 Clean Kicks Indonesia. All rights reserved.</p>
            <p style="margin-top: 8px; font-size: 0.65rem; opacity: 0.1;"><a href="{{ route('login') }}" style="color: inherit; text-decoration: none;">Staff</a></p>
        </div>
    </footer>

    <a href="https://wa.me/682116878685" target="_blank" class="wa-fab" title="Chat via WhatsApp">
        <svg viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
    </a>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
        
        // Close mobile nav when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                const navLinks = document.querySelector('.nav-links');
                if (navLinks) navLinks.classList.remove('active');
            });
        });
    </script>
</body>
</html>
