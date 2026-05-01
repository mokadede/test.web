<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
 {{ auth()->user()->role == 'owner' ? __('Owner Dashboard') : __('Karyawan Dashboard') }}
 </h2>
 </x-slot>

 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
 
 @if (session('success'))
 <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
 {{ session('success') }}
 </div>
 @endif
 @if (session('error'))
 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
 {{ session('error') }}
 </div>
 @endif

 <!-- MANAJEMEN PESANAN (Karyawan & Owner) -->
 <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
 <header>
 <h2 class="text-xl text-gray-900 dark:text-gray-100">
 {{ __('Manajemen Pesanan') }}
 </h2>
 </header>
 <div class="mt-6 overflow-x-auto">
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
 <td class="px-4 py-4 text-sm text-gray-400 font-medium">{{ $order->user->name }}</td>
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

 @if(auth()->user()->role === 'owner')
 <!-- OWNER ONLY: MANAJEMEN LAYANAN -->
 <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
 <header class="mb-4">
 <h2 class="text-xl text-gray-900 dark:text-gray-100">Manajemen Layanan & Harga</h2>
 </header>
 
 <form method="POST" action="{{ route('admin.services.store') }}" class="mb-6 flex gap-4 items-end">
 @csrf
 <div>
 <x-input-label value="Nama Layanan" />
 <x-text-input name="name" required />
 </div>
 <div>
 <x-input-label value="Harga (Rp)" />
 <x-text-input name="price" type="number" required />
 </div>
 <x-primary-button>Tambah Layanan</x-primary-button>
 </form>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
 @foreach($services as $service)
 <div class="border p-4 rounded flex justify-between items-center dark:border-gray-700">
 <form method="POST" action="{{ route('admin.services.update', $service) }}" class="flex gap-2">
 @csrf @method('PATCH')
 <x-text-input name="name" value="{{ $service->name }}" class="text-sm w-32" />
 <x-text-input name="price" value="{{ $service->price }}" type="number" class="text-sm w-24" />
 <x-secondary-button type="submit" class="text-xs">Save</x-secondary-button>
 </form>
 <form method="POST" action="{{ route('admin.services.destroy', $service) }}">
 @csrf @method('DELETE')
 <button type="submit" class="text-red-500 text-sm hover:underline">Hapus</button>
 </form>
 </div>
 @endforeach
 </div>
 </div>

 <!-- OWNER ONLY: MANAJEMEN KARYAWAN -->
 <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
 <header class="mb-4">
 <h2 class="text-xl text-gray-900 dark:text-gray-100">Manajemen Karyawan</h2>
 </header>

 <form method="POST" action="{{ route('admin.employees.store') }}" class="mb-6 flex gap-4 items-end">
 @csrf
 <div><x-input-label value="Nama" /><x-text-input name="name" required /></div>
 <div><x-input-label value="Email" /><x-text-input name="email" type="email" required /></div>
 <div><x-input-label value="Password" /><x-text-input name="password" type="password" required minlength="8" /></div>
 <x-primary-button>Tambah Karyawan</x-primary-button>
 </form>

 <ul>
 @foreach($employees as $employee)
 <li class="flex justify-between items-center py-2 border-b dark:border-gray-700">
 <span class="text-gray-800 dark:text-gray-200">{{ $employee->name }} ({{ $employee->email }})</span>
 <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}">
 @csrf @method('DELETE')
 <button type="submit" class="text-red-500 text-sm hover:underline">Hapus</button>
 </form>
 </li>
 @endforeach
 </ul>
 </div>

 <!-- OWNER ONLY: MANAJEMEN VOUCHER -->
 <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
 <header class="mb-4">
 <h2 class="text-xl text-gray-900 dark:text-gray-100">Manajemen Voucher Diskon</h2>
 </header>

 <form method="POST" action="{{ route('admin.vouchers.store') }}" class="mb-6 flex gap-4 items-end">
 @csrf
 <div><x-input-label value="Kode Voucher" /><x-text-input name="code" required /></div>
 <div><x-input-label value="Jumlah Diskon" /><x-text-input name="discount_amount" type="number" required /></div>
 <div>
 <x-input-label value="Tipe" />
 <select name="discount_type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
 <option value="fixed">Rupiah (Fixed)</option>
 <option value="percent">Persen (%)</option>
 </select>
 </div>
 <x-primary-button>Buat Voucher</x-primary-button>
 </form>

 <ul>
 @foreach($vouchers as $voucher)
 <li class="flex justify-between items-center py-2 border-b dark:border-gray-700">
 <span class="text-gray-800 dark:text-gray-200">
 <b>{{ $voucher->code }}</b> - 
 Diskon: {{ $voucher->discount_type == 'fixed' ? 'Rp '.number_format($voucher->discount_amount,0,',','.') : $voucher->discount_amount.'%' }}
 </span>
 <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}">
 @csrf @method('DELETE')
 <button type="submit" class="text-red-500 text-sm hover:underline">Hapus</button>
 </form>
 </li>
 @endforeach
 </ul>
 </div>
 @endif

 </div>
 </div>
</x-app-layout>
