<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait DTOTrait
{
    public ?string $methodType = null;
    public ?string $userId = null;
    
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

        if ($attribute !== 'users') {
    
            $clone->$attribute = $parameters[0];
    
            return $clone;
        }

        if ($parameters[0] instanceof User) {
            $clone->users = new Collection();

            $clone->users->push($parameters[0]);

            return $clone;
        }

        $clone->users = $parameters[0];

        return $clone;
    }
}
