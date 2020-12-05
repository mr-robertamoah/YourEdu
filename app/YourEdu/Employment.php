<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employment extends Model
{
    use SoftDeletes;

    protected $fillable = ['start_date','end_date'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function employee()
    {
        return $this->morphTo();
    }

    public function employer()
    {
        return $this->morphTo();
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
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
}
