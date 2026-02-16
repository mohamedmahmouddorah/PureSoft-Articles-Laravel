<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $stats = null;
        if (auth()->check() && auth()->user()->isAdmin()) {
            $users = User::latest()->get();
            $stats = [
                'users' => User::count(),
                'articles' => Article::count(),
                'comments' => Comment::count(),
            ];
        } else {
            $users = User::where('is_active', true)->latest()->get();
        }

        return view('users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        if (!$user->is_active) {
            abort(404);
        }

        $articles = Article::where('author', $user->username)->latest()->get(); // Using username as author per legacy schema
        $article_count = $articles->count();

        return view('users.show', compact('user', 'articles', 'article_count'));
    }
}
