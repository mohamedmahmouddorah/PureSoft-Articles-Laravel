<div class="comment-card" id="comment-{{ $comment->id }}">
    <div class="avatar">
        {{ mb_substr($comment->user->username, 0, 1) }}
    </div>
    
    <div class="comment-content-wrapper">
        <div class="comment-bubble">
            <span class="comment-author">{{ $comment->user->username }}</span>
            <div class="comment-text">{!! nl2br(e($comment->comment)) !!}</div>
        </div>

        <div class="comment-actions">
            <span class="comment-date" style="margin-right: 10px; font-size: 0.8rem; opacity: 0.7;">{{ $comment->created_at->format('Y/m/d - H:i') }}</span>
            
            @auth
                <button type="button" onclick="toggleReply({{ $comment->id }})" class="reply-btn fw-bold text-white-50">Reply</button>
                
                @if(Auth::id() == $comment->user_id)
                    <button type="button" onclick="toggleEdit({{ $comment->id }})" class="comment-action-link text-warning" style="background:none; border:none; padding:0; margin-left:10px;">Edit</button>
                @endif

                @if(Auth::id() == $comment->user_id || Auth::user()->isAdmin())
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="comment-action-link comment-action-delete text-danger" style="background:none; border:none; padding:0; margin-left:10px;" onclick="return confirm('Delete comment?')">Delete</button>
                    </form>
                @endif
            @endauth
        </div>

        @auth
            <!-- Edit Form -->
             @if(Auth::id() == $comment->user_id)
                <div id="edit-form-{{ $comment->id }}" class="reply-form" style="display: none;">
                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="input-group mt-2">
                            <textarea name="comment" class="form-control" rows="1" required>{{ $comment->comment }}</textarea>
                            <button type="submit" class="btn btn-warning">Update</button>
                            <button type="button" class="btn btn-secondary" onclick="toggleEdit({{ $comment->id }})">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Reply Form -->
            <div id="reply-form-{{ $comment->id }}" class="reply-form">
                <form action="{{ route('comments.store', $article) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="input-group mt-2">
                        <textarea name="comment" class="form-control" rows="1" placeholder="Write a reply..." required></textarea>
                        <button type="submit" class="btn btn-primary">Reply</button>
                    </div>
                </form>
            </div>
        @endauth

        @if($grouped_comments->has($comment->id))
            <div class="reply-list">
                @foreach($grouped_comments[$comment->id]->sortByDesc('created_at') as $child)
                    @include('partials.comment', ['comment' => $child, 'grouped_comments' => $grouped_comments, 'article' => $article])
                @endforeach
            </div>
        @endif
    </div>
</div>
