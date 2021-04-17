<?php

namespace App\YourEdu;

use App\Traits\FeeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYearSection extends Model
{
    //
    use SoftDeletes,
        HasFactory,
        FeeTrait;

    protected $fillable = [
        'start_date','end_date','name','promotion','school_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'promotion' => 'boolean'
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function assessments()
    {
        return $this->belongsToMany(
            related: Assessment::class,
            foreignPivotKey: 'section_id'
        )->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class,'academic_section_class','academic_year_section_id','class_id')
            ->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(Report::class,'academic_year_section_id');
    }

    protected static function newFactory()
    {
        
    }
}
