<x-app-layout title="Edit Pesanan">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pesanan') }} #{{ $order->tracking_code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="post" action="{{ route('admin.orders.update', $order) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="status" :value="__('Status Pesanan')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach(\App\Models\Order::statuses() as $status)
                                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
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

                    {{-- Data Pelanggan --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Data Pelanggan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="customer_name" :value="__('Nama Pelanggan')" />
                                <x-text-input id="customer_name" name="customer_name" type="text" class="mt-1 block w-full" required value="{{ old('customer_name', $order->customer_name) }}" />
                            </div>
                            <div>
                                <x-input-label for="phone_number" :value="__('Nomor HP / WhatsApp')" />
                                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" required value="{{ old('phone_number', $order->phone_number) }}" />
                            </div>
                        </div>
                    </div>

                    {{-- Detail Sepatu --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Detail Sepatu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="shoe_brand" :value="__('Merek Sepatu')" />
                                <x-text-input id="shoe_brand" name="shoe_brand" type="text" class="mt-1 block w-full" value="{{ old('shoe_brand', $order->shoe_brand) }}" />
                            </div>
                            <div>
                                <x-input-label for="shoe_size" :value="__('Ukuran Sepatu')" />
                                <x-text-input id="shoe_size" name="shoe_size" type="text" class="mt-1 block w-full" value="{{ old('shoe_size', $order->shoe_size) }}" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="shoe_condition" :value="__('Kondisi Sepatu')" />
                            <select id="shoe_condition" name="shoe_condition" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Kondisi --</option>
                                @foreach(\App\Models\Order::shoeConditions() as $condition)
                                    <option value="{{ $condition }}" {{ old('shoe_condition', $order->shoe_condition) == $condition ? 'selected' : '' }}>{{ $condition }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Layanan --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Layanan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="service_category" :value="__('Kategori Layanan')" />
                                <select id="service_category" name="service_category" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach(\App\Models\Order::serviceCategories() as $cat)
                                        <option value="{{ $cat }}" {{ old('service_category', $order->service_category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="service_name" :value="__('Nama Layanan')" />
                                <select id="service_name" name="service_name" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($services as $service)
                                        <option value="{{ $service->name }}" data-price="{{ $service->price }}" {{ old('service_name', $order->service_name) == $service->name ? 'selected' : '' }}>
                                            {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Harga & Estimasi --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Harga & Estimasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="total_price" :value="__('Total Harga (Rp)')" />
                                <x-text-input id="total_price" name="total_price" type="number" class="mt-1 block w-full" required value="{{ old('total_price', $order->total_price) }}" min="0" />
                            </div>
                            <div>
                                <x-input-label for="additional_fees" :value="__('Biaya Tambahan (Rp)')" />
                                <x-text-input id="additional_fees" name="additional_fees" type="number" class="mt-1 block w-full" value="{{ old('additional_fees', $order->additional_fees) }}" min="0" />
                            </div>
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Pembayaran</h3>
                        <div>
                            <x-input-label for="payment_method" :value="__('Metode Pembayaran')" />
                            <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach(\App\Models\Order::paymentMethods() as $method)
                                    <option value="{{ $method }}" {{ old('payment_method', $order->payment_method) == $method ? 'selected' : '' }}>{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <x-input-label for="notes" :value="__('Catatan Tambahan')" />
                        <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        <a href="{{ route('admin.orders') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        $specialTreatment = $services->filter(function($s) {
            return in_array($s->name, ['Whitening (For Upper)', 'Unyellowing (For Midsole)', 'Brightening', 'All Special Treatment']);
        })->values();
        
        $cleaning = $services->filter(function($s) {
            return in_array($s->name, ['Deep Clean One Day', 'Deep Clean Two Days', 'Deep Clean Three Days', 'Deep Clean Four - Five Days', 'Express Clean (With Deep Clean)', 'Bag / Hat Cleaning', 'Special Condition', 'Reguler Clean']);
        })->values();
        
        $repairTreatment = $services->filter(function($s) {
            return in_array($s->name, ['Custom Repair', 'Re-Glue + Press', 'Re-Glue Manual']);
        })->values();
        
        $repaintTreatment = $services->filter(function($s) {
            return in_array($s->name, ['Repaint Canvas', 'Repaint Leather', 'Repaint Midsole', 'Change Colour Repaint']);
        })->values();
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.getElementById('service_category');
            const serviceSelect = document.getElementById('service_name');
            const totalPriceInput = document.getElementById('total_price');

            const serviceMap = {
                'Special Treatment': @json($specialTreatment),
                'Cleaning': @json($cleaning),
                'Repair Treatment': @json($repairTreatment),
                'Repaint Treatment': @json($repaintTreatment),
            };
            const allServices = @json($services);

            if (categorySelect && serviceSelect) {
                categorySelect.addEventListener('change', function() {
                    const cat = this.value;
                    const list = cat ? (serviceMap[cat] || []) : allServices;
                    
                    serviceSelect.innerHTML = '';
                    list.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.name;
                        opt.dataset.price = s.price;
                        opt.textContent = s.name + ' - Rp ' + parseInt(s.price).toLocaleString('id-ID');
                        serviceSelect.appendChild(opt);
                    });
                    
                    if (list.length > 0) {
                        totalPriceInput.value = list[0].price;
                    }
                });
            }

            if (serviceSelect) {
                serviceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.dataset.price || 0;
                    totalPriceInput.value = price;
                });
            }
        });
    </script>
</x-app-layout>
