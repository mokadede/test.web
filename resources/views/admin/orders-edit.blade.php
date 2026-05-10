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

                    {{-- Add-Ons Checkboxes --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Pilih Biaya Tambahan (Add-Ons)</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($add_ons as $addon)
                                @php
                                    $isSelected = false;
                                    if ($order->add_ons) {
                                        foreach ($order->add_ons as $item) {
                                            if (is_array($item) && $item['name'] === $addon->name) {
                                                $isSelected = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <label class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors shadow-sm">
                                    <input type="checkbox" name="add_ons[]" 
                                        value="{{ json_encode(['name' => $addon->name, 'price' => $addon->price]) }}" 
                                        class="addon-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        data-price="{{ $addon->price }}"
                                        {{ $isSelected ? 'checked' : '' }}>
                                    <div>
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300 block">
                                            {{ $addon->name }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400">
                                            +Rp {{ number_format($addon->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Harga & Estimasi --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8 mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-5 bg-indigo-50 dark:bg-gray-900/50 rounded-xl border border-indigo-100 dark:border-gray-800 shadow-sm">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Total Tagihan
                                </h3>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-indigo-600 dark:text-yellow-400">Rp <span id="display_total_price">{{ number_format($order->total_price, 0, ',', '.') }}</span></span>
                                </div>
                                <input type="hidden" id="total_price" name="total_price" value="{{ $order->total_price }}">
                                <p class="text-[10px] text-gray-500 mt-1">*Otomatis terhitung dari Layanan + Add-Ons</p>
                            </div>

                            <div class="p-5 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Biaya Tambahan
                                </h3>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xl font-bold text-gray-700 dark:text-gray-300">Rp <span id="display_additional_fees">{{ number_format($order->additional_fees, 0, ',', '.') }}</span></span>
                                </div>
                                <input type="hidden" id="additional_fees" name="additional_fees" value="{{ $order->additional_fees }}">
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
            const addonCheckboxes = document.querySelectorAll('.addon-checkbox');
            const additionalFeesInput = document.getElementById('additional_fees');
            const totalPriceInput = document.getElementById('total_price');

            const serviceMap = {
                'Special Treatment': @json($specialTreatment),
                'Cleaning': @json($cleaning),
                'Repair Treatment': @json($repairTreatment),
                'Repaint Treatment': @json($repaintTreatment),
            };
            const allServices = @json($services);

            function calculateTotals() {
                // Get service price
                const selectedServiceOption = serviceSelect.options[serviceSelect.selectedIndex];
                const servicePrice = selectedServiceOption ? parseInt(selectedServiceOption.dataset.price || 0) : 0;

                // Get addons price
                let addonsTotal = 0;
                addonCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        addonsTotal += parseInt(cb.dataset.price || 0);
                    }
                });

                const total = servicePrice + addonsTotal;

                // Update inputs
                additionalFeesInput.value = addonsTotal;
                totalPriceInput.value = total;

                // Update displays (formatted)
                document.getElementById('display_additional_fees').textContent = addonsTotal.toLocaleString('id-ID');
                document.getElementById('display_total_price').textContent = total.toLocaleString('id-ID');
            }

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
                    
                    calculateTotals();
                });
            }

            if (serviceSelect) {
                serviceSelect.addEventListener('change', calculateTotals);
            }

            addonCheckboxes.forEach(cb => {
                cb.addEventListener('change', calculateTotals);
            });

            // Initial calculation
            calculateTotals();
        });
    </script>
</x-app-layout>
