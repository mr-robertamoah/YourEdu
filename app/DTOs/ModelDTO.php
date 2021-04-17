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
                item: property_exists($data, 'item') ? $data->item : 
                    (property_exists($data, 'type') ? $data->type : null),
                itemId: property_exists($data, 'itemId') ? $data->itemId :
                    (property_exists($data, 'id') ? $data->id : null),
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