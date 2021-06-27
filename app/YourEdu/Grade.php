<?php

namespace App\YourEdu;

use App\Traits\HasAliasesTrait;
use Database\Factories\GradeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes,
        HasFactory,
        HasAliasesTrait;

    protected $fillable = [
        'name', 'description', 'age_group'
    ];

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function gradable()
    {
        return $this->morphTo();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    public function facilitators()
    {
        return $this->belongsToMany(Facilitator::class)->withTimestamps();
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class, 'attachedwith');
    }

    public function requests()
    {
        return $this->morphMany(Request::class, 'requestable');
    }

    public function scopeWhereName($query, $name)
    {
        return $query->where('name', $name);
    }

    protected static function newFactory()
    {
        return GradeFactory::new();
    }
}
