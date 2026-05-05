<x-app-layout title="Manajemen Layanan">
    @section('title', 'Manajemen Layanan')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Layanan & Harga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Tambah Layanan --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tambah Layanan Baru</h3>
                <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <x-input-label value="Kategori" />
                            <select name="category" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                @foreach(\App\Models\Service::categories() as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Nama Layanan" />
                            <x-text-input name="name" required class="w-full text-sm" placeholder="Cth: Deep Clean One Day" />
                        </div>
                        <div>
                            <x-input-label value="Harga (Rp)" />
                            <x-text-input name="price" type="number" required class="w-full text-sm" placeholder="50000" />
                        </div>
                        <div>
                            <x-input-label value="Estimasi Pengerjaan" />
                            <x-text-input name="estimated_days" class="w-full text-sm" placeholder="Cth: 3-5 hari" />
                        </div>
                    </div>
                    <div>
                        <x-input-label value="Deskripsi (Opsional)" />
                        <x-text-input name="description" class="w-full text-sm" placeholder="Deskripsi singkat layanan" />
                    </div>
                    <x-primary-button>Tambah Layanan</x-primary-button>
                </form>
            </div>

            {{-- Daftar Layanan per Kategori --}}
            @foreach(\App\Models\Service::categories() as $category)
                @php $catServices = $services->where('category', $category); @endphp
                @if($catServices->count() > 0)
                    <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ $category === 'Special Treatment' ? 'bg-yellow-400' : ($category === 'Cleaning' ? 'bg-blue-400' : ($category === 'Repair Treatment' ? 'bg-red-400' : 'bg-purple-400')) }}"></span>
                            {{ $category }}
                            <span class="text-xs font-normal text-gray-400">({{ $catServices->count() }} layanan)</span>
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estimasi</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($catServices as $service)
                                        <tr>
                                            <td class="px-3 py-3">
                                                <form method="POST" action="{{ route('admin.services.update', $service) }}" class="flex flex-wrap gap-2 items-center" id="form-service-{{ $service->id }}">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="category" value="{{ $service->category }}">
                                                    <x-text-input name="name" value="{{ $service->name }}" class="text-sm w-48" />
                                            </td>
                                            <td class="px-3 py-3">
                                                <x-text-input name="price" value="{{ $service->price }}" type="number" class="text-sm w-24" />
                                            </td>
                                            <td class="px-3 py-3">
                                                <x-text-input name="estimated_days" value="{{ $service->estimated_days }}" class="text-sm w-28" placeholder="-" />
                                            </td>
                                            <td class="px-3 py-3">
                                                <x-text-input name="description" value="{{ $service->description }}" class="text-sm w-48" placeholder="Deskripsi" />
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="flex gap-2 items-center">
                                                    <x-secondary-button type="submit" class="text-xs !py-1 !px-2">Save</x-secondary-button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Hapus layanan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 rounded text-xs font-semibold transition">Hapus</button>
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

            {{-- Manajemen Add-Ons (Biaya Tambahan) --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-orange-400"></span>
                        Manajemen Add-Ons (Biaya Tambahan)
                    </h3>
                </div>

                {{-- Form Tambah Add-On --}}
                <form method="POST" action="{{ route('admin.add_ons.store') }}" class="mb-8 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <x-input-label value="Nama Add-On" />
                            <x-text-input name="name" required class="w-full text-sm mt-1" placeholder="Cth: Change Colour Repaint" />
                        </div>
                        <div>
                            <x-input-label value="Harga (Rp)" />
                            <x-text-input name="price" type="number" required class="w-full text-sm mt-1" placeholder="50000" />
                        </div>
                        <x-primary-button class="h-10 justify-center">Tambah Add-On</x-primary-button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($add_ons as $add_on)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.add_ons.update', $add_on) }}" class="flex items-center" id="form-addon-{{ $add_on->id }}">
                                            @csrf @method('PATCH')
                                            <x-text-input name="name" value="{{ $add_on->name }}" class="text-sm w-full sm:w-64" />
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <x-text-input name="price" value="{{ $add_on->price }}" type="number" class="text-sm w-32" />
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2 items-center">
                                            <x-secondary-button type="submit" class="text-xs !py-1 !px-2">Save</x-secondary-button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.add_ons.destroy', $add_on) }}" onsubmit="return confirm('Hapus add-on ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 rounded text-xs font-semibold transition">Hapus</button>
                                        </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm italic">
                                        Belum ada add-on yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
