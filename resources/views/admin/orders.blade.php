<x-app-layout title="Manajemen Pesanan">
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
        <a href="{{ route('admin.orders.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Pesanan Baru
        </a>
    </div>
 </x-slot>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-12" x-data="{ 
        loading: false,
        async fetchTable(url) {
            this.loading = true;
            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await response.text();
                document.getElementById('tableContainer').innerHTML = html;
                // Update browser URL without reload
                window.history.pushState({}, '', url);
            } catch (error) {
                console.error('Failed to fetch orders:', error);
            } finally {
                this.loading = false;
            }
        },
        get hasFilters() {
            const formData = new FormData(this.$refs.form);
            return Array.from(formData.values()).some(v => v !== '');
        },
        submitFilter() {
            const formData = new FormData(this.$refs.form);
            const params = new URLSearchParams(formData).toString();
            this.fetchTable('{{ route('admin.orders') }}?' + params);
        },
        resetFilter() {
            this.$refs.form.querySelectorAll('input').forEach(i => i.value = '');
            this.fetchTable('{{ route('admin.orders') }}');
        }
    }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 sm:mx-0">
                {{ session('success') }}
            </div>
        @endif

    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg relative" x-data="{ open: false, order: {} }">
        <!-- Inline Filters & Search -->
        <div class="p-4 sm:p-6 border-b dark:border-gray-700">
            <form id="filterForm" method="GET" action="{{ route('admin.orders') }}" 
                class="flex flex-col lg:flex-row lg:items-center justify-between gap-4" 
                x-ref="form"
                @submit.prevent="submitFilter()">
                
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Cari kode, nama, atau petugas..."
                        @input.debounce.500ms="submitFilter()"
                        class="block w-full pl-10 text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                            @change="submitFilter()"
                            class="flex-1 sm:w-32 text-[11px] border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2">
                        <span class="text-gray-400 text-[10px] font-bold uppercase">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                            @change="submitFilter()"
                            class="flex-1 sm:w-32 text-[11px] border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2">
                    </div>

                    <a href="{{ route('admin.orders') }}" 
                       x-show="hasFilters"
                       @click.prevent="resetFilter()"
                       class="w-full sm:w-auto px-4 py-2 bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 text-[10px] font-bold uppercase rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition flex justify-center items-center gap-1"
                       x-cloak>
                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Loading Overlay -->
        <div x-show="loading" x-cloak class="absolute inset-0 bg-white/30 dark:bg-gray-800/30 z-10 flex items-center justify-center rounded-lg backdrop-blur-[1px]">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div id="tableContainer">
            @include('admin.partials.orders-table')
        </div>
        <!-- Modal Detail Pesanan -->
        <div x-show="open" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             x-cloak
             @keydown.escape.window="open = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 flex justify-between">
                                    <span>Detail Pesanan</span>
                                    <span class="text-indigo-600 dark:text-indigo-400" x-text="order.tracking_code"></span>
                                </h3>
                                <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Pelanggan</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.customer_name"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">No. WhatsApp</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.phone_number"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Layanan</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.service_name"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Kategori</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.service_category || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Merek Sepatu</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.shoe_brand || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Ukuran</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.shoe_size || '-'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Kondisi Sepatu</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.shoe_condition || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Estimasi Selesai</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium" x-text="order.estimated_days ? order.estimated_days + ' hari' : '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Status</p>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" 
                                              :class="{
                                                  'bg-gray-100 text-gray-800': order.status === 'Waiting',
                                                  'bg-blue-100 text-blue-800': order.status === 'Cleaning',
                                                  'bg-yellow-100 text-yellow-800': order.status === 'Drying',
                                                  'bg-indigo-100 text-indigo-800': order.status === 'Ready',
                                                  'bg-green-100 text-green-800': order.status === 'Delivered',
                                                  'bg-red-100 text-red-800': order.status === 'cancelled'
                                              }"
                                              x-text="order.status"></span>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Pembayaran</p>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" 
                                              :class="order.payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                              x-text="order.payment_status === 'paid' ? 'Lunas' : 'Belum Lunas'"></span>
                                    </div>
                                    <div class="col-span-2 border-t pt-2 mt-2">
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold uppercase text-[10px]">Catatan</p>
                                        <p class="text-gray-900 dark:text-gray-100 italic" x-text="order.notes || 'Tidak ada catatan'"></p>
                                    </div>
                                    <div class="col-span-2 border-t pt-2 mt-2 flex justify-between items-center">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold uppercase text-xs">Total Harga</span>
                                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(order.total_price)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" 
                                @click="open = false"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</x-app-layout>
