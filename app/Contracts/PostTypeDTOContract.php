<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class PostTypeDTOContract
{
    abstract public function withAddedby(Model $addedby);
    
}