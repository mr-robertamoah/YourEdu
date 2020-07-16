<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    //
    // use SoftDeletes;

    public function facilitators()
    {
        return $this->belongsToMany(Facilitator::class)->withTimestamps();
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveable');
    }

    public function curricula()
    {
        return $this->belongsToMany(Curriculum::class,'curriculum_subject','subject_id','curriculum_id')
                ->withTimestamps();
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function subjectable()
    {
        return $this->morphTo();
    }

    public function classes()
    {
        return $this->belongsToMany(Subject::class,'class_subject','subject_id','class_id')
                ->withTimestamps();
    }

    public function grades(){
        return $this->belongsToMany(Grade::class)->withTimestamps();
    }

    public function curriculumDetails()
    {
        return $this->hasMany(CurriculumDetail::class);
    }

    public function curriculumStructures()
    {
        return $this->belongsToMany(CurriculumStructure::class,'curriculum_structure_subject','subject_id','curriculum_structure_id')
                ->withTimestamps();
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class);
    }

    public function totalDetails()
    {
        return $this->hasMany(TotalDetail::class);
    }
    
    public function requests()
    {
        return $this->morphMany(Request::class,'requestable');
    }

    public function postAttachments()
    {
        return $this->morphMany(PostAttachment::class,'attachable');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionon');
    }
}
