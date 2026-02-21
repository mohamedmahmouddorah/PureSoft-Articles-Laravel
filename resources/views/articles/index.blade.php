@extends('layouts.app')

@section('title', 'Home')

@section('content')
<h1 class="mb-4">Latest Articles</h1>

<div class="row">
    @if($articles->count() > 0)
        @foreach($articles as $article)
            <div class="col-12">
                <div class="glass-card article-card p-4 mb-4">
                    <h2 class="h3 mb-3">
                        <a href="{{ route('articles.show', $article) }}" class="text-info text-decoration-none">
                            {{ $article->title }}
                        </a>
                    </h2>
                    <div class="meta text-muted mb-3 pb-2 border-bottom border-secondary">
                        <i class="bi bi-person-fill"></i> 
                        <span class="text-white">{{ $article->author }}</span> <!-- Using author name directly as per model -->
                        <span class="mx-2">|</span> 
                        <i class="bi bi-calendar-event"></i> {{ $article->created_at->format('Y/m/d') }}
                    </div>
                    <p class="excerpt" style="color: #eee; line-height: 1.6;">
                        {{ Str::limit($article->content, 150) }}
                    </p>
                    
                    <div class="text-end mb-3">
                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-info">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>

                    @auth
                        <div class="mt-4 pt-3 border-top border-secondary">
                            <form action="{{ route('comments.store', $article) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="input-group">
                                    <textarea name="comment" class="form-control" rows="1" placeholder="Write a comment..." required></textarea>
                                    <button type="submit" class="btn btn-primary">Post</button>
                                </div>
                            </form>

                            @php
                                $recent_comments = $article->comments()
                                    ->whereNull('parent_id')
                                    ->with('user')
                                    ->latest()
                                    ->take(3)
                                    ->get();
                            @endphp

                            @if($recent_comments->isNotEmpty())
                                <div class="feed-comments">
                                    @foreach($recent_comments as $comment)
                                        <div class="feed-comment">
                                            <div class="feed-comment-header">
                                                <span class="feed-comment-author">{{ $comment->user->username }}</span>
                                                <span class="feed-comment-date">{{ $comment->created_at->format('Y/m/d - H:i') }}</span>
                                            </div>
                                            <div class="feed-comment-text">{!! nl2br(e($comment->comment)) !!}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-end mt-2">
                                    <a href="{{ route('articles.show', $article) }}" class="feed-comments-more">View all comments</a>
                                </div>
                            @else
                                <div class="text-center text-muted small">No comments yet.</div>
                            @endif
                        </div>

                        @if(Auth::user()->username == $article->author || Auth::user()->isAdmin())
                            <div class="mt-3 pt-3 border-top border-secondary d-flex gap-2">
                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach

        <div class="col-12">
            {{ $articles->links() }}
        </div>
    @else
        <div class="col-12">
            <div class="glass-card text-center p-5">
                <p class="h4 mb-4 text-muted">No articles have been posted yet.</p>
                @auth
                    <a href="{{ route('articles.create') }}" class="btn btn-success btn-lg">Create one now</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-info btn-lg">Login to post</a>
                @endauth
            </div>
        </div>
    @endif
</div>
@endsection
