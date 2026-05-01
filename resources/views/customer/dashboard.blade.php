<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Pesan Layanan Laundry') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Tambahkan satu atau lebih sepatu untuk diproses dalam satu transaksi.") }}
                    </p>
                </header>

                <form method="post" action="{{ route('orders.store') }}" class="mt-6 space-y-6" id="orderForm">
                    @csrf

                    <!-- Dynamic Items Container -->
                    <div id="itemsContainer">
                        <div class="item-row p-4 border border-gray-200 dark:border-gray-700 rounded-lg space-y-4" data-index="0">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300">Sepatu #1</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label :value="__('Layanan')" />
                                    <select name="items[0][service_id]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label :value="__('Nama/Tipe Sepatu')" />
                                    <x-text-input name="items[0][shoe_name]" type="text" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label :value="__('Merek (Opsional)')" />
                                    <x-text-input name="items[0][shoe_brand]" type="text" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Item Button -->
                    <button type="button" id="addItemBtn" class="w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-sm font-semibold text-gray-500 dark:text-gray-400 hover:border-indigo-400 hover:text-indigo-500 transition">
                        + Tambah Sepatu Lagi
                    </button>

                    @error('items')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <div>
                        <x-input-label for="notes" :value="__('Catatan Tambahan (Opsional)')" />
                        <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    </div>

                    <div>
                        <x-input-label for="voucher_code" :value="__('Kode Voucher (Opsional)')" />
                        <x-text-input id="voucher_code" name="voucher_code" type="text" class="mt-1 block w-full" placeholder="Masukkan kode voucher jika ada" />
                        <x-input-error class="mt-2" :messages="$errors->get('voucher_code')" />
                    </div>

                    <div>
                        <x-input-label for="payment_method" :value="__('Metode Pembayaran')" />
                        <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="QRIS">QRIS (Otomatis)</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Cash">Cash (Bayar di Toko)</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button id="submitBtn">{{ __('Pesan Sekarang') }}</x-primary-button>
                        <span id="loadingText" class="text-sm text-gray-500 hidden italic">Sedang memproses pesanan...</span>
                    </div>
                </form>

                <script>
                    document.getElementById('orderForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const form = this;
                        const submitBtn = document.getElementById('submitBtn');
                        const loadingText = document.getElementById('loadingText');
                        
                        // Disable button and show loading
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50');
                        loadingText.classList.remove('hidden');
                        
                        const formData = new FormData(form);
                        
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.payment_url) {
                                    // Open Xendit in new window
                                    window.location.href = data.payment_url;
                                } else {
                                    // Success but Cash
                                    window.location.reload();
                                }
                            } else {
                                alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                                submitBtn.disabled = false;
                                submitBtn.classList.remove('opacity-50');
                                loadingText.classList.add('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses pesanan.');
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50');
                            loadingText.classList.add('hidden');
                        });
                    });
                </script>
            </div>

            <!-- Order History -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Riwayat Pesanan Saya') }}
                    </h2>
                </header>
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kode Tracking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono font-bold text-gray-900 dark:text-gray-100">{{ $order->tracking_code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->items->count() }} sepatu
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-semibold text-gray-500">{{ $order->payment_method }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase w-fit {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                        @if($order->payment_status === 'unpaid' && $order->payment_method !== 'Cash')
                                            <a href="{{ route('payment.pay', $order) }}" class="text-xs text-indigo-600 hover:underline font-bold mt-1">Bayar Sekarang</a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                            @if($orders->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">Belum ada pesanan.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic item JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let itemIndex = 1;
            const container = document.getElementById('itemsContainer');
            const addBtn = document.getElementById('addItemBtn');
            const servicesOptions = document.querySelector('.item-row select').innerHTML;

            addBtn.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'item-row p-4 border border-gray-200 dark:border-gray-700 rounded-lg space-y-4 mt-4';
                row.dataset.index = itemIndex;
                row.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300">Sepatu #${itemIndex + 1}</h4>
                        <button type="button" class="remove-item text-red-500 text-sm hover:underline">Hapus</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Layanan</label>
                            <select name="items[${itemIndex}][service_id]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                ${servicesOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama/Tipe Sepatu</label>
                            <input name="items[${itemIndex}][shoe_name]" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required />
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Merek (Opsional)</label>
                            <input name="items[${itemIndex}][shoe_brand]" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                        </div>
                    </div>
                `;
                container.appendChild(row);
                itemIndex++;

                // Rebind remove buttons
                row.querySelector('.remove-item').addEventListener('click', () => {
                    row.remove();
                    renumberItems();
                });
            });

            function renumberItems() {
                const rows = container.querySelectorAll('.item-row');
                rows.forEach((row, i) => {
                    row.querySelector('h4').textContent = `Sepatu #${i + 1}`;
                });
            }
        });
    </script>
</x-app-layout>
