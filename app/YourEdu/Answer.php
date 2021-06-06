<?php

namespace App\YourEdu;

use App\Traits\ItemFilesTrait;
use Database\Factories\AnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    //
    use SoftDeletes,
        ItemFilesTrait,
        HasFactory;

    protected $fillable = [
        'answer','work_id','possible_answer_ids', 'answeredby_type',
        'answeredby_id'
    ];

    protected $casts = [
        'possible_answer_ids' => 'array'
    ];

    protected $touches = ['answerable'];
    
    public function answerable()
    {
        return $this->morphTo();
    }
    
    public function answeredby()
    {
        return $this->morphTo();
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function beenSaved()
    {
       return $this->morphMany(Save::class,'saveable');
    }

    public function marks()
    {
        return $this->morphMany(Mark::class,'markable');
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

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function possibleAnswers()
    {
        return PossibleAnswer::query()
            ->whereIn('id', $this->possible_answer_ids)
            ->get();
    }

    public function isMarkedbyUser($userId)
    {
        return $this->marks()
            ->whereHasMorph('markedby', '*', function($query) use ($userId) {
                $query->whereUser($userId);
            })
            ->exists();
    }

    public function isNotMarkedbyUser($userId)
    {
        return ! $this->isMarkedbyUser($userId);
    }

    public function isNotAutoMarkable()
    {
        if ($this->answerable->doesntHavePossibleAnswers()) {
            return true;
        }

        if ($this->answerable->doesntHaveCorrectPossibleAnswers()) {
            return true;
        }

        return false;
    }

    public function scopeWhereAnsweredby($query, $account)
    {
        return $query->where(function($query) use ($account) {
            $query->where('answeredby_type', $account::class)
                ->where('answeredby_id', $account->id);
        });
    }

    public function scopeWhereAnswerable($query, $item)
    {
        return $query->where(function($query) use ($item) {
            $query->where('answerable_type', $item::class)
                ->where('answerable_id', $item->id);
        });
    }

    protected static function newFactory()
    {
        return AnswerFactory::new();
    }
}
