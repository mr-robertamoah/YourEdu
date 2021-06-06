<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait DTOTrait
{
    public static function new()
    {
        return new static;
    }

    public function addData(...$parameters)
    {
        foreach ($parameters as $key => $value) {

            $this->$key = $value;
        }

        return $this;
    }

    public function __call($method, $parameters)
    {
        if (! str_contains($method, 'with')) {
            return $this;
        }

        $attribute = Str::camel((str_replace('with', '', $method)));
        
        $clone = clone $this;

        $clone->$attribute = $parameters[0];

        return $clone;
    }
}
