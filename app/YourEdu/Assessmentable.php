<?php

namespace App\YourEdu;

use App\Services\MarkService;
use Database\Factories\AssessmentableFactory;
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
            ->whereIn('assessmentable_type', MarkService::MARKER_CLASSES);
    }

    public function scopeWhereNotMarker($query)
    {
        return $query
            ->has('assessmentable')
            ->whereNotIn('assessmentable_type', MarkService::MARKER_CLASSES);
    }

    public function scopeWhereAssessmentableUser($query, $userId)
    {
        return $query
            ->whereHasMorph('assessmentable', MarkService::MARKER_CLASSES, function ($query) use ($userId) {
                $query->whereUser($userId);
            });
    }

    public function scopeWhereAssessmentableAccount($query, $account)
    {
        return $query
            ->where(function ($query) use ($account) {
                $query
                    ->where('assessmentable_type', $account::class)
                    ->where('assessmentable_id', $account->id);
            });
    }

    public function scopeWhereAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }

    protected static function newFactory()
    {
        return AssessmentableFactory::new();
    }
}
