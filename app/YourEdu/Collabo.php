<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collabo extends Model
{
    use HasFactory;
    
    const PENDING = 'PENDING';
    const DECLINED = 'DECLINED';
    const ACCEPTED = 'ACCEPTED';

    protected $table = 'collabo';

    protected $fillable = [
        'collaboration_id', 'state', 
    ];

    public function collaboration()
    {
        return $this->belongsTo(Collaboration::class);
    }

    public function collaborationable()
    {
        return $this->morphTo();
    }
}
