<x-app-layout title="Manajemen Karyawan">
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
 <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-4 border-b border-gray-200 dark:border-gray-700 last:border-0 gap-4">
     <form method="POST" action="{{ route('admin.employees.update', $employee) }}" class="flex flex-wrap gap-3 items-end w-full sm:w-auto">
         @csrf @method('PATCH')
         <div>
             <x-input-label value="Nama" class="text-xs" />
             <x-text-input name="name" value="{{ $employee->name }}" required class="text-sm w-full sm:w-48" />
         </div>
         <div>
             <x-input-label value="Email" class="text-xs" />
             <x-text-input name="email" value="{{ $employee->email }}" type="email" required class="text-sm w-full sm:w-48" />
         </div>
         <div>
             <x-input-label value="Password (Isi jika ganti)" class="text-xs" />
             <x-text-input name="password" type="password" minlength="8" class="text-sm w-full sm:w-40" placeholder="Biarkan kosong" />
         </div>
         <x-secondary-button type="submit" class="!px-3 !py-1.5 text-xs h-9">Update</x-secondary-button>
     </form>
     <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Hapus karyawan ini?')">
         @csrf @method('DELETE')
         <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 rounded text-xs font-semibold transition h-9">Hapus</button>
     </form>
 </li>
 @endforeach
 </ul>
 </div>
 </div>
 </div>
</x-app-layout>
