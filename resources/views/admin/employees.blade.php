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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tambah Karyawan Baru</h3>
        <form method="POST" action="{{ route('admin.employees.store') }}" class="mb-8 flex flex-col sm:flex-row gap-4 items-end bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
            @csrf
            <div class="w-full sm:flex-1">
                <x-input-label value="Nama" />
                <x-text-input name="name" required class="w-full mt-1" />
            </div>
            <div class="w-full sm:flex-1">
                <x-input-label value="Email" />
                <x-text-input name="email" type="email" required class="w-full mt-1" />
            </div>
            <div class="w-full sm:flex-1">
                <x-input-label value="Password" />
                <x-text-input name="password" type="password" required minlength="8" class="w-full mt-1" />
            </div>
            <x-primary-button class="w-full sm:w-auto h-10 justify-center">Tambah</x-primary-button>
        </form>

        <h3 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
            Daftar Karyawan
        </h3>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($employees as $employee)
            <li class="py-6">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 w-full">
                        <form method="POST" action="{{ route('admin.employees.update', $employee) }}" class="grid grid-cols-1 md:grid-cols-3 lg:flex lg:flex-wrap gap-4 w-full">
                            @csrf @method('PATCH')
                            <div class="w-full lg:w-48">
                                <x-input-label value="Nama" class="text-xs" />
                                <x-text-input name="name" value="{{ $employee->name }}" required class="text-sm w-full mt-1" />
                            </div>
                            <div class="w-full lg:w-56">
                                <x-input-label value="Email" class="text-xs" />
                                <x-text-input name="email" value="{{ $employee->email }}" type="email" required class="text-sm w-full mt-1" />
                            </div>
                            <div class="w-full lg:w-40">
                                <x-input-label value="Password Baru" class="text-xs" />
                                <x-text-input name="password" type="password" minlength="8" class="text-sm w-full mt-1" placeholder="Kosongkan jika tidak ganti" />
                            </div>
                            <div class="flex items-end gap-2 mt-auto">
                                <x-secondary-button type="submit" class="!px-4 !py-2 text-xs h-10">Update</x-secondary-button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Hapus karyawan ini?')" class="mt-4 lg:mt-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full lg:w-auto px-4 py-2 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 rounded text-xs font-semibold transition h-10">Hapus</button>
                        </form>
                    </div>
            </li>
            @endforeach
        </ul>
    </div>
 </div>
 </div>
</x-app-layout>
