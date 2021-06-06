<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessmentable extends Model
{
    use HasFactory;

    protected $fillable = ["assessmentable_type", 'assessmentable_id'];

    protected $table = "assessmentables";

    public function assessmentable()
    {
        return $this->morphTo();
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function scopeWhereMarker($query)
    {
        return $query
            ->has('assessmentable')
            ->whereIn('assessmentable_type', [
                "App\\YourEdu\\Facilitator",
                "App\\YourEdu\\Professional",
            ]);
    }

    public function scopeWhereNotMarker($query)
    {
        return $query
            ->has('assessmentable')
            ->whereNotIn('assessmentable_type', [
                "App\\YourEdu\\Facilitator",
                "App\\YourEdu\\Professional",
            ]);
    }

    public function scopeWhereAssessmentableUser($query, $userId)
    {
        return $query
            ->whereHasMorph('assessmentable', '*', function($query) use ($userId) {
                $query->whereUser($userId);
            });
    }

    public function scopeWhereAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }

    protected static function newFactory()
    {
        //
    }
}
