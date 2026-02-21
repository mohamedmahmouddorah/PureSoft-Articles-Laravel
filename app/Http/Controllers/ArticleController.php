<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArticleController extends Controller
{
    use AuthorizesRequests; // Ensure authorization traits works if not in base Controller

    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_path' => 'nullable|string|max:255',
        ]);

        $article = Auth::user()->articles()->create($validated);

        return redirect()->route('articles.show', $article)->with('success', 'تم إنشاء المقال بنجاح!');
    }

    public function show(Article $article)
    {
        $article->load(['user', 'comments.user', 'comments.replies.user']);
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        if (Auth::user()->cannot('update', $article)) {
             abort(403);
        }
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if ($request->user()->cannot('update', $article)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_path' => 'nullable|string|max:255',
        ]);

        $article->update($validated);

        return redirect()->route('articles.show', $article)->with('success', 'تم تحديث المقال بنجاح!');
    }

    public function destroy(Article $article)
    {
        if (Auth::user()->cannot('delete', $article)) {
            abort(403);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'تم حذف المقال.');
    }
}
