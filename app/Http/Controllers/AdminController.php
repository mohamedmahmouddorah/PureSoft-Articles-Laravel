<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'articles' => Article::count(),
            'comments' => Comment::count(),
            'orders' => \App\Models\Order::count(),
        ];
        
        $users = User::latest()->get();
        $articles = Article::with('user')->latest()->get();

        return view('admin.dashboard', compact('stats', 'users', 'articles'));
    }

    public function toggleUser(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', 'تم تحديث حالة المستخدم.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'تم حذف المستخدم.');
    }
}
