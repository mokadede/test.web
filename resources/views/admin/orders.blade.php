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

 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Tanggal & Search -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg" x-data>
            <form id="filterForm" method="GET" action="{{ route('admin.orders') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-end gap-4" x-ref="form">
                <div class="col-span-1 sm:col-span-2 lg:flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Pesanan</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Kode, Nama, atau Petugas..."
                        x-on:input.debounce.750ms="$refs.form.submit()"
                        class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-full lg:w-48">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                        x-on:change="$refs.form.submit()"
                        class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-full lg:w-48">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                        x-on:change="$refs.form.submit()"
                        class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari
                    </button>
                    @if(request()->anyFilled(['search', 'start_date', 'end_date']))
                        <a href="{{ route('admin.orders') }}" class="flex-1 lg:flex-none px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-md shadow transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

    <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg" x-data="{ open: false, order: {} }">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-10 text-center">Lihat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'tracking_code', 'sort_order' => request('sort_by') == 'tracking_code' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                Tracking
                                @if(request('sort_by', 'created_at') == 'tracking_code' || request('sort_by') == 'created_at')
                                    <svg class="w-3 h-3 {{ request('sort_order', 'desc') == 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                            <div class="flex items-center gap-2">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'customer_name', 'sort_order' => request('sort_by') == 'customer_name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                    Pelanggan
                                    @if(request('sort_by') == 'customer_name')
                                        <svg class="w-3 h-3 {{ request('sort_order') == 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'estimated_days', 'sort_order' => request('sort_by') == 'estimated_days' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   title="Urutkan Deadline"
                                   class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition {{ request('sort_by') == 'estimated_days' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-400 dark:text-gray-500' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </a>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Detail Sepatu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Layanan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pembayaran</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_price', 'sort_order' => request('sort_by') == 'total_price' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                Total
                                @if(request('sort_by') == 'total_price')
                                    <svg class="w-3 h-3 {{ request('sort_order') == 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                Status
                                @if(request('sort_by') == 'status')
                                    <svg class="w-3 h-3 {{ request('sort_order') == 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-center">Kirim Track ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-center">Aksi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-center">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-4 text-sm text-center">
                            <button @click="open = true; order = {{ $order->toJson() }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </td>
                        <td class="px-4 py-4 text-sm font-mono font-bold text-indigo-600 dark:text-neon">
                            {{ $order->order_id_formatted }}
                            <div class="text-[10px] text-gray-400 font-normal">{{ $order->created_at->format('d M Y') }}</div>
                        </td>
                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <div class="font-semibold">{{ $order->customer_name }}</div>
                        <div class="text-xs text-gray-500">{{ $order->phone_number }}</div>
                        @if($order->estimated_days)
                            @php
                                $deadlineStatus = $order->deadline_status;
                                $colorClass = match($deadlineStatus) {
                                    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200',
                                    'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border-yellow-200',
                                    'safe' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200',
                                };
                            @endphp
                            <div class="mt-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $colorClass }}">
                                    ⏱ {{ $order->estimated_days }} Hari
                                    @if($deadlineStatus === 'danger' && $order->status !== 'completed' && $order->status !== 'cancelled')
                                        <span class="ml-1">🔥</span>
                                    @endif
                                </span>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        <div>
                            @if($order->shoe_brand)
                                <b>{{ $order->shoe_brand }}</b>
                            @endif
                            @if($order->shoe_size)
                                <span class="text-xs">(Size: {{ $order->shoe_size }})</span>
                            @endif
                        </div>
                        @if($order->shoe_condition)
                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Kondisi: {{ $order->shoe_condition }}</div>
                        @endif
                        @if($order->deadline_date)
                            <div class="text-[10px] text-indigo-500 mt-1 font-medium">
                                📅 Selesai: {{ $order->deadline_date->format('d M Y') }}
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <div class="font-semibold">{{ $order->service_name }}</div>
                        @if($order->service_category)
                            <div class="text-xs text-gray-400 dark:text-gray-500">{{ $order->service_category }}</div>
                        @endif
                        @if($order->additional_fees > 0)
                            <div class="text-xs text-orange-500 mt-0.5">+ Rp {{ number_format($order->additional_fees, 0, ',', '.') }} tambahan</div>
                        @endif
                        @if($order->voucher_code)
                            <div class="text-green-500 text-xs mt-0.5">Voucher: {{ $order->voucher_code }} (-Rp {{ number_format($order->discount_amount, 0, ',', '.') }})</div>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm text-center">
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-xs font-semibold text-gray-400">{{ $order->payment_method ?? '-' }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase w-fit {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-sm text-center">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase border" 
                              :class="{
                                  'bg-gray-100 text-gray-800 border-gray-200': '{{ $order->status }}' === 'Waiting',
                                  'bg-blue-100 text-blue-800 border-blue-200': '{{ $order->status }}' === 'Cleaning',
                                  'bg-yellow-100 text-yellow-800 border-yellow-200': '{{ $order->status }}' === 'Drying',
                                  'bg-indigo-100 text-indigo-800 border-indigo-200': '{{ $order->status }}' === 'Ready',
                                  'bg-green-100 text-green-800 border-green-200': '{{ $order->status }}' === 'Delivered',
                                  'bg-red-100 text-red-800 border-red-200': '{{ $order->status }}' === 'cancelled'
                              }">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-sm">
                        @if($order->phone_number)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone_number) }}?text={{ urlencode('Halo ' . $order->customer_name . ', pesanan sepatu Anda telah diterima. Kode tracking Anda adalah: ' . $order->tracking_code . '. Silakan cek progresnya di: ' . route('track')) }}" 
                            target="_blank" 
                            class="inline-flex items-center justify-center px-3 py-1.5 bg-green-500 border border-transparent rounded-md font-semibold text-[10px] text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 transition ease-in-out duration-150">
                            WhatsApp
                        </a>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-500 border border-transparent rounded-md font-semibold text-[10px] text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 transition ease-in-out duration-150">
                                Edit
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-500 border border-transparent rounded-md font-semibold text-[10px] text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 transition ease-in-out duration-150">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-center">
                        @if($order->created_by)
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium uppercase tracking-tight">{{ $order->created_by }}</span>
                        @else
                            <span class="text-[10px] text-gray-300 dark:text-gray-600">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
 </tbody>
 </table>
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
