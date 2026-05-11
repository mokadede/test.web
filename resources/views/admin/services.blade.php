<x-app-layout title="Manajemen Layanan">
    @section('title', 'Manajemen Layanan')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Layanan & Harga') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        addServiceOpen: false, 
        editServiceOpen: false, 
        editService: { id: '', category: '', name: '', price: '', estimated_days: '', description: '' },
        addAddonOpen: false,
        editAddonOpen: false,
        editAddon: { id: '', name: '', price: '' }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Layanan Utama --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-6 border-b pb-4 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Manajemen Layanan Utama
                    </h3>
                    <button @click="addServiceOpen = true" class="px-4 mb-2 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Layanan
                    </button>
                </div>

                <div class="space-y-8">
                    @foreach(\App\Models\Service::categories() as $category)
                        @php $catServices = $services->where('category', $category); @endphp
                        @if($catServices->count() > 0)
                            <div>
                                <h4 class="text-sm mb-4 mt-2 font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $category === 'Special Treatment' ? 'bg-yellow-400' : ($category === 'Cleaning' ? 'bg-blue-400' : ($category === 'Repair Treatment' ? 'bg-red-400' : 'bg-purple-400')) }}"></span>
                                    {{ $category }}
                                </h4>
                                <div class="overflow-x-auto border border-gray-100 dark:border-gray-700 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/30">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Layanan</th>
                                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Estimasi (hari)</th>
                                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($catServices as $service)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $service->name }}</div>
                                                        @if($service->description)
                                                            <div class="text-xs text-gray-400 italic">{{ $service->description }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $service->estimated_days ?: '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex justify-end gap-2">
                                                            <button @click="editServiceOpen = true; editService = { id: '{{ $service->id }}', category: '{{ $service->category }}', name: '{{ $service->name }}', price: '{{ $service->price }}', estimated_days: '{{ $service->estimated_days }}', description: '{{ $service->description }}' }" 
                                                                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow flex items-center gap-1 transition">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                Edit
                                                            </button>
                                                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Hapus layanan ini?')" class="inline">
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
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Manajemen Add-Ons --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-6 border-b pb-4 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Manajemen Add-Ons (Biaya Tambahan)
                    </h3>
                    <button @click="addAddonOpen = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Add-On
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-100 dark:border-gray-700 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/30">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nama Add-On</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($add_ons as $add_on)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">{{ $add_on->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-600 dark:text-orange-400">
                                        Rp {{ number_format($add_on->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <button @click="editAddonOpen = true; editAddon = { id: '{{ $add_on->id }}', name: '{{ $add_on->name }}', price: '{{ $add_on->price }}' }" 
                                                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow flex items-center gap-1 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('admin.add_ons.destroy', $add_on) }}" onsubmit="return confirm('Hapus add-on ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow flex items-center gap-1 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm italic">Belum ada add-on yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODALS LAYANAN --}}
        {{-- Add Service Modal --}}
        <div x-show="addServiceOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak @keydown.escape.window="addServiceOpen = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="addServiceOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="addServiceOpen" x-transition.scale.95 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('admin.services.store') }}">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 border-b pb-2">Tambah Layanan Baru</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label value="Kategori" />
                                    <select name="category" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                        @foreach(\App\Models\Service::categories() as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Nama Layanan" />
                                    <x-text-input name="name" required class="w-full mt-1" placeholder="Cth: Deep Clean Premium" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label value="Harga (Rp)" />
                                        <x-text-input name="price" type="number" required class="w-full mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label value="Estimasi (Hari)" />
                                        <x-text-input name="estimated_days" class="w-full mt-1" placeholder="Cth: 3-5 hari" />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Deskripsi" />
                                    <x-text-input name="description" class="w-full mt-1" placeholder="Deskripsi singkat layanan" />
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                            <x-primary-button type="submit">Simpan Layanan</x-primary-button>
                            <x-secondary-button type="button" @click="addServiceOpen = false">Batal</x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Service Modal --}}
        <div x-show="editServiceOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak @keydown.escape.window="editServiceOpen = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editServiceOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="editServiceOpen" x-transition.scale.95 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="'/admin/services/' + editService.id" method="POST">
                        @csrf @method('PATCH')
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Edit Layanan</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label value="Kategori" />
                                    <select name="category" x-model="editService.category" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                        @foreach(\App\Models\Service::categories() as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Nama Layanan" />
                                    <x-text-input name="name" x-model="editService.name" required class="w-full mt-1" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label value="Harga (Rp)" />
                                        <x-text-input name="price" type="number" x-model="editService.price" required class="w-full mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label value="Estimasi" />
                                        <x-text-input name="estimated_days" x-model="editService.estimated_days" class="w-full mt-1" />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Deskripsi" />
                                    <x-text-input name="description" x-model="editService.description" class="w-full mt-1" />
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                            <x-primary-button type="submit">Update Layanan</x-primary-button>
                            <x-secondary-button type="button" @click="editServiceOpen = false">Batal</x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODALS ADD-ONS --}}
        {{-- Add Addon Modal --}}
        <div x-show="addAddonOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak @keydown.escape.window="addAddonOpen = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="addAddonOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="addAddonOpen" x-transition.scale.95 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('admin.add_ons.store') }}">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Tambah Add-On Baru</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label value="Nama Add-On" />
                                    <x-text-input name="name" required class="w-full mt-1" placeholder="Cth: Insole Clean" />
                                </div>
                                <div>
                                    <x-input-label value="Harga (Rp)" />
                                    <x-text-input name="price" type="number" required class="w-full mt-1" placeholder="10000" />
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                            <x-primary-button type="submit">Simpan Add-On</x-primary-button>
                            <x-secondary-button type="button" @click="addAddonOpen = false">Batal</x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Addon Modal --}}
        <div x-show="editAddonOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak @keydown.escape.window="editAddonOpen = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editAddonOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="editAddonOpen" x-transition.scale.95 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="'/admin/add-ons/' + editAddon.id" method="POST">
                        @csrf @method('PATCH')
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Edit Add-On</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label value="Nama Add-On" />
                                    <x-text-input name="name" x-model="editAddon.name" required class="w-full mt-1" />
                                </div>
                                <div>
                                    <x-input-label value="Harga (Rp)" />
                                    <x-text-input name="price" type="number" x-model="editAddon.price" required class="w-full mt-1" />
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                            <x-primary-button type="submit">Update Add-On</x-primary-button>
                            <x-secondary-button type="button" @click="editAddonOpen = false">Batal</x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
