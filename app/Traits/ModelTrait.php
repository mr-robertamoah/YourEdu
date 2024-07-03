<?php

namespace App\Traits;

trait ModelTrait
{
    public function hasAttribute($attribute)
    {
        return array_key_exists($attribute, $this->getAttributes());
    }

    public function doesntHaveAttribute($attribute)
    {
        return ! $this->hasAttribute($attribute);
    }
}
