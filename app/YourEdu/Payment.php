<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
    use SoftDeletes;

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
}
