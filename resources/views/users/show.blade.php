@extends('layouts.app')

@section('title', 'Profile: ' . $user->username)

@section('content')
<div class="row" style="margin-top: 20px;">
    <!-- Sidebar / User Info -->
    <div class="col-lg-4 mb-4">
        <div class="glass-card text-center p-4">
                <div class="avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; font-weight: bold;">
                {{ mb_substr($user->username, 0, 1) }}
            </div>
            <h3 class="mb-1">{{ $user->username }}</h3>
            
            <div class="badge bg-secondary mb-3">
                {{ $user->role === 'admin' ? 'Admin' : 'Member' }}
            </div>

            <div class="d-flex justify-content-center gap-3 text-start mt-3">
                <div class="text-center">
                    <h5 class="mb-0 text-info">{{ $article_count }}</h5>
                    <small class="text-muted">Articles</small>
                </div>
            </div>

            <hr class="border-secondary my-4">
            
            <div class="text-end text-muted small">
                <p class="mb-2"><i class="bi bi-calendar-check me-2"></i> Joined: {{ $user->created_at->format('Y/m/d') }}</p>
                <p class="mb-2"><i class="bi bi-envelope me-2"></i> {{ $user->email }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content / Articles -->
    <div class="col-lg-8">
        <h3 class="mb-4 text-white"><i class="bi bi-collection-fill text-warning me-2"></i> Articles by {{ $user->username }}</h3>
        
        @if($articles->count() > 0)
            @foreach($articles as $article)
                <div class="glass-card p-4 mb-3">
                    <h4 class="h5">
                        <a href="{{ route('articles.show', $article) }}" class="text-white text-decoration-none">
                            {{ $article->title }}
                        </a>
                    </h4>
                    <div class="text-muted small mb-2">
                            <i class="bi bi-calendar-event"></i> {{ $article->created_at->format('Y/m/d') }}
                    </div>
                    <p class="text-white-50 small mb-3">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-info">Read More</a>
                        
                        @auth
                            @if(Auth::user()->role === 'admin' || Auth::user()->username == $article->author)
                                <div>
                                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        @else
            <div class="glass-card text-center p-5">
                <i class="bi bi-journal-x fs-1 text-muted mb-3 d-block"></i>
                <p class="text-muted">This user hasn't posted any articles yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
