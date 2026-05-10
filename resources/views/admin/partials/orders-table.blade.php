{{-- Mobile Card View (Visible on small screens) --}}
<div class="block lg:hidden space-y-4 p-4">
    @foreach($orders as $order)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 space-y-3">
        <div class="flex justify-between items-start border-b dark:border-gray-700 pb-3">
            <div>
                <div class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $order->order_id_formatted }}</div>
                <div class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y') }}</div>
            </div>
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
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] text-gray-400 uppercase font-bold">Pelanggan</label>
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $order->customer_name }}</div>
                <div class="text-xs text-gray-500">{{ $order->phone_number }}</div>
            </div>
            <div class="text-right">
                <label class="text-[10px] text-gray-400 uppercase font-bold">Layanan</label>
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $order->service_name }}</div>
                <div class="text-xs text-indigo-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2 pt-2 border-t dark:border-gray-700">
            <button @click="open = true; order = {{ $order->toJson() }}" class="flex-1 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-bold flex justify-center items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Detail
            </button>
            <a href="{{ route('admin.orders.edit', $order) }}" class="flex-1 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold flex justify-center items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            @if($order->phone_number)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone_number) }}?text={{ urlencode('Halo ' . $order->customer_name . ', pesanan sepatu Anda telah diterima. Kode tracking Anda adalah: ' . $order->tracking_code . '. Silakan cek progresnya di: ' . route('track')) }}" 
                target="_blank" class="p-2 bg-green-500 text-white rounded-lg shadow-sm">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Desktop Table View (Visible on large screens) --}}
<div class="hidden lg:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-700/50">
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-10 text-center">Lihat</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tracking</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pelanggan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Detail Sepatu</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Layanan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pembayaran</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-center">WA</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-center">Aksi</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-center">Petugas</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($orders as $order)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
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
                        <div class="mt-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border-indigo-100 dark:border-indigo-900">
                                ⏱ {{ $order->estimated_days }}{{ stripos($order->estimated_days, 'same day') === false ? ' Hari' : '' }}
                            </span>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-4 text-sm text-gray-500">
                    <div class="font-medium text-gray-700 dark:text-gray-300">{{ $order->shoe_brand }} {{ $order->shoe_size ? '(Size: '.$order->shoe_size.')' : '' }}</div>
                    @if($order->deadline_date)
                        <div class="text-[10px] text-indigo-500 mt-1">📅 Selesai: {{ $order->deadline_date->format('d M Y') }}</div>
                    @endif
                </td>
                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                    <div class="font-semibold">{{ $order->service_name }}</div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $order->service_category }}</div>
                </td>
                <td class="px-4 py-4 text-sm text-center">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $order->payment_method }}</div>
                </td>
                <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
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
                <td class="px-4 py-4 text-sm text-center">
                    @if($order->phone_number)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone_number) }}?text={{ urlencode('Halo ' . $order->customer_name . ', pesanan sepatu Anda telah diterima. Kode tracking Anda adalah: ' . $order->tracking_code . '. Silakan cek progresnya di: ' . route('track')) }}" 
                        target="_blank" class="text-green-500 hover:text-green-600 transition">
                        <svg class="w-6 h-6 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    @endif
                </td>
                <td class="px-4 py-4 text-sm text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.orders.edit', $order) }}" class="p-1.5 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
                <td class="px-4 py-4 text-sm text-center text-xs text-gray-400 uppercase tracking-tighter">{{ $order->created_by }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
