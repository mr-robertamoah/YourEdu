<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\RequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes,
        HasFactory;

    const PENDING = 'PENDING';
    const ACCEPTED = 'ACCEPTED';
    const DECLINED = 'DECLINED';

    protected $fillable = ['state','data'];

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->morphMany(Message::class,'messageable');
    }

    public function requestfrom()
    {
        return $this->morphTo();
    }

    public function requestto()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
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
    
    public function allFiles()
    {
        $files = [];

        array_push($files, ...$this->images);
        array_push($files, ...$this->videos);
        array_push($files, ...$this->audios);
        array_push($files, ...$this->files);

        return $files;
    }

    public function requestables()
    {
        return $this->hasMany(Requestable::class);
    }

    public function commissions()
    {
        return $this->morphedByMany(Commission::class, 'requestable', 'requestables');
    }

    public function fees()
    {
        return $this->morphedByMany(Fee::class, 'requestable', 'requestables');
    }

    public function salaries()
    {
        return $this->morphedByMany(Salary::class, 'requestable', 'requestables');
    }

    public function discounts()
    {
        return $this->morphedByMany(Discount::class, 'requestable', 'requestables');
    }

    protected static function newFactory()
    {
        return RequestFactory::new();
    }
}
