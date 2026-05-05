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

    <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg" x-data="{ 
        addOpen: false,
        editOpen: false, 
        editVoucher: { id: '', code: '', discount_amount: '', discount_type: 'fixed' } 
    }">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                Daftar Voucher Aktif
            </h3>
            <button @click="addOpen = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Voucher
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode Voucher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diskon</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($vouchers as $voucher)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $voucher->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-semibold">
                            {{ $voucher->discount_type == 'fixed' ? 'Rp '.number_format($voucher->discount_amount,0,',','.') : $voucher->discount_amount.'%' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <button @click="editOpen = true; editVoucher = { id: '{{ $voucher->id }}', code: '{{ $voucher->code }}', discount_amount: '{{ $voucher->discount_amount }}', discount_type: '{{ $voucher->discount_type }}' }" 
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}" onsubmit="return confirm('Hapus voucher ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow flex items-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Add Modal -->
        <div x-show="addOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak @keydown.escape.window="addOpen = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="addOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="addOpen" x-transition.scale.95 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('admin.vouchers.store') }}">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Buat Voucher Baru</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label value="Kode Voucher" />
                                    <x-text-input name="code" required class="w-full mt-1" placeholder="Cth: DISKON50" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label value="Jumlah Diskon" />
                                        <x-text-input name="discount_amount" type="number" required class="w-full mt-1" placeholder="Cth: 10000" />
                                    </div>
                                    <div>
                                        <x-input-label value="Tipe" />
                                        <select name="discount_type" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                            <option value="fixed">Rupiah (Fixed)</option>
                                            <option value="percent">Persen (%)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                            <x-primary-button type="submit">Simpan Voucher</x-primary-button>
                            <x-secondary-button type="button" @click="addOpen = false">Batal</x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak @keydown.escape.window="editOpen = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="editOpen" x-transition.scale.95 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="'/admin/vouchers/' + editVoucher.id" method="POST">
                        @csrf @method('PATCH')
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Edit Data Voucher</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label value="Kode Voucher" />
                                    <x-text-input name="code" x-model="editVoucher.code" required class="w-full mt-1" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label value="Jumlah Diskon" />
                                        <x-text-input name="discount_amount" x-model="editVoucher.discount_amount" type="number" required class="w-full mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label value="Tipe" />
                                        <select name="discount_type" x-model="editVoucher.discount_type" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                            <option value="fixed">Rupiah (Fixed)</option>
                                            <option value="percent">Persen (%)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                            <x-primary-button type="submit">Update Voucher</x-primary-button>
                            <x-secondary-button type="button" @click="editOpen = false">Batal</x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
 </div>
 </div>
</x-app-layout>
