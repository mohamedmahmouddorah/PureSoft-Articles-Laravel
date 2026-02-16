@extends('layouts.app')

@section('title', 'Edit Article')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="glass-card p-4" style="width: 100%; max-width: 500px;">
        <h2 class="text-center mb-4">✏️ Edit Article</h2>
        
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('articles.update', $article) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <input type="text" class="form-control" name="title" value="{{ old('title', $article->title) }}" required placeholder="Article Title">
            </div>
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="6" required placeholder="Article Content...">{{ old('content', $article->content) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="number" step="0.01" class="form-control" name="price" value="{{ old('price', $article->price) }}" required placeholder="Price ($)">
                </div>
                <div class="col-md-6 mb-3">
                    <input type="number" class="form-control" name="stock" value="{{ old('stock', $article->stock) }}" required placeholder="Stock">
                </div>
            </div>
            <button type="submit" class="btn btn-warning w-100">Save Changes</button>
            <a href="{{ route('articles.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
