<?php

namespace App\DTOs;

class PoemSectionDTO
{
    public string | null $poemId;
    public string | null $body;
    public string | null $poemSectionId;

    public static function createFromArray(array $dataArray) : array
    {
        $poemSections = [];
        
        foreach ($dataArray as $data) {
            $poemSections[] = static::createFromData(
                poemId: $data->poemId ?? null,
                poemSectionId: $data->poemSectionId ?? null,
                body: $data->body ?? null,
            );
        }

        return $poemSections;
    }
    
    public static function createFromData
    (
        $poemId,
        $poemSectionId,
        $body
    )
    {
        $self = new static();

        $self->body = $body;
        $self->poemId = $poemId;
        $self->poemSectionId = $poemSectionId;

        return $self;
    }
}
