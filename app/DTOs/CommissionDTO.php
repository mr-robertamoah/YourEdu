<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class CommissionDTO
{
    public float | null $percentageOwned;
    public Model | null $for;
    public Model | null $ownedby;

    public function __invoke($for, $ownedby, $percentageOwned)
    {
        $this->for = $for;
        $this->ownedby = $ownedby;
        $this->percentageOwned = number_format((float) $percentageOwned, 4);
    }

    public static function createFromData
    (
        $for = null, 
        $ownedby = null, 
        $percentageOwned = 0
    )
    {
        $self = new self();
        $self->for = $for;
        $self->ownedby = $ownedby;
        $self->percentageOwned = number_format((float) $percentageOwned, 4);

        return $self;
    }
}
