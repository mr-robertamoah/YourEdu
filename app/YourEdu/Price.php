<?php

namespace App\YourEdu;

use Database\Factories\PriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'amount','description', 'for'
    ];

    public function paymentFor()
    {
        return $this->morphMany(Payment::class,'paidfor');
    }

    public function priceable()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return PriceFactory::new();
    }

}
