<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Pesanan — K-Clean</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        html:not(.dark) [style*="color: var(--neon)"],
        html:not(.dark) [style*="color: #FEFE01"],
        html:not(.dark) .text-neon {
            text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
        }
        /* Reset placeholder shadow */
        html:not(.dark) input::placeholder {
            text-shadow: none !important;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark); 
            color: var(--white); 
            overflow-x: hidden; 
            min-height: 100vh;
            transition: background-color 0.5s ease, color 0.5s ease;
        }
        
        /* NAV (Always Dark) */
        .nav { position: fixed; top: 0; width: 100%; z-index: 100; background: #080808; border-bottom: 1px solid rgba(128,128,128,0.2); }
        .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center; height: 72px; }
        .nav-logo { display: flex; align-items: center; gap: 10px; font-weight: 900; font-size: 1.1rem; letter-spacing: 2px; text-transform: uppercase; color: var(--neon); }
        .nav-logo-icon { width: 36px; height: 36px; background: var(--neon); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .nav-logo-icon svg { width: 20px; height: 20px; }
        .nav-links { display: flex; align-items: center; gap: 2rem; font-size: 0.85rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; }
        .nav-links a { color: #f0f0f0 !important; transition: color 0.3s; }
        .nav-links a:hover { color: var(--neon) !important; }
        
        .theme-toggle-btn { background: none; border: none; cursor: pointer; color: #f0f0f0; display: flex; align-items: center; justify-content: center; position: relative; width: 24px; height: 24px; }
        .theme-toggle-btn svg { width: 24px; height: 24px; stroke: currentColor; fill: none; position: absolute; top: 0; left: 0; transition: opacity 0.5s ease, transform 0.5s ease; }
        html.dark .sun-icon { opacity: 1; transform: rotate(0deg); }
        html.dark .moon-icon { opacity: 0; transform: rotate(90deg); }
        html:not(.dark) .sun-icon { opacity: 0; transform: rotate(-90deg); }
        html:not(.dark) .moon-icon { opacity: 1; transform: rotate(0deg); }

        [x-cloak] { display: none !important; }

        @media (max-width: 768px) {
            .nav-links { display: none; }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Navbar -->
    <nav class="nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="/images/logo.jpg" alt="Logo" style="height: 44px; width: auto; border-radius: 6px; object-fit: contain;">
            </a>
            <div class="nav-links">
                <a href="/">Beranda</a>
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @endauth
                <button onclick="toggleTheme()" class="theme-toggle-btn" title="Toggle Theme">
                    <svg class="sun-icon" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <svg class="moon-icon" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 py-24">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black mb-2" style="color: var(--white)">Lacak Pesanan</h1>
            <p style="color: var(--gray)">Masukkan ID Pesanan (KC-XXXXX) yang Anda terima.</p>
        </div>

        <!-- Search Form -->
        <form method="POST" action="{{ route('track.search') }}" class="mb-8 space-y-4">
            @csrf
            <div class="max-w-md mx-auto space-y-4">
                <div>
                    <label class="block text-sm font-bold uppercase tracking-wider mb-2" style="color: var(--gray)">ID Pesanan (KC-XXXXX)</label>
                    <input
                        type="text"
                        name="tracking_code"
                        value="{{ $query ?? '' }}"
                        placeholder="Contoh: KC-AB12C"
                        required
                        class="w-full px-4 py-4 text-center text-2xl font-black uppercase tracking-[0.3em] bg-transparent border-2 rounded-xl focus:ring-0 transition"
                        style="color: var(--neon); border-color: rgba(128,128,128,0.2);"
                        onfocus="this.style.borderColor='var(--neon)'"
                        onblur="this.style.borderColor='rgba(128,128,128,0.2)'"
                    >
                </div>
                <div>
                    <label class="block text-sm font-bold uppercase tracking-wider mb-2" style="color: var(--gray)">4 Digit Terakhir No HP</label>
                    <input
                        type="text"
                        name="phone_last_4"
                        value="{{ $phone_query ?? '' }}"
                        placeholder="Contoh: 1234"
                        maxlength="4"
                        required
                        class="w-full px-4 py-4 text-center text-2xl font-black tracking-[0.3em] bg-transparent border-2 rounded-xl focus:ring-0 transition"
                        style="color: var(--white); border-color: rgba(128,128,128,0.2);"
                        onfocus="this.style.borderColor='var(--neon)'"
                        onblur="this.style.borderColor='rgba(128,128,128,0.2)'"
                    >
                </div>
                <button type="submit" class="w-full px-6 py-4 bg-yellow-400 text-black font-black uppercase tracking-widest rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(254,254,1,0.3)]">
                    Lacak Pesanan
                </button>
            </div>
            @if ($errors->any())
                <div class="text-red-500 text-sm text-center">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </form>

        <!-- Results -->
        @if(isset($searched) && $searched)
            @if(isset($orders) && $orders->isNotEmpty())
                <!-- Orders Found -->
                <div class="rounded-2xl overflow-hidden border" style="background: var(--surface); border-color: rgba(128,128,128,0.2)">
                    <!-- Global Header -->
                    <div class="p-8 border-b" style="background: rgba(254,254,1,0.05); border-color: rgba(128,128,128,0.2)">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: var(--neon)">Nomor Tracking</p>
                                <p class="text-4xl font-black tracking-[0.2em] uppercase" style="color: var(--neon)">{{ $query }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: var(--gray)">Pelanggan</p>
                                <p class="text-xl font-black" style="color: var(--white)">{{ $orders->first()->customer_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="divide-y" style="border-color: rgba(128,128,128,0.1)">
                        @foreach($orders as $order)
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h3 class="text-2xl font-black uppercase italic" style="color: var(--white)">{{ $order->service_name }}</h3>
                                        <p class="text-xs font-bold uppercase tracking-widest mt-1" style="color: var(--gray)">{{ $order->service_category }}</p>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'Waiting' => 'background: rgba(128,128,128,0.1); color: #aaa;',
                                            'Cleaning' => 'background: rgba(59,130,246,0.1); color: #3b82f6;',
                                            'Drying' => 'background: rgba(234,179,8,0.1); color: #eab308;',
                                            'Ready' => 'background: rgba(168,85,247,0.1); color: #a855f7;',
                                            'Delivered' => 'background: rgba(34,197,94,0.1); color: #22c55e;',
                                            'cancelled' => 'background: rgba(239,68,68,0.1); color: #ef4444;'
                                        ];
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border" style="{{ $statusColors[$order->status] ?? '' }} border-color: currentColor;">
                                        {{ $order->status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 rounded-xl border" style="background: var(--surface2); border-color: rgba(128,128,128,0.1)">
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest w-24" style="color: var(--gray)">Merek</span>
                                            <span class="text-sm font-bold" style="color: var(--white)">{{ $order->shoe_brand ?: '-' }}</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest w-24" style="color: var(--gray)">Ukuran</span>
                                            <span class="text-sm font-bold" style="color: var(--white)">{{ $order->shoe_size ?: '-' }}</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest w-24" style="color: var(--gray)">Kondisi</span>
                                            <span class="text-sm font-bold" style="color: var(--white)">{{ $order->shoe_condition ?: '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-3 border-t md:border-t-0 md:border-l pt-4 md:pt-0 md:pl-8" style="border-color: rgba(128,128,128,0.1)">
                                        <div class="flex items-center gap-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest w-24" style="color: var(--gray)">Estimasi</span>
                                            <span class="text-sm font-black" style="color: var(--neon)">{{ $order->estimated_days ?: '-' }} hari</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest w-24" style="color: var(--gray)">Pembayaran</span>
                                            <span class="px-2 py-1 rounded text-[9px] font-black uppercase border" style="{{ $order->payment_status === 'paid' ? 'background: rgba(34,197,94,0.1); color: #22c55e; border-color: #22c55e;' : 'background: rgba(239,68,68,0.1); color: #ef4444; border-color: #ef4444;' }}">
                                                {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($order->notes)
                                    <div class="mt-6 text-sm italic" style="color: var(--gray)">
                                        <strong class="uppercase text-[10px] not-italic mr-2">Catatan:</strong> {{ $order->notes }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="p-8 border-t" style="background: var(--surface2); border-color: rgba(128,128,128,0.1)">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: var(--gray)">Total Pembayaran</p>
                                <p class="text-4xl font-black" style="color: var(--white)">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Not Found -->
                <div class="text-center p-12 rounded-2xl border" style="background: var(--surface); border-color: rgba(128,128,128,0.2)">
                    <p class="text-5xl mb-6">🔍</p>
                    <p class="text-2xl font-black mb-2" style="color: var(--white)">Pesanan Tidak Ditemukan</p>
                    <p style="color: var(--gray)">Kode tracking <strong>"{{ $query }}"</strong> tidak cocok atau nomor HP salah. Periksa kembali data Anda.</p>
                </div>
            @endif
        @endif
    </div>

    <footer class="text-center py-12" style="color: var(--gray)">
        <p class="text-[10px] font-bold uppercase tracking-widest">&copy; {{ date('Y') }} K-CLEAN PREMIUM CARE</p>
        <!-- <p style="margin-top: 8px; font-size: 0.6rem; opacity: 0.3;"><a href="{{ route('login') }}">STAFF ACCESS</a></p> -->
    </footer>

</body>
</html>
