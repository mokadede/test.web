<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Pesanan — Shoe Laundry</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-theme-script />
</head>
<body class="antialiased font-sans bg-gray-100 dark:bg-gray-900 min-h-screen">

    <!-- Simple Nav -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3 font-bold text-lg text-gray-800 dark:text-white">
                <img src="/images/logo.jpg" alt="Logo" class="h-10 w-auto rounded-lg object-contain">
                
            </a>
            <div class="flex gap-4 text-sm items-center">
                <a href="/" class="text-gray-500 hover:text-gray-800 dark:hover:text-white">Beranda</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-500 hover:text-gray-800 dark:hover:text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-800 dark:hover:text-white">Log in</a>
                    @endauth
                @endif
                <x-theme-toggle />
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Lacak Pesanan Anda</h1>
            <p class="text-gray-500 dark:text-gray-400">Masukkan kode tracking 5 karakter yang Anda terima saat order.</p>
        </div>

        <!-- Search Form -->
        <form method="POST" action="{{ route('track.search') }}" class="mb-8 space-y-4">
            @csrf
            <div class="max-w-md mx-auto space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Tracking</label>
                    <input
                        type="text"
                        name="tracking_code"
                        value="{{ $query ?? '' }}"
                        placeholder="Contoh: AB12C"
                        maxlength="5"
                        required
                        class="w-full px-4 py-3 text-center text-lg font-bold uppercase tracking-[0.3em] border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">4 Digit Terakhir Nomor WA</label>
                    <input
                        type="text"
                        name="whatsapp_last_4"
                        value="{{ $whatsapp_query ?? '' }}"
                        placeholder="Contoh: 1234"
                        maxlength="4"
                        required
                        class="w-full px-4 py-3 text-center text-lg font-bold tracking-[0.3em] border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
                <button type="submit" class="w-full px-6 py-3 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 font-semibold rounded-lg hover:bg-gray-700 dark:hover:bg-white transition">
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
            @if(isset($order) && $order)
                <!-- Order Found -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                    <!-- Order Header -->
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kode Tracking</p>
                                <p class="text-2xl font-bold tracking-widest text-gray-900 dark:text-white">{{ $order->tracking_code }}</p>
                            </div>
                            <div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                    $statusLabels = [
                                        'pending' => '⏳ Menunggu',
                                        'processing' => '🔄 Diproses',
                                        'completed' => '✅ Selesai',
                                        'cancelled' => '❌ Dibatalkan',
                                    ];
                                @endphp
                                <span class="px-4 py-2 rounded-full text-sm font-bold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            Tanggal Order: {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>

                    <!-- Items List -->
                    <div class="p-6">
                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="flex-1 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Metode Pembayaran</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $order->payment_method }}</p>
                            </div>
                            <div class="flex-1 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Status Pembayaran</p>
                                <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </div>
                        </div>

                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Detail Sepatu ({{ $order->items->count() }} item)</h3>
                        <div class="space-y-3">
                            @foreach($order->items as $i => $item)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $i + 1 }}. {{ $item->shoe_name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->shoe_brand ? $item->shoe_brand . ' — ' : '' }}{{ $item->service->name }}
                                        </p>
                                    </div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="px-6 pb-6">
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-1">
                                <span>Voucher ({{ $order->voucher_code }})</span>
                                <span class="text-green-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                            <span class="font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="font-bold text-lg text-gray-900 dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="px-6 pb-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400"><strong>Catatan:</strong> {{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            @else
                <!-- Not Found -->
                <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-xl shadow-md">
                    <p class="text-4xl mb-4">🔍</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pesanan tidak ditemukan</p>
                    <p class="text-gray-500 dark:text-gray-400">Kode tracking <strong>"{{ $query }}"</strong> tidak cocok dengan pesanan manapun. Periksa kembali kode Anda.</p>
                </div>
            @endif
        @endif
    </div>
</body>
</html>
