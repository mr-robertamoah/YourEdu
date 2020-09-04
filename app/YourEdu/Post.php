<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'content'
    ];

    public function postedby(){
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

    public function scopeHasPublished($query)
    {
        return $query->whereDoesntHave('activities',function(Builder $query){
            $query->where('published','>',now());
        })->whereDoesntHave('questions',function(Builder $query){
            $query->where('published','>',now());
        })->whereDoesntHave('poems',function(Builder $query){
            $query->where('published','>',now());
        })->whereDoesntHave('riddles',function(Builder $query){
            $query->where('published','>',now());
        })->whereDoesntHave('books',function(Builder $query){
            $query->where('published','>',now());
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
            $query->whereHasMorph('postedby','*',function(Builder $query){
                $query->where('user_id', (int)request()->user);
            });
        })->when(request()->has('followings'), function(Builder $query){
            $query->whereHasMorph('postedby','*',function(Builder $query){
                $query->whereHas('follows',function(Builder $query){
                    $query->where('user_id',(int)request()->user);
                });
            });
        })->when(request()->has('followers'), function(Builder $query){
            $query->whereHasMorph('postedby','*',function(Builder $query){
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
}
