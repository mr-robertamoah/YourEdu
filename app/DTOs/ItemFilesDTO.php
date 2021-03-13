<?php

namespace App\DTOs;

class ItemFilesDTO
{
    public int $imagesCount = 0;
    public int $videosCount = 0;
    public int $audiosCount = 0;
    public int $filesCount = 0;

    public function totalFiles()
    {
        return $this->imagesCount + $this->videosCount +
            $this->audiosCount + $this->filesCount;
    }
}
