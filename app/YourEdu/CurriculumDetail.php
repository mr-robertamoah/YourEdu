<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumDetail extends Model
{
    //
    use SoftDeletes;

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function curriculumStructure()
    {
        return $this->belongsTo(CurriculumStructure::class,'curriculum_structure_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentable');
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
