<?php

namespace App\DTOs;

class FileDTO
{
    public string | null $type;
    public string | null $id;

    public static function createFromArray(array $dataArray) : array
    {
        $sections = [];

        foreach ($dataArray as $data) {
            $sections[] = static::createFromData(
                type: $data->type ?? null,
                id: $data->id ?? null,
            );
        }

        return $sections;
    }

    public static function createFromData
    (
        $type = null,
        $id = null,
    )
    {
        $static = new static();

        $static->type = $type;
        $static->id = $id;

        return $static;
    }
}
