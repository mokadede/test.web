<x-app-layout title="Dashboard">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Konten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Artikel</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalArticles }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diterbitkan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $publishedArticles }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Draft</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $draftArticles }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Articles Table -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Artikel Terbaru</h3>
                    <a href="{{ route('admin.articles') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">Lihat Semua &rarr;</a>
                </div>
                
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($latestArticles as $article)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Str::limit($article->title, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($article->is_published)
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded">Published</span>
                                    @else
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $article->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.articles') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Belum ada artikel.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('admin.articles.create') }}" wire:navigate class="p-6 bg-indigo-600 rounded-lg shadow-lg flex items-center justify-between group transition hover:brightness-110">
                    <div>
                        <h4 class="text-white font-black text-xl uppercase italic tracking-tighter">Tulis Artikel Baru</h4>
                        <p class="text-indigo-100 text-sm">Bagikan tips & cerita terbaru untuk audiens Anda.</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                </a>
                
                <a href="{{ route('home') }}#articles" target="_blank" class="p-6 bg-gray-800 rounded-lg shadow-lg flex items-center justify-between group transition hover:brightness-110 border border-gray-700">
                    <div>
                        <h4 class="text-white font-black text-xl uppercase italic tracking-tighter">Lihat Blog Publik</h4>
                        <p class="text-gray-400 text-sm">Lihat tampilan artikel Anda di halaman depan.</p>
                    </div>
                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
