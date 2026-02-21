@extends('layouts.app')

@section('title', 'Add New Article')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="glass-card p-4" style="width: 100%; max-width: 500px;">
        <h2 class="text-center mb-4">➕ New Article</h2>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('articles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" class="form-control" name="title" placeholder="Article Title" value="{{ old('title') }}" required>
            </div>
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="6" placeholder="Write something amazing..." required>{{ old('content') }}</textarea>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="image_path" placeholder="Image URL (optional)" value="{{ old('image_path') }}">
            </div>
            <button type="submit" class="btn btn-success w-100">Publish Article</button>
            <a href="{{ route('articles.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
