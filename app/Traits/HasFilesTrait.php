<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait HasFilesTrait
{
    public function hasFiles()
    {
        if ($this->images()?->count()) {
            return true;
        }
        
        if ($this->videos()?->count()) {
            return true;
        }
        
        if ($this->images()?->count()) {
            return true;
        }
        
        if ($this->files()?->count()) {
            return true;
        }

        return false;
    }
    
    public function allFiles()
    {
        $files = new Collection();
        
        $files = $files->merge($this->images);
        
        $files = $files->merge($this->videos);
        
        $files = $files->merge($this->audios);
        
        $files = $files->merge($this->files);

        return $files;
    }
}
