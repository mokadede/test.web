<x-app-layout title="Manajemen Artikel">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Artikel Blog') }}
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
                <div class="flex justify-between items-center mb-6 border-b pb-4 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"></path></svg>
                        Daftar Artikel
                    </h3>
                    <a href="{{ route('admin.articles.create') }}" wire:navigate class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md shadow flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tulis Artikel Baru
                    </a>
                </div>

                <div class="overflow-x-auto border border-gray-100 dark:border-gray-700 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/30">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Info Artikel</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($articles as $article)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div style="width:48px;height:48px;min-width:48px;min-height:48px;max-width:48px;max-height:48px;border-radius:6px;overflow:hidden;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                                @if($article->image)
                                                    <img src="{{ asset('storage/' . $article->image) }}" style="width:100%;height:100%;object-fit:cover;">
                                                @else
                                                    <svg style="width:24px;height:24px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $article->title }}</div>
                                                <div class="text-xs text-gray-400">{{ $article->excerpt }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.articles.toggle', $article) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" title="Klik untuk ubah status">
                                                @if($article->is_published)
                                                    <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded cursor-pointer hover:bg-green-200 transition">Published</span>
                                                @else
                                                    <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded cursor-pointer hover:bg-yellow-200 transition">Draft</span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                        {{ $article->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.articles.edit', $article) }}" wire:navigate class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow flex items-center gap-1 transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Belum ada artikel yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
