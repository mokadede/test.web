<x-app-layout title="Dashboard">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard Pembukuan (Owner)') }}
            </h2>
            <div x-data="{ open: false }" class="relative w-full sm:w-auto">
                <button @click="open = !open" class="w-full sm:w-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md shadow flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                    <svg class="w-3 h-3 ml-1" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden">
                    <div class="p-3 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Periode Cepat</p>
                    </div>
                    <div class="p-2 space-y-1">
                        <a href="{{ route('admin.orders.export', ['period' => '7days']) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition group">
                            <span class="w-8 h-8 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-lg flex items-center justify-center text-xs font-black">7H</span>
                            <span class="font-medium group-hover:text-green-600">7 Hari Terakhir</span>
                        </a>
                        <a href="{{ route('admin.orders.export', ['period' => '30days']) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition group">
                            <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-lg flex items-center justify-center text-xs font-black">30H</span>
                            <span class="font-medium group-hover:text-green-600">30 Hari Terakhir</span>
                        </a>
                        <a href="{{ route('admin.orders.export', ['period' => 'this_month']) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition group">
                            <span class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 rounded-lg flex items-center justify-center text-xs font-black">BI</span>
                            <span class="font-medium group-hover:text-green-600">Bulan Ini</span>
                        </a>
                        <a href="{{ route('admin.orders.export', ['period' => 'last_month']) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition group">
                            <span class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-lg flex items-center justify-center text-xs font-black">BL</span>
                            <span class="font-medium group-hover:text-green-600">Bulan Lalu</span>
                        </a>
                    </div>
                    <div class="p-3 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Rentang Tanggal Kustom</p>
                        <form method="GET" action="{{ route('admin.orders.export') }}" class="space-y-2">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" name="start_date" required class="text-xs border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm w-full">
                                <input type="date" name="end_date" required class="text-xs border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm w-full">
                            </div>
                            <button type="submit" class="w-full px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold uppercase tracking-wider rounded-md transition">
                                Download Excel
                            </button>
                        </form>
                    </div>
                    <div class="p-2 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.orders.export') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span class="font-medium text-xs">Export Semua Data</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Grafik Pendapatan Tahun {{ date('Y') }}</h3>
                
                <div style="height: 400px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">Rincian Faktor Grafik</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Grafik di atas dipengaruhi secara langsung oleh metrik berikut pada tahun {{ $summary['year'] }}:</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Total Pendapatan Masuk</p>
                            <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 leading-tight">Total nilai transaksi. <b>Hanya pesanan berstatus "Lunas"</b> yang masuk ke dalam hitungan grafik.</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Volume Transaksi</p>
                            <p class="text-2xl font-black text-green-600 dark:text-green-400 mt-1">{{ $summary['total_orders'] }} Pesanan</p>
                            <p class="text-[10px] text-gray-400 mt-1 leading-tight">Jumlah pesanan yang sudah dibayar lunas sepanjang tahun ini.</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Layanan Terlaris</p>
                            <p class="text-xl font-bold text-orange-600 dark:text-orange-400 mt-1">{{ $summary['top_service'] ?: 'Belum Ada' }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 leading-tight">Layanan penyumbang frekuensi pesanan tertinggi pada grafik pendapatan.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                            Rincian Transaksi Lunas 
                            @if(request('start_date') || request('end_date'))
                                (Filter Aktif)
                            @else
                                Tahun {{ $summary['year'] }}
                            @endif
                        </h4>
                        
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-2">
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="flex-1 sm:w-auto text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                                <span class="text-gray-500 text-sm">s/d</span>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="flex-1 sm:w-auto text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto px-3 py-1.5 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition text-xs font-semibold flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Reset
                            </a>
                        </form>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Layanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($orders->sortByDesc('created_at') as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-indigo-600 dark:text-neon">
                                        {{ $order->order_id_formatted }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $order->customer_name }}
                                        <div class="text-xs text-gray-500">{{ $order->phone_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->service_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada transaksi lunas di tahun ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const rawData = {!! json_encode(array_values($chartData)) !!};
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: rawData,
                        backgroundColor: 'rgba(254, 254, 1, 0.5)',
                        borderColor: 'rgba(254, 254, 1, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)',
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
