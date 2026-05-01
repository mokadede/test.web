<x-app-layout>
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

 <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
 <form method="POST" action="{{ route('admin.services.store') }}" class="mb-6 flex gap-4 items-end">
 @csrf
 <div>
 <x-input-label value="Nama Layanan" />
 <x-text-input name="name" required />
 </div>
 <div>
 <x-input-label value="Harga (Rp)" />
 <x-text-input name="price" type="number" required />
 </div>
 <x-primary-button>Tambah Layanan</x-primary-button>
 </form>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
 @foreach($services as $service)
 <div class="border border-white/10 p-4 rounded-xl flex justify-between items-center bg-black/20">
 <form method="POST" action="{{ route('admin.services.update', $service) }}" class="flex gap-2">
 @csrf @method('PATCH')
 <x-text-input name="name" value="{{ $service->name }}" class="text-sm w-32" />
 <x-text-input name="price" value="{{ $service->price }}" type="number" class="text-sm w-24" />
 <x-secondary-button type="submit" class="text-xs">Save</x-secondary-button>
 </form>
 <form method="POST" action="{{ route('admin.services.destroy', $service) }}">
 @csrf @method('DELETE')
 <button type="submit" class="text-red-500 text-sm hover:underline">Hapus</button>
 </form>
 </div>
 @endforeach
 </div>
 </div>
 </div>
 </div>
</x-app-layout>
