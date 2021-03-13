<?php

namespace App\YourEdu;

use Database\Factories\PoemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poem extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title', 'author_names', 'about', 'published_at'
    ];

    // protected $touches = [
    //     'posts'
    // ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function authoredby()
    {
        return $this->morphTo();
    }

    public function poemable()
    {
        return $this->morphTo();
    }

    public function poemSections()
    {
        return $this->hasMany(PoemSection::class);
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable');
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function doenstHaveSections()
    {
        return $this->poemSections->count() < 1;
    }

    public function notRemovingAllSections(array $sections)
    {
        $poemSectionIds = $this->poemSections->pluck('id')->toArray();

        return count(
            array_filter($sections, function($section) use ($poemSectionIds) {
                return in_array(
                    $section->poemSectionId,
                    $poemSectionIds
                );
            })
        ) < $this->poemSections->count();
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
    
    protected static function newFactory()
    {
        return PoemFactory::new();
    }
}
