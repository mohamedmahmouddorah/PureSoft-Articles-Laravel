<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'author',
        'price',
        'stock',
        'image_path',
    ];

    /**
     * Get the user that wrote the article.
     * Note: The database uses 'author' (username) instead of user_id.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'author', 'username');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
