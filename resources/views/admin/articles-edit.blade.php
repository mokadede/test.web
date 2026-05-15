<x-app-layout title="Edit Artikel">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.articles') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Artikel
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form id="article-form" method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
                @csrf @method('PATCH')

                {{-- Judul --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Artikel</label>
                    <input type="text" name="title" id="title" required
                        value="{{ $article->title }}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg font-semibold">
                </div>

                {{-- Thumbnail --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Gambar Utama (Thumbnail)</label>
                    <div class="flex items-center gap-4">
                        <div id="image-preview" style="width:80px;height:80px;min-width:80px;max-width:80px;min-height:80px;max-height:80px;border-radius:8px;overflow:hidden;background:#f3f4f6;border:2px dashed #d1d5db;display:flex;align-items:center;justify-content:center;">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <svg style="width:24px;height:24px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="image" id="thumbnail-input" accept=".jpg,.jpeg,.png,.webp"
                                class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 cursor-pointer"
                                onchange="previewImage(event)">
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah. Maks 2MB.</p>
                            <p id="thumbnail-error" style="display:none;margin-top:4px;font-size:12px;color:#ef4444;font-weight:600;"></p>
                        </div>
                    </div>
                </div>

                {{-- Konten Editor.js --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konten Artikel</label>
                    </div>
                    <div id="editorjs" style="min-height: 350px; padding: 0.5rem 0;"></div>
                    <input type="hidden" name="content" id="content-json">
                </div>

                {{-- Opsi Terbit --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ $article->is_published ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Terbitkan artikel ini</span>
                    </label>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.articles') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">Batal</a>
                    <button type="button" onclick="saveAndSubmit()"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Editor.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.29.0/dist/editorjs.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.8.1/dist/header.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@1.9.0/dist/list.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.9.0/dist/image.umd.min.js"></script>

    <script>
        let editorInstance = null;
        let retryCount = 0;
        const MAX_RETRIES = 15;

        function initEditor() {
            const holder = document.getElementById('editorjs');
            if (!holder) return;

            if (typeof EditorJS === 'undefined') {
                retryCount++;
                if (retryCount <= MAX_RETRIES) {
                    setTimeout(initEditor, 500);
                    return;
                }
                holder.innerHTML = '<p style="padding:1.5rem;color:#ef4444;text-align:center;">Gagal memuat editor. <a href="" style="text-decoration:underline;">Segarkan halaman</a>.</p>';
                return;
            }

            // Parse existing content
            let initialData = {};
            try {
                const raw = @json($article->content);
                initialData = JSON.parse(raw);
            } catch (e) {
                const rawText = @json($article->content);
                initialData = {
                    blocks: [{ type: "paragraph", data: { text: rawText || '' } }]
                };
            }

            // Build tools config
            const tools = {};
            if (typeof Header !== 'undefined') tools.header = { class: Header, inlineToolbar: true };
            if (typeof List !== 'undefined') tools.list = { class: List, inlineToolbar: true };
            const imgClass = (typeof ImageTool !== 'undefined') ? ImageTool : null;
            if (imgClass) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                tools.image = {
                    class: imgClass,
                    config: {
                        endpoints: { byFile: '{{ route("admin.articles.upload_image") }}' },
                        additionalRequestHeaders: { 'X-CSRF-TOKEN': csrfToken },
                        field: 'upload'
                    }
                };
            }

            console.log('EditorJS: Initializing with tools:', Object.keys(tools));

            editorInstance = new EditorJS({
                holder: 'editorjs',
                placeholder: 'Edit konten artikel...',
                data: initialData,
                tools: tools,
            });
        }

        async function saveAndSubmit() {
            if (!editorInstance) {
                alert('Editor belum siap.');
                return;
            }
            try {
                const data = await editorInstance.save();
                document.getElementById('content-json').value = JSON.stringify(data);
                document.getElementById('article-form').submit();
            } catch (e) {
                console.error(e);
                alert('Gagal menyimpan: ' + e.message);
            }
        }

        function previewImage(event) {
            const file = event.target.files[0];
            const errorEl = document.getElementById('thumbnail-error');
            const previewEl = document.getElementById('image-preview');
            errorEl.style.display = 'none';
            errorEl.textContent = '';

            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                errorEl.textContent = '⚠ Format tidak didukung! Gunakan JPG, PNG, atau WEBP.';
                errorEl.style.display = 'block';
                event.target.value = '';
                previewEl.innerHTML = '<svg style="width:24px;height:24px;color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                return;
            }

            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                const sizeMB = (file.size / 1024 / 1024).toFixed(1);
                errorEl.textContent = '⚠ Ukuran file ' + sizeMB + 'MB melebihi batas 2MB!';
                errorEl.style.display = 'block';
                event.target.value = '';
                previewEl.innerHTML = '<svg style="width:24px;height:24px;color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                return;
            }

            const reader = new FileReader();
            reader.onload = function () {
                previewEl.innerHTML = '<img src="' + reader.result + '" style="width:100%;height:100%;object-fit:cover;">';
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('DOMContentLoaded', initEditor);
        document.addEventListener('livewire:navigated', () => {
            retryCount = 0;
            editorInstance = null;
            initEditor();
        });
    </script>
</x-app-layout>
