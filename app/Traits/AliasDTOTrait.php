<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait AliasDTOTrait
{
    public ?Model $aliasable = null;
    public ?Model $addedby = null;
    public ?string $aliasName = null;
    public ?string $aliasDescription = null;
    public bool $increasePoints = false;

    public function setAliasData(object $data)
    {
        $clone = clone $this;

        $clone->aliasName = $data->name ?? null;
        $clone->aliasDescription = $data->description ?? null;

        return $clone;
    }

    public function setAlias()
    {
        $clone = clone $this;

        $clone->aliasName = $this->name;
        $clone->aliasDescription = $this->description;

        return $clone;
    }
}
