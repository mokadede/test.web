<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
 {{ __('Manajemen Pesanan') }}
 </h2>
 </x-slot>

 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
 @if (session('success'))
 <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
 {{ session('success') }}
 </div>
 @endif

 <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
 <div class="overflow-x-auto">
 <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
 <thead>
 <tr>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status & Aksi</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
 @foreach($orders as $order)
 <tr>
 <td class="px-4 py-4 text-sm font-mono font-bold text-gray-900 dark:text-gray-100">{{ $order->tracking_code }}</td>
 <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $order->user->name }}</td>
 <td class="px-4 py-4 text-sm text-gray-500">
 @foreach($order->items as $item)
 <div><b>{{ $item->shoe_name }}</b> ({{ $item->service->name }})</div>
 @endforeach
 @if($order->voucher_code)
 <div class="text-green-500 text-xs mt-1">Voucher: {{ $order->voucher_code }} (-Rp {{ number_format($order->discount_amount, 0, ',', '.') }})</div>
 @endif
 </td>
 <td class="px-4 py-4 text-sm">
     <div class="flex flex-col gap-1">
         <span class="text-xs font-semibold text-gray-400">{{ $order->payment_method }}</span>
         <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase w-fit {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
             {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
         </span>
     </div>
 </td>
 <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
 <td class="px-4 py-4 text-sm">
 <div class="flex flex-col space-y-2">
 <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex items-center space-x-2">
 @csrf
 @method('PATCH')
 <select name="status" class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
 <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
 <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
 <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
 </select>
 <x-primary-button class="px-2 py-1 text-xs">Update</x-primary-button>
 </form>

 @if($order->user->whatsapp)
 <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->user->whatsapp) }}?text={{ urlencode('Halo ' . $order->user->name . ', pesanan sepatu Anda telah diterima. Kode tracking Anda adalah: ' . $order->tracking_code . '. Silakan cek progresnya di: ' . route('track')) }}" 
 target="_blank" 
 class="inline-flex items-center justify-center px-2 py-1 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
 📱 Kirim WA
 </a>
 @endif
 </div>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 </div>
 </div>
 </div>
</x-app-layout>
