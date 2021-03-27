<?php

namespace App\YourEdu;

use Database\Factories\AcademicYearFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'start_date','end_date','description', 'name', 'state'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function academicYearSections()
    {
        return $this->hasMany(AcademicYearSection::class);
    }

    public function classes()
    {
        return $this->morphedByMany(ClassModel::class,'academicable','academicables','academic_id')
            ->withTimestamps();
    }

    protected static function newFactory()
    {
        return AcademicYearFactory::new();
    }
}
