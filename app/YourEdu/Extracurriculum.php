<?php

namespace App\YourEdu;

use App\Traits\DashboardItemTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extracurriculum extends Model
{
    
    use SoftDeletes, DashboardItemTrait;

    protected $fillable = [
        'name', 'description', 'state'
    ];

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function extrable()
    {
        return $this->morphTo();
    }

    public function schools()
    {
        return $this->morphedByMany(School::class,'extra','extracurricumable');
    }

    public function classes()
    {
        return $this->morphedByMany(ClassModel::class,'extra','extracurricumable');
    }

    public function groups()
    {
        return $this->morphedByMany(Group::class,'extra','extracurricumable');
    }
    
    public function requests()
    {
        return $this->morphMany(Request::class,'requestable');
    }

    public function topics()
    {
        return $this->morphMany(Topic::class,'topicable');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
    
    public function discussion()
    {
        return $this->morphOne(Discussion::class,'discussionfor');
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }
}
