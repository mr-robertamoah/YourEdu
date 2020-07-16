<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Parent_;

class Read extends Model
{
    //
    use SoftDeletes;

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function words()
    {
        return $this->hasMany(Word::class);
    }

    public function expressions()
    {
        return $this->hasMany(Expression::class);
    }

    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    public function duration()
    {
        return $this->morphOne(Duration::class,'durationable');
    }

    public function comments()
    {
        return $this->morphOne(Comment::class,'commentable');
    }

    public function summaries()
    {
        return $this->morphOne(Summary::class,'summariable');
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'readable','readables');
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class,'readable','readables');
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'readable','readables');
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'readable','readables');
    }
}
