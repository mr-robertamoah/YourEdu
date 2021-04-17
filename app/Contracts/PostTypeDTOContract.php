<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class PostTypeDTOContract extends ItemDataContract
{
    abstract public function withAddedby(Model $addedby);
    
}