<?php

namespace App\Traits;

use App\YourEdu\Audio;
use App\YourEdu\File;
use App\YourEdu\Image;
use App\YourEdu\Video;

trait AccountFilesTrait
{
    
    public function ownedImages()
    {
        return $this->morphMany(Image::class,'ownedby');
    }
    
    public function ownedFiles()
    {
        return $this->morphMany(File::class,'ownedby');
    }
    
    public function ownedVideos()
    {
        return $this->morphMany(Video::class,'ownedby');
    }
    
    public function ownedAudio()
    {
        return $this->morphMany(Audio::class,'ownedby');
    }
    
    public function addedImages()
    {
        return $this->morphMany(Image::class,'addedby');
    }
    
    public function addedFiles()
    {
        return $this->morphMany(File::class,'addedby');
    }
    
    public function addedVideos()
    {
        return $this->morphMany(Video::class,'addedby');
    }
    
    public function addedAudio()
    {
        return $this->morphMany(Audio::class,'addedby');
    }

}
