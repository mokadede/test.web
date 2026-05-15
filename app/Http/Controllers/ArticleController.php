<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $allArticlesCount = Article::where('is_published', true)->count();
        $articles = Article::where('is_published', true)->latest()->take(6)->get();
        $services = Service::all();
        return view('welcome', compact('articles', 'services', 'allArticlesCount'));
    }

    public function blogIndex()
    {
        $articles = Article::where('is_published', true)->latest()->paginate(9);
        return view('articles.index', compact('articles'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return view('articles.show', compact('article'));
    }

    public function adminIndex()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles-create');
    }

    public function edit(Article $article)
    {
        return view('admin.articles-edit', compact('article'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil dibuat.');
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . $article->id,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
        ];

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function togglePublish(Article $article)
    {
        $article->update([
            'is_published' => !$article->is_published,
        ]);

        $status = $article->is_published ? 'diterbitkan' : 'dijadikan draft';
        return back()->with('success', "Artikel berhasil {$status}.");
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        $article->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    public function uploadImage(Request $request)
    {
        try {
            if (!$request->hasFile('upload')) {
                return response()->json(['success' => 0, 'message' => 'No file uploaded.']);
            }

            $file = $request->file('upload');

            // Check file validity
            if (!$file->isValid()) {
                return response()->json(['success' => 0, 'message' => 'File upload error.']);
            }

            // Check size (max 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                return response()->json(['success' => 0, 'message' => 'File too large.']);
            }

            $originName = $file->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['success' => 0, 'message' => 'Invalid file type. Only images are allowed.']);
            }

            $fileName = $fileName . '_' . time() . '.' . $extension;

            $file->storeAs('articles/content', $fileName, 'public');

            $url = $request->getSchemeAndHttpHost() . '/storage/articles/content/' . $fileName;

            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => $url
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return response()->json(['success' => 0, 'message' => 'Upload failed.']);
        }
    }
}
