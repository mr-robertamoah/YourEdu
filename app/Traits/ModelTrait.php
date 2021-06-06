<?php

namespace App\Traits;

trait ModelTrait
{
    public function hasAttribute(string $attribute)
    {
        return array_key_exists($attribute, $this->getAttributes());
    }

    public function doesntHaveAttribute(string $attribute)
    {
        return ! $this->hasAttribute($attribute);
    }
}
