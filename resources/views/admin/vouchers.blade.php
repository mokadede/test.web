<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
 {{ __('Manajemen Voucher Diskon') }}
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
 <li class="flex justify-between items-center py-4 border-b border-white/5 last:border-0">
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
 </div>
 </div>
</x-app-layout>
