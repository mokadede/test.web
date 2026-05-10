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
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm animate-pulse">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Voucher</h3>
                        <p class="text-sm text-gray-500">Kelola kode promosi dan diskon layanan</p>
                    </div>
                    <button @click="resetForm(); addOpen = true" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none flex items-center gap-2 transition-all transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Voucher
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Kode Voucher</th>
                                <th class="px-6 py-4">Diskon</th>
                                <th class="px-6 py-4">Kuota Penggunaan</th>
                                <th class="px-6 py-4">Periode Berlaku</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            @forelse($vouchers as $voucher)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-lg text-sm font-black uppercase tracking-widest border border-indigo-100 dark:border-indigo-800">
                                        {{ $voucher->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $voucher->discount_type == 'fixed' ? 'Rp '.number_format($voucher->discount_amount,0,',','.') : $voucher->discount_amount.'%' }}
                                        </span>
                                        <span class="text-[10px] text-gray-400">Min. Order: Rp {{ number_format($voucher->min_order, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="w-full max-w-[100px] bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mb-1">
                                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $voucher->max_uses > 0 ? ($voucher->used_count / $voucher->max_uses * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $voucher->used_count }} / {{ $voucher->max_uses ?: '∞' }} dipakai</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">
                                        {{ $voucher->valid_from ? $voucher->valid_from->format('d M y') : '-' }} — {{ $voucher->valid_until ? $voucher->valid_until->format('d M y') : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button @click="editOpen = true; form = { 
                                            id: '{{ $voucher->id }}', 
                                            code: '{{ $voucher->code }}', 
                                            discount_amount: '{{ $voucher->discount_amount }}', 
                                            discount_type: '{{ $voucher->discount_type }}',
                                            min_order: '{{ $voucher->min_order }}',
                                            max_uses: '{{ $voucher->max_uses }}',
                                            valid_from: '{{ $voucher->valid_from ? $voucher->valid_from->format('Y-m-d') : '' }}',
                                            valid_until: '{{ $voucher->valid_until ? $voucher->valid_until->format('Y-m-d') : '' }}'
                                        }" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}" onsubmit="return confirm('Hapus voucher ini?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada voucher yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Refined Voucher Modal -->
        <template x-if="addOpen || editOpen">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-cloak>
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     @click="addOpen = false; editOpen = false"></div>
                
                {{-- Modal Panel --}}
                <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-[2rem] shadow-2xl overflow-hidden transform transition-all"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95">
                    
                    <form :action="editOpen ? '/admin/vouchers/' + form.id : '{{ route('admin.vouchers.store') }}'" method="POST">
                        @csrf
                        <template x-if="editOpen">
                            <input type="hidden" name="_method" value="PATCH">
                        </template>

                        {{-- Header --}}
                        <div class="px-8 pt-8 pb-4 flex justify-between items-center bg-white dark:bg-gray-900">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white" x-text="editOpen ? 'Edit Voucher' : 'Tambah Voucher'"></h3>
                            <button type="button" @click="addOpen = false; editOpen = false" class="p-2 bg-gray-50 dark:bg-gray-800 rounded-full text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="px-8 pb-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            {{-- Section 1: Detail --}}
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Detail Voucher</label>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Kode Voucher</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        </div>
                                        <input type="text" name="code" x-model="form.code" required class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all uppercase placeholder-gray-300" placeholder="Contoh: DISKON10">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-2">Tipe Diskon</label>
                                    <div class="flex bg-gray-50 dark:bg-gray-800 p-1.5 rounded-2xl border border-gray-100 dark:border-gray-700">
                                        <button type="button" @click="form.discount_type = 'percent'" :class="form.discount_type === 'percent' ? 'bg-white dark:bg-gray-700 shadow-md text-indigo-600 dark:text-indigo-400' : 'text-gray-400'" class="flex-1 py-2.5 text-xs font-black rounded-xl transition-all duration-300">
                                            Persentase (%)
                                        </button>
                                        <button type="button" @click="form.discount_type = 'fixed'" :class="form.discount_type === 'fixed' ? 'bg-white dark:bg-gray-700 shadow-md text-indigo-600 dark:text-indigo-400' : 'text-gray-400'" class="flex-1 py-2.5 text-xs font-black rounded-xl transition-all duration-300">
                                            Nominal (Rp)
                                        </button>
                                        <input type="hidden" name="discount_type" :value="form.discount_type">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1" x-text="form.discount_type === 'percent' ? 'Nilai Diskon (%)' : 'Nilai Diskon (Rp)'"></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                            <span x-show="form.discount_type === 'percent'" class="text-sm font-bold">%</span>
                                            <span x-show="form.discount_type === 'fixed'" class="text-xs font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="discount_amount" x-model="form.discount_amount" required class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="0">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Min. Order (Rp)</label>
                                        <input type="number" name="min_order" x-model="form.min_order" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Maks. Pakai</label>
                                        <input type="number" name="max_uses" x-model="form.max_uses" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="100">
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: Periode --}}
                            <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-gray-800">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Periode Berlaku</label>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Mulai Dari</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <input type="date" name="valid_from" x-model="form.valid_from" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Sampai Dengan</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <input type="date" name="valid_until" x-model="form.valid_until" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-8 pb-8">
                            <button type="submit" class="w-full py-4 bg-gray-900 dark:bg-indigo-600 hover:bg-black dark:hover:bg-indigo-700 text-white rounded-2xl font-black text-sm flex items-center justify-center gap-3 transition-all shadow-xl shadow-gray-200 dark:shadow-none transform active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Voucher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; }
    </style>
</x-app-layout>
