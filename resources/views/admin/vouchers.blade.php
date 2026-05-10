<x-app-layout title="Manajemen Voucher">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Voucher Diskon') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        addOpen: false,
        editOpen: false, 
        form: {
            id: '',
            code: '',
            discount_type: 'percent',
            discount_amount: '',
            min_order: 0,
            max_uses: 100,
            valid_from: '{{ date('Y-m-d') }}',
            valid_until: '{{ date('Y-m-d', strtotime('+1 month')) }}'
        },
        resetForm() {
            this.form = {
                id: '',
                code: '',
                discount_type: 'percent',
                discount_amount: '',
                min_order: 0,
                max_uses: 100,
                valid_from: '{{ date('Y-m-d') }}',
                valid_until: '{{ date('Y-m-d', strtotime('+1 month')) }}'
            };
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-6 border-b pb-4 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        Daftar Voucher Aktif
                    </h3>
                    <button @click="resetForm(); addOpen = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Voucher
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-100 dark:border-gray-700 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/30">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Voucher</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Diskon</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kuota</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Berlaku</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($vouchers as $voucher)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest text-sm">{{ $voucher->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                    {{ $voucher->discount_type == 'fixed' ? 'Rp '.number_format($voucher->discount_amount,0,',','.') : $voucher->discount_amount.'%' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400">
                                    {{ $voucher->used_count }} / {{ $voucher->max_uses ?: '∞' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[10px] text-gray-500">
                                    {{ $voucher->valid_from ? $voucher->valid_from->format('d M y') : '-' }} s/d {{ $voucher->valid_until ? $voucher->valid_until->format('d M y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button @click="editOpen = true; form = { 
                                            id: '{{ $voucher->id }}', 
                                            code: '{{ $voucher->code }}', 
                                            discount_amount: '{{ $voucher->discount_amount }}', 
                                            discount_type: '{{ $voucher->discount_type }}',
                                            min_order: '{{ $voucher->min_order }}',
                                            max_uses: '{{ $voucher->max_uses }}',
                                            valid_from: '{{ $voucher->valid_from ? $voucher->valid_from->format('Y-m-d') : '' }}',
                                            valid_until: '{{ $voucher->valid_until ? $voucher->valid_until->format('Y-m-d') : '' }}'
                                        }" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow flex items-center gap-1 transition">
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
            </div>
        </div>

        {{-- Voucher Modal (Frame following Service Modal style) --}}
        <template x-if="addOpen || editOpen">
            <div class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 transition-opacity" @click="addOpen = false; editOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form :action="editOpen ? '/admin/vouchers/' + form.id : '{{ route('admin.vouchers.store') }}'" method="POST">
                            @csrf
                            <template x-if="editOpen">
                                <input type="hidden" name="_method" value="PATCH">
                            </template>

                            {{-- Modal Header (Styled like Services) --}}
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 border-b pb-2" x-text="editOpen ? 'Edit Data Voucher' : 'Tambah Voucher Baru'"></h3>
                                
                                <div class="space-y-6">
                                    {{-- Section: Detail Voucher --}}
                                    <div class="space-y-4">
                                        <div>
                                            <x-input-label value="Kode Voucher" />
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 h-full flex items-center pointer-events-none text-gray-400">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                                </div>
                                                <input type="text" name="code" x-model="form.code" required class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: DISKON10">
                                            </div>
                                        </div>

                                        <div>
                                            <x-input-label value="Tipe Diskon" />
                                            <div class="mt-2 flex bg-gray-100 dark:bg-gray-900 p-1 rounded-xl">
                                                <button type="button" @click="form.discount_type = 'percent'" :class="form.discount_type === 'percent' ? 'bg-white dark:bg-gray-700 shadow-sm text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-500'" class="flex-1 py-2 text-xs rounded-lg transition-all duration-200">
                                                    Persentase (%)
                                                </button>
                                                <button type="button" @click="form.discount_type = 'fixed'" :class="form.discount_type === 'fixed' ? 'bg-white dark:bg-gray-700 shadow-sm text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-500'" class="flex-1 py-2 text-xs rounded-lg transition-all duration-200">
                                                    Nominal (Rp)
                                                </button>
                                                <input type="hidden" name="discount_type" :value="form.discount_type">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" x-text="form.discount_type === 'percent' ? 'Nilai Diskon (%)' : 'Nilai Diskon (Rp)'"></label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 h-full flex items-center pointer-events-none text-gray-400">
                                                        <template x-if="form.discount_type === 'percent'">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.45a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm0 12a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                            </svg>
                                                        </template>
                                                        <template x-if="form.discount_type === 'fixed'">
                                                            <span class="text-xs font-bold">Rp</span>
                                                        </template>
                                                    </div>
                                                    <input type="number" name="discount_amount" x-model="form.discount_amount" required class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            <div>
                                                <x-input-label value="Maks. Pakai" />
                                                <input type="number" name="max_uses" x-model="form.max_uses" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>

                                        <div>
                                            <x-input-label value="Min. Order (Rp)" />
                                            <input type="number" name="min_order" x-model="form.min_order" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>

                                    {{-- Section: Periode Berlaku --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label value="Mulai" />
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 h-full flex items-center pointer-events-none text-gray-400">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <input type="date" name="valid_from" x-model="form.valid_from" class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label value="Sampai" />
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 h-full flex items-center pointer-events-none text-gray-400">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <input type="date" name="valid_until" x-model="form.valid_until" class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Footer (Styled like Services) --}}
                            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                                <x-primary-button type="submit" x-text="editOpen ? 'Update Voucher' : 'Simpan Voucher'"></x-primary-button>
                                <x-secondary-button type="button" @click="addOpen = false; editOpen = false">Batal</x-secondary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
