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
            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada transaksi lunas di periode ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>
