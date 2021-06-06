<?php

namespace App\YourEdu;

use App\Traits\FlaggableTrait;
use App\Traits\HasCommentsTrait;
use App\Traits\HasSocialMediaTrait;
use App\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes, 
        HasFactory,
        FlaggableTrait,
        HasSocialMediaTrait,
        HasCommentsTrait;

    protected $fillable = [
        'content'
    ];

    public function addedby(){
        return $this->morphTo();
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function lessons()
    {
        return $this->morphMany(Lesson::class,'lessonable');
    }

    public function books()
    {
        return $this->morphMany(Book::class,'bookable');
    }

    public function poems()
    {
        return $this->morphMany(Poem::class,'poemable');
    }

    public function riddles()
    {
        return $this->morphMany(Riddle::class,'riddleable');
    }

    public function activityTrack()
    {
       return $this->morphOne(ActivityTrack::class,'what');
    }

    public function beenSaved()
    {
       return $this->morphMany(Save::class,'saveable');
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachable');
    }
    
    public function discussion()
    {
        return $this->morphOne(Discussion::class,'discussionable');
    }

    public function activities(){
        return $this->morphMany(Activity::class,'activityfor');
    }

    public function questions(){
        return $this->morphMany(Question::class,'questionable');
    }
    
    public function allFiles()
    {
        $files = [];

        array_push($files, ...$this->images);
        array_push($files, ...$this->videos);
        array_push($files, ...$this->audios);
        array_push($files, ...$this->files);

        return $files;
    }

    public function hasNoTypes()
    {
        $count = 0;
        $count += $this->questions?->count();
        $count += $this->lessons?->count();
        $count += $this->activities?->count();
        $count += $this->poems?->count();
        $count += $this->riddles?->count();
        $count += $this->books?->count();

        return $count === 0;
    }

    public function scopeDoesntHaveType($query)
    {
        return $query->doesntHave('activities')
            ->doesntHave('questions')
            ->doesntHave('poems')
            ->doesntHave('riddles')
            ->doesntHave('books');
    }

    public function scopeWherePublished($query)
    {
        return $query->whereDoesntHave('activities',function(Builder $query){
            $query->where('published_at','>',now());
        })->whereDoesntHave('questions',function(Builder $query){
            $query->where('published_at','>',now());
        })->whereDoesntHave('poems',function(Builder $query){
            $query->where('published_at','>',now());
        })->whereDoesntHave('riddles',function(Builder $query){
            $query->where('published_at','>',now());
        })->whereDoesntHave('books',function(Builder $query){
            $query->where('published_at','>',now());
        });
    }

    public function scopeWithRelations($query)
    {
        return $query->with([
            'books'=>function($query){
                $query->with(['images','videos','audios','files','comments']);
            },
            'poems'=>function($query){
                $query->with(['images','videos','audios','files','comments']);
            },
            'activities'=>function($query){
                $query->with(['images','videos','audios','files','comments']);
            },
            'riddles'=>function($query){
                $query->with(['images','videos','audios','files','answers']);
            },
            'questions'=>function($query){
                $query->with(['images','videos','audios','files','answers']);
            },
            'comments', 'images', 'videos', 'audios', 'files'
        ]);
    }

    public function scopeWithTypes($query)
    {
        return $query::with(['questions.images','questions.videos',
        'questions.audios','questions.files','activities.images','activities.videos',
        'activities.files','activities.audios','riddles.images','riddles.videos',
        'riddles.files','riddles.audios','poems.images','poems.videos',
        'poems.files','poems.audios','books.images','books.videos','books.files',
        'books.audios','addedby.profile']);
    }

    public function scopeWherePostTypes($query, $postType)
    {
        return $query->when($postType === 'questions', function(Builder $query){
                $query->has('questions');
            })->when($postType === 'poems', function(Builder $query){
                $query->has('poems');
            })->when($postType === 'activities', function(Builder $query){
                $query->has('activities');
            })->when($postType === 'books', function(Builder $query){
                $query->has('books');
            })->when($postType === 'riddles', function(Builder $query){
                $query->has('riddles');
            });
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
