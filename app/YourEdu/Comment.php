<?php

namespace App\YourEdu;

use App\Traits\HasLikeableTrait;
use App\Traits\HasSaveableTrait;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory,
        HasSaveableTrait,
        HasLikeableTrait;

    protected $fillable = [
        'body'
    ];

    protected $touches = [
        'commentable'
    ];

    public function commentedby()
    {
        return $this->morphTo();
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class, 'audioable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class, 'videoable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function flags()
    {
        return $this->morphMany(Flag::class, 'flaggable');
    }

    public function activityTrack()
    {
        return $this->morphOne(ActivityTrack::class, 'what');
    }

    protected static function newFactory()
    {
        return CommentFactory::new();
    }
}
