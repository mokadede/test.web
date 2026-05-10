<x-app-layout title="Edit Pesanan">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pesanan') }} #{{ $order->order_id_formatted }}
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
                        {{ __('Edit Data Pelanggan & Layanan') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Perbarui detail pesanan pelanggan. Harga akan otomatis terhitung kembali jika layanan atau add-on diubah.") }}
                    </p>
                </header>

                <form method="post" action="{{ route('admin.orders.update', $order) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Data Pelanggan --}}
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

                    {{-- Item Box (Styled like Create) --}}
                    <div class="border border-indigo-100 dark:border-indigo-900/50 rounded-lg p-4 mt-6 bg-indigo-50/30 dark:bg-indigo-900/10 relative">
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-md font-bold text-indigo-700 dark:text-indigo-400">Detail Item</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label :value="__('Merek Sepatu')" />
                                <x-text-input name="shoe_brand" type="text" class="mt-1 block w-full" value="{{ old('shoe_brand', $order->shoe_brand) }}" />
                            </div>
                            <div>
                                <x-input-label :value="__('Ukuran Sepatu')" />
                                <x-text-input name="shoe_size" type="text" class="mt-1 block w-full" value="{{ old('shoe_size', $order->shoe_size) }}" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label :value="__('Kondisi Sepatu')" />
                            <select name="shoe_condition" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Kondisi --</option>
                                @foreach(\App\Models\Order::shoeConditions() as $condition)
                                    <option value="{{ $condition }}" {{ $order->shoe_condition == $condition ? 'selected' : '' }}>{{ $condition }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6">
                            <x-input-label :value="__('Layanan')" />
                            <select x-model="item.service_name" @change="updatePrice()" name="service_name" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
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

                        {{-- Add-Ons (Checkbox Card Style) --}}
                        <div class="mt-6">
                            <x-input-label :value="__('Biaya Tambahan (opsional)')" class="mb-2" />
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                <template x-for="(fee, feeIndex) in additionalFeeOptions" :key="feeIndex">
                                    <label class="flex items-center gap-2 p-2 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-indigo-300 transition-colors">
                                        <input type="checkbox" name="add_ons[]" 
                                            :value="JSON.stringify({name: fee.name, price: fee.price})" 
                                            :checked="item.selected_add_ons.includes(fee.name)"
                                            @change="toggleFee(fee.name, fee.price, $event.target.checked)" 
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                <span x-text="fee.name"></span>
                                                <span class="block text-[10px] text-gray-400" x-text="'+Rp ' + parseInt(fee.price).toLocaleString('id-ID')"></span>
                                            </span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Estimasi Selesai:</span>
                                <span class="text-xs text-indigo-600 font-bold" x-text="item.estimated_days + ' Hari'"></span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500 block">Subtotal:</span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400" x-text="'Rp ' + (item.total_price + item.additional_fees).toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        <input type="hidden" name="total_price" :value="item.total_price + item.additional_fees">
                        <input type="hidden" name="additional_fees" :value="item.additional_fees">
                    </div>

                    {{-- Total Summary Box --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
                        <div class="p-6 bg-indigo-50 dark:bg-gray-900 rounded-xl border border-indigo-100 dark:border-gray-800 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Ringkasan Pembayaran
                            </h3>
                            <div class="flex justify-between items-center pt-3 border-t border-indigo-200 dark:border-gray-700">
                                <span class="text-xl font-bold text-gray-900 dark:text-white">Grand Total:</span>
                                <span class="text-3xl font-black text-indigo-600 dark:text-yellow-400 tracking-tighter" x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"></span>
                            </div>
                            <p x-show="item.discount_amount > 0" class="text-xs text-green-600 mt-2 font-bold" x-text="'Potongan Voucher: -Rp ' + item.discount_amount.toLocaleString('id-ID')"></p>
                        </div>
                    </div>

                    {{-- Pembayaran & Status --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Pembayaran & Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="status" :value="__('Status Pesanan')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach(\App\Models\Order::statuses() as $status)
                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="payment_method" :value="__('Metode Pembayaran')" />
                                <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach(\App\Models\Order::paymentMethods() as $method)
                                        <option value="{{ $method }}" {{ $order->payment_method == $method ? 'selected' : '' }}>{{ $method }}</option>
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

                        {{-- Voucher Section --}}
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                            <x-input-label for="voucher_code" :value="__('Punya Kode Voucher?')" />
                            <div class="flex gap-2 mt-1">
                                <x-text-input id="voucher_code" x-model="item.voucher_code" name="voucher_code" type="text" class="block w-full" placeholder="Contoh: DISKON10" />
                                <button type="button" @click="checkVoucher()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-xs font-bold transition flex-shrink-0">
                                    Gunakan
                                </button>
                            </div>
                            <p x-show="voucherMessage" x-text="voucherMessage" :class="voucherValid ? 'text-green-600' : 'text-red-600'" class="text-[10px] mt-1 font-semibold"></p>
                            <input type="hidden" name="discount_amount" :value="item.discount_amount">
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="mt-4">
                        <x-input-label for="notes" :value="__('Catatan Tambahan (Opsional)')" />
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderForm', () => ({
                services: @json($services),
                additionalFeeOptions: @json($add_ons),
                voucherMessage: '',
                voucherValid: false,
                item: {
                    service_name: '{{ $order->service_name }}',
                    total_price: {{ $order->total_price - ($order->additional_fees ?? 0) + ($order->discount_amount ?? 0) }},
                    additional_fees: {{ $order->additional_fees ?? 0 }},
                    estimated_days: '{{ $order->estimated_days ?? "-" }}',
                    selected_add_ons: @json($order->add_ons ? array_column($order->add_ons, 'name') : []),
                    voucher_code: '{{ $order->voucher_code }}',
                    discount_amount: {{ $order->discount_amount ?? 0 }}
                },
                get grandTotal() {
                    return (this.item.total_price + this.item.additional_fees) - this.item.discount_amount;
                },
                updatePrice() {
                    const service = this.services.find(s => s.name === this.item.service_name);
                    if (service) {
                        this.item.total_price = parseInt(service.price);
                        this.item.estimated_days = service.estimated_days || '-';
                    }
                },
                toggleFee(feeName, feePrice, isChecked) {
                    if (isChecked) {
                        this.item.selected_add_ons.push(feeName);
                        this.item.additional_fees += parseInt(feePrice);
                    } else {
                        const index = this.item.selected_add_ons.indexOf(feeName);
                        if (index > -1) {
                            this.item.selected_add_ons.splice(index, 1);
                            this.item.additional_fees -= parseInt(feePrice);
                        }
                    }
                },
                async checkVoucher() {
                    if (!this.item.voucher_code) return;
                    
                    try {
                        const response = await fetch('{{ route('admin.vouchers.check') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                code: this.item.voucher_code,
                                subtotal: this.item.total_price + this.item.additional_fees
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            const voucher = result.data;
                            let discount = 0;
                            if (voucher.discount_type === 'percent') {
                                discount = (this.item.total_price + this.item.additional_fees) * (voucher.discount_amount / 100);
                            } else {
                                discount = voucher.discount_amount;
                            }
                            this.item.discount_amount = discount;
                            alert(`Sukses! Voucher berhasil digunakan.\nPotongan: Rp ${discount.toLocaleString('id-ID')}`);
                        } else {
                            this.item.discount_amount = 0;
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
