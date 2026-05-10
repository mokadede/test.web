<x-app-layout title="Buat Pesanan Baru">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Pesanan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="orderForm()">
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
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Data Pelanggan & Sepatu') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Isi data pesanan lengkap untuk pelanggan. Anda dapat menambahkan beberapa sepatu sekaligus.") }}
                    </p>
                </header>

                <form method="post" action="{{ route('orders.store') }}" class="mt-6 space-y-6" id="orderForm">
                    @csrf

                    {{-- Data Pelanggan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="customer_name" :value="__('Nama Pelanggan')" />
                            <x-text-input id="customer_name" name="customer_name" type="text" class="mt-1 block w-full" required value="{{ old('customer_name') }}" placeholder="Nama lengkap pelanggan" />
                        </div>
                        <div>
                            <x-input-label for="phone_number" :value="__('Nomor HP / WhatsApp')" />
                            <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" required value="{{ old('phone_number') }}" placeholder="08xxxxxxxxxx" />
                        </div>
                    </div>

                    {{-- Items Loop --}}
                    <template x-for="(item, index) in items" :key="index">
                        <div class="border border-indigo-100 dark:border-indigo-900/50 rounded-lg p-4 mt-6 bg-indigo-50/30 dark:bg-indigo-900/10 relative">
                            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-md font-bold text-indigo-700 dark:text-indigo-400">Sepatu #<span x-text="index + 1"></span></h3>
                                <button type="button" x-show="items.length > 1" @click="removeItem(index)" class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label :value="__('Merek Sepatu')" />
                                    <x-text-input x-model="item.shoe_brand" x-bind:name="'items[' + index + '][shoe_brand]'" type="text" class="mt-1 block w-full" placeholder="Cth: Nike, Adidas" />
                                </div>
                                <div>
                                    <x-input-label :value="__('Ukuran Sepatu')" />
                                    <x-text-input x-model="item.shoe_size" x-bind:name="'items[' + index + '][shoe_size]'" type="text" class="mt-1 block w-full" placeholder="Cth: 42" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-input-label :value="__('Kondisi Sepatu')" />
                                <select x-model="item.shoe_condition" x-bind:name="'items[' + index + '][shoe_condition]'" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih Kondisi --</option>
                                    @foreach(\App\Models\Order::shoeConditions() as $condition)
                                        <option value="{{ $condition }}">{{ $condition }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-6">
                                <x-input-label :value="__('Layanan')" />
                                <select x-model="item.service_name" @change="updatePrice(index)" x-bind:name="'items[' + index + '][service_name]'" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    <optgroup label="Cleaning">
                                    @foreach($services->where('category', 'Cleaning') as $service)
                                        <option value="{{ $service->name }}" data-price="{{ $service->price }}">{{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}</option>
                                    @endforeach
                                    </optgroup>
                                    <optgroup label="Special Treatment">
                                    @foreach($services->where('category', 'Special Treatment') as $service)
                                        <option value="{{ $service->name }}" data-price="{{ $service->price }}">{{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}</option>
                                    @endforeach
                                    </optgroup>
                                    <optgroup label="Repaint Treatment">
                                    @foreach($services->where('category', 'Repaint Treatment') as $service)
                                        <option value="{{ $service->name }}" data-price="{{ $service->price }}">{{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}</option>
                                    @endforeach
                                    </optgroup>
                                    <optgroup label="Repair Treatment">
                                    @foreach($services->where('category', 'Repair Treatment') as $service)
                                        <option value="{{ $service->name }}" data-price="{{ $service->price }}">{{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}</option>
                                    @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div class="mt-4" x-show="item.is_custom_price" x-cloak>
                                <x-input-label :value="__('Harga Custom (Rp)')" />
                                <x-text-input x-model.number="item.total_price" type="number" class="mt-1 block w-full" placeholder="Masukkan harga kesepakatan" />
                            </div>

                            <div class="mt-6">
                                <x-input-label :value="__('Biaya Tambahan (opsional)')" class="mb-2" />
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    <template x-for="(fee, feeIndex) in additionalFeeOptions" :key="feeIndex">
                                        <label class="flex items-center gap-2 p-2 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-indigo-300 transition-colors">
                                            <input type="checkbox" :value="fee.name" @change="toggleFee(index, fee.name, fee.price, $event.target.checked)" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <div>
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    <span x-text="fee.name"></span>
                                                    <span class="block text-[10px] text-gray-400" x-text="fee.name === 'Suede Care' ? '+Rp 7K - 25K' : '+Rp ' + parseInt(fee.price).toLocaleString('id-ID')"></span>
                                                </span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-4" x-show="item.selected_add_ons.includes('Suede Care')" x-cloak>
                                <x-input-label :value="__('Harga Suede Care (Rp)')" />
                                <x-text-input x-model.number="item.suede_care_price" @input="recalculateFees(index)" type="number" class="mt-1 block w-full" placeholder="Cth: 15000" />
                            </div>

                            <div class="mt-4 space-y-4" x-show="item.selected_add_ons.includes('Basah, Berbau, Jamur, Kotoran Hewan')" x-cloak>
                                <div>
                                    <x-input-label :value="__('Harga Tambahan (Basah/Berbau/Jamur/Kotoran Hewan)')" />
                                    <x-text-input x-model.number="item.complex_add_on_price" @input="recalculateFees(index)" type="number" class="mt-1 block w-full" placeholder="Cth: 20000" />
                                </div>
                                <div>
                                    <x-input-label :value="__('Tambahan Estimasi Hari')" />
                                    <x-text-input x-model="item.extra_days" type="text" class="mt-1 block w-full" placeholder="Cth: +2 hari" />
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Subtotal Layanan:</span>
                                    <span class="text-[10px] text-gray-500" x-text="'Estimasi: ' + item.estimated_days + (item.extra_days ? ' (' + item.extra_days + ')' : '')"></span>
                                </div>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400" x-text="'Rp ' + (item.total_price + item.additional_fees).toLocaleString('id-ID')"></span>
                            </div>

                            <input type="hidden" x-bind:name="'items[' + index + '][total_price]'" :value="item.total_price">
                            <input type="hidden" x-bind:name="'items[' + index + '][additional_fees]'" :value="item.additional_fees">
                            <input type="hidden" x-bind:name="'items[' + index + '][estimated_days]'" :value="item.estimated_days + (item.extra_days ? ' (' + item.extra_days + ')' : '')">
                        </div>
                    </template>

                    <button type="button" @click="addItem()" class="mt-4 flex items-center justify-center w-full py-2 px-4 border-2 border-dashed border-indigo-300 dark:border-indigo-700 text-indigo-600 dark:text-indigo-400 font-semibold rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                        + Tambah Sepatu Lain
                    </button>

                    {{-- Total Keseluruhan --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
                        <div class="p-6 bg-indigo-50 dark:bg-gray-900 rounded-xl border border-indigo-100 dark:border-gray-800 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Total Keseluruhan
                            </h3>
                            <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-300 mb-2">
                                <span>Total Item Sepatu:</span>
                                <span class="font-bold text-gray-900 dark:text-white" x-text="items.length + ' Pasang'"></span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-indigo-200 dark:border-gray-700 mt-2">
                                <span class="text-xl font-bold text-gray-900 dark:text-white">Grand Total:</span>
                                <span class="text-3xl font-black text-indigo-600 dark:text-yellow-400 tracking-tighter" x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"></span>
                            </div>
                            <p x-show="discount_amount > 0" class="text-xs text-green-600 mt-2 font-bold" x-text="'Potongan Voucher: -Rp ' + discount_amount.toLocaleString('id-ID')"></p>
                        </div>
                    </div>

                    {{-- Pembayaran & Status --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Pembayaran & Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="payment_method" :value="__('Metode Pembayaran')" />
                                <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach(\App\Models\Order::paymentMethods() as $method)
                                        <option value="{{ $method }}" {{ old('payment_method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="payment_status" :value="__('Status Pembayaran')" />
                                <select id="payment_status" name="payment_status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="unpaid" {{ old('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                </select>
                            </div>
                        </div>

                        {{-- Voucher Section --}}
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                            <x-input-label for="voucher_code" :value="__('Punya Kode Voucher?')" />
                            <div class="flex gap-2 mt-1">
                                <x-text-input id="voucher_code" x-model="voucher_code" name="voucher_code" type="text" class="block w-full" placeholder="Contoh: DISKON10" />
                                <button type="button" @click="checkVoucher()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-xs font-bold transition flex-shrink-0">
                                    Gunakan
                                </button>
                            </div>
                            <input type="hidden" name="discount_amount" :value="discount_amount">
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="mt-4">
                        <x-input-label for="notes" :value="__('Catatan Tambahan (Opsional)')" />
                        <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="Catatan tambahan untuk pesanan ini...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button id="submitBtn">{{ __('Simpan Pesanan') }}</x-primary-button>
                        <a href="{{ route('admin.orders') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderForm', () => ({
                services: @json($services),
                additionalFeeOptions: @json($add_ons),
                voucher_code: '',
                discount_amount: 0,
                items: [
                    { shoe_brand: '', shoe_size: '', shoe_condition: '', service_name: '', total_price: 0, additional_fees: 0, is_custom_price: false, selected_add_ons: [], suede_care_price: 0, complex_add_on_price: 0, extra_days: '', estimated_days: '-' }
                ],
                get grandTotal() {
                    const subtotal = this.items.reduce((total, item) => total + item.total_price + item.additional_fees, 0);
                    return subtotal - this.discount_amount;
                },
                addItem() {
                    this.items.push({ shoe_brand: '', shoe_size: '', shoe_condition: '', service_name: '', total_price: 0, additional_fees: 0, is_custom_price: false, selected_add_ons: [], suede_care_price: 0, complex_add_on_price: 0, extra_days: '', estimated_days: '-' });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                updatePrice(index) {
                    const serviceName = this.items[index].service_name;
                    const service = this.services.find(s => s.name === serviceName);
                    if (service) {
                        this.items[index].total_price = parseInt(service.price);
                        this.items[index].estimated_days = service.estimated_days || '-';
                        this.items[index].is_custom_price = (serviceName === 'Bag / Hat Cleaning' || serviceName === 'Custom Repair');
                    } else {
                        this.items[index].total_price = 0;
                        this.items[index].estimated_days = '-';
                        this.items[index].is_custom_price = false;
                    }
                },
                toggleFee(index, feeName, feePrice, isChecked) {
                    if (isChecked) {
                        this.items[index].selected_add_ons.push(feeName);
                    } else {
                        const i = this.items[index].selected_add_ons.indexOf(feeName);
                        if (i > -1) this.items[index].selected_add_ons.splice(i, 1);
                    }
                    this.recalculateFees(index);
                },
                recalculateFees(index) {
                    let total = 0;
                    this.items[index].selected_add_ons.forEach(name => {
                        if (name === 'Suede Care') {
                            total += parseInt(this.items[index].suede_care_price) || 0;
                        } else if (name === 'Basah, Berbau, Jamur, Kotoran Hewan') {
                            total += parseInt(this.items[index].complex_add_on_price) || 0;
                        } else {
                            const fee = this.additionalFeeOptions.find(f => f.name === name);
                            if (fee) total += parseInt(fee.price);
                        }
                    });
                    this.items[index].additional_fees = total;
                },
                async checkVoucher() {
                    if (!this.voucher_code) return;
                    
                    const subtotal = this.items.reduce((total, item) => total + item.total_price + item.additional_fees, 0);
                    
                    try {
                        const response = await fetch('{{ route('admin.vouchers.check') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                code: this.voucher_code,
                                subtotal: subtotal
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            const voucher = result.data;
                            let discount = 0;
                            if (voucher.discount_type === 'percent') {
                                discount = subtotal * (voucher.discount_amount / 100);
                            } else {
                                discount = voucher.discount_amount;
                            }
                            this.discount_amount = discount;
                            alert(`Sukses! Voucher berhasil digunakan.\nPotongan: Rp ${discount.toLocaleString('id-ID')}`);
                        } else {
                            this.discount_amount = 0;
                            alert('Gagal: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Voucher check failed:', error);
                        alert('Terjadi kesalahan sistem saat mengecek voucher.');
                    }
                }
            }))
        })
    </script>
</x-app-layout>
