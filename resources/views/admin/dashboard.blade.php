<x-app-layout title="Dashboard">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard Pembukuan') }}
            </h2>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md shadow flex items-center gap-2 transition">
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

    <div class="py-12" x-data="dashboardComponent()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 relative">
            <!-- Loading Overlay -->
            <div x-show="loading" x-cloak class="absolute inset-0 bg-white/30 dark:bg-gray-800/30 z-50 flex items-center justify-center rounded-lg backdrop-blur-[1px]">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Grafik Pendapatan Tahun {{ date('Y') }}</h3>
                
                <div style="height: 400px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">Rincian Faktor Grafik</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Grafik di atas dipengaruhi secara langsung oleh metrik berikut:</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Total Pendapatan Masuk</p>
                            <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1" x-ref="revenueText">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 leading-tight">Total nilai transaksi. <b>Hanya pesanan berstatus "Lunas"</b> yang masuk ke dalam hitungan grafik.</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Volume Transaksi</p>
                            <p class="text-2xl font-black text-green-600 dark:text-green-400 mt-1" x-ref="ordersText">{{ $summary['total_orders'] }} Pesanan</p>
                            <p class="text-[10px] text-gray-400 mt-1 leading-tight">Jumlah pesanan yang sudah dibayar lunas sepanjang periode ini.</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Layanan Terlaris</p>
                            <p class="text-xl font-bold text-orange-600 dark:text-orange-400 mt-1" x-ref="topServiceText">{{ $summary['top_service'] ?: 'Belum Ada' }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 leading-tight">Layanan penyumbang frekuensi pesanan tertinggi pada grafik pendapatan.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4 text-left">
                            Rincian Transaksi Lunas 
                            <span x-ref="yearIndicator">
                            @if(request('start_date') || request('end_date'))
                                (Filter Aktif)
                            @else
                                Tahun {{ $summary['year'] }}
                            @endif
                            </span>
                        </h4>
                        
                        <form x-ref="filterForm" method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-3" @submit.prevent="submitFilter()">
                            <div class="flex items-center gap-2">
                                <input type="date" name="start_date" x-ref="startDate" value="{{ request('start_date') }}" 
                                    @change="submitFilter()"
                                    class="text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">s/d</span>
                                <input type="date" name="end_date" x-ref="endDate" value="{{ request('end_date') }}" 
                                    @change="submitFilter()"
                                    class="text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <a href="{{ route('admin.dashboard') }}" 
                               x-show="hasFilters"
                               @click.prevent="resetDashboard()"
                               class="px-3 py-1.5 bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition text-[10px] font-bold uppercase flex items-center gap-1"
                               x-cloak>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Reset
                            </a>
                        </form>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg" id="ordersTableContainer">
                        @include('admin.partials.dashboard-orders')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function dashboardComponent() {
            let chartInstance = null; // Private variable to avoid Alpine Proxy recursion
            
            return {
                loading: false,
                hasFilters: false,
                init() {
                    this.checkFilters();
                    const ctx = document.getElementById('revenueChart').getContext('2d');
                    const initialData = {!! json_encode(array_values($chartData)) !!};
                    
                    chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data: initialData,
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
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                x: {
                                    grid: { display: false },
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
                                            if (label) label += ': ';
                                            if (context.parsed.y !== null) label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    window.addEventListener('theme-changed', () => {
                        this.updateChartTheme();
                    });
                },
                updateChartTheme() {
                    if (!chartInstance) return;
                    
                    const isDark = document.documentElement.classList.contains('dark');
                    const color = isDark ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';
                    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                    
                    chartInstance.options.scales.y.grid.color = gridColor;
                    chartInstance.options.scales.y.ticks.color = color;
                    chartInstance.options.scales.x.ticks.color = color;
                    chartInstance.options.plugins.legend.labels.color = color;
                    
                    chartInstance.update();
                },
                checkFilters() {
                    this.hasFilters = this.$refs.startDate?.value || this.$refs.endDate?.value;
                },
                async fetchDashboard(url) {
                    this.loading = true;
                    try {
                        const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const data = await response.json();
                        
                        document.getElementById('ordersTableContainer').innerHTML = data.html;
                        this.$refs.revenueText.innerText = data.summary_formatted.revenue;
                        this.$refs.ordersText.innerText = data.summary_formatted.orders;
                        this.$refs.topServiceText.innerText = data.summary_formatted.top_service;
                        this.$refs.yearIndicator.innerText = data.summary_formatted.year_text;
                        
                        if (chartInstance) {
                            chartInstance.data.datasets[0].data = data.chartData;
                            chartInstance.update();
                        }
                        
                        window.history.pushState({}, '', url);
                        this.checkFilters();
                    } catch (e) { console.error(e); }
                    finally { this.loading = false; }
                },
                submitFilter() {
                    this.checkFilters();
                    const params = new URLSearchParams(new FormData(this.$refs.filterForm)).toString();
                    this.fetchDashboard('{{ route('admin.dashboard') }}?' + params);
                },
                resetDashboard() {
                    this.$refs.startDate.value = '';
                    this.$refs.endDate.value = '';
                    this.checkFilters();
                    this.fetchDashboard('{{ route('admin.dashboard') }}');
                }
            }
        }
    </script>
</x-app-layout>
