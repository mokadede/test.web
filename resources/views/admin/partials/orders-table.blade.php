<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead>
        <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-10 text-center">Lihat</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'tracking_code', 'sort_order' => request('sort_by') == 'tracking_code' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                   @click.prevent="fetchTable($el.href)"
                   class="flex items-center gap-1 hover:text-indigo-600 transition">
                    Tracking
                    @if(request('sort_by', 'created_at') == 'tracking_code' || request('sort_by') == 'created_at')
                        <svg class="w-3 h-3 {{ request('sort_order', 'desc') == 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    @endif
                </a>
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                <div class="flex items-center gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'customer_name', 'sort_order' => request('sort_by') == 'customer_name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                       @click.prevent="fetchTable($el.href)"
                       class="flex items-center gap-1 hover:text-indigo-600 transition">
                        Pelanggan
                        @if(request('sort_by') == 'customer_name')
                            <svg class="w-3 h-3 {{ request('sort_order') == 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        @endif
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'estimated_days', 'sort_order' => request('sort_by') == 'estimated_days' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                       @click.prevent="fetchTable($el.href)"
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
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_price', 'sort_order' => request('sort_by') == 'total_price' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                   @click.prevent="fetchTable($el.href)"
                   class="flex items-center gap-1 hover:text-indigo-600 transition">
                    Total
                    @if(request('sort_by') == 'total_price')
                        <svg class="w-3 h-3 {{ request('sort_order') == 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    @endif
                </a>
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                   @click.prevent="fetchTable($el.href)"
                   class="flex items-center gap-1 hover:text-indigo-600 transition">
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
            <td class="px-4 py-4 text-sm font-mono font-bold text-indigo-600 dark:text-indigo-400">
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
                            ⏱ {{ $order->estimated_days }}{{ stripos($order->estimated_days, 'same day') === false ? ' Hari' : '' }}
                        </span>
                    </div>
                @endif
            </td>
            <td class="px-4 py-4 text-sm text-gray-500">
                <div class="flex flex-col gap-0.5">
                    <div class="text-gray-900 dark:text-gray-100">
                        @if($order->shoe_brand)
                            <span class="font-bold">{{ $order->shoe_brand }}</span>
                        @endif
                        @if($order->shoe_size)
                            <span class="text-xs text-gray-500">(Size: {{ $order->shoe_size }})</span>
                        @endif
                    </div>
                    @if($order->shoe_condition)
                        <div class="text-[11px] text-gray-400 dark:text-gray-500 italic">Kondisi: {{ $order->shoe_condition }}</div>
                    @endif
                    @if($order->deadline_date)
                        <div class="text-[10px] text-indigo-500 mt-0.5 font-semibold flex items-center gap-1">
                            <span>📅</span> Selesai: {{ $order->deadline_date->format('d M Y') }}
                        </div>
                    @endif
                </div>
            </td>
            <td class="px-4 py-4 text-sm">
                <div class="flex flex-col gap-0.5">
                    <div class="font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $order->service_name }}</div>
                    @if($order->service_category)
                        <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $order->service_category }}</div>
                    @endif
                    @if($order->additional_fees > 0)
                        <div class="text-[10px] text-orange-600 font-bold mt-0.5 bg-orange-50 dark:bg-orange-900/20 px-1.5 py-0.5 rounded-sm w-fit">
                            + Rp {{ number_format($order->additional_fees, 0, ',', '.') }} tambahan
                        </div>
                    @endif
                </div>
            </td>
            <td class="px-4 py-4 text-sm text-center">
                <div class="flex flex-col items-center gap-1.5">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $order->payment_method ?? '-' }}</span>
                    <form action="{{ route('admin.orders.payment_status', $order) }}" method="POST" class="m-0 p-0">
                        @csrf @method('PATCH')
                        <button type="submit" title="Klik untuk ubah status" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase w-fit transition hover:scale-105 active:scale-95 shadow-sm {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                            {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                        </button>
                    </form>
                </div>
            </td>
            <td class="px-4 py-4 text-sm font-black text-gray-900 dark:text-gray-100">
                <div class="flex flex-col">
                    <span class="text-[10px] text-gray-400 font-normal">Total</span>
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
            </td>
            <td class="px-4 py-4 text-sm text-center">
                <form action="{{ route('admin.orders.next_status', $order) }}" method="POST" class="m-0 p-0">
                    @csrf @method('PATCH')
                    <button type="submit" title="Klik untuk memajukan status" 
                           class="px-2 py-1 rounded text-[10px] font-bold uppercase border transition hover:scale-110 active:scale-95 shadow-sm min-w-[80px]" 
                           :class="{
                               'bg-gray-100 text-gray-800 border-gray-200': '{{ $order->status }}' === 'Waiting',
                               'bg-blue-100 text-blue-800 border-blue-200': '{{ $order->status }}' === 'Cleaning',
                               'bg-yellow-100 text-yellow-800 border-yellow-200': '{{ $order->status }}' === 'Drying',
                               'bg-indigo-100 text-indigo-800 border-indigo-200': '{{ $order->status }}' === 'Ready',
                               'bg-green-100 text-green-800 border-green-200': '{{ $order->status }}' === 'Delivered',
                               'bg-red-100 text-red-800 border-red-200': '{{ $order->status }}' === 'cancelled'
                           }">
                        {{ $order->status }}
                    </button>
                </form>
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
                    <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-[10px] text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 transition ease-in-out duration-150 gap-1 shadow-sm">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-500 border border-transparent rounded-md font-semibold text-[10px] text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 transition ease-in-out duration-150 gap-1 shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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

@if($orders->hasPages())
<div class="mt-6 px-4 py-3 bg-transparent border-t border-gray-200 dark:border-gray-700 sm:px-6">
    <div @click="if($event.target.tagName === 'A') { $event.preventDefault(); fetchTable($event.target.href); }">
        {{ $orders->links() }}
    </div>
</div>
@endif
