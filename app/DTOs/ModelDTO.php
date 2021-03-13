<?php

namespace App\DTOs;

class ModelDTO
{
    public string | null $item;
    public string | null $itemId;

    public static function createFromArray(array $dataArray) : array
    {
        $sections = [];

        foreach ($dataArray as $data) {
            $sections[] = static::createFromData(
                item: $data->item ?? null,
                itemId: $data->itemId ?? null,
            );
        }

        return $sections;
    }

    public static function createFromData
    (
        $item = null,
        $itemId = null,
    )
    {
        $static = new static();

        $static->item = $item;
        $static->itemId = $itemId;

        return $static;
    }
}