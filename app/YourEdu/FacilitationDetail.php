<?php

namespace App\YourEdu;

use Database\Factories\FacilitationDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilitationDetail extends Model
{
    use HasFactory;

    public function facilitatable()
    {
        return $this->morphTo();
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    public function accountable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return FacilitationDetailFactory::new();
    }
}
