<?php

namespace App\YourEdu;

use App\Traits\HasMarkableTrait;
use App\Traits\HasFilesTrait;
use Database\Factories\AnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    //
    use SoftDeletes,
        HasFilesTrait,
        HasFactory,
        HasMarkableTrait;

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

    public function scopeWhereAssessment($query, $assessmentId)
    {
        return $query
            ->whereHasMorph(
                'answerable', 
                'App\\YourEdu\\Question',
                function($query) use($assessmentId) {
                    $query->whereAssessment($assessmentId);
                }
            );
    }

    protected static function newFactory()
    {
        return AnswerFactory::new();
    }
}
