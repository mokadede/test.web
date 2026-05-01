<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pesanan') }} #{{ $order->tracking_code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="post" action="{{ route('admin.orders.update', $order) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="status" :value="__('Status Pesanan')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="payment_status" :value="__('Status Pembayaran')" />
                            <select id="payment_status" name="payment_status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Item Sepatu</h3>
                        
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300 mb-4">Sepatu #{{ $loop->iteration }}</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label :value="__('Layanan')" />
                                            <select name="items[{{ $item->id }}][service_id]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" {{ $item->service_id == $service->id ? 'selected' : '' }}>
                                                        {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Nama/Tipe Sepatu')" />
                                            <x-text-input name="items[{{ $item->id }}][shoe_name]" type="text" class="mt-1 block w-full" value="{{ $item->shoe_name }}" required />
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Merek')" />
                                            <x-text-input name="items[{{ $item->id }}][shoe_brand]" type="text" class="mt-1 block w-full" value="{{ $item->shoe_brand }}" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <x-input-label :value="__('Ukuran Sepatu')" />
                                            <x-text-input name="items[{{ $item->id }}][shoe_size]" type="text" class="mt-1 block w-full" value="{{ $item->shoe_size }}" placeholder="Cth: 42" />
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Bahan Sepatu')" />
                                            <select name="items[{{ $item->id }}][shoe_material]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                <option value="">-- Pilih Bahan --</option>
                                                <option value="Kanvas" {{ $item->shoe_material == 'Kanvas' ? 'selected' : '' }}>Kanvas</option>
                                                <option value="Suede" {{ $item->shoe_material == 'Suede' ? 'selected' : '' }}>Suede</option>
                                                <option value="Kulit" {{ $item->shoe_material == 'Kulit' ? 'selected' : '' }}>Kulit</option>
                                                <option value="Nubuck" {{ $item->shoe_material == 'Nubuck' ? 'selected' : '' }}>Nubuck</option>
                                                <option value="Mesh" {{ $item->shoe_material == 'Mesh' ? 'selected' : '' }}>Mesh</option>
                                                <option value="Rajut" {{ $item->shoe_material == 'Rajut' ? 'selected' : '' }}>Rajut</option>
                                                <option value="Poliester" {{ $item->shoe_material == 'Poliester' ? 'selected' : '' }}>Poliester</option>
                                                <option value="Karet" {{ $item->shoe_material == 'Karet' ? 'selected' : '' }}>Karet</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        <a href="{{ route('admin.orders') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
