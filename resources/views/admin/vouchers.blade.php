<x-app-layout title="Manajemen Voucher">
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Buat Voucher Baru</h3>
        <form method="POST" action="{{ route('admin.vouchers.store') }}" class="mb-8 flex flex-col sm:flex-row gap-4 items-end bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
            @csrf
            <div class="w-full sm:flex-1">
                <x-input-label value="Kode Voucher" />
                <x-text-input name="code" required class="w-full mt-1" placeholder="Cth: DISKON50" />
            </div>
            <div class="w-full sm:flex-1">
                <x-input-label value="Jumlah Diskon" />
                <x-text-input name="discount_amount" type="number" required class="w-full mt-1" placeholder="Cth: 10000" />
            </div>
            <div class="w-full sm:w-40">
                <x-input-label value="Tipe" />
                <select name="discount_type" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                    <option value="fixed">Rupiah (Fixed)</option>
                    <option value="percent">Persen (%)</option>
                </select>
            </div>
            <x-primary-button class="w-full sm:w-auto h-10 justify-center">Buat Voucher</x-primary-button>
        </form>

        <h3 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4">
            Daftar Voucher Aktif
        </h3>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($vouchers as $voucher)
            <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-4 gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                    <div>
                        <span class="text-gray-900 dark:text-gray-100 font-bold text-lg tracking-wider">{{ $voucher->code }}</span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Diskon: <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $voucher->discount_type == 'fixed' ? 'Rp '.number_format($voucher->discount_amount,0,',','.') : $voucher->discount_amount.'%' }}</span>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}" onsubmit="return confirm('Hapus voucher ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 rounded text-xs font-semibold transition">Hapus Voucher</button>
                </form>
            </li>
            @endforeach
        </ul>
    </div>
 </div>
 </div>
</x-app-layout>
