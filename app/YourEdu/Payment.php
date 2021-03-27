<?php

namespace App\YourEdu;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'type', 'amount', 'state', 'postponement_date'
    ];

    public function transactions()
    {
        return $this->morphOne(Transaction::class,'transactable');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function paidby()
    {
        return $this->morphTo();
    }

    public function what()
    {
        return $this->morphTo();
    }

    public function paidto()
    {
        return $this->morphTo();
    }

    public function paidfor()
    {
        return $this->morphTo();
    }

    public function for()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return PaymentFactory::new();
    }
}
