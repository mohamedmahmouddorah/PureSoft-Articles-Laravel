@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="glass-card p-4">
    <h1 class="mb-3">{{ $article->title }}</h1>
    <div class="article-meta text-center">
        <span class="text-white-50">By:</span> 
        @if($article->user)
            <a href="#" class="text-info fw-bold text-decoration-none">{{ $article->author }}</a>
        @else
            <span class="text-info fw-bold">{{ $article->author }}</span>
        @endif
        <span class="mx-2">|</span>
        <span class="text-white-50">{{ $article->created_at->format('d M Y, h:i A') }}</span>
    </div>
    <div class="content mb-4" style="line-height: 1.8; white-space: pre-wrap; font-size: 1.1em; color: #f0f0f0;">{{ $article->content }}</div>

    <div class="mt-4"></div>

    @auth
        @if(Auth::user()->username == $article->author || Auth::user()->isAdmin())
            <div class="pt-3 border-top border-secondary d-flex justify-content-end gap-2">
                <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete article?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                </form>
            </div>
        @endif
    @endauth
        </div>
    </div>
</div>

<!-- Comments Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="glass-card p-4">
    <h3 class="mb-4 border-bottom border-secondary pb-2 d-inline-block">Comments</h3>

    @auth
        <form action="{{ route('comments.store', $article) }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3 custom-input-wrapper">
                <i class="bi bi-chat-text custom-input-icon" style="top: 25px; transform: none;"></i>
                <textarea name="comment" class="form-control" rows="3" placeholder="Share your thoughts..." required style="padding-left: 45px;"></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Post Comment</button>
            </div>
        </form>
    @else
        <div class="alert alert-dark text-center" role="alert" style="background: rgba(255,255,255,0.1); border: none; color: #ccc;">
            You must <a href="{{ route('login') }}" class="alert-link text-info">login</a> to comment.
        </div>
    @endauth

    <div class="comments-list">
        @php
            $comments = $article->comments()->with('user')->get();
            $grouped_comments = $comments->groupBy('parent_id');
            $root_comments = $grouped_comments->get(null, collect())->sortByDesc('created_at');
        @endphp

        @if($root_comments->isEmpty())
            <p class="text-center text-muted">No comments yet. Be the first to share!</p>
        @else
            @foreach($root_comments as $comment)
                @include('partials.comment', ['comment' => $comment, 'grouped_comments' => $grouped_comments, 'article' => $article])
            @endforeach
        @endif
    </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .article-meta { color: #aaa; font-size: 0.9em; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; }
</style>
@endpush

@push('scripts')
<script>
    function toggleReply(id) {
        var form = document.getElementById('reply-form-' + id);
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }

    function toggleEdit(id) {
        var form = document.getElementById('edit-form-' + id);
        var commentText = document.querySelector('#comment-' + id + ' .comment-bubble');
        
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
            // commentText.style.display = "none"; // Optional: hide original text while editing
        } else {
            form.style.display = "none";
            // commentText.style.display = "block";
        }
    }
</script>
@endpush
@endsection
