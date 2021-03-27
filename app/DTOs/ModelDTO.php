<?php

namespace App\DTOs;

class ModelDTO
{
    public string | null $item;
    public string | null $itemId;
    public string | null $extraItemId;

    public static function createFromArray(array $dataArray) : array
    {
        $sections = [];

        foreach ($dataArray as $data) {
            $sections[] = static::createFromData(
                item: $data->item ?? null,
                itemId: $data->itemId ?? null,
                extraItemId: $data->extraItemId ?? null,
            );
        }

        return $sections;
    }

    public static function createFromData
    (
        $item = null,
        $itemId = null,
        $extraItemId = null,
    )
    {
        $static = new static();

        $static->item = $item;
        $static->itemId = $itemId;
        $static->extraItemId = $extraItemId;

        return $static;
    }
}