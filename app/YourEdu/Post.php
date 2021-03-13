<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    //
    use SoftDeletes, HasFactory;

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

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
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

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
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

    public function scopeHasPublished($query)
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

    public function scopeHasNoFlags($query, $parentsLearnerUserIds)
    {
        return $query->whereDoesntHave('flags',function(Builder $query) use ($parentsLearnerUserIds){
            $query->whereIn('user_id',$parentsLearnerUserIds)
                ->orWhere('status',"APPROVED");
        });
    }

    public function scopeHasNoApprovedFlags($query)
    {
        return $query->whereDoesntHave('flags',function(Builder $query){
            $query->where('status',"APPROVED");
            });
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

    public function scopeHasPostTypes($query)
    {
        return $query->when(request()->has('postType') && request()->postType === 'questions',
                function(Builder $query){
                $query->has('questions');
            })->when(request()->has('postType') && request()->postType === 'poems',
                function(Builder $query){
                $query->has('poems');
            })->when(request()->has('postType') && request()->postType === 'activities',
                function(Builder $query){
                $query->has('activities');
            })->when(request()->has('postType') && request()->postType === 'books',
                function(Builder $query){
                $query->has('books');
            })->when(request()->has('postType') && request()->postType === 'riddles',
                function(Builder $query){
                $query->has('riddles');
            });
    }

    public function scopeWithFilter($query)
    {
        $user = User::find((int)request()->user);
        if (!$user) {
            return $query;
        }

        $type = null;
        $id = null;
        if (request()->has('attachments')) {
            if (request()->attach === 'subjects') {
                $type = 'App\YourEdu\Subject';
            } else if (request()->attach === 'grades') {
                $type = 'App\YourEdu\Grade';
            } else if (request()->attach === 'curriculum') {
                $type = 'App\YourEdu\Curriculum';
            }
            $id = request()->id;
        }

        return $query->when(request()->has('mine'), function(Builder $query){
            $query->whereHasMorph('addedby','*',function(Builder $query){
                $query->where('user_id', (int)request()->user);
            });
        })->when(request()->has('followings'), function(Builder $query){
            $query->whereHasMorph('addedby','*',function(Builder $query){
                $query->whereHas('follows',function(Builder $query){
                    $query->where('user_id',(int)request()->user);
                });
            });
        })->when(request()->has('followers'), function(Builder $query){
            $query->whereHasMorph('addedby','*',function(Builder $query){
                $query->whereHas('followings',function(Builder $query){
                    $query->where('followed_user_id',(int)request()->user);
                });
            });
        })->when(request()->has('attachments'), function(Builder $query) use ($type,$id) {
            $query->whereHas('attachments',function(Builder $query) use ($type, $id) {
                $query->where('attachedwith_type', $type)
                    ->where('attachedwith_id', $id);
            });
        });
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
