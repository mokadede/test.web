<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
 {{ __('Manajemen Karyawan') }}
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
 <form method="POST" action="{{ route('admin.employees.store') }}" class="mb-6 flex gap-4 items-end">
 @csrf
 <div><x-input-label value="Nama" /><x-text-input name="name" required /></div>
 <div><x-input-label value="Email" /><x-text-input name="email" type="email" required /></div>
 <div><x-input-label value="Password" /><x-text-input name="password" type="password" required minlength="8" /></div>
 <x-primary-button>Tambah Karyawan</x-primary-button>
 </form>

 <ul>
 @foreach($employees as $employee)
 <li class="flex justify-between items-center py-4 border-b border-white/5 last:border-0">
 <span class="text-gray-800 dark:text-gray-200">{{ $employee->name }} ({{ $employee->email }})</span>
 <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}">
 @csrf @method('DELETE')
 <button type="submit" class="text-red-500 text-sm hover:underline">Hapus</button>
 </form>
 </li>
 @endforeach
 </ul>
 </div>
 </div>
 </div>
</x-app-layout>
